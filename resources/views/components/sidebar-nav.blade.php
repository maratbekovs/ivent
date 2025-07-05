<nav class="mt-4 flex-1">
    <ul class="space-y-2">
        <li>
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="block px-4 py-2 text-sm rounded-md hover:bg-primary-700 transition duration-150 ease-in-out">
                {{ __('Dashboard') }}
            </x-nav-link>
        </li>

        @can('view_assets')
        <li>
            <x-nav-link :href="route('assets.index')" :active="request()->routeIs('assets.*')" class="block px-4 py-2 text-sm rounded-md hover:bg-primary-700 transition duration-150 ease-in-out">
                {{ __('Inventory Management') }}
            </x-nav-link>
        </li>
        @endcan

        @can('view_users')
        <li>
            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="block px-4 py-2 text-sm rounded-md hover:bg-primary-700 transition duration-150 ease-in-out">
                {{ __('Responsible Persons') }}
            </x-nav-link>
        </li>
        @endcan

        @can('view_locations')
        <li>
            <x-nav-link :href="route('locations.index')" :active="request()->routeIs('locations.*') || request()->routeIs('floors.*') || request()->routeIs('rooms.*')" class="block px-4 py-2 text-sm rounded-md hover:bg-primary-700 transition duration-150 ease-in-out">
                {{ __('Locations & Rooms') }}
            </x-nav-link>
        </li>
        @endcan

        @can('view_stock_movements')
        <li>
            <x-nav-link :href="route('stock_movements.index')" :active="request()->routeIs('stock_movements.*')" class="block px-4 py-2 text-sm rounded-md hover:bg-primary-700 transition duration-150 ease-in-out">
                {{ __('Warehouse & Movements') }}
            </x-nav-link>
        </li>
        @endcan

        @can('view_requests')
        <li>
            <x-nav-link :href="route('requests.index')" :active="request()->routeIs('requests.*')" class="block px-4 py-2 text-sm rounded-md hover:bg-primary-700 transition duration-150 ease-in-out">
                {{ __('Requests & Service') }}
            </x-nav-link>
        </li>
        @endcan

        @can('view_documents')
        <li>
            <x-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.*')" class="block px-4 py-2 text-sm rounded-md hover:bg-primary-700 transition duration-150 ease-in-out">
                {{ __('Document Management') }}
            </x-nav-link>
        </li>
        @endcan

        @can('view_reports')
        <li>
            <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="block px-4 py-2 text-sm rounded-md hover:bg-primary-700 transition duration-150 ease-in-out">
                {{ __('Reports') }}
            </x-nav-link>
        </li>
        @endcan

        @can('manage_roles')
        <li>
            <x-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.*')" class="block px-4 py-2 text-sm rounded-md hover:bg-primary-700 transition duration-150 ease-in-out">
                {{ __('Admin Settings') }}
            </x-nav-link>
        </li>
        @endcan
    </ul>
</nav>
