<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Asset;
use App\Models\AssetStatus;
use App\Models\Request;
use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;

class ReportController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the reports.
     */
    public function index(): View
    {
        $this->authorize('view_reports');

        // 1. Сводка по статусам техники
        $assetStatusSummary = AssetStatus::withCount('assets')->get()->map(function ($status) {
            return [
                'name' => $status->name,
                'count' => $status->assets_count,
            ];
        });

        // 2. Распределение техники по возрасту (по году покупки)
        $assetAgeDistribution = Asset::selectRaw('purchase_year, count(*) as count')
            ->whereNotNull('purchase_year')
            ->groupBy('purchase_year')
            ->orderBy('purchase_year', 'asc')
            ->get();

        // 3. Частота ремонта (количество заявок на ремонт по месяцам за последний год)
        $repairFrequency = Request::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, count(*) as count')
            ->where('type', 'repair')
            ->where('created_at', '>=', Carbon::now()->subYear())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // 4. Техника по местоположению (по локациям) - ИСПРАВЛЕНО
        $assetsByLocation = Location::with(['floors.rooms.assets'])->get()->map(function ($location) {
            $assetCount = 0;
            foreach ($location->floors as $floor) {
                foreach ($floor->rooms as $room) {
                    $assetCount += $room->assets->count(); // Теперь assets - это коллекция, а не withCount
                }
            }
            return [
                'name' => $location->name,
                'count' => $assetCount,
            ];
        });

        // 5. Техника по ответственным лицам (топ-10)
        $assetsByResponsiblePerson = User::withCount('currentAssets')
            ->orderBy('current_assets_count', 'desc')
            ->take(10)
            ->get()->map(function ($user) {
                return [
                    'name' => $user->name,
                    'count' => $user->current_assets_count,
                ];
            });


        return view('reports.index', compact(
            'assetStatusSummary',
            'assetAgeDistribution',
            'repairFrequency',
            'assetsByLocation',
            'assetsByResponsiblePerson'
        ));
    }
}
