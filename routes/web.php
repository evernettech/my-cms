<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Enums\RoleEnum;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'zh'])) {
        abort(400);
    }
    
    session(['locale' => $locale]);
    app()->setLocale($locale); 

    return redirect()->back();
})->name('lang.switch');


Route::get('/dashboard', function () {
    $user = auth()->user();

    return match (true) {
        $user->hasRole('admin') => redirect()->route('admin.dashboard'),
        $user->hasRole('operator') => redirect()->route('operator.dashboard'),
        default => abort(403, 'Unauthorized'),
    };
})->middleware(['auth'])->name('dashboard');


Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'role:' . RoleEnum::Admin->value])->name('admin.dashboard');

Route::get('/operator/dashboard', function () {
    return view('operator.dashboard');
})->middleware(['auth', 'role:operator'])->name('operator.dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // User Management routes
    Route::get('/users/trash', [UserController::class, 'trash'])->name('users.trash');

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});




Route::middleware(['auth', 'role:' . RoleEnum::Admin->value . '|' . RoleEnum::Operator->value])->group(function () {

    // custom blogs route
    Route::get('/blogs/trash', [BlogController::class, 'trash'])->name('blogs.trash');
    Route::post('/blogs/{id}/restore', [BlogController::class, 'restore'])->name('blogs.restore');
    Route::delete('/blogs/{id}/force-delete', [BlogController::class, 'forceDelete'])->name('blogs.force-delete');

    Route::resource('blogs', BlogController::class);
    Route::resource('downloads', DownloadController::class);
});

Route::post('/tinymce/upload', [BlogController::class, 'uploadImage'])->name('tinymce.image-upload');


Route::get('/force-logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});


Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
