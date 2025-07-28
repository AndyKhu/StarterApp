<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth','crud.permission:Roles Management'])->group(function () {
    Route::get('/admin/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/admin/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::put('/admin/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/admin/roles/{role}', [RoleController::class, 'destroy'])->name('roles.delete');
});

Route::middleware(['auth','crud.permission:Users Management'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('users.delete');
});