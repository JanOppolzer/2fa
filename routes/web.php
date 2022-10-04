<?php

use App\Http\Controllers\FakeController;
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
    return view('welcome');
});

if (App::environment(['local', 'testing'])) {
    Route::match(['get', 'post'], '/fakelogin/{id?}', [FakeController::class, 'login']);
    Route::get('fakelogout', [FakeController::class, 'logout']);
}

Route::resource('users', UserController::class)->only('index', 'show', 'update', 'destroy');
