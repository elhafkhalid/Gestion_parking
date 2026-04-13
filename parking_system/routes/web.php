<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VisiteurController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgentRequestController;


Route::get('/', [VisiteurController::class, 'index'])
    ->name('visiteur.dashboard');
    
    
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return "Dashboard Admin";
    })->name('admin.dashboard');
});

Route::middleware(['auth', 'role:agent'])->group(function () {
    Route::get('/agent/dashboard', function () {
        return "Dashboard Agent";
    })->name('agent.dashboard');
});

Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/user/dashboard', [UserController::class, 'index'])
        ->name('user.dashboard');
});

Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get(
        '/user/become-agent',
        [AgentRequestController::class, 'create']
    )->name('user.agent.create');

    Route::post(
        '/user/become-agent',
        [AgentRequestController::class, 'store']
    )->name('user.agent.store');

    Route::post(
        '/user/become-agent/step',
        [AgentRequestController::class, 'processStep']
    )
        ->name('user.agent.step');
});


