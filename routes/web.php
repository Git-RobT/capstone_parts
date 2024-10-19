<?php
use App\Http\Middleware\BlockCountry;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OTPController;

// Apply BlockCountry middleware
Route::middleware([BlockCountry::class])->group(function () {
    Route::get('/', function () {
        return view('welcome'); // View for blocked countries or welcome page
    });

    Route::get('/otp', function () {
        return view('otp');
    });

    Route::get('/otp', [OTPController::class, 'index'])->name('otp.form');
    Route::post('/otp/send', [OTPController::class, 'sendOtp'])->name('otp.send');
    Route::post('/otp/verify', [OTPController::class, 'verifyOtp'])->name('otp.verify');
});
