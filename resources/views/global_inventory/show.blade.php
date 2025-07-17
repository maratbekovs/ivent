<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-text-primary leading-tight">
                    {{ __('Inventory in Progress') }}
                </h2>
                <p class="text-sm text-text-secondary mt-1">
                    {{ __('Room') }}: <span class="font-semibold">{{ $session->room->name }}</span>
                </p>
            </div>
            <form action="{{ route('global-inventory.complete', $session) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to complete this session?') }}')">
                @csrf
                <x-primary-button>
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ __('Complete Session') }}
                </x-primary-button>
            </form>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="inventoryScanner()">
        <!-- Scanner & Found List Column -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Scanner -->
            <div class="bg-surface p-6 rounded-lg shadow-md">
                <h3 class="font-semibold text-lg text-text-primary mb-4">{{ __('Scanner') }}</h3>
                <div class="w-full aspect-square bg-white border border-gray-200 rounded-md overflow-hidden">
                    <div id="reader"></div>
                </div>
                <div id="scanner-status" class="text-center text-sm text-text-secondary mt-4 h-5 transition-all">
                    <i class="fas fa-camera mr-2"></i>{{ __('Point the camera at a QR code.') }}
                </div>
            </div>

            <!-- Found List -->
            <div class="bg-surface p-6 rounded-lg shadow-md">
                <h3 class="font-semibold text-lg text-text-primary mb-4">{{ __('Found') }} (<span x-text="foundItems.length"></span>)</h3>
                <ul id="found-list" class="space-y-2 max-h-96 overflow-y-auto">
                    @foreach($foundItems as $item)
                        <li data-asset-id="{{ $item->asset_id }}" class="text-sm p-2 bg-green-50 rounded-md">{{ $item->asset->inventory_number ?? $item->asset->serial_number }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Missing & Extra Lists Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Missing List -->
            <div class="bg-surface p-6 rounded-lg shadow-md">
                <h3 class="font-semibold text-lg text-text-primary mb-4">{{ __('Missing') }} (<span x-text="missingItems.length"></span>)</h3>
                <ul id="missing-list" class="space-y-2 max-h-[40rem] overflow-y-auto">
                     @foreach($missingAssets as $asset)
                        <li data-asset-id="{{ $asset->id }}" class="text-sm p-2 bg-gray-50 rounded-md">{{ $asset->inventory_number ?? $asset->serial_number }}</li>
                    @endforeach
                </ul>
            </div>

            <!-- Extra List -->
            <div class="bg-surface p-6 rounded-lg shadow-md">
                <h3 class="font-semibold text-lg text-text-primary mb-4">{{ __('Extra') }} (<span x-text="extraItems.length"></span>)</h3>
                <ul id="extra-list" class="space-y-2 max-h-96 overflow-y-auto">
                    @foreach($extraItems as $item)
                        <li data-asset-id="{{ $item->asset_id }}" class="text-sm p-2 bg-yellow-50 rounded-md">{{ $item->asset->inventory_number ?? $item->asset->serial_number }} <span class="text-xs text-yellow-700">({{ __('Expected in') }}: {{ $item->asset->room->name ?? 'N/A' }})</span></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode/html5-qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function inventoryScanner() {
            return {
                missingItems: @json($missingAssets->pluck('id')),
                foundItems: @json($foundItems->pluck('asset_id')),
                extraItems: @json($extraItems->pluck('asset_id')),

                handleScan(decodedText) {
                    axios.post('{{ route("global-inventory.scan", $session) }}', {
                        qr_code_data: decodedText,
                        _token: '{{ csrf_token() }}'
                    })
                    .then(response => {
                        const data = response.data;
                        if (data.success) {
                            new Audio('https://cdn.jsdelivr.net/gh/joshwcomeau/use-sound-hook@master/sounds/pop-up-on.mp3').play();
                            this.updateLists(data.status, data.asset);
                        } else {
                            new Audio('https://cdn.jsdelivr.net/gh/joshwcomeau/use-sound-hook@master/sounds/error.mp3').play();
                            this.showToast(data.message, 'info');
                        }
                    })
                    .catch(error => {
                        new Audio('https://cdn.jsdelivr.net/gh/joshwcomeau/use-sound-hook@master/sounds/error.mp3').play();
                        this.showToast(error.response.data.message, 'error');
                    });
                },

                updateLists(status, asset) {
                    if (status === 'found') {
                        const missingItemEl = document.querySelector(`#missing-list li[data-asset-id="${asset.id}"]`);
                        if (missingItemEl) {
                            missingItemEl.remove();
                            this.missingItems = this.missingItems.filter(id => id !== asset.id);
                        }
                        
                        const foundList = document.getElementById('found-list');
                        foundList.insertAdjacentHTML('afterbegin', `<li data-asset-id="${asset.id}" class="text-sm p-2 bg-green-50 rounded-md animate-pulse">${asset.name}</li>`);
                        this.foundItems.push(asset.id);
                        this.showToast(`${asset.name} ${'{{ __("found") }}'}`, 'success');

                    } else if (status === 'extra') {
                        const extraList = document.getElementById('extra-list');
                        extraList.insertAdjacentHTML('afterbegin', `<li data-asset-id="${asset.id}" class="text-sm p-2 bg-yellow-50 rounded-md animate-pulse">${asset.name} <span class="text-xs text-yellow-700">({{ __('Expected in') }}: ${asset.expected_room})</span></li>`);
                        this.extraItems.push(asset.id);
                        this.showToast(`${asset.name} ${'{{ __("is extra") }}'}`, 'warning');
                    }
                },

                showToast(message, type = 'info') {
                    const statusEl = document.getElementById('scanner-status');
                    statusEl.textContent = message;
                    
                    const colorClass = {
                        success: 'text-green-600',
                        info: 'text-blue-600',
                        warning: 'text-yellow-600',
                        error: 'text-red-600'
                    };
                    statusEl.className = `text-center text-sm mt-4 h-5 transition-all font-semibold ${colorClass[type]}`;

                    setTimeout(() => {
                        statusEl.textContent = '{{ __("Point the camera at a QR code.") }}';
                        statusEl.className = 'text-center text-sm text-text-secondary mt-4 h-5 transition-all';
                    }, 3000);
                },

                initScanner() {
                    const html5QrcodeScanner = new Html5QrcodeScanner("reader", {
                        fps: 10,
                        qrbox: {width: 250, height: 250},
                        rememberLastUsedCamera: true,
                    }, false);

                    const onScanSuccess = (decodedText, decodedResult) => {
                        html5QrcodeScanner.pause();
                        this.handleScan(decodedText);
                        setTimeout(() => {
                            if(html5QrcodeScanner.getState() === Html5QrcodeScannerState.PAUSED) {
                                html5QrcodeScanner.resume()
                            }
                        }, 1500); // Prevent rapid re-scans
                    };
                    
                    html5QrcodeScanner.render(onScanSuccess);
                },

                init() {
                    this.initScanner();
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
