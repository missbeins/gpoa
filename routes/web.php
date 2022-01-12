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
    Route::get('/gpoa/upcoming-events', [SuperAdminController::class, 'index'])->name('admin.home');
    Route::get('/gpoa/event-approval', [SuperAdminController::class, 'eventApproval'])->name('admin.event-approval');
    Route::post('/gpoa/approved-event/{event}', [SuperAdminController::class, 'approved'])->name('admin.approved');
    Route::post('/gpoa/disapproved-event/{event}', [SuperAdminController::class, 'disapproved'])->name('admin.disapproved');
});

//Officer Routes
Route::prefix('/officer')->middleware('auth')->name('officer.')->group(function(){
    Route::get('/gpoa/upcoming-events', [EventsController::class, 'upcomingEvents'])->name('officer.home');
    Route::resource('/gpoa/events', EventsController::class);
    Route::post('/gpoa/import-events', [EventsController::class, 'import'])->name('event-import');
    Route::post('/gpoa/mark-as-done/{event}', [EventsController::class, 'markasDone'])->name('mark-as-done');
    Route::get('generate-pdf', [EventsController::class, 'generatePDF'])->name('print-pdf');
});

//Adviser Routes
Route::prefix('/adviser')->middleware('auth')->name('adviser.')->group(function(){
    Route::get('/gpoa/upcoming-events', [AdviserController::class, 'index'])->name('adviser.home');
    Route::get('/gpoa/event-approval', [AdviserController::class, 'eventApproval'])->name('adviser.event-approval');
    Route::post('/gpoa/approved-event/{event}', [AdviserController::class, 'approved'])->name('adviser.approved');
    Route::post('/gpoa/disapproved-event/{event}', [AdviserController::class, 'disapproved'])->name('adviser.disapproved');

});

