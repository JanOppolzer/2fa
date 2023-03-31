<?php

use App\Http\Controllers\FakeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ShibbolethController;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManagerController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\App;
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

Route::get('language/{language}', LanguageController::class);

Route::view('/', 'welcome')->middleware('guest');

Route::view('home', 'home')->name('home')->middleware('auth');

Route::resource('users', UserController::class)->only('index', 'show', 'update', 'destroy');

Route::post('users/{user}/admin', [UserAdminController::class, 'store'])->name('users.grant_admin');
Route::delete('users/{user}/admin', [UserAdminController::class, 'destroy'])->name('users.revoke_admin');

Route::post('users/{user}/manager', [UserManagerController::class, 'store'])->name('users.grant_manager');
Route::delete('users/{user}/manager', [UserManagerController::class, 'destroy'])->name('users.revoke_manager');

Route::get('profile', UserProfileController::class)->name('profile');

Route::get('login', [ShibbolethController::class, 'create'])->middleware('guest')->name('login');
Route::get('auth', [ShibbolethController::class, 'store'])->middleware('guest');
Route::get('logout', [ShibbolethController::class, 'destroy'])->middleware('auth')->name('logout');

if (App::environment(['local', 'testing'])) {
    Route::post('fakelogin', [FakeController::class, 'store'])->middleware('guest')->name('fakelogin');
    Route::get('fakelogout', [FakeController::class, 'destroy'])->middleware('auth')->name('fakelogout');
}
