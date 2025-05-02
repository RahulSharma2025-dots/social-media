<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BankDetailController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LiveSessionController;
use App\Http\Controllers\OneOnOneSessionController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\UserController as AppUserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ChatController;

// Regular user authentication routes
Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Post Routes
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');
    Route::post('/comments/{comment}/reply', [PostController::class, 'reply'])->name('comments.reply');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    
    // Bank Details Routes
    Route::get('/bank-details', [BankDetailController::class, 'create'])->name('bank.details.create');
    Route::post('/bank-details', [BankDetailController::class, 'store'])->name('bank.details.store');

    // Live Session Routes
    Route::get('/live-sessions', [LiveSessionController::class, 'index'])->name('live-sessions');
    Route::get('/live-sessions/create', [LiveSessionController::class, 'create'])->name('live-sessions.create');
    Route::post('/live-sessions', [LiveSessionController::class, 'store'])->name('live-sessions.store');
    Route::get('/live-sessions/{session}', [LiveSessionController::class, 'show'])->name('live-sessions.show');
    Route::post('/live-sessions/{session}/join', [LiveSessionController::class, 'join'])->name('live-sessions.join');

    // One-on-One Session Routes
    Route::get('/sessions', [OneOnOneSessionController::class, 'index'])->name('sessions');
    Route::get('/sessions/create', [OneOnOneSessionController::class, 'create'])->name('sessions.create');
    Route::post('/sessions', [OneOnOneSessionController::class, 'store'])->name('sessions.store');
    Route::get('/sessions/{session}', [OneOnOneSessionController::class, 'show'])->name('sessions.show');
    Route::post('/sessions/{session}/book', [OneOnOneSessionController::class, 'book'])->name('sessions.book');

    // Wallet Routes
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw'])->name('wallet.withdraw');

    // User Routes
    Route::post('/users/{user}/follow', [AppUserController::class, 'follow'])->name('users.follow');
    Route::get('/explore', [AppUserController::class, 'explore'])->name('explore');

    // Messages Routes
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');

    // Notifications Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    // Bookmarks Routes
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks');
    Route::post('/bookmarks/{post}', [BookmarkController::class, 'store'])->name('bookmarks.store');
    Route::delete('/bookmarks/{post}', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');

    // Chat Routes
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{user}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{user}', [ChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/{user}/messages', [ChatController::class, 'getMessages'])->name('chat.getMessages');
    Route::post('/chat/{user}/read', [ChatController::class, 'markAsRead'])->name('chat.markAsRead');
});

// Profile routes
Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

// Settings Route
Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
Route::put('/settings', [ProfileController::class, 'updateSettings'])->name('settings.update');

// Admin Routes
Route::group(['prefix' => 'admin'], function () {
    // Redirect /admin to login if not authenticated
    Route::get('/', function () {
        if (!auth()->guard('admin')->check()) {
            return redirect()->route('admin.login');
        }
        return redirect()->route('admin.dashboard');
    });

    // Admin Auth Routes
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AuthController::class, 'login'])->name('admin.login.submit');

    // Protected Admin Routes
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');
        Route::get('users/index', [UserController::class, 'index'])->name('admin.users');
        Route::post('users/{user}/verify', [UserController::class, 'verifyInfluencer'])->name('admin.users.verify');
        Route::post('users/{user}/ban', [UserController::class, 'banUser'])->name('admin.users.ban');
        Route::post('users/{user}/unban', [UserController::class, 'unbanUser'])->name('admin.users.unban');
    });
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']); 