<?php

use App\Http\Controllers\Admin\DomainController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

// Domain Manager routes (admin)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DomainController::class, 'dashboard'])->name('domains.dashboard');
    Route::get('/reports', [DomainController::class, 'reports'])->name('domains.reports');
    Route::get('/settings', [DomainController::class, 'settings'])->name('domains.settings');
    Route::post('/settings', [DomainController::class, 'saveSettings'])->name('domains.settings.save');
    Route::post('/domains/import', [DomainController::class, 'importCsv'])->name('domains.import');
    Route::get('/domains/export', [DomainController::class, 'exportCsv'])->name('domains.export');
    Route::get('/reports/export', [DomainController::class, 'exportReports'])->name('reports.export');
    Route::resource('domains', DomainController::class)->names([
        'index' => 'domains.index',
        'create' => 'domains.create',
        'store' => 'domains.store',
        'show' => 'domains.show',
        'edit' => 'domains.edit',
        'update' => 'domains.update',
        'destroy' => 'domains.destroy',
    ]);
    Route::post('domains/{domain}/renew', [DomainController::class, 'renew'])->name('domains.renew');
});

// Profile routes (from auth scaffolding)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
