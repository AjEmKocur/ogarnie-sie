<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientTestimonialController extends Controller
{
    public function create(Request $request): View
    {
        $eligibleTickets = $request->user()
            ->tickets()
            ->where('status', Ticket::STATUS_CLOSED)
            ->whereDoesntHave('testimonial')
            ->latest()
            ->get();

        $requestedTicketId = (int) $request->integer('ticket');
        $preselectedTicketId = $eligibleTickets->contains('id', $requestedTicketId) ? $requestedTicketId : null;

        return view('client.testimonials.create', [
            'eligibleTickets' => $eligibleTickets,
            'preselectedTicketId' => $preselectedTicketId,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ticket_id' => ['required', 'integer', 'exists:tickets,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'content' => ['required', 'string', 'min:20', 'max:2000'],
        ]);

        $ticket = $request->user()
            ->tickets()
            ->whereKey($validated['ticket_id'])
            ->where('status', Ticket::STATUS_CLOSED)
            ->whereDoesntHave('testimonial')
            ->firstOrFail();

        Testimonial::create([
            'user_id' => $request->user()->id,
            'ticket_id' => $ticket->id,
            'rating' => $validated['rating'],
            'content' => $validated['content'],
            'is_approved' => false,
            'approved_at' => null,
        ]);

        return redirect()
            ->route('public.testimonials')
            ->with('status', 'Dziękujemy. Opinia została zapisana i czeka na akceptację.');
    }
}

