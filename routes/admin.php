<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
