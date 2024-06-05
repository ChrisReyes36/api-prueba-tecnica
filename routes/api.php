<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\PermissionsController;
use App\Http\Controllers\Api\Auth\RolesController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Auth
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        // Auth
        Route::get('user', [AuthController::class, 'user']);
        Route::post('logout', [AuthController::class, 'logout']);

        //Roles
        Route::prefix('roles')->group(function () {
            Route::get('/', [RolesController::class, 'index']);
            Route::post('store', [RolesController::class, 'store']);
            Route::put('update', [RolesController::class, 'update']);
            Route::delete('delete/{id}', [RolesController::class, 'delete']);
        });

        //Permisos
        Route::prefix('permisos')->group(function () {
            Route::get('/', [PermissionsController::class, 'index']);
            Route::post('store', [PermissionsController::class, 'store']);
            Route::put('update', [PermissionsController::class, 'update']);
            Route::delete('delete/{id}', [PermissionsController::class, 'delete']);
        });

        //Users
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('store', [UserController::class, 'store']);
            Route::put('update', [UserController::class, 'update']);
            Route::delete('delete/{id}', [UserController::class, 'delete']);
        });
    });
});
