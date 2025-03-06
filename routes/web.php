<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// app/Http/Controllers/dashboard/ClientMemberController.php
use App\Http\Controllers\dashboard\ClientMemberController;
use App\Http\Controllers\dashboard\SuperAdminController;
use App\Http\Controllers\dashboard\ClientAdminController;
use App\Http\Controllers\dashboard\ShortUrlController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/invitation/{token}', [ProfileController::class, 'invitation'])->name('invitation');
Route::get('/client-member-invitation/{token}', [ProfileController::class, 'clientMemberInvitation'])->name('client.member.invitation');
Route::get('/short-url/{shortCode}', [ShortUrlController::class, 'show']);
Route::middleware(['auth', 'protected'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/short-url', [ShortUrlController::class, 'store'])->name('short_url');

    
    Route::prefix('sadmin')->group(function () {
        Route::get('dashboard', [SuperAdminController::class, 'index'])->name('super-admin.dashboard');
        Route::get('invitation-form', [SuperAdminController::class, 'invitationForm'])->name('super-admin.invitation-form');
        Route::post('send-invitation', [SuperAdminController::class, 'sendInvitationToClient'])->name('super-admin.send-invitation');
    });
    Route::prefix('cadmin')->group(function () {
        Route::get('dashboard', [ClientAdminController::class, 'index'])->name('client-admin.dashboard');
        Route::get('invitation-form', [ClientAdminController::class, 'invitationForm'])->name('client-admin.invitation-form');
        Route::post('send-invitation', [ClientAdminController::class, 'sendInvitationToClient'])->name('client-admin.send-invitation');
        Route::get('download', [ClientAdminController::class, 'downloadReport'])->name('client-admin.download');
    });
    Route::get('client-member/dashboard', [ClientMemberController::class, 'index'])->name('client-member.dashboard');
    Route::get('url-shortner-form', [ClientMemberController::class, 'form'])->name('url.shortner.form');
});

require __DIR__.'/auth.php';