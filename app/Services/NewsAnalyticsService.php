<?php

namespace App\Services;

use App\Models\NewsPost;
use Illuminate\Support\Facades\DB;

class NewsAnalyticsService
{
    public function trackView(NewsPost $newsPost, ?string $sessionId): bool
    {
        $settings = config('services.news_analytics');
        if (! ($settings['enabled'] ?? false)) {
            return false;
        }

        $cooldownSeconds = max(0, (int) ($settings['track_cooldown_seconds'] ?? 1800));
        $normalizedSessionId = $this->normalizeSessionId($sessionId);

        if ($normalizedSessionId !== null && $cooldownSeconds > 0) {
            $alreadyTracked = DB::table('news_view_events')
                ->where('news_post_id', $newsPost->id)
                ->where('session_id', $normalizedSessionId)
                ->where('viewed_at', '>=', now()->subSeconds($cooldownSeconds))
                ->exists();

            if ($alreadyTracked) {
                return false;
            }
        }

        DB::table('news_view_events')->insert([
            'news_post_id' => $newsPost->id,
            'session_id' => $normalizedSessionId,
            'viewed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return true;
    }

    /**
     * @return array<int,array{slug:string,title:string,views:int,cover_image_url:?string}>
     */
    public function popular(?string $excludeSlug = null): array
    {
        $settings = config('services.news_analytics');
        if (! ($settings['enabled'] ?? false)) {
            return [];
        }

        $days = max(1, min(365, (int) ($settings['popular_days'] ?? 30)));
        $limit = max(1, min(20, (int) ($settings['popular_limit'] ?? 5)));

        $rows = DB::table('news_view_events as v')
            ->join('news_posts as p', 'p.id', '=', 'v.news_post_id')
            ->where('p.is_published', true)
            ->whereNotNull('p.published_at')
            ->where('v.viewed_at', '>=', now()->subDays($days))
            ->selectRaw('p.slug, p.title, COUNT(*)::int as views, MAX(v.viewed_at) as last_viewed_at')
            ->groupBy('p.id', 'p.slug', 'p.title', 'p.published_at')
            ->orderByDesc('views')
            ->orderByDesc('last_viewed_at')
            ->limit($limit)
            ->get();

        $mapped = [];
        foreach ($rows as $row) {
            $slug = (string) ($row->slug ?? '');
            $title = (string) ($row->title ?? '');
            if ($slug === '' || $title === '') {
                continue;
            }

            $mapped[] = [
                'slug' => $slug,
                'title' => $title,
                'views' => max(0, (int) ($row->views ?? 0)),
            ];
        }

        if ($excludeSlug) {
            $mapped = array_values(array_filter($mapped, static fn (array $item) => $item['slug'] !== $excludeSlug));
        }

        if ($mapped === []) {
            return [];
        }

        $slugs = array_values(array_unique(array_map(static fn (array $item) => $item['slug'], $mapped)));
        $postsBySlug = NewsPost::query()->whereIn('slug', $slugs)->get()->keyBy('slug');

        foreach ($mapped as &$item) {
            $item['cover_image_url'] = $postsBySlug->get($item['slug'])?->coverImageUrl();
        }
        unset($item);

        return $mapped;
    }

    private function normalizeSessionId(?string $sessionId): ?string
    {
        if ($sessionId === null) {
            return null;
        }

        $trimmed = trim($sessionId);
        if ($trimmed === '') {
            return null;
        }

        return mb_substr($trimmed, 0, 120);
    }
}
