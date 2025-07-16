<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Inventory Scanner') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-neutral-900">
                    <h3 class="text-lg font-medium text-neutral-900 mb-4">{{ __('Scan QR Code') }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- QR Code Scanner Area -->
                        <div>
                            <p class="text-neutral-600 mb-4">{{ __('Point your camera at a QR code to scan it.') }}</p>
                            <div id="qr-reader" style="width:100%; max-width:500px;"></div>
                            <div id="qr-reader-results" class="mt-4 text-sm text-neutral-700"></div>
                        </div>

                        <!-- Scanned Item Details / Inventory List -->
                        <div>
                            <h4 class="font-semibold text-md text-neutral-900 mb-2">{{ __('Scanned Item Details') }}</h4>
                            <div id="scanned-item-details" class="bg-neutral-50 p-4 rounded-md border border-neutral-200 min-h-[100px]">
                                <p class="text-neutral-600">{{ __('Scan a QR code to see details here.') }}</p>
                            </div>

                            <h4 class="font-semibold text-md text-neutral-900 mt-6 mb-2">{{ __('Inventory Scan List') }}</h4>
                            <div id="inventory-list" class="bg-neutral-50 p-4 rounded-md border border-neutral-200 min-h-[150px]">
                                <p class="text-neutral-600">{{ __('Scanned items will appear here.') }}</p>
                                <ul id="scanned-items-ul" class="list-disc list-inside mt-2 space-y-1"></ul>
                                <button id="clear-scan-list" class="mt-4 inline-flex items-center px-4 py-2 bg-rose-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-rose-700 focus:bg-rose-700 active:bg-rose-900 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Clear Scan List') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        console.log('Script loaded: html5-qrcode'); // Отладка: Скрипт загружен

        function docReady(fn) {
            console.log('docReady function called'); // Отладка: docReady вызвана
            if (document.readyState === "complete" || document.readyState === "interactive") {
                setTimeout(fn, 1);
            } else {
                document.addEventListener("DOMContentLoaded", fn);
            }
        }

        docReady(function() {
            console.log('DOMContentLoaded or docReady callback executed'); // Отладка: DOM готов

            var html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader", { fps: 10, qrbox: { width: 250, height: 250 }, rememberLastUsedCamera: true });

            console.log('Html5QrcodeScanner instance created'); // Отладка: Экземпляр сканера создан

            function onScanSuccess(decodedText, decodedResult) {
                console.log(`Scan Success: ${decodedText}`, decodedResult); // Отладка: Успешное сканирование
                document.getElementById('qr-reader-results').innerHTML = `{{ __('Last Scanned:') }} <b>${decodedText}</b>`;

                fetch('{{ route('inventory_scanner.scan') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ qr_data: decodedText })
                })
                .then(response => {
                    console.log('Fetch response received'); // Отладка: Ответ от сервера
                    return response.json();
                })
                .then(data => {
                    console.log('Fetch data parsed:', data); // Отладка: Данные распарсены
                    const detailsDiv = document.getElementById('scanned-item-details');
                    const scannedListUl = document.getElementById('scanned-items-ul');

                    if (data.success) {
                        detailsDiv.innerHTML = `
                            <p class="text-secondary-700 font-semibold mb-2">{{ __('Found:') }} ${data.type === 'asset' ? '{{ __('Asset') }}' : '{{ __('Room') }}'}</p>
                            <p><strong>{{ __('Name') }}:</strong> ${data.name}</p>
                            ${data.type === 'asset' ? `<p><strong>{{ __('Serial Number') }}:</strong> ${data.serial_number}</p>` : ''}
                            ${data.type === 'asset' ? `<p><strong>{{ __('Inventory Number') }}:</strong> ${data.inventory_number}</p>` : ''}
                            ${data.type === 'room' ? `<p><strong>{{ __('Location') }}:</strong> ${data.location}</p>` : ''}
                            ${data.type === 'room' ? `<p><strong>{{ __('Floor') }}:</strong> ${data.floor}</p>` : ''}
                            <a href="${data.url}" class="text-primary-600 hover:underline mt-2 inline-block" target="_blank">{{ __('View Details') }}</a>
                        `;

                        const listItemId = `${data.type}-${data.id}`;
                        if (!document.getElementById(listItemId)) {
                            const listItem = document.createElement('li');
                            listItem.id = listItemId;
                            listItem.innerHTML = `${data.type === 'asset' ? '{{ __('Asset') }}' : '{{ __('Room') }}'}: ${data.name} (<a href="${data.url}" target="_blank" class="text-primary-600 hover:underline">{{ __('View') }}</a>)`;
                            scannedListUl.appendChild(listItem);
                        }
                    } else {
                        detailsDiv.innerHTML = `<p class="text-rose-600">{{ __('Error:') }} ${data.message}</p>`;
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error); // Отладка: Ошибка запроса
                    document.getElementById('scanned-item-details').innerHTML = `<p class="text-rose-600">{{ __('An error occurred while fetching data.') }}</p>`;
                });
            }

            function onScanError(errorMessage) {
                console.warn(`Scan Error: ${errorMessage}`); // Отладка: Ошибка сканирования
                // Можно отобразить сообщение об ошибке пользователю, если нужно
            }

            html5QrcodeScanner.render(onScanSuccess, onScanError)
                .catch(error => {
                    console.error('Html5QrcodeScanner render failed:', error); // Отладка: Ошибка рендеринга сканера
                    document.getElementById('qr-reader-results').innerHTML = `<p class="text-rose-600">{{ __('Failed to start camera. Please ensure camera access is granted and no other application is using it.') }}</p>`;
                });

            document.getElementById('clear-scan-list').addEventListener('click', function() {
                document.getElementById('scanned-items-ul').innerHTML = '';
                document.getElementById('scanned-item-details').innerHTML = `<p class="text-neutral-600">{{ __('Scan a QR code to see details here.') }}</p>`;
                document.getElementById('qr-reader-results').innerHTML = '';
            });
        });
    </script>
    @endpush
</x-app-layout>
