<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\InvitationController;


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

Route::middleware(['auth', 'banned'])->group(function () {
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::get('/colocations/create', [ColocationController::class, 'create'])->name('colocations.create');
    Route::post('/colocations', [ColocationController::class, 'store'])->name('colocations.store');
    Route::get('/colocations/{colocation}', [ColocationController::class, 'show'])->name('colocations.show');
    Route::get('/my-colocation', [ColocationController::class, 'my'])->name('colocations.my');


    Route::post('/invitations/{token}/accept', [InvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('/invitations/{token}/refuse', [InvitationController::class, 'refuse'])->name('invitations.refuse');
});
Route::get('/invitations/{token}', [InvitationController::class, 'show'])->name('invitations.show');


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', fn () => 'Admin Dashboard');
});

Route::middleware(['auth', 'banned', 'role:owner'])->group(function () {
    Route::post('/colocations/{colocation}/invitations', [InvitationController::class, 'store'])
        ->name('invitations.store');
});





require __DIR__.'/auth.php';




