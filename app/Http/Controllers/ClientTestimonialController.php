<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\Ticket;
use App\Services\TestimonialModerationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ClientTestimonialController extends Controller
{
    public function __construct(
        private readonly TestimonialModerationService $moderationService
    ) {}

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

        $moderation = $this->moderationService->moderate($validated['content']);

        if ($moderation['status'] !== Testimonial::MODERATION_APPROVE) {
            $reasons = $moderation['reasons'] ?? [];
            $reasonText = implode(' ', array_map(static fn ($reason) => '- '.$reason, $reasons));

            throw ValidationException::withMessages([
                'content' => trim('Wykryto nieprawidłowości w opinii. Popraw treść i spróbuj ponownie. '.$reasonText),
            ]);
        }

        Testimonial::create([
            'user_id' => $request->user()->id,
            'ticket_id' => $ticket->id,
            'rating' => $validated['rating'],
            'content' => $validated['content'],
            'moderation_status' => $moderation['status'],
            'moderation_score' => $moderation['score'],
            'moderation_reasons' => $moderation['reasons'],
            'moderated_at' => now(),
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('public.testimonials')
            ->with('status', 'Dziękujemy. Opinia została opublikowana.');
    }
}
