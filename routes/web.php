<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\MaintenanceController; // Pastikan Controller ini dibuat nanti
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() 
        ? redirect('/dashboard') 
        : redirect('/register');
});

// Grup Route yang Membutuhkan Login (Auth)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Utama (Menampilkan daftar tiket sesuai role)
    Route::get('/dashboard', [TicketController::class, 'index'])->name('dashboard');

    // --- FITUR TICKETING ---
    
    // 1. Alur Karyawan: Membuat Tiket
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');

    // 2. Alur SPV: Mendelegasikan Tiket ke Staff IT
    Route::patch('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');

    // 3. Alur Staff IT: Mengubah Status Tiket (On-Progress/Resolved)
    Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');


    // --- FITUR MAINTENANCE (Menu Baru) ---

    // Route untuk Staff IT (Melihat Jadwal)
    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');

    // Route untuk SPV (Membuat Jadwal)
    Route::get('/maintenance/create', [MaintenanceController::class, 'create'])->name('maintenance.create');
    Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
    Route::patch('/maintenance/{maintenance}/assign', [MaintenanceController::class, 'assign'])->name('maintenance.assign');
    Route::patch('/maintenance/{maintenance}/complete', [MaintenanceController::class, 'complete'])->name('maintenance.complete');


    // --- FITUR PROFILE (Bawaan Laravel Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';