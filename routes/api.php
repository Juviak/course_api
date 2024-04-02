<?php

use App\Http\Controllers\courseApiController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/courses', [CourseApiController::class, 'index']); // GET: Retrieve all courses
Route::post('/courses', [CourseApiController::class, 'store']); // POST: Create a new course
Route::get('/courses/{id}', [CourseApiController::class, 'show']); // GET: Retrieve a specific course
Route::put('/courses/{id}', [CourseApiController::class, 'update']); // PUT: Update a specific course
Route::delete('/courses/{id}', [CourseApiController::class, 'destroy']);




