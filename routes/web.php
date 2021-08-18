<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the 'web' middleware group. Now create something great!
|
*/

/* Authentication Routes */
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/verified');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/forgot-password', function (Request $request) {
    Password::sendResetLink($request->params);
})->name('password.email');

Route::get('/reset-password{token}', function ($token) {
    return view('app', ['passwordToken' => $token]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    Password::reset($request->params, function ($user, $password) use ($request) {
        $user->forceFill(['password' => Hash::make($password)])->setRememberToken(Str::random(60));
        $user->save();

        event(new PasswordReset($user));
    });
})->name('password.update');

Route::post('/user', 'App\Http\Controllers\AuthController@user');
Route::post('/login', 'App\Http\Controllers\AuthController@login');
Route::get('/login', 'App\Http\Controllers\AuthController@verification')->name('login');
Route::post('/logout', 'App\Http\Controllers\AuthController@logout')->middleware(['auth']);
Route::post('/register', 'App\Http\Controllers\AuthController@register');
Route::post('/emailcheck', 'App\Http\Controllers\AuthController@emailcheck');
Route::post('/phonecheck', 'App\Http\Controllers\AuthController@phonecheck');
Route::post('/purchase', 'App\Http\Controllers\UserController@purchase');
Route::post('/oddsPreference', 'App\Http\Controllers\UserController@oddsPreference');
Route::post('/user/preferences', 'App\Http\Controllers\UserController@getPreference');
Route::post('/new-slip', 'App\Http\Controllers\SlipController@newSlip');
Route::post('/get-bets', 'App\Http\Controllers\BetsController@getBets');

/* Profile Routes */
Route::post('/avatar-update', 'App\Http\Controllers\UserController@avatarUpdate');
Route::post('/profile-update', 'App\Http\Controllers\UserController@profileUpdate');

/* Languages Routes */
Route::post('/getLanguage', 'App\Http\Controllers\LanguageController@getLanguage');
Route::post('/setLanguage', 'App\Http\Controllers\LanguageController@setLanguage');

Route::get('/{any}', 'App\Http\Controllers\PagesController@index')->where('any', '.*');

/* Admin Routes */
Route::post('/getRules', 'App\Http\Controllers\RulesController@getRules');

/* Contact Form Route */
Route::post('/message', 'App\Http\Controllers\ContactController@send');
