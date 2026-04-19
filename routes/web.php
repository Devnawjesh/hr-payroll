<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Modules\Settings\Http\Controllers\SettingsController;
use App\Modules\Departments\Http\Controllers\DepartmentController;
use App\Modules\Designations\Http\Controllers\DesignationController;
use App\Modules\Users\Http\Controllers\PermissionController;
use App\Modules\Users\Http\Controllers\RoleController;
use App\Modules\Users\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('throttle:6,1')->name('password.email');

Route::middleware('auth')->group(function (): void {
    Route::view('/dashboard', 'hr.dashboard.dashboard')->middleware('permission:dashboard.view')->name('dashboard');
    Route::get('/settings', [SettingsController::class, 'edit'])->middleware('permission:settings.view')->name('settings.edit');
    Route::put('/settings', [SettingsController::class, 'update'])->middleware('permission:settings.update')->name('settings.update');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::middleware('role.any:super-admin,hr-manager')->group(function (): void {
        Route::prefix('departments')->name('departments.')->group(function (): void {
            Route::get('/', [DepartmentController::class, 'index'])->middleware('permission:department.view')->name('index');
            Route::get('/create', [DepartmentController::class, 'create'])->middleware('permission:department.create')->name('create');
            Route::post('/', [DepartmentController::class, 'store'])->middleware('permission:department.create')->name('store');
            Route::get('/{department}/edit', [DepartmentController::class, 'edit'])->middleware('permission:department.update')->name('edit');
            Route::put('/{department}', [DepartmentController::class, 'update'])->middleware('permission:department.update')->name('update');
            Route::delete('/{department}', [DepartmentController::class, 'destroy'])->middleware('permission:department.delete')->name('destroy');
        });

        Route::prefix('designations')->name('designations.')->group(function (): void {
            Route::get('/', [DesignationController::class, 'index'])->middleware('permission:designation.view')->name('index');
            Route::get('/create', [DesignationController::class, 'create'])->middleware('permission:designation.create')->name('create');
            Route::post('/', [DesignationController::class, 'store'])->middleware('permission:designation.create')->name('store');
            Route::get('/{designation}/edit', [DesignationController::class, 'edit'])->middleware('permission:designation.update')->name('edit');
            Route::put('/{designation}', [DesignationController::class, 'update'])->middleware('permission:designation.update')->name('update');
            Route::delete('/{designation}', [DesignationController::class, 'destroy'])->middleware('permission:designation.delete')->name('destroy');
        });

        Route::prefix('users')->name('users.')->group(function (): void {
            Route::get('/', [UserController::class, 'index'])->middleware('permission:role.assign,role.view')->name('index');
            Route::get('/create', [UserController::class, 'create'])->middleware('permission:role.assign')->name('create');
            Route::post('/', [UserController::class, 'store'])->middleware('permission:role.assign')->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->middleware('permission:role.assign')->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->middleware('permission:role.assign')->name('update');
            Route::get('/{user}/approval', [UserController::class, 'approval'])->middleware('permission:role.assign')->name('approval');
            Route::post('/{user}/approval', [UserController::class, 'approveOrReject'])->middleware('permission:role.assign')->name('approval.process');
        });

        Route::prefix('roles')->name('roles.')->group(function (): void {
            Route::get('/', [RoleController::class, 'index'])->middleware('permission:role.view')->name('index');
            Route::get('/create', [RoleController::class, 'create'])->middleware('permission:role.create')->name('create');
            Route::post('/', [RoleController::class, 'store'])->middleware('permission:role.create')->name('store');
            Route::get('/{role}/edit', [RoleController::class, 'edit'])->middleware('permission:role.update')->name('edit');
            Route::put('/{role}', [RoleController::class, 'update'])->middleware('permission:role.update')->name('update');
            Route::get('/{role}/permissions', [RoleController::class, 'permissions'])->middleware('permission:role.assign')->name('permissions');
            Route::post('/{role}/permissions', [RoleController::class, 'syncPermissions'])->middleware('permission:role.assign')->name('permissions.sync');
        });

        Route::prefix('permissions')->name('permissions.')->group(function (): void {
            Route::get('/', [PermissionController::class, 'index'])->middleware('permission:role.view')->name('index');
            Route::get('/create', [PermissionController::class, 'create'])->middleware('permission:role.update')->name('create');
            Route::post('/', [PermissionController::class, 'store'])->middleware('permission:role.update')->name('store');
            Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->middleware('permission:role.update')->name('edit');
            Route::put('/{permission}', [PermissionController::class, 'update'])->middleware('permission:role.update')->name('update');
        });
    });
});
