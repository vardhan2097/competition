<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrganizationSetupController;
use App\Http\Controllers\Admin\UserInvitationController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserOrganizationController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\ReportsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::middleware(['auth', 'verified'])->group(function()
{
    Route::get('/dashboard', function()
    {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('organization')->name('organization.')->group(function()
    {
        // view & manage users
        Route::get('users', [UserOrganizationController::class, 'indexUsers'])->name('users.index');

        // for user invite
        Route::get('users/invite', [UserOrganizationController::class, 'inviteUserForm'])->name('users.invite.form');
        Route::post('users/invite', [UserOrganizationController::class, 'sendUserInvite'])->name('users.invite.send');

        // edit organization details
        Route::get('details/edit', [UserOrganizationController::class, 'editDetails'])->name('details.edit');
        Route::put('details/update', [UserOrganizationController::class, 'updateDetails'])->name('details.update');

        Route::get('reports', [ReportsController::class, 'index'])->name('reports.index');
    });

    // For fetching events via AJAX
    Route::get('/calendar-events', [EventController::class, 'calendarEvents'])->name('events.index');

    // For saving a new event (via AJAX or modal form)
    Route::post('/calendar-events', [EventController::class, 'store'])->name('events.store');

    Route::put('/calendar-events/{id}', [EventController::class, 'update'])->name('events.update');
});

Route::get('invitation/accept/{token}', [UserOrganizationController::class, 'acceptInvitation'])->name('invitation.accept');
Route::post('/invitation/store', [UserOrganizationController::class, 'storeUserFromInvite'])->name('organization.invitation.store');


// Admin Login
Route::prefix('admin')->name('admin.')->group(function()
{
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.submit');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware('auth.admin')->group(function()
    {
        Route::get('/dashboard', function()
        {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('organization/create',[OrganizationSetupController::class,'create'])->name('organization.create');
        Route::post('organization/store',[OrganizationSetupController::class,'store'])->name('organization.store');

        Route::get('organization', [OrganizationSetupController::class, 'index'])->name('organization.index');
    });
});
