<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Permintaan Pembelian: ') . $purchaseRequest->pr_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('purchase-requests.update', $purchaseRequest->id) }}">
                        @csrf
                        @method('PUT')
                        <!-- General Info -->
                        <div class="mb-4">
                            <label for="request_date" class="block text-sm font-medium text-gray-700">Tanggal Permintaan</label>
                            <input type="date" name="request_date" id="request_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('request_date', $purchaseRequest->request_date) }}" required>
                        </div>
                        
                        <!-- Dynamic Items -->
                        <div class="border-t pt-4">
                            <h3 class="text-lg font-semibold mb-2">Barang yang Diminta</h3>
                            <div id="items-container" class="space-y-3">
                                {{-- Menampilkan item yang sudah ada --}}
                                @foreach ($purchaseRequest->items as $index => $item)
                                <div class="flex items-center space-x-2 item-row">
                                    {{-- Hidden input untuk menyimpan ID item yang sudah ada --}}
                                    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                    <select name="items[{{ $index }}][product_id]" class="w-1/2 form-select rounded-md border-gray-300 shadow-sm" required>
                                        @foreach($products as $product)
                                           <option value="{{ $product->id }}" @selected($item->product_id == $product->id)>{{ $product->name }} ({{ $product->unit }})</option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="items[{{ $index }}][quantity]" placeholder="Jumlah" class="w-1/4 form-input rounded-md border-gray-300 shadow-sm" value="{{ $item->quantity }}" min="1" required>
                                    <button type="button" class="remove-item-btn text-red-500 hover:text-red-700 p-2 rounded-md">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" id="add-item-btn" class="mt-4 text-sm text-blue-600 hover:text-blue-900 font-semibold">
                                <i class="fas fa-plus-circle mr-1"></i>Tambah Barang Baru
                            </button>
                        </div>

                        <div class="mt-6 border-t pt-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('notes', $purchaseRequest->notes) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('purchase-requests.show', $purchaseRequest->id) }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Update Permintaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Kita mulai index dari jumlah item yang sudah ada untuk item baru
            let itemIndex = {{ $purchaseRequest->items->count() }};
            const container = document.getElementById('items-container');

            document.getElementById('add-item-btn').addEventListener('click', function() {
                const newItemRow = document.createElement('div');
                newItemRow.className = 'flex items-center space-x-2 item-row';
                
                // Konten untuk baris baru (tanpa ID)
                newItemRow.innerHTML = `
                    <select name="items[new_${itemIndex}][product_id]" class="w-1/2 form-select rounded-md border-gray-300 shadow-sm" required>
                        <option value="">Pilih Barang</option>
                        @foreach($products as $product)
                           <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->unit }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="items[new_${itemIndex}][quantity]" placeholder="Jumlah" class="w-1/4 form-input rounded-md border-gray-300 shadow-sm" min="1" required>
                    <button type="button" class="remove-item-btn text-red-500 hover:text-red-700 p-2 rounded-md">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                `;
                container.appendChild(newItemRow);
                itemIndex++;
            });

            container.addEventListener('click', function(event) {
                const removeButton = event.target.closest('.remove-item-btn');
                if (removeButton) {
                    removeButton.closest('.item-row').remove();
                }
            });
        });
    </script>
</x-app-layout>