<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use Laravel\Sanctum\Http\Controllers\AuthorizedAccessTokenController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SalesController;
use App\Http\Controllers\Api\KendaraanController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\GudangController;
use App\Http\Controllers\Api\SuratJalanController;
use App\Http\Controllers\Api\DepoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Route::post('/sanctum/csrf-cookie', CsrfCookieController::class);
Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::match(['get', 'post'], '/login', [\App\Http\Controllers\Api\AuthController::class, 'login'])->name('login');
Route::middleware(['auth:sanctum'])->group(function () {
    // Users
    Route::apiResource('/users', UserController::class);

    // Sales
    Route::apiResource('/sales', SalesController::class);

    // Transports
    Route::apiResource('/transports', KendaraanController::class);

    // Customers
    Route::apiResource('/customers', CustomerController::class);

    // Inventories
    Route::apiResource('/inventories', GudangController::class);

    // GNR
    Route::apiResource('/gnr', SuratJalanController::class);

    // Depos
    Route::apiResource('/depos', DepoController::class);
});
// Route::post('login', [AuthController::class, 'login'])->name('login');
Route::apiResource('/transactions', App\Http\Controllers\Api\TransaksiController::class);
Route::apiResource('/stores', App\Http\Controllers\Api\StoreController::class);
Route::apiResource('/distributions', App\Http\Controllers\Api\PengirimanController::class);
Route::apiResource('/drivers', App\Http\Controllers\Api\DriverController::class);
Route::apiResource('/bonus-products', App\Http\Controllers\Api\BarangBonusController::class);
Route::apiResource('/products', App\Http\Controllers\Api\BarangController::class);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
