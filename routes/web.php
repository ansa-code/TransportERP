<?php
use App\Http\Controllers\LoginController;
use App\Http\Controllers\FuelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', [LoginController::class, 'login'])
    ->name('login');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth');
Route::resource('vehicles', VehicleController::class);
Route::resource('drivers', DriverController::class);
Route::resource('clients', ClientController::class);
Route::resource('assignments', AssignmentController::class);
Route::resource('vendors', VendorController::class);
Route::resource('fuels', FuelController::class);
Route::resource('maintenances', MaintenanceController::class);
Route::resource('expenses', ExpenseController::class);
Route::resource('invoices', InvoiceController::class);
Route::resource('payrolls', PayrollController::class);
Route::get('/reports', [ReportController::class, 'index'])
    ->name('reports.index');

    


