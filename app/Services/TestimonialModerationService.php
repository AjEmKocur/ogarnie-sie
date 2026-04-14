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
        $openAiEnabled = (bool) config('services.openai.moderation_enabled', false);
        $openAiKey = (string) config('services.openai.key', '');

        Log::info('Moderation start.', [
            'openai_enabled' => $openAiEnabled,
            'has_openai_key' => $openAiKey !== '',
        ]);

        if ($openAiEnabled && $openAiKey !== '') {
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

        $pythonEnabled = (bool) config('services.moderation.python_enabled', true);

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
    private function moderateWithOpenAi(string $content): ?array
    {
        try {
            $model = (string) config('services.openai.moderation_model', 'omni-moderation-latest');
            $timeout = (int) config('services.openai.timeout_seconds', 12);
            $apiKey = (string) config('services.openai.key', '');

            $response = Http::withToken($apiKey)
                ->timeout($timeout)
                ->post('https://api.openai.com/v1/moderations', [
                    'model' => $model,
                    'input' => $content,
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
            $result = $data['results'][0] ?? null;
            if (! is_array($result)) {
                return null;
            }

            $categories = is_array($result['categories'] ?? null) ? $result['categories'] : [];
            $scores = is_array($result['category_scores'] ?? null) ? $result['category_scores'] : [];
            $flagged = (bool) ($result['flagged'] ?? false);

            $reasons = $this->openAiReasons($categories);
            $score = $this->scoreFromCategoryScores($scores);

            $pii = $this->detectPii($content);
            $this->applyPiiFindings($pii, $score, $reasons);
            $this->applyProfanityFindings($content, $score, $reasons);

            if ($flagged) {
                $score = max($score, 60);
            }

            $status = $this->statusFromScore($score);

            if ($status === 'approve' && empty($reasons)) {
                $reasons[] = 'Brak wykrytych ryzyk. Opinia może zostać opublikowana automatycznie.';
            }

            $this->appendSourceReason($reasons, 'OpenAI Moderation');

            return [
                'status' => $status,
                'score' => min(100, $score),
                'reasons' => $reasons,
                'source' => 'openai',
            ];
        } catch (Throwable $e) {
            Log::warning('OpenAI moderation unavailable. Using fallback.', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
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
                $reasons = ['Brak szczegółowego uzasadnienia z modułu moderacji.'];
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
                $reasons[] = 'Wykryto słownictwo obraźliwe.';
                $hasDirectProfanity = true;
                break;
            }
        }

        if (! $hasDirectProfanity && $this->containsObfuscatedProfanity($content, $blockedWords)) {
            $score = max($score, 70);
            $reasons[] = 'Wykryto maskowane słownictwo obraźliwe.';
        }

        if (preg_match('/https?:\/\/|www\./i', $content)) {
            $score += 25;
            $reasons[] = 'Wykryto link w opinii.';
        }

        if (preg_match('/\+?\d[\d\-\s]{7,}\d/', $content)) {
            $score += 25;
            $reasons[] = 'Wykryto numer telefonu lub ciąg cyfr.';
        }

        if (preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', $content)) {
            $score += 25;
            $reasons[] = 'Wykryto adres e-mail w opinii.';
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

        $status = $this->statusFromScore($score);

        if ($status === 'approve' && empty($reasons)) {
            $reasons[] = 'Brak wykrytych ryzyk. Opinia może zostać opublikowana automatycznie.';
        }

        $this->appendSourceReason($reasons, 'Lokalne reguły');

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

    /**
     * @param array<string,mixed> $categories
     * @return array<int,string>
     */
    private function openAiReasons(array $categories): array
    {
        $labels = [
            'harassment' => 'Wykryto treści nękające.',
            'harassment/threatening' => 'Wykryto treści nękające z groźbami.',
            'hate' => 'Wykryto mowę nienawiści.',
            'hate/threatening' => 'Wykryto mowę nienawiści z groźbami.',
            'sexual' => 'Wykryto treści seksualne.',
            'sexual/minors' => 'Wykryto treści seksualne z udziałem nieletnich.',
            'violence' => 'Wykryto treści o przemocy.',
            'violence/graphic' => 'Wykryto drastyczne treści przemocy.',
            'self-harm' => 'Wykryto treści o samookaleczeniu.',
            'self-harm/intent' => 'Wykryto intencje samookaleczenia.',
            'self-harm/instructions' => 'Wykryto instrukcje samookaleczenia.',
            'illicit' => 'Wykryto treści o działaniach nielegalnych.',
            'illicit/violent' => 'Wykryto treści o przemocy w kontekście działań nielegalnych.',
        ];

        $reasons = [];
        foreach ($categories as $key => $value) {
            if ($value === true) {
                $reasons[] = $labels[$key] ?? ('Wykryto ryzykowną kategorię: '.$key.'.');
            }
        }

        return $reasons;
    }

    /**
     * @param array<string,mixed> $scores
     */
    private function scoreFromCategoryScores(array $scores): int
    {
        $max = 0.0;
        foreach ($scores as $score) {
            if (is_numeric($score)) {
                $max = max($max, (float) $score);
            }
        }

        return (int) round($max * 100);
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
     * @return array{emails:array<int,string>, phones:array<int,string>, urls:array<int,string>}
     */
    private function detectPii(string $content): array
    {
        $emails = $this->collectMatches('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', $content);
        $urls = $this->collectMatches('/\bhttps?:\/\/[^\s]+/i', $content);

        $phones = [];
        if (preg_match_all('/(?:\+?\d[\d\-\s()]{7,}\d)/', $content, $matches)) {
            foreach ($matches[0] as $raw) {
                $digits = preg_replace('/\D/', '', $raw);
                if ($digits !== null && strlen($digits) >= 9 && strlen($digits) <= 15) {
                    $phones[] = trim($raw);
                }
            }
        }

        return [
            'emails' => array_values(array_unique($emails)),
            'phones' => array_values(array_unique($phones)),
            'urls' => array_values(array_unique($urls)),
        ];
    }

    /**
     * @param array<int,string> $reasons
     */
    private function applyPiiFindings(array $pii, int &$score, array &$reasons): void
    {
        if (! empty($pii['emails'])) {
            $score += 25;
            $reasons[] = 'Wykryto adres e-mail w opinii.';
        }

        if (! empty($pii['phones'])) {
            $score += 25;
            $reasons[] = 'Wykryto numer telefonu lub ciąg cyfr.';
        }

        if (! empty($pii['urls'])) {
            $score += 25;
            $reasons[] = 'Wykryto link w opinii.';
        }
    }

    /**
     * @param array<int,string> $reasons
     */
    private function applyProfanityFindings(string $content, int &$score, array &$reasons): void
    {
        $blockedWords = $this->blockedWords();
        $normalized = mb_strtolower($content);

        foreach ($blockedWords as $word) {
            if (str_contains($normalized, $word)) {
                $score = max($score, 70);
                $reasons[] = 'Wykryto słownictwo obraźliwe.';
                return;
            }
        }

        if ($this->containsObfuscatedProfanity($content, $blockedWords)) {
            $score = max($score, 70);
            $reasons[] = 'Wykryto maskowane słownictwo obraźliwe.';
        }
    }

    /**
     * @return array<int,string>
     */
    private function collectMatches(string $pattern, string $content): array
    {
        if (! preg_match_all($pattern, $content, $matches)) {
            return [];
        }

        return array_values(array_unique($matches[0]));
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

        $reasons[] = 'Źródło moderacji: '.$source.'.';
    }
}
