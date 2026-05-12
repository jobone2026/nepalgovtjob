<?php

use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\WhatsAppController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    // Redirect /admin to /admin/login
    Route::get('/', function () {
        return redirect()->route('admin.login');
    });

    // Admin authentication routes (not protected)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AuthController::class, 'login'])
            ->middleware('throttle:5,1')
            ->name('admin.login.submit');
    });

    // Protected admin routes
    Route::middleware('admin.auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Posts management
        Route::get('/posts', [PostController::class, 'index'])->name('admin.posts.index');
        Route::get('/posts/load-more', [PostController::class, 'loadMore'])->name('admin.posts.load-more');
        Route::get('/posts/create', [PostController::class, 'create'])->name('admin.posts.create');
        Route::post('/posts', [PostController::class, 'store'])->name('admin.posts.store');
        Route::post('/posts/bulk-action', [PostController::class, 'bulkAction'])->name('admin.posts.bulk-action');
        Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('admin.posts.edit');
        Route::put('/posts/{post}', [PostController::class, 'update'])->name('admin.posts.update');
        Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('admin.posts.destroy');
        Route::post('/posts/{post}/toggle-published', [PostController::class, 'togglePublished'])->name('admin.posts.toggle-published');
        Route::post('/posts/{post}/toggle-featured', [PostController::class, 'toggleFeatured'])->name('admin.posts.toggle-featured');

        // Categories management
        Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

        // States management
        Route::get('/states', [StateController::class, 'index'])->name('admin.states.index');
        Route::post('/states', [StateController::class, 'store'])->name('admin.states.store');
        Route::put('/states/{state}', [StateController::class, 'update'])->name('admin.states.update');
        Route::delete('/states/{state}', [StateController::class, 'destroy'])->name('admin.states.destroy');

        // Authors management
        Route::get('/authors', [AuthorController::class, 'index'])->name('admin.authors.index');
        Route::post('/authors', [AuthorController::class, 'store'])->name('admin.authors.store');
        Route::get('/authors/{author}/edit', [AuthorController::class, 'edit'])->name('admin.authors.edit');
        Route::put('/authors/{author}', [AuthorController::class, 'update'])->name('admin.authors.update');
        Route::delete('/authors/{author}', [AuthorController::class, 'destroy'])->name('admin.authors.destroy');
        Route::post('/authors/{author}/toggle', [AuthorController::class, 'toggleActive'])->name('admin.authors.toggle');

        // Ads management
        Route::get('/ads', [AdController::class, 'index'])->name('admin.ads.index');
        Route::post('/ads', [AdController::class, 'store'])->name('admin.ads.store');
        Route::put('/ads/{ad}', [AdController::class, 'update'])->name('admin.ads.update');
        Route::delete('/ads/{ad}', [AdController::class, 'destroy'])->name('admin.ads.destroy');
        Route::post('/ads/{ad}/toggle', [AdController::class, 'toggle'])->name('admin.ads.toggle');

        // Settings management
        Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('admin.settings.update');

        // Backup management
        Route::get('/backups', [BackupController::class, 'index'])->name('admin.backups.index');
        Route::post('/backups/create', [BackupController::class, 'create'])->name('admin.backups.create');
        Route::get('/backups/download/{filename}', [BackupController::class, 'download'])->name('admin.backups.download');
        Route::delete('/backups/delete/{filename}', [BackupController::class, 'delete'])->name('admin.backups.delete');
        Route::post('/backups/restore', [BackupController::class, 'restore'])->name('admin.backups.restore');
        Route::post('/backups/upload', [BackupController::class, 'upload'])->name('admin.backups.upload');
        
        // Notifications management
        Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
        Route::post('/notifications/send', [NotificationController::class, 'send'])->name('admin.notifications.send');
        
        // Feedback management
        Route::get('/feedback', [\App\Http\Controllers\NotificationController::class, 'feedbackPage'])->name('admin.feedback');
        Route::get('/feedback/list', [\App\Http\Controllers\NotificationController::class, 'feedbackList'])->name('admin.feedback.list');
        
        // WhatsApp Share
        Route::get('/whatsapp', [WhatsAppController::class, 'index'])->name('admin.whatsapp.index');
        Route::get('/whatsapp/generate/{post}', [WhatsAppController::class, 'generateMessage'])->name('admin.whatsapp.generate');
    });
});
