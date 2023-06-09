<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SubjectController;
use Illuminate\Http\Request;
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



//Unauthorized Routes
Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
Route::get('/subject/view', [SubjectController::class, 'index']);
Route::get('/subject/view/{id}', [SubjectController::class, 'show']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'subject', 'middleware' => ['auth:sanctum']], function(){
    Route::get('/', [SubjectController::class, 'index']);
    Route::post('/', [SubjectController::class, 'store']);
    Route::put('/{id}', [SubjectController::class, 'update']);
    Route::get('/{id}', [SubjectController::class, 'show']);
});

