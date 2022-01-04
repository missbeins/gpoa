<?php

use App\Http\Controllers\Officer\OfficerController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Adviser\AdviserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Officer\EventsController;

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


//Route::get('/home', [HomeController::class, 'index'])->name('home');
/////////////////////////////////////LOGIN REDIRECT BY ROLES//////////////////////////////////////////

//Super Admin Routes
Route::prefix('/admin')->middleware('auth')->name('admin.')->group(function(){
    Route::get('/gpoa/admin/upcoming-events', [SuperAdminController::class, 'index'])->name('admin.home');
});

//Officer Routes
Route::prefix('/officer')->middleware('auth')->name('officer.')->group(function(){
    Route::get('/gpoa/officer/upcoming-events', [EventsController::class, 'upcomingEvents'])->name('officer.home');
    Route::resource('/gpoa/officer/events', EventsController::class);
    Route::post('/gpoa/officer/import-events', [EventsController::class, 'import'])->name('event-import');
    Route::post('/gpoa/officer/mark-as-done/{event}', [EventsController::class, 'markasDone'])->name('mark-as-done');
});

//Adviser Routes
Route::prefix('/adviser')->middleware('auth')->name('adviser.')->group(function(){
    Route::get('/gpoa/adviser/upcoming-events', [AdviserController::class, 'index'])->name('adviser.home');
});

