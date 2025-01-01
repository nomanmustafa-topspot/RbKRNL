<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', [AdminController::class, 'login']);

Route::post('loginsave', [AuthenticatedSessionController::class, 'store']);

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'userDashboard'])->name('dashboard');

    // Route::get('user-change-password/{id}', [AdminController::class, 'changePasswordById']);

});

Route::get('/generate-pdf', [PdfController::class, 'generatePDF']);
Route::get('/edit-pdf', [PdfController::class, 'editPDF']);


Route::get('/factor/list', [PdfController::class, 'getFactorList'])->name('getFactorList');
Route::post('/factor/save', [PdfController::class, 'saveFactor'])->name('factor.save');

Route::get('/pdf/list', [PdfController::class, 'getPdfList']);
Route::post('/upload-pdf', [PdfController::class, 'uploadPDF'])->name('upload.pdf');


Route::get('/add-client', [AdminController::class, 'addClient']);
Route::post('/save-client', [AdminController::class, 'createClient']);
Route::post('client-update', [AdminController::class, 'updateUser'])->name('client.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
