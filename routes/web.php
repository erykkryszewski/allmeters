<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\DayController;

// redirect home to dashboard
Route::get('/', function () {
  // simple redirect so we have one entry point
  return redirect()->route('dashboard');
})->name('home');

// basic pages (placeholders for now)
Route::get('/dashboard', [PagesController::class, 'dashboard'])->name('dashboard');
Route::get('/entries/{date}', [PagesController::class, 'entries'])->name('entries');
Route::get('/reports', [PagesController::class, 'reports'])->name('reports');

Route::post('/entries/{date}', [DayController::class, 'store'])->name('entries.store');