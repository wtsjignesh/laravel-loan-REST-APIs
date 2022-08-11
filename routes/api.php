<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanRepaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

Route::middleware('auth:sanctum')->group( function () {
    Route::resource('loans', LoanController::class);
    Route::post('loan-status', [LoanController::class, 'changeLoanStatus']);
    Route::post('repayment', [LoanRepaymentController::class, 'loanPayment']);
});