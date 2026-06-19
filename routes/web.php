<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\KirController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdditionalFeeTypeController;

// Authentication routes
Route::get('/signin', [AuthController::class, 'showLogin'])->name('signin');
Route::get('/login', function () {
    return redirect()->route('signin');
});
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/signup', [AuthController::class, 'showRegister'])->name('signup');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data Kendaraan
    Route::get('kendaraan/template', [KendaraanController::class, 'downloadTemplate'])->name('kendaraan.template');
    Route::post('kendaraan/import', [KendaraanController::class, 'import'])->name('kendaraan.import');
    Route::resource('kendaraan', KendaraanController::class);

    // Master Data Biaya Tambahan
    Route::patch('biaya-tambahan/{additional_fee_type}/toggle', [AdditionalFeeTypeController::class, 'toggleStatus'])->name('biaya-tambahan.toggle');
    Route::resource('biaya-tambahan', AdditionalFeeTypeController::class);

    // KIR routes
    Route::prefix('kir')->name('kir.')->group(function () {
        Route::get('/monitoring', [KirController::class, 'monitoring'])->name('monitoring');
        Route::get('/proses', [KirController::class, 'proses'])->name('proses');
        Route::get('/proses/{kendaraan}', [KirController::class, 'showProsesForm'])->name('proses-form');
        Route::post('/proses/{kendaraan}', [KirController::class, 'prosesStore'])->name('proses-store');
        Route::get('/history', [KirController::class, 'history'])->name('history');
        Route::get('/history/{history}/print', [KirController::class, 'printHistory'])->name('history.print');
        Route::get('/import', [KirController::class, 'importForm'])->name('import-form');
        Route::post('/import', [KirController::class, 'import'])->name('import');
    });

    // Report routes
    Route::prefix('report')->name('report.')->group(function () {
        Route::get('/export', [ReportController::class, 'export'])->name('export');
        Route::get('/rekap-biaya', [ReportController::class, 'rekapBiaya'])->name('rekap-biaya');
    });
});

// calender pages
Route::get('/calendar', function () {
    return view('pages.calender', ['title' => 'Calendar']);
})->name('calendar');

// profile pages
Route::get('/profile', function () {
    return view('pages.profile', ['title' => 'Profile']);
})->name('profile');

// form pages
Route::get('/form-elements', function () {
    return view('pages.form.form-elements', ['title' => 'Form Elements']);
})->name('form-elements');

// tables pages
Route::get('/basic-tables', function () {
    return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
})->name('basic-tables');

// pages

Route::get('/blank', function () {
    return view('pages.blank', ['title' => 'Blank']);
})->name('blank');

// error pages
Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');

// chart pages
Route::get('/line-chart', function () {
    return view('pages.chart.line-chart', ['title' => 'Line Chart']);
})->name('line-chart');

Route::get('/bar-chart', function () {
    return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
})->name('bar-chart');




// ui elements pages
Route::get('/alerts', function () {
    return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
})->name('alerts');

Route::get('/avatars', function () {
    return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
})->name('avatars');

Route::get('/badge', function () {
    return view('pages.ui-elements.badges', ['title' => 'Badges']);
})->name('badges');

Route::get('/buttons', function () {
    return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
})->name('buttons');

Route::get('/image', function () {
    return view('pages.ui-elements.images', ['title' => 'Images']);
})->name('images');

Route::get('/videos', function () {
    return view('pages.ui-elements.videos', ['title' => 'Videos']);
})->name('videos');






















