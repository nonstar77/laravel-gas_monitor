<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', fn () => view('auth.login'))->name('login');
route::post('/login', [AuthController::class, 'login']);

Route::get('/register', fn () => view('auth.register'))->name('register');
route::post('/register', [AuthController::class, 'register']);

// Route group for dashboard and checkrole
// Route::group(['middleware' => ['auth', 'check_role:admin,staff']], function () {
//     Route::get('/dashboard', [DashboardController::class, 'index']);
// });

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');
Route::get('/logout', [AuthController::class, 'logout']);

// Route::get('/', function () {
//     return view('dashboard');
// })->middleware('auth');

// Route::middleware('guest')->group(function () {
//     Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
//     Route::post('login', [AuthenticatedSessionController::class, 'store']);
//     Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
//     Route::post('register', [RegisteredUserController::class, 'store']);
//     Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
//     Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
//     Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
//     Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
// });

// Route::middleware('auth')->group(function () {
//     Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
//     Route::get('verify-email', [EmailVerificationNotificationController::class, 'create'])->name('verification.notice');
//     Route::post('verify-email', [EmailVerificationNotificationController::class, 'store'])->name('verification.send');
//     Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
// });

// Route::middleware('auth')->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
//     Route::get('/dashboard/{device}/{type}', [DashboardController::class, 'type'])->name('dashboard.type');

//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

//     Route::resource('devices', DevicesController::class)->withTrashed();
//     Route::resource('receivers', ReceiversController::class)->withTrashed();
// });

// require __DIR__.'/auth.php';