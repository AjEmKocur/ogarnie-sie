<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class TestimonialModerationService
{
    /**
     * @return array{status:string, score:int, reasons:array<int,string>, source:string}
     */
    public function moderate(string $content): array
    {
        $pythonEnabled = (bool) config('services.moderation.python_enabled', true);

        Log::info('Moderation start.', [
            'python_enabled' => $pythonEnabled,
        ]);

        if ($pythonEnabled) {
            $pythonResult = $this->moderateWithPython($content);
            if ($pythonResult !== null) {
                Log::info('Moderation used Python.', [
                    'status' => $pythonResult['status'],
                    'score' => $pythonResult['score'],
                ]);

                return $pythonResult;
            }

            Log::warning('Moderation fell back from Python.');
        }

        $local = $this->moderateLocally($content);
        Log::info('Moderation used local rules.', [
            'status' => $local['status'],
            'score' => $local['score'],
        ]);

        return $local;
    }

    /**
     * @return array{status:string, score:int, reasons:array<int,string>, source:string}|null
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
                $reasons = ['Brak szczegolowego uzasadnienia z modulu moderacji.'];
            }

            $reasons = array_values(array_map(static fn ($r) => (string) $r, $reasons));
            $this->appendSourceReason($reasons, 'Python moderation');

            return [
                'status' => $status,
                'score' => max(0, min(100, $score)),
                'reasons' => $reasons,
                'source' => 'python',
            ];
        } catch (Throwable $e) {
            Log::warning('Python moderation API unavailable. Using local fallback.', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * @return array{status:string, score:int, reasons:array<int,string>, source:string}
     */
    private function moderateLocally(string $content): array
    {
        $normalized = mb_strtolower($content);
        $score = 0;
        $reasons = [];
        $hasDirectProfanity = false;

        $blockedWords = $this->blockedWords();

        foreach ($blockedWords as $word) {
            if (str_contains($normalized, $word)) {
                $score += 70;
                $reasons[] = 'Wykryto slownictwo obrazliwe.';
                $hasDirectProfanity = true;
                break;
            }
        }

        if (! $hasDirectProfanity && $this->containsObfuscatedProfanity($content, $blockedWords)) {
            $score = max($score, 70);
            $reasons[] = 'Wykryto maskowane slownictwo obrazliwe.';
        }

        if (preg_match('/https?:\/\/|www\./i', $content)) {
            $score += 25;
            $reasons[] = 'Wykryto link w opinii.';
        }

        if (preg_match('/\+?\d[\d\-\s]{7,}\d/', $content)) {
            $score += 25;
            $reasons[] = 'Wykryto numer telefonu lub ciag cyfr.';
        }

        if (preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', $content)) {
            $score += 25;
            $reasons[] = 'Wykryto adres e-mail w opinii.';
        }

        if (preg_match('/(.)\1{5,}/u', $content)) {
            $score += 20;
            $reasons[] = 'Wykryto powtarzajace sie znaki (potencjalny spam).';
        }

        $upperRatio = $this->uppercaseRatio($content);
        if ($upperRatio > 0.6) {
            $score += 20;
            $reasons[] = 'Nadmierne uzycie wielkich liter.';
        }

        $status = $this->statusFromScore($score);

        if ($status === 'approve' && empty($reasons)) {
            $reasons[] = 'Brak wykrytych ryzyk. Opinia moze zostac opublikowana automatycznie.';
        }

        $this->appendSourceReason($reasons, 'Lokalne reguly');

        return [
            'status' => $status,
            'score' => min(100, $score),
            'reasons' => $reasons,
            'source' => 'local',
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

    private function statusFromScore(int $score): string
    {
        if ($score >= 60) {
            return 'reject';
        }

        if ($score >= 25) {
            return 'review';
        }

        return 'approve';
    }

    /**
     * @return array<int,string>
     */
    private function blockedWords(): array
    {
        return [
            'kurwa',
            'chuj',
            'pierdol',
            'debil',
            'idiota',
            'japierdole',
        ];
    }

    /**
     * @param array<int,string> $blockedWords
     */
    private function containsObfuscatedProfanity(string $content, array $blockedWords): bool
    {
        $lettersOnly = preg_replace('/[^\p{L}]/u', '', $content) ?? '';
        if ($lettersOnly === '') {
            return false;
        }

        $normalized = mb_strtolower($lettersOnly);

        foreach ($blockedWords as $word) {
            if (str_contains($normalized, $word)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<int,string> $reasons
     */
    private function appendSourceReason(array &$reasons, string $source): void
    {
        if (! (bool) config('services.moderation.debug_source', false)) {
            return;
        }

        $reasons[] = 'Zrodlo moderacji: '.$source.'.';
    }
}
