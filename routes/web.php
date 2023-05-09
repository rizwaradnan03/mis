<?php

use App\Http\Controllers\ChangePassController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MisController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'login']);

Route::controller(['Auth'])->group(function () {
    Route::get('/ksp', [MisController::class, 'ksp']);
    Route::get('/changePass', [ChangePassController::class, 'changePass']);
    Route::get('/getChangePass', [ChangePassController::class, 'getChangePass']);
    Route::post('/postChangePass', [ChangePassController::class, 'postChangePass']);
    Route::post('/getKSP', [MisController::class, 'getKSP']);
    Route::get('/logout', [LoginController::class, 'logout']);
})->middleware('Auth');
