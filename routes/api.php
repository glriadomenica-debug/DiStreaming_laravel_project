<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\categoriesController;
use App\Http\Controllers\Api\moviesController;
use App\Http\Controllers\Api\UsersController;

Route::post('auth/registration', [AuthController::class, 'registration']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::get('movies', [moviesController::class, 'index']);
Route::get('movies/{id}', [moviesController::class, 'show']);

Route::get('categories', [CategoriesController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {

  Route::post('categories', [categoriesController::class, 'store']);
  Route::get('categories/{id}', [CategoriesController::class, 'show']);
  Route::put('categories/{id}', [CategoriesController::class, 'update']);
  Route::delete('categories/{id}', [CategoriesController::class, 'destroy']);

  Route::post('movies', [moviesController::class, 'store']);
  Route::put('movies/{id}', [moviesController::class, 'update']);
  Route::delete('movies/{id}', [moviesController::class, 'destroy']);

  Route::get('users', [UsersController::class, 'index']);
  Route::post('users', [UsersController::class, 'store']);
  Route::get('users/{id}', [UsersController::class, 'show']);
  Route::put('users/{id}', [UsersController::class, 'update']);
  Route::delete('users/{id}', [UsersController::class, 'destroy']);
});
