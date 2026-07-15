<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminServiceController extends Controller
{
    public function index(): View
    {
        return view('admin.cms.services', [
            'categories' => ServiceCategory::orderBy('sort_order')->orderBy('name')->get(),
            'services' => Service::with('category')->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_category_id' => ['nullable', 'integer', 'exists:service_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'long_description' => ['nullable', 'string', 'max:20000'],
            'price_from' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Service::create([
            ...$validated,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.cms.services.index')
            ->with('status', __('Usługa dodana.'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'service_category_id' => ['nullable', 'integer', 'exists:service_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'long_description' => ['nullable', 'string', 'max:20000'],
            'price_from' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $service->update([
            ...$validated,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.cms.services.index')
            ->with('status', __('Usługa zaktualizowana.'));
    }

    public function bulkUpdate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'services' => ['required', 'array', 'min:1'],
            'services.*.service_category_id' => ['nullable', 'integer', 'exists:service_categories,id'],
            'services.*.name' => ['required', 'string', 'max:255'],
            'services.*.description' => ['nullable', 'string', 'max:5000'],
            'services.*.long_description' => ['nullable', 'string', 'max:20000'],
            'services.*.price_from' => ['nullable', 'numeric', 'min:0'],
            'services.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'services.*.is_active' => ['nullable', 'boolean'],
        ]);

        $ids = array_keys($validated['services']);
        $services = Service::whereIn('id', $ids)->get()->keyBy('id');

        foreach ($validated['services'] as $serviceId => $data) {
            $service = $services->get((int) $serviceId);

            if (! $service) {
                continue;
            }

            $service->update([
                'service_category_id' => $data['service_category_id'] ?? null,
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'long_description' => $data['long_description'] ?? null,
                'price_from' => $data['price_from'] ?? null,
                'sort_order' => $data['sort_order'] ?? 0,
                'is_active' => (bool) ($data['is_active'] ?? false),
            ]);
        }

        return redirect()
            ->route('admin.cms.services.index')
            ->with('status', __('Wszystkie zmiany w usługach zostały zapisane.'));
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()
            ->route('admin.cms.services.index')
            ->with('status', __('Usługa usunięta.'));
    }
}
