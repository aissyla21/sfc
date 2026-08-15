<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\Achievement;

Route::get('/', function () {
    $achievements = App\Models\Achievement::orderBy('year', 'desc')->get();
    $news = App\Models\News::orderBy('date', 'desc')->take(3)->get();
    $galleries = App\Models\Gallery::orderBy('created_at', 'desc')->get();
    return view('welcome', compact('achievements', 'news', 'galleries'));
});



Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'processRegister']);
});

// Member Welcome Route (Accessible without auth so they can see their NIA after registering)
Route::get('/welcome-member', function () {
    return view('auth.welcome-member');
})->name('welcome.member');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Member Routes
    Route::get('/dashboard', [\App\Http\Controllers\MemberController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/absen', [\App\Http\Controllers\MemberController::class, 'absenPage'])->name('dashboard.absen');
    Route::post('/dashboard/absen', [\App\Http\Controllers\MemberController::class, 'storeAbsen'])->name('dashboard.absen.store');
    Route::post('/dashboard/izin', [\App\Http\Controllers\MemberController::class, 'storeLeave'])->name('dashboard.izin.store');
    Route::post('/dashboard/avatar', [\App\Http\Controllers\MemberController::class, 'updateAvatar'])->name('dashboard.avatar.update');

    // Pelatih Routes
    Route::middleware('role:pelatih')->prefix('pelatih')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\PelatihController::class, 'index'])->name('pelatih.dashboard');
        Route::post('/news', [\App\Http\Controllers\PelatihController::class, 'storeNews'])->name('pelatih.news.store');
        Route::post('/achievement', [\App\Http\Controllers\PelatihController::class, 'storeAchievement'])->name('pelatih.achievement.store');
        Route::post('/gallery', [\App\Http\Controllers\PelatihController::class, 'storeGallery'])->name('pelatih.gallery.store');
        Route::post('/leave/{id}/approve', [\App\Http\Controllers\PelatihController::class, 'approveLeave'])->name('pelatih.leave.approve');
        Route::post('/leave/{id}/reject', [\App\Http\Controllers\PelatihController::class, 'rejectLeave'])->name('pelatih.leave.reject');
    });
});
