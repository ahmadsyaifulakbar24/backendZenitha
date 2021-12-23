<?php

use App\Http\Controllers\EmailVerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route Mail
Route::get('sendMail', function () {
    return view('mail.sendActivationEmail');
});

Route::get('email_verification/{user_id}', [EmailVerificationController::class, 'email_verification'])->name('email_verification');

