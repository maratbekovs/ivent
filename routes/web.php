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
// ИЗМЕНЕНИЕ: Добавлен импорт нового контроллера
use App\Http\Controllers\GlobalInventoryController;


// Фасады и модели, используемые в замыканиях
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Asset;
use App\Models\Room;
use App\Models\Request as RequestModel;
use App\Models\Location;
use App\Models\Floor;
use App\Models\User;
use App\Models\AssetStatus;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $totalAssets = Asset::count();
    $totalRooms = Room::count();
    $totalLocations = Location::count();
    $totalFloors = Floor::count();
    $totalUsers = User::count();
    $openRequests = RequestModel::whereIn('status', ['new', 'in_progress'])->count();
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

    // Ресурсные маршруты
    Route::resource('asset-categories', AssetCategoryController::class)->middleware('can:manage_asset_categories');
    Route::resource('asset-statuses', AssetStatusController::class)->middleware('can:manage_asset_statuses');
    Route::resource('assets', AssetController::class)->middleware('can:view_assets');
    Route::resource('locations', LocationController::class)->middleware('can:manage_locations');
    Route::resource('floors', FloorController::class)->middleware('can:manage_locations');
    Route::resource('rooms', RoomController::class)->middleware('can:manage_locations');
    Route::resource('users', UserController::class)->middleware('can:manage_users');
    Route::resource('stock-movements', StockMovementController::class)->middleware('can:manage_stock_movements');
    Route::resource('requests', RequestController::class)->middleware('can:view_requests');
    Route::resource('documents', DocumentController::class)->middleware('can:view_documents');
    
    // Отчеты
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index')->middleware('can:view_reports');

    // ... другие маршруты
    Route::get('stock-movements/{movement}/pdf', [\App\Http\Controllers\StockMovementController::class, 'downloadPdf'])->name('stock-movements.pdf')->middleware('can:view_stock_movements');
    Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download')->middleware('can:view_documents');
    // Админ-панель
    Route::prefix('admin')->name('admin.')->group(function () {
        // ИСПОЛЬЗУЕТСЯ ВАШ ПРАВИЛЬНЫЙ МАРШРУТ
        Route::get('/settings', [AdminController::class, 'index'])->name('settings')->middleware('can:manage_settings');
        Route::get('/roles', [AdminController::class, 'rolesIndex'])->name('roles.index')->middleware('can:manage_roles');
        Route::get('/roles/{role}/edit', [AdminController::class, 'rolesEdit'])->name('roles.edit')->middleware('can:manage_roles');
        Route::put('/roles/{role}', [AdminController::class, 'rolesUpdate'])->name('roles.update')->middleware('can:manage_roles');
    });

    // Сканер инвентаря (существующий)
    Route::prefix('inventory-scanner')->name('inventory_scanner.')->group(function () {
        Route::get('/', [InventoryScannerController::class, 'index'])->name('index')->middleware('can:scan_inventory');
        Route::post('/scan', [InventoryScannerController::class, 'scan'])->name('scan')->middleware('can:scan_inventory');
    });

    // ИЗМЕНЕНИЕ: Добавлена группа маршрутов для Глобальной инвентаризации
    Route::prefix('global-inventory')->name('global-inventory.')->middleware('can:scan_inventory')->group(function () {
        Route::get('/', [GlobalInventoryController::class, 'index'])->name('index');
        Route::get('/create', [GlobalInventoryController::class, 'create'])->name('create');
        Route::post('/', [GlobalInventoryController::class, 'store'])->name('store');
        Route::get('/{session}', [GlobalInventoryController::class, 'show'])->name('show');
        Route::post('/{session}/scan', [GlobalInventoryController::class, 'scan'])->name('scan');
        Route::post('/{session}/complete', [GlobalInventoryController::class, 'complete'])->name('complete');
        Route::get('/{session}/report', [GlobalInventoryController::class, 'report'])->name('report');
    });
});

require __DIR__.'/auth.php';
