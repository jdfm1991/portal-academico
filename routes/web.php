<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Public\CourseCatalogController;
use App\Http\Controllers\Public\CourseDetailController;
use App\Http\Controllers\Public\EnrollmentController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\PaymentReceiptController;

Route::get('/', HomeController::class)->name('home');

Route::get('/cursos', [CourseCatalogController::class, 'index'])->name('courses.index');

Route::get('/cursos/{slug}', CourseDetailController::class)->name('courses.show');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/verify-email', [EmailVerificationController::class, 'notice'])
        ->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::post('/cursos/{slug}/inscribirse', [EnrollmentController::class, 'store'])
        ->name('enrollments.store');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mi-cuenta/cursos', [DashboardController::class, 'index'])
        ->name('student.courses');

    Route::get('/mi-cuenta/inscripciones/{enrollment}/comprobante', [PaymentReceiptController::class, 'create'])
        ->name('student.receipts.create');

    Route::post('/mi-cuenta/inscripciones/{enrollment}/comprobante', [PaymentReceiptController::class, 'store'])
        ->name('student.receipts.store');
});
