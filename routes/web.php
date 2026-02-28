<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\DepenceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;





Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'banned'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
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
    Route::post('/colocations/{colocation}/cancel', [ColocationController::class, 'cancel'])->name('colocations.cancel');
    // quitter une colocation (member)
    Route::post('/colocations/{colocation}/leave', [MembershipController::class, 'leave'])->name('colocations.leave');

    Route::post('/invitations/{token}/accept', [InvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('/invitations/{token}/refuse', [InvitationController::class, 'refuse'])->name('invitations.refuse');

    // catégories (owner seulement si tu veux)
    Route::post('/colocations/{colocation}/categories', [CategoryController::class, 'store'])
        ->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
        ->name('categories.destroy');

    // dépenses
    Route::get('/colocations/{colocation}/depences', [DepenceController::class, 'index'])
        ->name('depences.index');
    Route::post('/colocations/{colocation}/depences', [DepenceController::class, 'store'])
        ->name('depences.store');
    Route::delete('/depences/{depence}', [DepenceController::class, 'destroy'])
        ->name('depences.destroy');
    Route::post('/regles/{regle}/pay', [PaymentController::class, 'pay'])->name('settlements.pay');

});
Route::get('/invitations/{token}', [InvitationController::class, 'show'])->name('invitations.show');

Route::middleware(['auth','banned','role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/users/{user}/ban', [AdminController::class, 'ban'])->name('admin.users.ban');
    Route::post('/admin/users/{user}/unban', [AdminController::class, 'unban'])->name('admin.users.unban');
});

Route::middleware(['auth', 'banned', 'role:owner'])->group(function () {
    Route::post('/colocations/{colocation}/invitations', [InvitationController::class, 'store'])
        ->name('invitations.store');
    // retirer un membre (owner)
    Route::post('/colocations/{colocation}/members/{user}/remove', [MembershipController::class, 'remove'])
        ->name('colocations.members.remove');
});




require __DIR__.'/auth.php';




