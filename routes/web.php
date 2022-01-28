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

Route::get('/sois-gpoa/initializeStorageLink/', function () {
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    return redirect()->route('officer.officer.home');
})->middleware('auth');

//Route::get('/home', [HomeController::class, 'index'])->name('home');
/////////////////////////////////////LOGIN REDIRECT BY ROLES//////////////////////////////////////////

//Super Admin Routes
Route::prefix('/admin')->middleware('auth')->name('admin.')->group(function(){
    Route::get('/gpoa/upcoming-events', [SuperAdminController::class, 'index'])->name('admin.home');
    Route::get('/gpoa/upcoming-events/{organization}', [SuperAdminController::class, 'viewOrganizationevents'])->name('organization-events');
    Route::get('/gpoa/event-approval/{organization}', [SuperAdminController::class, 'eventApproval'])->name('admin.event-approval');
    Route::post('/gpoa/approved-event/{event}', [SuperAdminController::class, 'approved'])->name('admin.approved');
    Route::post('/gpoa/disapproved-event/{event}', [SuperAdminController::class, 'disapproved'])->name('admin.disapproved');
    Route::get('profile',[SuperAdminController::class, 'profile'])->name('profile');
    Route::put('/update-profile/{user}', [SuperAdminController::class, 'updateProfile'])->name('update-profile');
    Route::post('/add-signature', [SuperAdminController::class, 'addSignature'])->name('add-signature');
    Route::put('/update-signature/{signature}', [SuperAdminController::class, 'updateSignature'])->name('update-signature');
    Route::get('/gpoa/events/{event}/{orgId}', [SuperAdminController::class, 'show'])->name('events.show');
    Route::get('/events/search', [SuperAdminController::class, 'searchEvent'])->name('searchEvents');
    Route::post('generate-gpoa', [SuperAdminController::class, 'generatePDF'])->name('print-pdf');
    Route::get('/events/approved-events', [SuperAdminController::class, 'approvedEvents'])->name('approvedEvents');
    Route::get('/events/disapproved-events', [SuperAdminController::class, 'disapprovedEvent'])->name('disapprovedEvent');


});

//Officer Routes
Route::prefix('/officer')->middleware('auth')->name('officer.')->group(function(){
    Route::get('/gpoa/upcoming-events', [EventsController::class, 'upcomingEvents'])->name('officer.home');
    Route::get('/gpoa/events', [EventsController::class, 'index'])->name('events.index');
    Route::post('/gpoa/events', [EventsController::class, 'store'])->name('events.store');
    Route::get('/gpoa/events/create', [EventsController::class, 'create'])->name('events.create');
    Route::put('/gpoa/events/{event}/{orgId}', [EventsController::class, 'update'])->name('events.update');
    Route::get('/gpoa/events/{event}/{orgId}', [EventsController::class, 'show'])->name('events.show');
    Route::get('/gpoa/events/{event}/edit/{orgId}', [EventsController::class, 'edit'])->name('events.edit');
    Route::post('/gpoa/import-events', [EventsController::class, 'import'])->name('event-import');
    Route::put('/gpoa/mark-as-done/{event}/{orgId}', [EventsController::class, 'markasDone'])->name('mark-as-done');
    Route::post('generate-pdf', [EventsController::class, 'generatePDF'])->name('print-pdf');
    Route::get('profile',[EventsController::class, 'profile'])->name('profile');
    Route::put('/update-profile/{user}', [EventsController::class, 'updateProfile'])->name('update-profile');
    Route::post('/add-signature', [EventsController::class, 'addSignature'])->name('add-signature');
    Route::put('/update-signature/{signature}', [EventsController::class, 'updateSignature'])->name('update-signature');
    Route::get('/events/filter', [EventsController::class, 'filterEvents'])->name('filterEvents');
    Route::get('/events/search', [EventsController::class, 'searchEvent'])->name('searchEvents');
    Route::get('/events/approved-events', [EventsController::class, 'approvedEvents'])->name('approvedEvents');
    Route::get('/events/disapproved-events', [EventsController::class, 'disapprovedEvents'])->name('disapprovedEvents');
    

});

//Adviser Routes
Route::prefix('/adviser')->middleware('auth')->name('adviser.')->group(function(){
    Route::get('/gpoa/upcoming-events', [AdviserController::class, 'index'])->name('adviser.home');
    Route::get('/gpoa/event-approval', [AdviserController::class, 'eventApproval'])->name('adviser.event-approval');
    Route::post('/gpoa/approved-event/{event}', [AdviserController::class, 'approved'])->name('adviser.approved');
    Route::post('/gpoa/disapproved-event/{event}', [AdviserController::class, 'disapproved'])->name('adviser.disapproved');
    Route::get('profile',[AdviserController::class, 'profile'])->name('profile');
    Route::put('/update-profile/{user}', [AdviserController::class, 'updateProfile'])->name('update-profile');
    Route::post('/add-signature', [AdviserController::class, 'addSignature'])->name('add-signature');
    Route::put('/update-signature/{signature}', [AdviserController::class, 'updateSignature'])->name('update-signature');
    Route::get('/gpoa/events/{event}/{orgId}', [AdviserController::class, 'show'])->name('events.show');
    Route::get('/events/search', [AdviserController::class, 'searchEvent'])->name('searchEvents');
    Route::get('/events/approved-events', [AdviserController::class, 'approvedEvents'])->name('approvedEvents');
    Route::get('/events/disapproved-events', [AdviserController::class, 'disapprovedEvent'])->name('disapprovedEvent');

});

