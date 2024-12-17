<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\OfficeAnalyticsController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\OTPController;

// Home route after successful login
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/register', function () {
    return view('register');
});

// OTP login routes
Route::view('/login-with-otp','auth.loginwithotp')->name('login.with.otp');
Route::post('/login-with-otp-post', [App\Http\Controllers\OTPController::class, 'loginwithotppost'])->name('login.with.otp.post');

// OTP confirmation routes
Route::view('/confirm-login-with-otp','auth.confirmloginwithotp')->name('confirm.login.with.otp');
Route::post('/confirm-login-with-otp-post', [App\Http\Controllers\OTPController::class, 'confirmloginwithotppost'])->name('confirm.with.otp.post');

// Authentication routes
Route::get('/', [LoginController::class, 'showLoginForm']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

// Dashboard routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/userdashboard', [UserController::class, 'dashboard'])->name('userdashboard');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'admin'])->name('admin.dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/usermanagement', [UserController::class, 'index'])->name('user.index');
    Route::post('/usermanagement', [UserController::class, 'store'])->name('user.store');
    Route::put('/usermanagement/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/usermanagement/{id}', [UserController::class, 'destroy'])->name('user.destroy');
});


// Office routes
Route::middleware(['auth'])->group(function () {
    Route::get('/offices', [OfficeController::class, 'index'])->name('offices.index');
    Route::post('/offices', [OfficeController::class, 'store'])->name('offices.store');
    Route::get('/offices/{id}/edit', [OfficeController::class, 'edit'])->name('offices.edit');
    Route::put('/offices/{id}', [OfficeController::class, 'update'])->name('offices.update');
    Route::get('/offices/{id}', [OfficeController::class, 'show'])->name('offices.show');
    Route::delete('/offices/{id}', [OfficeController::class, 'destroy'])->name('offices.destroy');
});

// About us route
Route::get('/aboutus', function () {
    return view('aboutus/aboutus');
})->name('aboutus');

// Activity Log routes
Route::middleware(['auth'])->group(function () {
    // Use pluralized naming for consistency
    Route::get('/activitylogs', [ActivityLogController::class, 'index'])->name('activitylogs.index');
    // Usually, a delete route targets a specific log by ID
    Route::delete('/activitylogs/{id}', [ActivityLogController::class, 'destroy'])->name('activitylogs.destroy');
});

// Archives route
Route::middleware('auth')->group(function () {
    Route::get('/archives', [ArchiveController::class, 'listFiles'])->name('archives.listFiles');
    Route::post('/archives/upload', [ArchiveController::class, 'uploadFile'])->name('archives.uploadFile');
    Route::delete('/archives/{id}', [ArchiveController::class, 'destroy'])->name('archives.destroy');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Document routes
Route::middleware(['auth'])->group(function () {
  Route::resource('documents', DocumentController::class);
    
});

// Analytics routes
Route::middleware(['auth'])->group(function () {
    Route::get('/analytics', [OfficeAnalyticsController::class, 'index'])->name('analytics.index');
});

// QR Code Scan and Verification routes (no authentication required)
Route::get('/qrcode/scan/{qrcode}', [DocumentController::class, 'scan'])->name('documents.scan');
Route::post('/qrcode/verify/{qrcode}', [DocumentController::class, 'verify'])->name('documents.verify');
Route::get('/confirmation', function () {
    return view('confirmation');
})->name('confirmation');

Route::post('/documents/{document}/request-revision', [DocumentController::class, 'requestRevision'])->name('documents.request-revision');

Route::resource('document-types', DocumentTypeController::class);

// Route for fetching signatories of a specific document type
Route::get('document-types/{documentTypeId}/signatories', [DocumentTypeController::class, 'getSignatories'])
    ->name('document-types.signatories');


// Route to fetch recent activity data
Route::get('/recent-activity', [DocumentController::class, 'getRecentActivity'])->name('recent-activity');

Route::get('/documents/{id}/signatories', [DocumentController::class, 'getSignatories'])->name('documents.get-signatories');

// Route for displaying the revision request page
Route::get('/documents/{document}/revision-request', [DocumentController::class, 'showRevisionRequestForm'])->name('documents.show-revision-request');

// Route for submitting the revision request
Route::post('/documents/{document}/submit-revision-request', [DocumentController::class, 'submitRevisionRequest'])->name('documents.submit-revision-request');





// Include Laravel's default authentication routes
require __DIR__.'/auth.php';


