<?php

use App\Http\Controllers\AdminTicketController;
use App\Http\Controllers\AdminBlogPostController;
use App\Http\Controllers\AdminContactMessageController;
use App\Http\Controllers\AdminOperatorController;
use App\Http\Controllers\AdminServiceController;
use App\Http\Controllers\AdminTestimonialController;
use App\Http\Controllers\ClientTicketController;
use App\Http\Controllers\ClientTestimonialController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicContactController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\TicketMessageController;
use App\Http\Controllers\TicketAttachmentController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'home'])->name('public.home');
Route::view('/o-nas', 'public.about')->name('public.about');
Route::get('/uslugi', [PublicPageController::class, 'services'])->name('public.services');
Route::get('/uslugi/{service}', [PublicPageController::class, 'service'])->name('public.services.show');
Route::get('/cennik', [PublicPageController::class, 'pricing'])->name('public.pricing');
Route::view('/kontakt', 'public.contact')->name('public.contact');
Route::get('/opinie', [PublicPageController::class, 'testimonials'])->name('public.testimonials');
Route::get('/blog', [PublicPageController::class, 'blog'])->name('public.blog');

// Tymczasowa diagnostyka sesji na deployu (usunąć po naprawie logowania).
Route::get('/diag/session', function (Request $request) {
    $request->session()->put('diag_touch', now()->timestamp);

    return response()->json([
        'app_env' => app()->environment(),
        'session_driver' => config('session.driver'),
        'session_cookie' => config('session.cookie'),
        'session_domain' => config('session.domain'),
        'session_secure' => config('session.secure'),
        'session_same_site' => config('session.same_site'),
        'has_session' => $request->hasSession(),
        'session_id' => $request->session()->getId(),
        'csrf_token' => csrf_token(),
    ]);
});

Route::get('/diag/user', function (Request $request) {
    $token = (string) env('DIAG_TOKEN', '');
    if ($token === '' || ! hash_equals($token, (string) $request->query('token', ''))) {
        abort(403);
    }

    $email = trim((string) $request->query('email', ''));
    $user = $email !== '' ? User::where('email', $email)->first() : null;

    return response()->json([
        'email_checked' => $email,
        'user_exists' => (bool) $user,
        'user_id' => $user?->id,
        'username' => $user?->username,
        'is_admin' => $user?->is_admin,
        'email_verified_at' => $user?->email_verified_at,
        'force_password_change' => $user?->force_password_change,
    ]);
});

Route::post('/diag/reset-password', function (Request $request) {
    $token = (string) env('DIAG_TOKEN', '');
    if ($token === '' || ! hash_equals($token, (string) $request->input('token', ''))) {
        abort(403);
    }

    $email = trim((string) $request->input('email', ''));
    $password = (string) $request->input('password', '');

    if ($email === '' || $password === '') {
        return response()->json(['ok' => false, 'message' => 'email and password are required'], 422);
    }

    $user = User::where('email', $email)->first();
    if (! $user) {
        return response()->json(['ok' => false, 'message' => 'user not found'], 404);
    }

    $user->password = Hash::make($password);
    $user->force_password_change = false;
    $user->save();

    return response()->json(['ok' => true, 'user_id' => $user->id]);
});

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

    Route::get('/admin/cms/blog', [AdminBlogPostController::class, 'index'])->middleware('admin.permission:cms_blog')->name('admin.cms.blog.index');
    Route::post('/admin/cms/blog', [AdminBlogPostController::class, 'store'])->middleware('admin.permission:cms_blog')->name('admin.cms.blog.store');
    Route::patch('/admin/cms/blog/{blogPost}', [AdminBlogPostController::class, 'update'])->middleware('admin.permission:cms_blog')->name('admin.cms.blog.update');
    Route::delete('/admin/cms/blog/{blogPost}', [AdminBlogPostController::class, 'destroy'])->middleware('admin.permission:cms_blog')->name('admin.cms.blog.destroy');

    Route::middleware('main_admin')->group(function () {
        Route::get('/admin/team', [AdminOperatorController::class, 'index'])->name('admin.team.index');
        Route::post('/admin/team', [AdminOperatorController::class, 'store'])->name('admin.team.store');
        Route::patch('/admin/team/{user}/permissions', [AdminOperatorController::class, 'updatePermissions'])->name('admin.team.permissions');
        Route::patch('/admin/team/{user}/toggle', [AdminOperatorController::class, 'toggle'])->name('admin.team.toggle');
        Route::patch('/admin/team/{user}/reset-password', [AdminOperatorController::class, 'resetPassword'])->name('admin.team.reset-password');
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

