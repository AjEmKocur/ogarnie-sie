<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTestimonialController extends Controller
{
    public function index(Request $request): View
    {
        $status = (string) $request->query('status', 'all');
        $query = Testimonial::with(['user', 'ticket'])->latest();

        if ($status !== 'all' && array_key_exists($status, Testimonial::moderationStatuses())) {
            $query->where('moderation_status', $status);
        } else {
            $status = 'all';
        }

        return view('admin.testimonials.index', [
            'testimonials' => $query->get(),
            'moderationStatuses' => Testimonial::moderationStatuses(),
            'statusFilter' => $status,
        ]);
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'in:approve,review,reject'],
        ]);

        $action = $validated['action'];
        $isApproved = $action === Testimonial::MODERATION_APPROVE;

        $testimonial->update([
            'moderation_status' => $action,
            'is_approved' => $isApproved,
            'approved_at' => $isApproved ? now() : null,
            'moderated_at' => now(),
        ]);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('status', 'Status opinii został zaktualizowany.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return redirect()
            ->route('admin.testimonials.index')
            ->with('status', 'Opinia została usunięta.');
    }
}

