<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\ManageUserController;
use App\Http\Controllers\Manage\ManageTempUsersController;
use App\Http\Controllers\Manage\ManageCWalletController;
use App\Http\Controllers\Manage\ManageTransactionsController;
use App\Http\Controllers\Manage\ManageMailboxController;
use App\Http\Controllers\Manage\ManageMyProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QrController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ConvertController;
use App\Http\Controllers\ReferralController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $wallets = Auth::user()->wallets()->get();
    
    return view('dashboard',compact('wallets'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/ref/{code}', [ReferralController::class, 'handle'])->name('referral.handle');


Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::post('/wallet/create', [DashboardController::class, 'createWallet'])->name('wallet.create');

    Route::get('/wallets', [DashboardController::class, 'index'])->name('wallet.index');
    Route::get('/wallets/{address}/send', [DashboardController::class, 'showSendForm'])->name('wallet.send');
    Route::post('/wallets/send', [DashboardController::class, 'send'])->name('wallet.send.post');

    Route::get('/transactions', [DashboardController::class, 'transactionHistory'])->name('transactions.index');
    Route::get('/convert', [ConvertController::class,'show'])->name('convert');
    Route::post('/convert', [ConvertController::class,'execute'])->name('convert.execute');
    Route::get('/token',     [TokenController::class, 'index'])->name('token.index');
    Route::post('/token',    [TokenController::class, 'convert'])->name('token.convert');
    Route::post('/token/redeem', [TokenController::class, 'redeem'])->name('token.redeem');

    Route::get('/token-info', [TokenController::class,'showTokenInfo'])->name('token.info');
    Route::get('/transaction',[TransactionController::class,'index'])->name('transactions.index');
    Route::get('/token/price', [TokenController::class, 'price'])->name('token.price');
    Route::get('/token/procedures', [TokenController::class, 'procedures'])
         ->name('token.procedures');
    // 1. Airdrop
    Route::post('/token/airdrop', [TokenController::class, 'airdrop'])
         ->name('token.airdrop');

    // 2. Staking rewards distribution
    Route::post('/token/staking-rewards', [TokenController::class, 'distributeStakingRewards'])
         ->name('token.distributeStakingRewards');

    // 3. Liquidity incentives
    Route::post('/token/liquidity-incentives', [TokenController::class, 'incentivizeLiquidity'])
         ->name('token.incentivizeLiquidity');

    Route::get('/scan', [QrController::class, 'scan'])->name('qr.scan');
    Route::post('/submit-scan', [QrController::class, 'submit'])->name('qr.submit');

    
});

Route::middleware('auth')->group(function () {
    Route::get('/manage/myprofile', [ManageMyProfileController::class, 'index'])->name('managemyprofile.index');
    Route::get('/manage/myprofile/changepassword', [ManageMyProfileController::class, 'changepassword'])->name('managemyprofile.changepassword');
    Route::get('/manage/myprofile/signature', [ManageMyProfileController::class, 'signature'])->name('managemyprofile.signature');
    Route::get('/manage/myprofile/avatar', [ManageMyProfileController::class, 'myavatar'])->name('managemyprofile.myavatar');
    Route::patch('/manage/myprofile/avatar/update/{myprofile}', [ManageMyProfileController::class, 'savemyavatar'])->name('managemyprofile.savemyavatar');
    Route::patch('/manage/myprofile/signature/update/{myprofile}', [ManageMyProfileController::class, 'savesignature'])->name('managemyprofile.savesignature');
    Route::post('/manage/myprofile', [ManageMyProfileController::class, 'store'])->name('managemyprofile.store');
    Route::get('/manage/myprofile/create', [ManageMyProfileController::class, 'create'])->name('managemyprofile.create');
    Route::get('/manage/myprofile/search', [ManageMyProfileController::class, 'search'])->name('managemyprofile.search');
    Route::get('/manage/myprofile/{myprofile}', [ManageMyProfileController::class, 'show'])->name('managemyprofile.show');
    Route::patch('/manage/myprofile/{myprofile}', [ManageMyProfileController::class, 'update'])->name('managemyprofile.update');
    Route::delete('/manage/myprofile/{myprofile}', [ManageMyProfileController::class, 'destroy'])->name('managemyprofile.destroy');
    Route::get('/manage/myprofile/{myprofile}/edit', [ManageMyProfileController::class, 'edit'])->name('managemyprofile.edit');

    Route::get('/manage/user', [ManageUserController::class, 'index'])->name('manageuser.index');
    Route::post('/manage/user', [ManageUserController::class, 'store'])->name('manageuser.store');
    Route::post('/manage/user/{userid}/generate', [ManageUserController::class, 'createwallet'])->name('manageuser.createwallet');
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

    Route::get('/manage/transactions', [ManageTransactionsController::class, 'index'])->name('managetxn.index');
    Route::post('/manage/transactions', [ManageTransactionsController::class, 'store'])->name('managetxn.store');
    Route::post('/manage/transactions/convert', [ManageTransactionsController::class, 'storeconvert'])->name('managetxn.storeconvert');
    Route::post('/manage/transactions/deposit', [ManageTransactionsController::class, 'storedeposit'])->name('managetxn.storedeposit');
    Route::post('/manage/transactions/withdraw', [ManageTransactionsController::class, 'storewithdraw'])->name('managetxn.storewithdraw');
    Route::get('/manage/transactions/create', [ManageTransactionsController::class, 'create'])->name('managetxn.create');
    Route::get('/manage/transactions/convert', [ManageTransactionsController::class, 'convert'])->name('managetxn.convert');
    Route::get('/manage/transactions/deposit', [ManageTransactionsController::class, 'deposit'])->name('managetxn.deposit');
    Route::get('/manage/transactions/withraw', [ManageTransactionsController::class, 'withdraw'])->name('managetxn.withdraw');
    Route::get('/manage/transactions/search', [ManageTransactionsController::class, 'search'])->name('managetxn.search');
    Route::get('/manage/transactions/{txnid}', [ManageTransactionsController::class, 'show'])->name('managetxn.show');
    Route::patch('/manage/transactions/{txnid}', [ManageTransactionsController::class, 'update'])->name('managetxn.update');
    Route::delete('/manage/transactions/{txnid}', [ManageTransactionsController::class, 'destroy'])->name('managetxn.destroy');
    Route::get('/manage/transactions/{txnid}/edit', [ManageTransactionsController::class, 'edit'])->name('managetxn.edit');
    // Route::post('/manage/transactions/convert/execute', [ConvertController::class, 'execute'])->name('managetxn.convertexecute');

    Route::get('/manage/mailbox', [ManageMailboxController::class, 'index'])->name('managemailbox.index');
    Route::post('/manage/mailbox', [ManageMailboxController::class, 'store'])->name('managemailbox.store');
    Route::get('/manage/mailbox/create', [ManageMailboxController::class, 'create'])->name('managemailbox.create');
    Route::get('/manage/mailbox/search', [ManageMailboxController::class, 'search'])->name('managemailbox.search');
    Route::get('/manage/mailbox/{mailbox}', [ManageMailboxController::class, 'show'])->name('managemailbox.show');
    Route::patch('/manage/mailbox/{mailbox}', [ManageMailboxController::class, 'update'])->name('managemailbox.update');
    Route::delete('/manage/mailbox/{mailbox}', [ManageMailboxController::class, 'destroy'])->name('managemailbox.destroy');
    Route::get('/manage/mailbox/{mailbox}/edit', [ManageMailboxController::class, 'edit'])->name('managemailbox.edit');

});
require __DIR__.'/auth.php';
