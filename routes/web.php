<?php

use App\Http\Controllers\Officer\OfficerController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Adviser\AdviserController;
use App\Http\Controllers\Director\DirectorController;
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
    Route::get('/gpoa/event-approval', [SuperAdminController::class, 'showAllPendingApproval'])->name('admin.showAllPendingApproval');
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

//Director Routes
Route::prefix('/director')->middleware('auth')->name('director.')->group(function(){
    Route::get('/gpoa/upcoming-events', [DirectorController::class, 'index'])->name('director.home');
    Route::get('/gpoa/upcoming-events/{organization}', [DirectorController::class, 'viewOrganizationevents'])->name('organization-events');
    Route::get('/gpoa/event-approval/{organization}', [DirectorController::class, 'eventApproval'])->name('director.event-approval');
    Route::get('/gpoa/event-approval', [DirectorController::class, 'showAllPendingApproval'])->name('director.showAllPendingApproval');
    Route::post('/gpoa/approved-event/{event}', [DirectorController::class, 'approved'])->name('director.approved');
    Route::post('/gpoa/disapproved-event/{event}', [DirectorController::class, 'disapproved'])->name('director.disapproved');
    Route::get('profile',[DirectorController::class, 'profile'])->name('profile');
    Route::put('/update-profile/{user}', [DirectorController::class, 'updateProfile'])->name('update-profile');
    Route::post('/add-signature', [DirectorController::class, 'addSignature'])->name('add-signature');
    Route::put('/update-signature/{signature}', [DirectorController::class, 'updateSignature'])->name('update-signature');
    Route::get('/gpoa/events/{event}/{orgId}', [DirectorController::class, 'show'])->name('events.show');
    Route::get('/events/search', [DirectorController::class, 'searchEvent'])->name('searchEvents');
    Route::post('generate-gpoa', [DirectorController::class, 'generatePDF'])->name('print-pdf');
    Route::get('/events/approved-events', [DirectorController::class, 'approvedEvents'])->name('approvedEvents');
    Route::get('/events/disapproved-events', [DirectorController::class, 'disapprovedEvent'])->name('disapprovedEvent');


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
    Route::post('generate-presentation-pdf', [EventsController::class, 'generatePresentationPDF'])->name('presentation-pdf');
    Route::get('profile',[EventsController::class, 'profile'])->name('profile');
    Route::put('/update-profile/{user}', [EventsController::class, 'updateProfile'])->name('update-profile');
    Route::post('/add-signature', [EventsController::class, 'addSignature'])->name('add-signature');
    Route::put('/update-signature/{signature}', [EventsController::class, 'updateSignature'])->name('update-signature');
    Route::get('/events/filter', [EventsController::class, 'filterEvents'])->name('filterEvents');
    Route::get('/events/search', [EventsController::class, 'searchEvent'])->name('searchEvents');
    Route::get('/events/approved-events', [EventsController::class, 'approvedEvents'])->name('approvedEvents');
    Route::get('/events/disapproved-events', [EventsController::class, 'disapprovedEvents'])->name('disapprovedEvents');
    Route::get('/events/partnership-requests', [EventsController::class, 'partnershipRequests'])->name('partnershipRequests');
    Route::get('/events/partnership-applications', [EventsController::class, 'partnershipApplications'])->name('partnershipApplications');
    Route::post('/partnerships/accept-request/{event}', [EventsController::class, 'acceptRequest'])->name('acceptRequest');
    Route::post('/partnerships/decline-request/{event}', [EventsController::class, 'declineRequest'])->name('declineRequest');
    Route::get('/gpoa/event-details/{event}', [EventsController::class, 'availablePartnershipDetails'])->name('availablePartnershipDetails');
    Route::post('/partnerships/apply/{event}', [EventsController::class, 'applyPartnership'])->name('applyPartnership');
    Route::get('/events/approved-partnership', [EventsController::class, 'approvedPartnerships'])->name('approvedPartnerships');
    Route::get('/events/disapproved-partnership', [EventsController::class, 'disapprovedPartnerships'])->name('disapprovedPartnerships');
    Route::get('/gpoa/notifications', [EventsController::class, 'notifications'])->name('notifications');
    Route::get('/gpoa/budget/add-budget-breakdown/{event}', [EventsController::class, 'showBreakdownForm'])->name('showBreakdownForm');
    Route::post('event/budget/breakdown/{event}', [EventsController::class, 'budgetBreakdown'])->name('budgetBreakdown');
    Route::get('event/view-budget-breakdown/{breakdown}/{org}', [EventsController::class, 'showBudgetBreakdown'])->name('view-breakdown');
    Route::put('event/update-budget-breakdown/{breakdown}', [EventsController::class, 'updateBudgetBreakdown'])->name('update-breakdown');



    // Route::get('/gpoa/budget/add-budget-breakdown/{event}/names', [EventsController::class, 'showBreakdownNamesForm'])->name('showBreakdownNamesForm');
    // Route::post('event/budget/breakdown/{event}/names', [EventsController::class, 'budgetBreakdownNames'])->name('budgetBreakdownNames');
    // Route::get('/gpoa/budget/add-budget-breakdown/{event}/amounts', [EventsController::class, 'showBreakdownAmountForm'])->name('showBreakdownAmountForm');
    // Route::post('event/budget/breakdown/{event}/amounts', [EventsController::class, 'budgetBreakdownAmount'])->name('budgetBreakdownAmount');

    
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

