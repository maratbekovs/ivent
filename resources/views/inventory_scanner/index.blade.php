<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-text-primary leading-tight">
            {{ __('Inventory Scanner') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Scanner Column -->
        <div class="bg-surface p-6 rounded-lg shadow-md">
            <h3 class="font-semibold text-lg text-text-primary mb-4">{{ __('Scan QR Code') }}</h3>
            {{-- ИЗМЕНЕНИЕ: Фон изменен с bg-gray-900 на bg-white с рамкой --}}
            <div class="w-full aspect-square bg-white border border-gray-200 rounded-md overflow-hidden">
                <div id="reader"></div>
            </div>
            <div id="scanner-status" class="text-center text-sm text-text-secondary mt-4">
                <i class="fas fa-camera mr-2"></i>{{ __('Point the camera at a QR code to scan.') }}
            </div>
        </div>

        <!-- Result Column -->
        <div class="bg-surface p-6 rounded-lg shadow-md">
             <h3 class="font-semibold text-lg text-text-primary mb-4">{{ __('Scan Result') }}</h3>
             <div id="result-container" class="min-h-[300px] transition-opacity duration-300">
                <div id="result-placeholder" class="text-center py-12 flex flex-col items-center justify-center h-full">
                    <i class="fas fa-qrcode text-6xl text-gray-300"></i>
                    <p class="mt-4 text-text-secondary">{{ __('Waiting for scan...') }}</p>
                </div>
                <div id="result-content" class="hidden">
                    <!-- Dynamic content will be built here by JavaScript -->
                </div>
                <div id="result-error" class="hidden text-center py-12 flex flex-col items-center justify-center h-full">
                     <i class="fas fa-exclamation-triangle text-6xl text-red-400"></i>
                    <p id="error-message" class="mt-4 text-red-600 font-semibold"></p>
                </div>
             </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode/html5-qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const resultPlaceholder = document.getElementById('result-placeholder');
            const resultContent = document.getElementById('result-content');
            const resultError = document.getElementById('result-error');
            const errorMessage = document.getElementById('error-message');

            function showLoading() {
                resultPlaceholder.classList.add('hidden');
                resultError.classList.add('hidden');
                resultContent.innerHTML = `
                    <div class="text-center py-12 flex flex-col items-center justify-center h-full">
                        <i class="fas fa-spinner fa-spin text-4xl text-primary"></i>
                        <p class="mt-4 text-text-secondary">{{ __('Loading data...') }}</p>
                    </div>`;
                resultContent.classList.remove('hidden');
            }

            function showError(message) {
                resultContent.classList.add('hidden');
                errorMessage.textContent = message;
                resultError.classList.remove('hidden');
            }
            
            function buildResultHtml(data) {
                let detailsHtml = '';
                let typeBadge = '';

                if (data.type === 'asset') {
                    typeBadge = `<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-primary/10 text-primary mb-4 inline-block">{{ __('Asset') }}</span>`;
                    detailsHtml = `
                        <p><strong class="text-text-secondary w-32 inline-block">{{ __('Status') }}:</strong> ${data.status}</p>
                        <p><strong class="text-text-secondary w-32 inline-block">{{ __('Location') }}:</strong> ${data.location}</p>
                        <p><strong class="text-text-secondary w-32 inline-block">{{ __('Responsible') }}:</strong> ${data.responsible_person}</p>
                    `;
                } else if (data.type === 'room') {
                    typeBadge = `<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 mb-4 inline-block">{{ __('Room') }}</span>`;
                    detailsHtml = `
                        <p><strong class="text-text-secondary w-32 inline-block">{{ __('Floor') }}:</strong> ${data.floor}</p>
                        <p><strong class="text-text-secondary w-32 inline-block">{{ __('Building') }}:</strong> ${data.location}</p>
                        <p><strong class="text-text-secondary w-32 inline-block">{{ __('Assets in room') }}:</strong> ${data.assets_count}</p>
                    `;
                }

                resultContent.innerHTML = `
                    <div class="flex flex-col h-full">
                        <div>
                            <h4 class="text-lg font-bold text-text-primary">${data.name}</h4>
                            ${typeBadge}
                            <div class="space-y-2 text-sm">${detailsHtml}</div>
                        </div>
                        <div class="mt-auto pt-6 border-t border-border-color">
                            <a href="${data.url}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark">
                                {{ __('View Full Details') }}
                            </a>
                        </div>
                    </div>
                `;
            }

            function onScanSuccess(decodedText, decodedResult) {
                new Audio('https://cdn.jsdelivr.net/gh/joshwcomeau/use-sound-hook@master/sounds/pop-up-on.mp3').play();
                html5QrcodeScanner.pause();
                showLoading();

                axios.post('{{ route("inventory_scanner.scan") }}', {
                    qr_data: decodedText,
                    _token: '{{ csrf_token() }}'
                })
                .then(function (response) {
                    if (response.data.success) {
                        buildResultHtml(response.data);
                    } else {
                        showError(response.data.message || '{{ __("Unknown error") }}');
                    }
                })
                .catch(function (error) {
                    const message = error.response?.data?.message || '{{ __("Item not found or type not recognized.") }}';
                    showError(message);
                    console.error('Lookup Error:', error);
                })
                .finally(function() {
                    setTimeout(() => {
                        if (html5QrcodeScanner.getState() === Html5QrcodeScannerState.PAUSED) {
                            html5QrcodeScanner.resume();
                        }
                    }, 3000);
                });
            }

            let html5QrcodeScanner = new Html5QrcodeScanner("reader", {
                fps: 10,
                qrbox: {width: 250, height: 250},
                rememberLastUsedCamera: true,
                supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
            }, false);

            html5QrcodeScanner.render(onScanSuccess, (error) => {});
        });
    </script>
    @endpush
</x-app-layout>
