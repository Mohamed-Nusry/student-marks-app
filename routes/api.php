<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FavouriteSubjectController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\StudentSubjectController;
use App\Http\Controllers\Api\SubjectAssignmentController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\TeacherSubjectController;
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

Route::group(['prefix' => ''], function(){

    Route::group(['prefix' => 'user', 'middleware' => ['auth:sanctum']], function(){
        Route::post('/block', [AuthController::class, 'blockUser']);
        Route::post('/unblock', [AuthController::class, 'unblockUser']);
    });

    Route::group(['prefix' => 'subject', 'middleware' => ['auth:sanctum']], function(){
        Route::get('/', [SubjectController::class, 'index']);
        Route::post('/', [SubjectController::class, 'store']);
        Route::put('/{id}', [SubjectController::class, 'update']);
        Route::get('/{id}', [SubjectController::class, 'show']);
        Route::delete('/{id}', [SubjectController::class, 'delete']);
    });

    Route::group(['prefix' => 'student'], function(){

        Route::post('/', [StudentController::class, 'store']);

        Route::group(['prefix' => '', 'middleware' => ['auth:sanctum']], function(){
            Route::get('/', [StudentController::class, 'index']);
            Route::put('/{id}', [StudentController::class, 'update']);
        });
    });

    Route::group(['prefix' => 'teacher'], function(){

        Route::post('/', [TeacherController::class, 'store']);

        Route::group(['prefix' => '', 'middleware' => ['auth:sanctum']], function(){
            Route::get('/', [TeacherController::class, 'index']);
            Route::put('/{id}', [TeacherController::class, 'update']);
        });
    });

    Route::group(['prefix' => 'student/subject', 'middleware' => ['auth:sanctum']], function(){
        Route::get('/', [StudentSubjectController::class, 'index']);
        Route::post('/', [StudentSubjectController::class, 'store']);
        Route::put('/{id}', [StudentSubjectController::class, 'update']);
        Route::delete('/{id}', [StudentSubjectController::class, 'delete']);
        Route::get('/rank/all', [StudentSubjectController::class, 'rank']);
        Route::get('/rank/{id}', [StudentSubjectController::class, 'rankBySubject']);
    });

    Route::group(['prefix' => 'student/favourite', 'middleware' => ['auth:sanctum']], function(){
        Route::get('/', [FavouriteSubjectController::class, 'index']);
        Route::post('/', [FavouriteSubjectController::class, 'store']);
        Route::put('/{id}', [FavouriteSubjectController::class, 'update']);
        Route::get('/{id}', [FavouriteSubjectController::class, 'show']);
        Route::delete('/{id}', [FavouriteSubjectController::class, 'delete']);
    });

    Route::group(['prefix' => 'teacher/subject', 'middleware' => ['auth:sanctum']], function(){
        Route::get('/', [TeacherSubjectController::class, 'index']);
        Route::post('/', [TeacherSubjectController::class, 'store']);
        Route::put('/{id}', [TeacherSubjectController::class, 'update']);
        Route::delete('/{id}', [TeacherSubjectController::class, 'delete']);
    });

    Route::group(['prefix' => 'teacher/assignment', 'middleware' => ['auth:sanctum']], function(){
        Route::get('/', [SubjectAssignmentController::class, 'index']);
        Route::post('/', [SubjectAssignmentController::class, 'store']);
        Route::put('/{id}', [SubjectAssignmentController::class, 'update']);
        Route::delete('/{id}', [SubjectAssignmentController::class, 'delete']);
        Route::get('/{id}', [SubjectAssignmentController::class, 'show']);
    });

});



