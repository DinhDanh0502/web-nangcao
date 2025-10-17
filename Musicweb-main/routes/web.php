<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ArtistController;
use App\Http\Controllers\Admin\SongController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\PlaylistController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;

// Route Auth (Đăng nhập, đăng ký)
Auth::routes();

// Trang chủ → Redirect đến trang login nếu chưa đăng nhập
Route::get('/', function () {
    return redirect('/login');
});

// ===== ADMIN ROUTES ===== 
// THÊM ->name('admin.') để tất cả routes có prefix admin.
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/home', [DashboardController::class, 'index'])->name('home');
        
        // Resource routes - bây giờ sẽ tự động có tên như admin.artists.index
        Route::resource('users', UserController::class);
        Route::resource('artists', ArtistController::class);
        Route::resource('songs', SongController::class);
        Route::resource('genres', GenreController::class);
        Route::resource('playlists', PlaylistController::class);
    });

// ===== AUTH ROUTES =====
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ===== FRONTEND ROUTES =====
Route::get('/music', [HomeController::class, 'index'])->name('music.index');
Route::get('/music/{id}', [HomeController::class, 'show'])->name('music.show');
Route::get('/playlist/{name}', [HomeController::class, 'playlist'])->name('music.playlist');

// Frontend genres & artists
Route::get('/genres', [HomeController::class, 'genres'])->name('genres.index');
Route::get('/artists', [HomeController::class, 'artists'])->name('artists.index');
Route::get('/artists/{id}', [HomeController::class, 'artistShow'])->name('artist.show');
Route::get('/genres/{id}', [HomeController::class, 'genreShow'])->name('genre.show');
Route::get('/playlists', [HomeController::class, 'playlists'])->name('playlists.index');

// Personal area (auth required)
Route::middleware('auth')->group(function () {
    Route::get('/me', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/me', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/favorites/{song}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});
