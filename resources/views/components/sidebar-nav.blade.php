@php
// Вспомогательная функция для проверки активности маршрутов
function areRoutesActive(array $routes) {
    foreach ($routes as $route) {
        if (request()->routeIs($route)) {
            return true;
        }
    }
    return false;
}
@endphp

<nav class="p-4 space-y-2">
    <x-side-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        <i class="fas fa-tachometer-alt w-6 text-center"></i>
        <span class="ms-3">{{ __('Dashboard') }}</span>
    </x-side-nav-link>

    <!-- ИЗМЕНЕНИЕ: Ссылка теперь ведет на список сессий (index), а не на страницу создания (create) -->
    @can('scan_inventory')
        <x-side-nav-link :href="route('global-inventory.index')" :active="request()->routeIs('global-inventory.*')">
            <i class="fas fa-tasks w-6 text-center"></i>
            <span class="ms-3">{{ __('Global Inventory') }}</span>
        </x-side-nav-link>
    @endcan
    
    <x-side-nav-link :href="route('inventory_scanner.index')" :active="request()->routeIs('inventory_scanner.*')">
        <i class="fas fa-qrcode w-6 text-center"></i>
        <span class="ms-3">{{ __('Scanner') }}</span>
    </x-side-nav-link>

    <!-- Выпадающий список для Инвентаря -->
    <div x-data="{ open: {{ areRoutesActive(['assets.*', 'asset-categories.*', 'asset-statuses.*']) ? 'true' : 'false' }} }">
        <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium text-gray-300 hover:bg-primary-dark/50 hover:text-white rounded-lg transition-colors duration-200">
            <span class="inline-flex items-center">
                <i class="fas fa-boxes w-6 text-center"></i>
                <span class="ms-3">{{ __('Inventory') }}</span>
            </span>
            <i class="fas" :class="{'fa-chevron-down': !open, 'fa-chevron-up': open}"></i>
        </button>
        <div x-show="open" x-transition class="mt-2 space-y-2 pl-6">
            @can('view_assets')
                <x-side-nav-link :href="route('assets.index')" :active="request()->routeIs('assets.*')">{{ __('Assets') }}</x-side-nav-link>
            @endcan
            @can('manage_asset_categories')
                <x-side-nav-link :href="route('asset-categories.index')" :active="request()->routeIs('asset-categories.*')">{{ __('Categories') }}</x-side-nav-link>
            @endcan
            @can('manage_asset_statuses')
                 <x-side-nav-link :href="route('asset-statuses.index')" :active="request()->routeIs('asset-statuses.*')">{{ __('Statuses') }}</x-side-nav-link>
            @endcan
        </div>
    </div>

    <!-- Выпадающий список для Локаций -->
    <div x-data="{ open: {{ areRoutesActive(['locations.*', 'floors.*', 'rooms.*']) ? 'true' : 'false' }} }">
        <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium text-gray-300 hover:bg-primary-dark/50 hover:text-white rounded-lg transition-colors duration-200">
            <span class="inline-flex items-center">
                <i class="fas fa-map-marker-alt w-6 text-center"></i>
                <span class="ms-3">{{ __('Locations') }}</span>
            </span>
            <i class="fas" :class="{'fa-chevron-down': !open, 'fa-chevron-up': open}"></i>
        </button>
        <div x-show="open" x-transition class="mt-2 space-y-2 pl-6">
            @can('view_locations')
                <x-side-nav-link :href="route('locations.index')" :active="request()->routeIs('locations.*')">{{ __('Buildings') }}</x-side-nav-link>
                <x-side-nav-link :href="route('floors.index')" :active="request()->routeIs('floors.*')">{{ __('Floors') }}</x-side-nav-link>
                <x-side-nav-link :href="route('rooms.index')" :active="request()->routeIs('rooms.*')">{{ __('Rooms') }}</x-side-nav-link>
            @endcan
        </div>
    </div>

    @can('view_users')
        <x-side-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
            <i class="fas fa-users w-6 text-center"></i>
            <span class="ms-3">{{ __('Users') }}</span>
        </x-side-nav-link>
    @endcan
    
    @can('view_stock_movements')
        <x-side-nav-link :href="route('stock-movements.index')" :active="request()->routeIs('stock-movements.*')">
            <i class="fas fa-truck-loading w-6 text-center"></i>
            <span class="ms-3">{{ __('Movements') }}</span>
        </x-side-nav-link>
    @endcan

    @can('view_requests')
        <x-side-nav-link :href="route('requests.index')" :active="request()->routeIs('requests.*')">
            <i class="fas fa-tools w-6 text-center"></i>
            <span class="ms-3">{{ __('Requests') }}</span>
        </x-side-nav-link>
    @endcan

    @can('view_documents')
        <x-side-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.*')">
            <i class="fas fa-file-alt w-6 text-center"></i>
            <span class="ms-3">{{ __('Documents') }}</span>
        </x-side-nav-link>
    @endcan

    @can('view_reports')
        <x-side-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
            <i class="fas fa-chart-pie w-6 text-center"></i>
            <span class="ms-3">{{ __('Reports') }}</span>
        </x-side-nav-link>
    @endcan

    @can('manage_roles')
        <x-side-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.*')">
            <i class="fas fa-cogs w-6 text-center"></i>
            <span class="ms-3">{{ __('Admin') }}</span>
        </x-side-nav-link>
    @endcan
</nav>
