<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\PostApiController;

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

// Post type listings
Route::get('/posts', [PostApiController::class, 'list']);
Route::get('/posts/search', [PostApiController::class, 'search']);
Route::get('/posts/featured', [PostApiController::class, 'featured']);
Route::get('/posts/scholarships', [PostApiController::class, 'scholarships']); // scholarship-specific endpoint
Route::get('/posts/{id}', [PostApiController::class, 'get']);
Route::post('/posts', [PostApiController::class, 'create']);
Route::put('/posts/{id}', [PostApiController::class, 'update']);
Route::delete('/posts/{id}', [PostApiController::class, 'delete']);

// Categories & States (public, no auth required)
Route::get('/categories', [PostApiController::class, 'categories']);
Route::get('/states', [PostApiController::class, 'states']);

// Home sections (all types in one response)
Route::get('/home', [PostApiController::class, 'home']);

// Stats / counts by type
Route::get('/stats', [PostApiController::class, 'stats']);

// Token management
Route::get('/token', [PostApiController::class, 'getToken']);
Route::post('/token/generate', [PostApiController::class, 'generateToken']);

// Admin authentication routes
Route::post('/admin/login', [AdminApiController::class, 'login']);

// Protected admin routes (require Sanctum authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/admin/logout', [AdminApiController::class, 'logout']);
});
