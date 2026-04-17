<?php

use App\Http\Controllers\AdminTicketController;
use App\Http\Controllers\AdminNewsPostController;
use App\Http\Controllers\AdminContactMessageController;
use App\Http\Controllers\AdminMaintenanceController;
use App\Http\Controllers\AdminOperatorController;
use App\Http\Controllers\AdminServiceController;
use App\Http\Controllers\AdminAboutGalleryController;
use App\Http\Controllers\AdminTestimonialController;
use App\Http\Controllers\ClientTicketController;
use App\Http\Controllers\ClientTestimonialController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicContactController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\TicketNotificationController;
use App\Http\Controllers\TicketMessageController;
use App\Http\Controllers\TicketAttachmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'home'])->name('public.home');
Route::get('/o-nas', [PublicPageController::class, 'about'])->name('public.about');
Route::get('/uslugi', [PublicPageController::class, 'services'])->name('public.services');
Route::get('/uslugi/{service}', [PublicPageController::class, 'service'])->name('public.services.show');
Route::get('/cennik', [PublicPageController::class, 'pricing'])->name('public.pricing');
Route::view('/kontakt', 'public.contact')->name('public.contact');
Route::get('/opinie', [PublicPageController::class, 'testimonials'])->name('public.testimonials');
Route::get('/aktualnosci', [PublicPageController::class, 'news'])->name('public.news');
Route::get('/aktualnosci/{newsPost:slug}', [PublicPageController::class, 'newsShow'])->name('public.news.show');

Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('client.dashboard');
})->middleware(['auth', 'verified', 'password.change.required'])->name('dashboard');

Route::middleware(['auth', 'verified', 'password.change.required', 'admin'])->group(function () {
    Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
    Route::view('/admin/cms', 'admin.cms.dashboard')->name('admin.cms.dashboard');
    Route::get('/admin/tickets', [AdminTicketController::class, 'index'])->middleware('admin.permission:tickets')->name('admin.tickets.index');
    Route::get('/admin/tickets/{ticket}', [AdminTicketController::class, 'show'])->middleware('admin.permission:tickets')->name('admin.tickets.show');
    Route::patch('/admin/tickets/{ticket}', [AdminTicketController::class, 'update'])->middleware('admin.permission:tickets')->name('admin.tickets.update');
    Route::get('/admin/contact', [AdminContactMessageController::class, 'index'])->middleware('admin.permission:contact_messages')->name('admin.contact.index');
    Route::patch('/admin/contact/{contactMessage}', [AdminContactMessageController::class, 'update'])->middleware('admin.permission:contact_messages')->name('admin.contact.update');
    Route::get('/admin/testimonials', [AdminTestimonialController::class, 'index'])->middleware('admin.permission:testimonials_moderation')->name('admin.testimonials.index');
    Route::patch('/admin/testimonials/{testimonial}', [AdminTestimonialController::class, 'update'])->middleware('admin.permission:testimonials_moderation')->name('admin.testimonials.update');
    Route::delete('/admin/testimonials/{testimonial}', [AdminTestimonialController::class, 'destroy'])->middleware('admin.permission:testimonials_moderation')->name('admin.testimonials.destroy');

    Route::get('/admin/cms/services', [AdminServiceController::class, 'index'])->middleware('admin.permission:cms_services')->name('admin.cms.services.index');
    Route::post('/admin/cms/services', [AdminServiceController::class, 'store'])->middleware('admin.permission:cms_services')->name('admin.cms.services.store');
    Route::patch('/admin/cms/services', [AdminServiceController::class, 'bulkUpdate'])->middleware('admin.permission:cms_services')->name('admin.cms.services.bulk-update');
    Route::patch('/admin/cms/services/{service}', [AdminServiceController::class, 'update'])->middleware('admin.permission:cms_services')->name('admin.cms.services.update');
    Route::delete('/admin/cms/services/{service}', [AdminServiceController::class, 'destroy'])->middleware('admin.permission:cms_services')->name('admin.cms.services.destroy');

    Route::get('/admin/cms/about-gallery', [AdminAboutGalleryController::class, 'index'])->middleware('admin.permission:cms_services')->name('admin.cms.about-gallery.index');
    Route::post('/admin/cms/about-gallery', [AdminAboutGalleryController::class, 'store'])->middleware('admin.permission:cms_services')->name('admin.cms.about-gallery.store');
    Route::patch('/admin/cms/about-gallery/{aboutGalleryImage}', [AdminAboutGalleryController::class, 'update'])->middleware('admin.permission:cms_services')->name('admin.cms.about-gallery.update');
    Route::delete('/admin/cms/about-gallery/{aboutGalleryImage}', [AdminAboutGalleryController::class, 'destroy'])->middleware('admin.permission:cms_services')->name('admin.cms.about-gallery.destroy');

    Route::get('/admin/cms/aktualnosci', [AdminNewsPostController::class, 'index'])->middleware('admin.permission:cms_news')->name('admin.cms.news.index');
    Route::post('/admin/cms/aktualnosci', [AdminNewsPostController::class, 'store'])->middleware('admin.permission:cms_news')->name('admin.cms.news.store');
    Route::get('/admin/cms/aktualnosci/{newsPost}/edit', [AdminNewsPostController::class, 'edit'])->middleware('admin.permission:cms_news')->name('admin.cms.news.edit');
    Route::patch('/admin/cms/aktualnosci/{newsPost}', [AdminNewsPostController::class, 'update'])->middleware('admin.permission:cms_news')->name('admin.cms.news.update');
    Route::delete('/admin/cms/aktualnosci/{newsPost}', [AdminNewsPostController::class, 'destroy'])->middleware('admin.permission:cms_news')->name('admin.cms.news.destroy');

    Route::middleware('main_admin')->group(function () {
        Route::get('/admin/team', [AdminOperatorController::class, 'index'])->name('admin.team.index');
        Route::post('/admin/team', [AdminOperatorController::class, 'store'])->name('admin.team.store');
        Route::patch('/admin/team/{user}/permissions', [AdminOperatorController::class, 'updatePermissions'])->name('admin.team.permissions');
        Route::patch('/admin/team/{user}/toggle', [AdminOperatorController::class, 'toggle'])->name('admin.team.toggle');
        Route::patch('/admin/team/{user}/reset-password', [AdminOperatorController::class, 'resetPassword'])->name('admin.team.reset-password');
        Route::post('/admin/maintenance/purge-tickets-testimonials', [AdminMaintenanceController::class, 'purgeTicketsAndTestimonials'])->name('admin.maintenance.purge-tickets-testimonials');
    });
});

Route::middleware(['auth', 'verified', 'password.change.required', 'client'])->group(function () {
    Route::view('/client/dashboard', 'client.dashboard')->name('client.dashboard');
    Route::get('/client/tickets', [ClientTicketController::class, 'index'])->name('client.tickets.index');
    Route::get('/client/tickets/create', [ClientTicketController::class, 'create'])->name('client.tickets.create');
    Route::post('/client/tickets', [ClientTicketController::class, 'store'])->name('client.tickets.store');
    Route::get('/client/tickets/{ticket}', [ClientTicketController::class, 'show'])->name('client.tickets.show');
    Route::patch('/client/tickets/{ticket}/cancel', [ClientTicketController::class, 'cancel'])->name('client.tickets.cancel');
    Route::post('/client/tickets/{ticket}/pay', [ClientTicketController::class, 'pay'])->name('client.tickets.pay');
    Route::get('/client/testimonials/create', [ClientTestimonialController::class, 'create'])->name('client.testimonials.create');
    Route::post('/client/testimonials', [ClientTestimonialController::class, 'store'])->name('client.testimonials.store');
});

Route::middleware(['auth', 'verified', 'password.change.required'])->group(function () {
    Route::get('/notifications/tickets', [TicketNotificationController::class, 'index'])->name('notifications.tickets');
    Route::post('/tickets/{ticket}/messages', [TicketMessageController::class, 'store'])->name('tickets.messages.store');
    Route::post('/tickets/{ticket}/attachments', [TicketAttachmentController::class, 'store'])->name('tickets.attachments.store');
    Route::get('/attachments/{attachment}/download', [TicketAttachmentController::class, 'download'])->name('tickets.attachments.download');
    Route::delete('/attachments/{attachment}', [TicketAttachmentController::class, 'destroy'])->name('tickets.attachments.destroy');
});

Route::post('/kontakt', [PublicContactController::class, 'store'])->name('public.contact.store');

Route::middleware(['auth', 'verified', 'password.change.required'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
