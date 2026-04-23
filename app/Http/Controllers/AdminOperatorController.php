<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminOperatorController extends Controller
{
    public function index(): View
    {
        return view('admin.team.index', [
            'operators' => User::query()
                ->where('role', User::ROLE_OPERATOR)
                ->latest()
                ->get(),
            'permissionOptions' => $this->permissionOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'lowercase', 'max:50', 'regex:/^[a-z0-9._-]+$/', 'unique:users,username'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['string', Rule::in(User::ADMIN_PERMISSIONS)],
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_OPERATOR,
            'is_active' => true,
            'admin_permissions' => array_values(array_unique($validated['permissions'])),
            'force_password_change' => true,
            'email_verified_at' => now(),
        ]);

        return redirect()
            ->route('admin.team.index')
            ->with('status', 'Konto operatora zostało utworzone.');
    }

    public function updatePermissions(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === User::ROLE_OPERATOR, 404);

        $validated = $request->validate([
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['string', Rule::in(User::ADMIN_PERMISSIONS)],
        ]);

        $user->update([
            'admin_permissions' => array_values(array_unique($validated['permissions'])),
        ]);

        return redirect()
            ->route('admin.team.index')
            ->with('status', 'Uprawnienia operatora zostały zaktualizowane.');
    }

    public function toggle(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === User::ROLE_OPERATOR, 404);

        $user->update([
            'is_active' => ! $user->is_active,
        ]);

        return redirect()
            ->route('admin.team.index')
            ->with('status', $user->is_active ? 'Operator został aktywowany.' : 'Operator został zablokowany.');
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === User::ROLE_OPERATOR, 404);

        $validated = $request->validate([
            'new_password' => ['required', 'string', 'min:8'],
        ]);

        $user->update([
            'password' => Hash::make($validated['new_password']),
            'force_password_change' => true,
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.team.index')
            ->with('status', 'Hasło operatora zostało zresetowane. Operator musi je zmienić po zalogowaniu.');
    }

    private function permissionOptions(): array
    {
        return [
            'tickets' => 'Zgłoszenia serwisowe',
            'cms_services' => 'CMS: usługi i cennik',
            'cms_news' => 'CMS: aktualności',
            'testimonials_moderation' => 'Moderacja opinii',
        ];
    }
}
