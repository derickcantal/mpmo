<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\ManageUserController;
use App\Http\Controllers\Manage\ManageTempUsersController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/manage/user', [ManageUserController::class, 'index'])->name('manageuser.index');
    Route::post('/manage/user', [ManageUserController::class, 'store'])->name('manageuser.store');
    Route::get('/manage/user/create', [ManageUserController::class, 'create'])->name('manageuser.create');
    Route::get('/manage/user/search', [ManageUserController::class, 'search'])->name('manageuser.search');
    Route::get('/manage/user/{user}', [ManageUserController::class, 'show'])->name('manageuser.show');
    Route::patch('/manage/user/{user}', [ManageUserController::class, 'update'])->name('manageuser.update');
    Route::delete('/manage/user/{user}', [ManageUserController::class, 'destroy'])->name('manageuser.destroy');
    Route::get('/manage/user/{user}/edit', [ManageUserController::class, 'edit'])->name('manageuser.edit');

    Route::get('/manage/users/temp', [ManageTempUsersController::class, 'index'])->name('managetempusers.index');
    Route::post('/manage/users/temp', [ManageTempUsersController::class, 'store'])->name('managetempusers.store');
    Route::get('/manage/users/temp/create', [ManageTempUsersController::class, 'create'])->name('managetempusers.create');
    Route::get('/manage/users/temp/search', [ManageTempUsersController::class, 'search'])->name('managetempusers.search');
    Route::get('/manage/users/temp/{tempusers}', [ManageTempUsersController::class, 'show'])->name('managetempusers.show');
    Route::patch('/manage/users/temp/{tempusers}', [ManageTempUsersController::class, 'update'])->name('managetempusers.update');
    Route::delete('/manage/users/temp/{tempusers}', [ManageTempUsersController::class, 'destroy'])->name('managetempusers.destroy');
    Route::get('/manage/users/temp/{tempusers}/edit', [ManageTempUsersController::class, 'edit'])->name('managetempusers.edit');

});
require __DIR__.'/auth.php';
