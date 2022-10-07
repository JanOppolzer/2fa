<?php

use App\Http\Controllers\FakeController;
use App\Http\Controllers\ShibbolethController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return auth()->user() ? view('dashboard') : view('welcome');
})->name('home');

if (App::environment(['local', 'testing'])) {
    Route::match(['get', 'post'], '/fakelogin/{id?}', [FakeController::class, 'login'])->middleware('guest');
    Route::get('fakelogout', [FakeController::class, 'logout'])->middleware('auth');
}

Route::resource('users', UserController::class)->only('index', 'show', 'update', 'destroy');

Route::get('profile', function () {
    return to_route('users.show', auth()->user());
})->middleware('auth');

Route::get('login', [ShibbolethController::class, 'create'])->middleware('guest')->name('login');
Route::get('auth', [ShibbolethController::class, 'store'])->middleware('guest');
Route::get('logout', [ShibbolethController::class, 'destroy'])->middleware('auth')->name('logout');
