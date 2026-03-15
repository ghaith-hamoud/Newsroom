<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\NewsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WorkflowController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('news', NewsController::class);
Route::apiResource('categories', CategoryController::class)->only(['index', 'store']);
Route::post('news/{news}/transition', [WorkflowController::class, 'transition']);
