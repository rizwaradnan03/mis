<?php

use App\Http\Controllers\BaseAPIController;
use App\Http\Controllers\BaseResponseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

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

// try {
//     DB::connection('sqlsrv')->getPdo();
//     return 'Connection successful!';
// } catch (\Exception $e) {
//     return 'Connection failed: ' . $e->getMessage();
// }

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/getDropdown', [BaseAPIController::class, 'index']);
Route::post('/getKSP', [BaseAPIController::class, 'getKSP']);
