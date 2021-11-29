<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\MembersController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/gpoa/admin', [HomeController::class, 'admin'])->name('admin.home');
Route::get('/gpoa/officer', [HomeController::class, 'officer'])->name('officer.home');
Route::get('/gpoa/adviser', [HomeController::class, 'adviser'])->name('adviser.home');
