<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class TestimonialModerationService
{
    /**
     * @return array{status:string, score:int, reasons:array<int,string>}
     */
    public function moderate(string $content): array
    {
        $pythonEnabled = (bool) config('services.moderation.python_enabled', true);

        if ($pythonEnabled) {
            $pythonResult = $this->moderateWithPython($content);
            if ($pythonResult !== null) {
                return $pythonResult;
            }
        }

        return $this->moderateLocally($content);
    }

    /**
     * @return array{status:string, score:int, reasons:array<int,string>}|null
     */
    private function moderateWithPython(string $content): ?array
    {
        try {
            $url = (string) config('services.moderation.python_url');
            $timeout = (int) config('services.moderation.timeout_seconds', 5);

            $response = Http::timeout($timeout)->post($url, [
                'content' => $content,
            ]);

            if (! $response->ok()) {
                Log::warning('Python moderation API returned non-OK response.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            /** @var array<string,mixed> $data */
            $data = $response->json() ?? [];

            $status = (string) ($data['status'] ?? '');
            $score = (int) ($data['score'] ?? 0);
            $reasons = $data['reasons'] ?? [];

            if (! in_array($status, ['approve', 'review', 'reject'], true)) {
                return null;
            }

            if (! is_array($reasons)) {
                $reasons = ['Brak szczegółowego uzasadnienia z modułu moderacji.'];
            }

            return [
                'status' => $status,
                'score' => max(0, min(100, $score)),
                'reasons' => array_values(array_map(static fn ($r) => (string) $r, $reasons)),
            ];
        } catch (Throwable $e) {
            Log::warning('Python moderation API unavailable. Using local fallback.', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * @return array{status:string, score:int, reasons:array<int,string>}
     */
    private function moderateLocally(string $content): array
    {
        $normalized = mb_strtolower($content);
        $score = 0;
        $reasons = [];

        $blockedWords = [
            'kurwa', 'chuj', 'pierdol', 'debil', 'idiota',
        ];

        foreach ($blockedWords as $word) {
            if (str_contains($normalized, $word)) {
                $score += 70;
                $reasons[] = 'Wykryto słownictwo obraźliwe.';
                break;
            }
        }

        if (preg_match('/https?:\/\/|www\./i', $content)) {
            $score += 25;
            $reasons[] = 'Wykryto link w opinii.';
        }

        if (preg_match('/\+?\d[\d\-\s]{7,}\d/', $content)) {
            $score += 25;
            $reasons[] = 'Wykryto numer telefonu lub ciąg cyfr.';
        }

        if (preg_match('/(.)\1{5,}/u', $content)) {
            $score += 20;
            $reasons[] = 'Wykryto powtarzające się znaki (potencjalny spam).';
        }

        $upperRatio = $this->uppercaseRatio($content);
        if ($upperRatio > 0.6) {
            $score += 20;
            $reasons[] = 'Nadmierne użycie wielkich liter.';
        }

        $status = 'approve';
        if ($score >= 60) {
            $status = 'reject';
        } elseif ($score >= 25) {
            $status = 'review';
        }

        if ($status === 'approve' && empty($reasons)) {
            $reasons[] = 'Brak wykrytych ryzyk. Opinia może zostać opublikowana automatycznie.';
        }

        return [
            'status' => $status,
            'score' => min(100, $score),
            'reasons' => $reasons,
        ];
    }

    private function uppercaseRatio(string $text): float
    {
        $letters = preg_replace('/[^\p{L}]/u', '', $text) ?? '';
        if ($letters === '') {
            return 0.0;
        }

        $upper = preg_replace('/[^\p{Lu}]/u', '', $letters) ?? '';

        return mb_strlen($upper) / max(1, mb_strlen($letters));
    }
}

