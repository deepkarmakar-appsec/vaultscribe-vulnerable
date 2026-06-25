<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\AdminController;
use App\Models\ActivityLog;

// PUBLIC ROUTES
Route::get('/', fn() => view('login'))->name('home');
Route::get('login', [AuthController::class, 'log'])->name('login');

// VULNERABILITY: Missing Rate Limiting (OWASP A04)
// Why: Throttling hata di, Brute-force/Credential Stuffing possible.
Route::post('login', [AuthController::class, 'logstore']); 

Route::get('/logout', function () { return redirect()->route('login'); });
Route::get('register', [RegisterController::class, 'register'])->name('register');
Route::post('register', [RegisterController::class, 'store']);

Route::get('/otp', [OtpController::class, 'otp'])->name('otp.verify');
Route::post('/otp', [OtpController::class, 'verifyOtp'])->name('otp.verify.post');

Route::get('/2fa/challenge', [TwoFactorController::class, 'challenge'])->name('2fa.challenge');
Route::post('/2fa/verify', [TwoFactorController::class, 'verify'])->name('2fa.verify');

Route::get('forget', [PasswordController::class, 'forget'])->name('forget');
Route::post('forgetpass', [PasswordController::class, 'forgetpass'])->name('password.forget.post');
Route::get('/reset-password/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordController::class, 'resetPassword'])->name('password.update');

// AUTHENTICATED ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/2fa/setup', [TwoFactorController::class, 'setup'])->name('2fa.setup');
    Route::post('/2fa/enable', [TwoFactorController::class, 'enable'])->name('2fa.enable');
});

// VULNERABILITY: Missing 2FA Enforcement (OWASP A01)
// Why: '2fa' middleware hata diya. User bina 2FA verification ke protected data access kar sakta hai.
Route::middleware(['auth'])->group(function () { 

    Route::post('/notes/import-url', [NoteController::class, 'importUrl'])->name('notes.importUrl');
    Route::get('/dashboard', [NoteController::class, 'dashboard'])->name('dashboard');

    Route::post('/notes', [NoteController::class, 'dashboardValue'])->name('notes.store');
    Route::get('/notes/{note}/edit', [NoteController::class, 'notesedit'])->name('notes.edit');
    Route::put('/notes/{note}', [NoteController::class, 'notesupdate'])->name('notes.update');
    Route::delete('/notes/{note}', [NoteController::class, 'notesdelete'])->name('notes.delete');

    Route::get('/notes/trash', [NoteController::class, 'showtrash'])->name('notes.trash');
    Route::patch('/notes/trash/restore-all', [NoteController::class, 'restoreAll'])->name('notes.restoreAll');
    Route::delete('/notes/trash/delete-all', [NoteController::class, 'forcedeleteall'])->name('notes.deleteAll');
    Route::patch('/notes/{id}/restore', [NoteController::class, 'restore'])->name('notes.restore');
    Route::delete('/notes/{id}/force-delete', [NoteController::class, 'forcedelete'])->name('notes.forceDelete');
    
    Route::post('/2fa/disable', [TwoFactorController::class, 'disable'])->name('2fa.disable');

    Route::get('/upload', [FileUploadController::class, 'upload'])->name('upload');
    // VULNERABILITY: Missing Throttling on File Upload (OWASP A04)
    Route::post('/fileupload', [FileUploadController::class, 'fileupload'])->name('fileupload');

    // VULNERABILITY: Insecure File Access (Path Traversal Risk)
    Route::get('/user/avatar/{filename}', function ($filename) {
        // Vulnerable version ke liye path update karein (public disk ka path)
        $path = storage_path('app/public/uploads/' . $filename); 
        
        // Path Traversal check (security demo ke liye)
        // Agar $filename mein '../' hoga toh ye kisi bhi file ko access kar sakta hai
        return response()->file($path); 
    })->name('profile.photo');
});

Route::post('/ai-summary', [AiController::class, 'generate'])->middleware('auth');

// VULNERABILITY: Broken Access Control (OWASP A01)
// Why: 'admin' middleware remove kar diya. Forced browsing of admin dashboard.
Route::prefix('admin')->group(function () {
    Route::get('/admindashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/logs', [AdminController::class, 'logs'])->name('admin.logs');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('admin.users.show');
    Route::get('/notes', [AdminController::class, 'notes'])->name('admin.notes');
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
});

// VULNERABILITY: Weak Logout Handling (OWASP A01)
// Why: Session invalidation aur token regeneration hataya. Session fixation risk.
Route::post('logout', function (Request $req) {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');