<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTestimonialController extends Controller
{
    public function index(): View
    {
        return view('admin.testimonials.index', [
            'testimonials' => Testimonial::with(['user', 'ticket'])
                ->latest()
                ->get(),
        ]);
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $request->validate([
            'is_approved' => ['required', 'boolean'],
        ]);

        $approved = (bool) $validated['is_approved'];

        $testimonial->update([
            'is_approved' => $approved,
            'approved_at' => $approved ? now() : null,
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

