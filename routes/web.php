<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\FuelController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\TrafficFineController;
use App\Http\Controllers\DriverAdvanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| Welcome
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/health-check', function () {
    return response('Laravel is running', 200);
});
/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('welcome');
})->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth');


/*
|--------------------------------------------------------------------------
| Fleet & Operations
|--------------------------------------------------------------------------
*/

Route::resource('vehicles', VehicleController::class);

Route::resource('drivers', DriverController::class);

Route::resource('clients', ClientController::class);

Route::resource('assignments', AssignmentController::class);

Route::resource('trips', TripController::class);

Route::resource('maintenances', MaintenanceController::class);

Route::resource('leaves', LeaveController::class);


/*
|--------------------------------------------------------------------------
| Finance
|--------------------------------------------------------------------------
*/

Route::resource('vendors', VendorController::class);

Route::resource('fuels', FuelController::class);

Route::resource('expenses', ExpenseController::class);

Route::resource('invoices', InvoiceController::class);

Route::get(
    'invoices/{id}/pdf',
    [InvoiceController::class, 'pdf']
)->name('invoices.pdf');

Route::post(
    'invoices/{id}/email',
    [InvoiceController::class, 'email']
)->name('invoices.email');

Route::resource('payrolls', PayrollController::class);

Route::resource('traffic-fines', TrafficFineController::class);

Route::resource('driver-advances', DriverAdvanceController::class);

Route::resource('payments', PaymentController::class);


/*
|--------------------------------------------------------------------------
| Reports
|--------------------------------------------------------------------------
*/

Route::get('/reports', [ReportController::class, 'index'])
    ->name('reports.index');


/*
|--------------------------------------------------------------------------
| Alerts
|--------------------------------------------------------------------------
*/


Route::get('/alerts', [AlertController::class, 'index'])
    ->name('alerts.index');

Route::patch('/alerts/{alert}/read', [AlertController::class, 'markAsRead'])
    ->name('alerts.read');

Route::patch('/alerts/read-all', [AlertController::class, 'markAllAsRead'])
    ->name('alerts.read-all');

/*
|--------------------------------------------------------------------------
| Roles & Permissions
|--------------------------------------------------------------------------
*/

Route::resource('roles', RoleController::class);
    

Route::patch(
    'roles/{role}/toggle-status',
    [RoleController::class, 'toggleStatus']
)->name('roles.toggle-status');


Route::resource('users', UserController::class);
Route::resource('documents', DocumentController::class);
Route::post(
    'documents/{document}/replace',
    [DocumentController::class, 'replace']
)->name('documents.replace');

Route::get('/activity-logs', [ActivityLogController::class, 'index'])
    ->name('activity-logs.index');

Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])
    ->name('activity-logs.show');

Route::middleware('settings')->group(function () {

    Route::get('/settings', [SettingController::class, 'index'])
        ->name('settings.index');

    Route::put('/settings', [SettingController::class, 'update'])
        ->name('settings.update');

});