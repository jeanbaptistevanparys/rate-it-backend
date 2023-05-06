<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopicController;

Route::get('/topic', [TopicController::class, 'all']);
Route::post('/topic', [TopicController::class, 'add']);
Route::delete('/topic/{id}', [TopicController::class, 'delete']);
