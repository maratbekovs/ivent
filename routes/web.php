<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetCategoryController;
use App\Http\Controllers\AssetStatusController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InventoryScannerController;

// Добавлено: Фасад QrCode для генерации в маршруте
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Asset; // Для маршрута скачивания QR актива
use App\Models\Room;  // Для маршрута скачивания QR кабинета
use App\Models\Request as RequestModel; // Для подсчета заявок
use App\Models\Location; // Для подсчета локаций
use App\Models\Floor; // Для подсчета этажей
use App\Models\User; // Для подсчета пользователей
use App\Models\AssetStatus; // Для подсчета по статусам

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Получаем данные для дашборда
    $totalAssets = Asset::count();
    $totalRooms = Room::count();
    $totalLocations = Location::count();
    $totalFloors = Floor::count();
    $totalUsers = User::count();
    $openRequests = RequestModel::whereIn('status', ['pending', 'in_progress'])->count();

    // Активы по статусам
    $assetsByStatus = AssetStatus::withCount('assets')->get();

    return view('dashboard', compact(
        'totalAssets',
        'totalRooms',
        'totalLocations',
        'totalFloors',
        'totalUsers',
        'openRequests',
        'assetsByStatus'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Маршруты для скачивания QR-кодов
    Route::get('assets/{asset}/qrcode', function (Asset $asset) {
        if (!$asset->qr_code_data) {
            abort(404, __('QR Code data not found.'));
        }
        $svg = QrCode::size(200)->generate($asset->qr_code_data);
        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="' . ($asset->inventory_number ?? $asset->serial_number) . '_qrcode.svg"',
        ]);
    })->name('assets.qrcode')->middleware('can:view_assets');

    Route::get('rooms/{room}/qrcode', function (Room $room) {
        if (!$room->qr_code_data) {
            abort(404, __('QR Code data not found.'));
        }
        $svg = QrCode::size(200)->generate($room->qr_code_data);
        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="' . $room->name . '_qrcode.svg"',
        ]);
    })->name('rooms.qrcode')->middleware('can:view_locations');

    // Маршруты для модулей системы
    Route::resource('asset-categories', AssetCategoryController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('can:manage_asset_categories');

    Route::resource('asset-statuses', AssetStatusController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('can:manage_asset_statuses');

    Route::resource('assets', AssetController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy', 'show'])
        ->middleware('can:view_assets');

    Route::resource('locations', LocationController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('can:manage_locations');

    Route::resource('floors', FloorController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('can:manage_locations');

    Route::resource('rooms', RoomController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy', 'show'])
        ->middleware('can:manage_locations');

    Route::resource('users', UserController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('can:manage_users');

    Route::resource('stock-movements', StockMovementController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('can:manage_stock_movements');

    Route::resource('requests', RequestController::class)
        ->middleware('can:view_requests');

    Route::post('documents/{document}/sign', [DocumentController::class, 'sign'])->name('documents.sign')
        ->middleware('can:sign_documents');

    Route::resource('documents', DocumentController::class)
        ->middleware('can:view_documents');

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index')
            ->middleware('can:view_reports');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/settings', [AdminController::class, 'index'])->name('settings')
            ->middleware('can:manage_settings');

        Route::get('/roles', [AdminController::class, 'rolesIndex'])->name('roles.index')
            ->middleware('can:manage_roles');
        Route::get('/roles/{role}/edit', [AdminController::class, 'rolesEdit'])->name('roles.edit')
            ->middleware('can:manage_roles');
        // ИСПРАВЛЕНО: Использование Route::put() для обновления роли
        Route::put('/roles/{role}', [AdminController::class, 'rolesUpdate'])->name('roles.update')
            ->middleware('can:manage_roles');
    });

    // Маршруты для сканера инвентаря
    Route::prefix('inventory-scanner')->name('inventory_scanner.')->group(function () {
        Route::get('/', [InventoryScannerController::class, 'index'])->name('index')
            ->middleware('can:scan_inventory');
        Route::post('/scan', [InventoryScannerController::class, 'scan'])->name('scan')
            ->middleware('can:scan_inventory');
    });
});

require __DIR__.'/auth.php';
