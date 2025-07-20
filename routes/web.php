<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProjectManagerController;

// Auth routes
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('/auth', [AuthController::class, 'auth']);
Route::post('/auth', [AuthController::class, 'auth'])->name('auth');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman admin
Route::prefix('admin')->middleware('auth.custom')->group(function () {
    Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('addnewproject', [AdminController::class, 'addNewProject'])->name('admin.addnewproject');
    Route::post('addnewproject', [AdminController::class, 'store']);
    Route::get('history', [AdminController::class, 'historyProject'])->name('admin.history');
    Route::get('history/updateproject/{id}', [AdminController::class, 'updateHistoryProject'])->name('admin.history.updateproject');
    Route::put('history/updateproject/save', [AdminController::class, 'updateHistoryProject'])->name('admin.history.updateproject.save');
    Route::get('download/{id}', [AdminController::class, 'download'])->name('admin.download');
    Route::get('history/document/{id}', [AdminController::class, 'docsHistory'])->name('admin.history.document');
});

// Halaman project manager
Route::prefix('project-manager')->middleware('auth.custom')->group(function () {
    Route::get('dashboard', [ProjectManagerController::class, 'index'])->name('projectmanager.dashboard');
    Route::get('addnewproject', [ProjectManagerController::class, 'addNewProject'])->name('projectmanager.addnewproject');
    Route::post('addnewproject', [ProjectManagerController::class, 'store'])->name('projectmanager.addnewproject.store');
    Route::get('listproject', [ProjectManagerController::class, 'listProject'])->name('projectmanager.listproject');
    Route::get('history', [ProjectManagerController::class, 'historyProject'])->name('projectmanager.history');
    Route::get('history/updateproject/{id}', [ProjectManagerController::class, 'updateHistoryProject'])->name('projectmanager.history.updateproject');
    Route::put('history/updateproject/save', [ProjectManagerController::class, 'updateHistoryProject'])->name('projectmanager.history.updateproject.save');
    Route::get('download/{id}', [ProjectManagerController::class, 'download'])->name('projectmanager.download');
    Route::get('history/document/{id}', [ProjectManagerController::class, 'docsHistory'])->name('projectmanager.history.document');

    // Fitur
    Route::get('manageproject/{id}', [ProjectManagerController::class, 'manageProject'])->name('projectmanager.manageproject');
    Route::post('manageproject/save', [ProjectManagerController::class, 'saveFeatures'])->name('projectmanager.manageproject.save');
    Route::post('manageproject/update/{id}', [ProjectManagerController::class, 'updateFeature'])->name('projectmanager.manageproject.update');
    Route::get('manageproject/delete/{id}', [ProjectManagerController::class, 'deleteFeature'])->name('projectmanager.manageproject.delete');
    Route::get('manageproject/feature-uat/{id}', [ProjectManagerController::class, 'featureUAT'])->name('projectmanager.featureuat');
    Route::put('manageproject/feature-uat', [ProjectManagerController::class, 'featureUAT'])->name('projectmanager.featureuat.update');

    // Generate UAT
    Route::get('generate-pdf/{id}', [ProjectManagerController::class, 'generatePDF'])->name('projectmanager.generatepdf');
});
