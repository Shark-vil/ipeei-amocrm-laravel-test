<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AmoCRM\LeadsController;
use App\Http\Controllers\Api\AmoCRM\AmoCRMController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('amocrm')->group(function () {
	Route::get('/', [AmoCRMController::class, 'index']);
	Route::get('/leads/{id}', [LeadsController::class, 'get_lead_by_id']);
});