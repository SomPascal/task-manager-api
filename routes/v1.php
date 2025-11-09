<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1.')->group(function (){
    Route::prefix('auth')->name('auth.')->group(function (){
        Route::post('login', [LoginController::class, 'login'])
        ->middleware('guest.sanctum')
        ->name('login');

        Route::post('register', [RegisterController::class, 'register'])
        ->middleware('guest.sanctum')
        ->name('register');

        Route::post('logout', [LogoutController::class, 'logout'])
        ->middleware('auth:sanctum')
        ->name('logout');
    });

    Route::get('users/me', [UserController::class, 'me'])
    ->middleware('auth:sanctum')
    ->name('users.me');

    Route::prefix('categories')
    ->name('categories.')
    ->middleware('auth:sanctum')
    ->group(function (){
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/', [CategoryController::class, 'create'])->name('create');

        Route::get('{category}', [CategoryController::class, 'show'])
        ->name('show')
        ->whereNumber('category');

        Route::post('{category}/update', [CategoryController::class, 'update'])
        ->name('update')
        ->whereNumber('category');

        Route::post('{category}/pin', [CategoryController::class, 'pin'])
        ->name('pin')
        ->whereNumber('category');

        Route::post('{category}/unpin', [CategoryController::class, 'unpin'])
        ->name('unpin')
        ->whereNumber('category');

        Route::post('{category}/delete', [CategoryController::class, 'delete'])
        ->name('delete')
        ->whereNumber('category');

        Route::post('{category}/restore', [CategoryController::class, 'restore'])
        ->name('restore')
        ->whereNumber('category');
    });

    Route::prefix('tasks')
    ->name('tasks.')
    ->middleware('auth:sanctum')
    ->group(function (){
        Route::post('/', [TaskController::class, 'create'])->name('create');
    });
});