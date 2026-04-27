<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VisiteurController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;


Route::get('/', [VisiteurController::class, 'index'])->name('/');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:client'])->group(function () {
    Route::get('/client/dashboard', [ClientController::class, 'index'])->name('client.dashboard');
    Route::post('/reserve', [ClientController::class, 'reserve'])->name('client.reserve');
    Route::post('/reservation/{id}/cancel', [ClientController::class, 'cancel'])->name('client.reservation.cancel');
});


Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/become-agent', [RequestController::class, 'create'])->name('user.agent.create');
    Route::post('/user/become-agent/step', [RequestController::class, 'procesStep'])->name('user.agent.step');
    Route::post('/user/become-agent', [RequestController::class, 'store'])->name('user.agent.store');
});

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.delete');
    Route::post('/admin/parkings', [AdminController::class, 'storeParking'])->name('parkings.store');
    Route::put('/admin/parkings/{parking}', [AdminController::class, 'updateParking'])->name('parkings.update');
    Route::delete('/admin/parkings/{parking}', [AdminController::class, 'deleteParking'])->name('parkings.delete');
    Route::post('/admin/agent/{id}/accept', [AdminController::class, 'acceptAgent'])->name('admin.agent.accept');
    Route::post('/admin/agent/{id}/reject', [AdminController::class, 'rejectAgent'])->name('admin.agent.reject');
});

Route::middleware(['auth', 'role:agent'])->group(function () {
    Route::get('/agent/dashboard', [AgentController::class, 'index'])->name('agent.dashboard');
    Route::post('/agent/entry', [AgentController::class, 'storeEntry'])->name("agent.entry");
    Route::post('/agent/exit/{id}', [AgentController::class, 'storeExit'])->name('agent.exit');
    Route::get('/agent/places/{parking}', [AgentController::class, 'getPlaces'])->name('fetch.places');
    Route::post('/agent/reservation/{id}/confirm', [AgentController::class, 'confirmReservation'])->name('agent.confirm.reservation');
    Route::post('/agent/reservation/{id}/cancel', [AgentController::class, 'cancelReservation'])->name('agent.cancel.reservation');
});
