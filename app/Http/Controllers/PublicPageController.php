<?php

namespace App\Http\Controllers;

use App\Models\AboutGalleryImage;
use App\Models\BlogPost;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function about(): View
    {
        return view('public.about', [
            'aboutGalleryImages' => AboutGalleryImage::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderByDesc('id')
                ->get(),
        ]);
    }

    public function home(): View
    {
        return view('public.home', [
            'featuredServices' => Service::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->take(3)
                ->get(),
        ]);
    }

    public function services(): View
    {
        return view('public.services', [
            'services' => Service::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function service(Service $service): View
    {
        if (! $service->is_active) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('public.service-show', [
            'service' => $service,
            'relatedServices' => Service::where('is_active', true)
                ->whereKeyNot($service->id)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->take(3)
                ->get(),
        ]);
    }

    public function pricing(): View
    {
        return view('public.pricing', [
            'services' => Service::where('is_active', true)
                ->whereNotNull('price_from')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function blog(): View
    {
        return view('public.blog', [
            'posts' => BlogPost::where('is_published', true)
                ->whereNotNull('published_at')
                ->orderByDesc('published_at')
                ->get(),
            'popularNews' => $this->fetchPopularNews(),
        ]);
    }

    public function blogShow(BlogPost $blogPost): View
    {
        if (! $blogPost->is_published || ! $blogPost->published_at instanceof Carbon) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $this->trackNewsView($blogPost);

        return view('public.news-show', [
            'post' => $blogPost,
            'popularNews' => $this->fetchPopularNews($blogPost->slug),
        ]);
    }

    public function testimonials(): View
    {
        return view('public.testimonials', [
            'testimonials' => Testimonial::with('user')
                ->where('is_approved', true)
                ->latest('approved_at')
                ->latest()
                ->get(),
        ]);
    }

    private function fetchPopularNews(?string $excludeSlug = null): array
    {
        $settings = config('services.news_analytics');
        if (! ($settings['enabled'] ?? false)) {
            return [];
        }

        $baseUrl = rtrim((string) ($settings['python_url'] ?? ''), '/');
        if ($baseUrl === '') {
            return [];
        }

        $days = (int) ($settings['popular_days'] ?? 30);
        $limit = (int) ($settings['popular_limit'] ?? 5);
        $cacheSeconds = max(0, (int) ($settings['cache_seconds'] ?? 120));
        $cacheKey = sprintf('news_analytics.popular.v1.%d.%d', $days, $limit);

        try {
            $mapped = $cacheSeconds > 0
                ? Cache::remember($cacheKey, now()->addSeconds($cacheSeconds), fn () => $this->fetchPopularNewsFromApi($baseUrl, $settings))
                : $this->fetchPopularNewsFromApi($baseUrl, $settings);

            if ($mapped === []) {
                $mapped = $this->fetchPopularNewsFromDatabase($settings);
            }

            if ($excludeSlug) {
                $mapped = array_values(array_filter($mapped, static fn ($item) => ($item['slug'] ?? null) !== $excludeSlug));
            }

            return $mapped;
        } catch (\Throwable $e) {
            Log::warning('News analytics popular fetch failed', ['error' => $e->getMessage()]);
            $mapped = $this->fetchPopularNewsFromDatabase($settings);
            if ($excludeSlug) {
                $mapped = array_values(array_filter($mapped, static fn ($item) => ($item['slug'] ?? null) !== $excludeSlug));
            }
            return $mapped;
        }
    }

    private function fetchPopularNewsFromApi(string $baseUrl, array $settings): array
    {
        $response = Http::timeout((float) ($settings['timeout_seconds'] ?? 2.5))
            ->acceptJson()
            ->get($baseUrl.'/news/popular', [
                'days' => (int) ($settings['popular_days'] ?? 30),
                'limit' => (int) ($settings['popular_limit'] ?? 5),
            ]);

        if (! $response->ok()) {
            return [];
        }

        $items = $response->json('items');
        if (! is_array($items)) {
            return [];
        }

        $mapped = array_values(array_filter(array_map(static function ($item) {
            if (! is_array($item)) {
                return null;
            }
            $slug = (string) ($item['slug'] ?? '');
            $title = (string) ($item['title'] ?? '');
            $views = (int) ($item['views'] ?? 0);

            if ($slug === '' || $title === '') {
                return null;
            }

            return [
                'slug' => $slug,
                'title' => $title,
                'views' => max(0, $views),
            ];
        }, $items)));

        if ($mapped === []) {
            return [];
        }

        $slugs = array_values(array_unique(array_map(static fn ($item) => $item['slug'], $mapped)));
        $postsBySlug = BlogPost::query()
            ->whereIn('slug', $slugs)
            ->get()
            ->keyBy('slug');

        foreach ($mapped as &$item) {
            $post = $postsBySlug->get($item['slug']);
            $item['cover_image_url'] = $post?->coverImageUrl();
        }
        unset($item);

        return $mapped;
    }

    private function trackNewsView(BlogPost $blogPost): void
    {
        $settings = config('services.news_analytics');
        if (! ($settings['enabled'] ?? false)) {
            return;
        }

        $baseUrl = rtrim((string) ($settings['python_url'] ?? ''), '/');
        if ($baseUrl === '') {
            $this->trackNewsViewInDatabase($blogPost, $settings);
            return;
        }

        try {
            $response = Http::timeout((float) ($settings['timeout_seconds'] ?? 2.5))
                ->acceptJson()
                ->post($baseUrl.'/news/track-view', [
                    'slug' => $blogPost->slug,
                    'session_id' => session()->getId(),
                ]);

            if (! $response->ok()) {
                $this->trackNewsViewInDatabase($blogPost, $settings);
            }
        } catch (\Throwable $e) {
            Log::warning('News analytics track failed', ['error' => $e->getMessage()]);
            $this->trackNewsViewInDatabase($blogPost, $settings);
        }
    }

    private function trackNewsViewInDatabase(BlogPost $blogPost, array $settings): void
    {
        try {
            $sessionId = trim((string) session()->getId());
            $sessionId = $sessionId !== '' ? substr($sessionId, 0, 120) : null;
            $cooldown = max(0, (int) ($settings['track_cooldown_seconds'] ?? 1800));

            if ($sessionId && $cooldown > 0) {
                $alreadyTracked = DB::table('news_view_events')
                    ->where('blog_post_id', $blogPost->id)
                    ->where('session_id', $sessionId)
                    ->where('viewed_at', '>=', now()->subSeconds($cooldown))
                    ->exists();

                if ($alreadyTracked) {
                    return;
                }
            }

            DB::table('news_view_events')->insert([
                'blog_post_id' => $blogPost->id,
                'session_id' => $sessionId,
                'viewed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('News track-view local fallback failed', ['error' => $e->getMessage()]);
        }
    }

    private function fetchPopularNewsFromDatabase(array $settings): array
    {
        try {
            $days = max(1, min(365, (int) ($settings['popular_days'] ?? 30)));
            $limit = max(1, min(20, (int) ($settings['popular_limit'] ?? 5)));

            $rows = DB::table('news_view_events as v')
                ->join('blog_posts as p', 'p.id', '=', 'v.blog_post_id')
                ->where('p.is_published', true)
                ->whereNotNull('p.published_at')
                ->where('v.viewed_at', '>=', now()->subDays($days))
                ->groupBy('p.id', 'p.slug', 'p.title', 'p.published_at')
                ->orderByRaw('COUNT(*) DESC')
                ->orderByRaw('MAX(v.viewed_at) DESC')
                ->limit($limit)
                ->get([
                    'p.slug',
                    'p.title',
                    DB::raw('COUNT(*) as views'),
                ]);

            $mapped = array_values(array_filter(array_map(static function ($row) {
                $slug = (string) ($row->slug ?? '');
                $title = (string) ($row->title ?? '');

                if ($slug === '' || $title === '') {
                    return null;
                }

                return [
                    'slug' => $slug,
                    'title' => $title,
                    'views' => max(0, (int) ($row->views ?? 0)),
                ];
            }, $rows->all())));

            if ($mapped === []) {
                return [];
            }

            $slugs = array_values(array_unique(array_map(static fn ($item) => $item['slug'], $mapped)));
            $postsBySlug = BlogPost::query()
                ->whereIn('slug', $slugs)
                ->get()
                ->keyBy('slug');

            foreach ($mapped as &$item) {
                $post = $postsBySlug->get($item['slug']);
                $item['cover_image_url'] = $post?->coverImageUrl();
            }
            unset($item);

            return $mapped;
        } catch (\Throwable $e) {
            Log::warning('News popular local fallback failed', ['error' => $e->getMessage()]);
            return [];
        }
    }
}
