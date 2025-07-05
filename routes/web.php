<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

    // Маршруты для модулей системы (пока заглушки)
    // Группа маршрутов для активов
    Route::prefix('assets')->name('assets.')->group(function () {
        Route::get('/', function () { return view('assets.index'); })->name('index')->middleware('can:view_assets');
        // Добавьте другие маршруты для CRUD: create, store, show, edit, update, destroy
    });

    // Группа маршрутов для пользователей (ответственных лиц)
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', function () { return view('users.index'); })->name('index')->middleware('can:view_users');
        // Добавьте другие маршруты для CRUD
    });

    // Группа маршрутов для локаций, этажей, кабинетов
    Route::prefix('locations')->name('locations.')->group(function () {
        Route::get('/', function () { return view('locations.index'); })->name('index')->middleware('can:view_locations');
        // Маршруты для floors и rooms могут быть вложенными или отдельными
        Route::get('/floors', function () { return view('floors.index'); })->name('floors.index')->middleware('can:view_locations');
        Route::get('/rooms', function () { return view('rooms.index'); })->name('rooms.index')->middleware('can:view_locations');
    });

    // Группа маршрутов для склада и перемещений
    Route::prefix('stock-movements')->name('stock_movements.')->group(function () {
        Route::get('/', function () { return view('stock_movements.index'); })->name('index')->middleware('can:view_stock_movements');
    });

    // Группа маршрутов для заявок
    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/', function () { return view('requests.index'); })->name('index')->middleware('can:view_requests');
    });

    // Группа маршрутов для документов
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', function () { return view('documents.index'); })->name('index')->middleware('can:view_documents');
    });

    // Группа маршрутов для отчетов
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', function () { return view('reports.index'); })->name('index')->middleware('can:view_reports');
    });

    // Группа маршрутов для админских настроек
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/settings', function () { return view('admin.settings'); })->name('settings')->middleware('can:manage_settings');
        // Добавьте другие маршруты для управления ролями/разрешениями
    });
});

require __DIR__.'/auth.php';
