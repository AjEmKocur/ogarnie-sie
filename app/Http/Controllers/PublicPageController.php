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
            'post' => $newsPost,
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
        $urls = [
            route('public.home'),
            route('public.about'),
            route('public.services'),
            route('public.testimonials'),
            route('public.news'),
            route('public.contact'),
            route('public.terms'),
            route('public.privacy'),
            route('public.cookies'),
            route('public.faq'),
        ];

        Service::where('is_active', true)
            ->orderBy('id')
            ->get()
            ->each(function (Service $service) use (&$urls): void {
                $urls[] = route('public.services.show', $service);
            });

        NewsPost::where('is_published', true)
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->get()
            ->each(function (NewsPost $post) use (&$urls): void {
                $urls[] = route('public.news.show', $post);
            });

        $xml = view('public.sitemap', [
            'urls' => array_values(array_unique($urls)),
        ])->render();

        return response($xml, Response::HTTP_OK)
            ->header('Content-Type', 'application/xml');
    }

}
