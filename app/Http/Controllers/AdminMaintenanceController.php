<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AdminMaintenanceController extends Controller
{
    public function purgeTicketsAndTestimonials(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Podaj hasło administratora, aby potwierdzić operację.',
            'password.current_password' => 'Podane hasło jest niepoprawne.',
        ]);

        try {
            DB::transaction(function (): void {
                $attachments = DB::table('ticket_attachments')
                    ->select(['disk', 'path'])
                    ->get();

                foreach ($attachments as $attachment) {
                    if (! $attachment->path) {
                        continue;
                    }

                    try {
                        Storage::disk((string) ($attachment->disk ?: 'public'))->delete((string) $attachment->path);
                    } catch (Throwable $e) {
                        report($e);
                    }
                }

                DB::table('testimonials')->delete();
                DB::table('ticket_messages')->delete();
                DB::table('ticket_status_histories')->delete();
                DB::table('ticket_attachments')->delete();
                DB::table('tickets')->delete();
            });
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'Nie udało się wyczyścić danych. Spróbuj ponownie za chwilę.');
        }

        return back()->with('status', 'Wyczyszczono dane zgłoszeń i opinii.');
    }
}
