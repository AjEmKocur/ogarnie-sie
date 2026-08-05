<?php

namespace App\Http\Controllers;

use App\Models\AboutGalleryImage;
use App\Models\NewsPost;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Testimonial;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
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
        ]);
    }

    public function newsShow(NewsPost $newsPost): View
    {
        if (! $newsPost->is_published || ! $newsPost->published_at instanceof Carbon) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('public.news-show', [
            'post' => $newsPost->load('images'),
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

    public function sitemap(): Response
    {
        $urls = collect([
            ['loc' => route('public.home'), 'lastmod' => null],
            ['loc' => route('public.about'), 'lastmod' => null],
            ['loc' => route('public.services'), 'lastmod' => null],
            ['loc' => route('public.testimonials'), 'lastmod' => null],
            ['loc' => route('public.news'), 'lastmod' => null],
            ['loc' => route('public.contact'), 'lastmod' => null],
            ['loc' => route('public.terms'), 'lastmod' => null],
            ['loc' => route('public.privacy'), 'lastmod' => null],
            ['loc' => route('public.cookies'), 'lastmod' => null],
            ['loc' => route('public.faq'), 'lastmod' => null],
        ]);

        Service::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get(['id', 'updated_at'])
            ->each(function (Service $service) use ($urls): void {
                $urls->push([
                    'loc' => route('public.services.show', ['service' => $service->id]),
                    'lastmod' => optional($service->updated_at)?->toAtomString(),
                ]);
            });

        NewsPost::query()
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->whereNotNull('slug')
            ->orderByDesc('published_at')
            ->get(['slug', 'updated_at'])
            ->each(function (NewsPost $post) use ($urls): void {
                $urls->push([
                    'loc' => route('public.news.show', ['newsPost' => $post->slug]),
                    'lastmod' => optional($post->updated_at)?->toAtomString(),
                ]);
            });

        $uniqueUrls = $urls
            ->unique('loc')
            ->values()
            ->all();

        $xml = view('public.sitemap', [
            'urls' => $uniqueUrls,
        ])->render();

        return response($xml, Response::HTTP_OK)
            ->header('Content-Type', 'application/xml');
    }

}
