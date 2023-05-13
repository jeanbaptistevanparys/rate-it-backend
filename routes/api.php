<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\RatableController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\AuthController;

Route::post('register', [AuthController::class, "register"]);
Route::post('login', [AuthController::class, "login"]);

Route::get('/topic', [TopicController::class, 'all']);
Route::get('/topic/hot', [TopicController::class, 'hot']);

Route::get('/topic/{topicId}/ratable', [RatableController::class, 'all']);

Route::middleware('auth:api')->group(function() {
    Route::post('/topic', [TopicController::class, 'add']);
    Route::delete('/topic/{id}', [TopicController::class, 'remove']);

    Route::post('/topic/{topicId}/ratable', [RatableController::class, 'add']);
    Route::get('/topic/{topicId}/ratable/{id}', [RatableController::class, 'find']);
    Route::put('/topic/{topicId}/ratable/{id}', [RatableController::class, 'update']);
    Route::delete('/topic/{topicId}/ratable/{id}', [RatableController::class, 'remove']);

    Route::post('/topic/{topicId}/ratable/{ratableId}/rating/', [RatingController::class, 'add']);
    Route::delete('/topic/{topicId}/ratable/{ratableId}/rating/{id}', [RatingController::class, 'remove']);
});
