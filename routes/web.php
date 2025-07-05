<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\ManageUserController;
use App\Http\Controllers\Manage\ManageTempUsersController;
use App\Http\Controllers\Manage\ManageCWalletController;


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

    Route::get('/manage/wallet', [ManageCWalletController::class, 'index'])->name('managewallet.index');
    Route::get('/manage/wallet/{walletid}/users', [ManageCWalletController::class, 'userlist'])->name('managewallet.userlist');
    Route::post('/manage/wallet/{walletid}/users/{userid}', [ManageCWalletController::class, 'userliststore'])->name('managewallet.userliststore');
    Route::post('/manage/wallet', [ManageCWalletController::class, 'store'])->name('managewallet.store');
    Route::get('/manage/wallet/create', [ManageCWalletController::class, 'create'])->name('managewallet.create');
    Route::get('/manage/wallet/search', [ManageCWalletController::class, 'search'])->name('managewallet.search');
    Route::get('/manage/wallet/{walletid}/users/search', [ManageCWalletController::class, 'userlistsearch'])->name('managewallet.userlistsearch');
    Route::get('/manage/wallet/{wallet}', [ManageCWalletController::class, 'show'])->name('managewallet.show');
    Route::patch('/manage/wallet/{wallet}', [ManageCWalletController::class, 'update'])->name('managewallet.update');
    Route::delete('/manage/wallet/{wallet}', [ManageCWalletController::class, 'destroy'])->name('managewallet.destroy');
    Route::get('/manage/wallet/{wallet}/edit', [ManageCWalletController::class, 'edit'])->name('managewallet.edit');

});
require __DIR__.'/auth.php';
