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
        $openAiEnabled = (bool) config('services.moderation.openai_enabled', false);

        Log::info('Moderation start.', [
            'openai_enabled' => $openAiEnabled,
        ]);

        if ($openAiEnabled) {
            $openAiResult = $this->moderateWithOpenAi($content);
            if ($openAiResult !== null) {
                Log::info('Moderation used OpenAI.', [
                    'status' => $openAiResult['status'],
                    'score' => $openAiResult['score'],
                ]);

                return $openAiResult;
            }

            Log::warning('Moderation fell back from OpenAI.');
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
    private function moderateWithOpenAi(string $content): ?array
    {
        $apiKey = (string) config('services.moderation.openai_api_key', '');
        if ($apiKey === '') {
            Log::warning('OpenAI moderation enabled but OPENAI_API_KEY is empty.');

            return null;
        }

        try {
            $timeout = (int) config('services.moderation.openai_timeout_seconds', 12);

            $response = Http::withToken($apiKey)
                ->timeout($timeout)
                ->asJson()
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => (string) config('services.moderation.openai_model', 'gpt-4.1-mini'),
                    'temperature' => 0,
                    'response_format' => ['type' => 'json_object'],
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $this->openAiSystemPrompt(),
                        ],
                        [
                            'role' => 'user',
                            'content' => $content,
                        ],
                    ],
                ]);

            if (! $response->ok()) {
                Log::warning('OpenAI moderation returned non-OK response.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            /** @var array<string,mixed> $data */
            $data = $response->json() ?? [];
            $messageContent = (string) data_get($data, 'choices.0.message.content', '');
            $parsed = json_decode($messageContent, true);

            if (! is_array($parsed)) {
                Log::warning('OpenAI moderation returned invalid JSON.', [
                    'content' => $messageContent,
                ]);

                return null;
            }

            $status = (string) ($parsed['status'] ?? '');
            $score = (int) ($parsed['score'] ?? 0);
            $reasons = $parsed['reasons'] ?? [];

            if (! in_array($status, ['approve', 'review', 'reject'], true)) {
                return null;
            }

            if (! is_array($reasons)) {
                $reasons = ['Brak szczegolowego uzasadnienia z AI.'];
            }

            $reasons = array_values(array_map(static fn ($r) => (string) $r, $reasons));
            $this->appendSourceReason($reasons, 'OpenAI');

            return [
                'status' => $status,
                'score' => max(0, min(100, $score)),
                'reasons' => $reasons,
                'source' => 'openai',
            ];
        } catch (Throwable $e) {
            Log::warning('OpenAI moderation unavailable. Using local fallback.', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function openAiSystemPrompt(): string
    {
        return <<<'PROMPT'
Jestes klasyfikatorem moderacji opinii uzytkownikow po polsku.
Zwracaj wylacznie poprawny JSON o strukturze:
{
  "status": "approve" | "review" | "reject",
  "score": integer 0-100,
  "reasons": [string, ...]
}

Reguly decyzji:
- Jesli tekst zawiera wulgaryzmy, obelgi albo maskowane formy obrazliwe, zwroc status="reject" i score >= 60.
- Jesli tekst zawiera dane kontaktowe, telefon, email, link, social handle albo zachete do kontaktu poza platforma, zwroc status="reject" i score >= 60.
- Traktuj jako kontakt takze numery rozdzielone separatorami, cyfry zapisane slowami i zamaskowane adresy email.
- Nie traktuj jako kontaktu samych kwot lub cen, np. "50,50 zl", "30.45", "za 120 PLN", jesli brak innych sygnalow kontaktowych.
- Jesli tekst wyglada na spam lub promocje bez danych kontaktowych, zwroc status="review" i score 25-59.
- Jesli tekst jest neutralny i bez powyzszych ryzyk, zwroc status="approve" i score 0-24.

Zawsze podaj co najmniej jeden reason.
Nie dodawaj zadnego tekstu poza JSON.
PROMPT;
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
