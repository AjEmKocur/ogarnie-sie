<?php

namespace App\Http\Controllers;

use App\Models\AboutGalleryImage;
use App\Models\NewsPost;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Testimonial;
use App\Services\NewsAnalyticsService;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function __construct(
        private readonly NewsAnalyticsService $newsAnalyticsService
    ) {}

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
            'serviceCategories' => ServiceCategory::query()
                ->where('is_active', true)
                ->whereHas('services', fn ($query) => $query->where('is_active', true))
                ->with(['services' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'uncategorizedServices' => Service::where('is_active', true)
                ->whereNull('service_category_id')
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
            'service' => $service->load('category'),
            'relatedServices' => Service::where('is_active', true)
                ->whereKeyNot($service->id)
                ->when($service->service_category_id, fn ($query) => $query->where('service_category_id', $service->service_category_id))
                ->orderBy('sort_order')
                ->orderBy('name')
                ->take(3)
                ->get(),
        ]);
    }

    public function news(): View
    {
        return view('public.news', [
            'posts' => NewsPost::where('is_published', true)
                ->whereNotNull('published_at')
                ->orderByDesc('published_at')
                ->get(),
            'popularNews' => $this->fetchPopularNews(),
        ]);
    }

    public function newsShow(NewsPost $newsPost): View
    {
        if (! $newsPost->is_published || ! $newsPost->published_at instanceof Carbon) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $this->trackNewsView($newsPost);

        return view('public.news-show', [
            'post' => $newsPost,
            'popularNews' => $this->fetchPopularNews($newsPost->slug),
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

        $days = (int) ($settings['popular_days'] ?? 30);
        $limit = (int) ($settings['popular_limit'] ?? 5);
        $cacheSeconds = max(0, (int) ($settings['cache_seconds'] ?? 120));
        $cacheKey = sprintf('news_analytics.popular.v1.%d.%d', $days, $limit);

        $mapped = $cacheSeconds > 0
            ? Cache::remember(
                $cacheKey,
                now()->addSeconds($cacheSeconds),
                fn () => $this->newsAnalyticsService->popular()
            )
            : $this->newsAnalyticsService->popular();

        if ($excludeSlug) {
            $mapped = array_values(array_filter($mapped, static fn (array $item) => $item['slug'] !== $excludeSlug));
        }

        return $mapped;
    }

    private function trackNewsView(NewsPost $newsPost): void
    {
        $this->newsAnalyticsService->trackView($newsPost, session()->getId());
    }
}
