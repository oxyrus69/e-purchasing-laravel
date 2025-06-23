<!-- File: resources/views/purchase_requests/create.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Permintaan Pembelian Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('purchase-requests.store') }}">
                        @csrf
                        <!-- General Info -->
                        <div class="mb-4">
                            <label for="request_date" class="block text-sm font-medium text-gray-700">Tanggal Permintaan</label>
                            <input type="date" name="request_date" id="request_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        
                        <!-- Dynamic Items -->
                        <div id="items-container">
                             <h3 class="text-lg font-semibold mb-2">Barang yang Diminta</h3>
                             <!-- JavaScript will add item rows here -->
                             <div class="flex space-x-2 mb-2">
                                 <select name="products[0][product_id]" class="w-1/2 rounded-md border-gray-300 shadow-sm">
                                     <option value="">Pilih Barang</option>
                                     @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->unit }})</option>
                                     @endforeach
                                 </select>
                                 <input type="number" name="products[0][quantity]" placeholder="Jumlah" class="w-1/4 rounded-md border-gray-300 shadow-sm" min="1">
                             </div>
                        </div>
                        <button type="button" id="add-item-btn" class="mb-4 text-sm text-blue-600 hover:text-blue-900">Tambah Barang +</button>

                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>

                        <div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Simpan Permintaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Simple JS to add more items -->
    <script>
        document.getElementById('add-item-btn').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const index = container.children.length - 1; // to manage array index
            const newItemRow = document.createElement('div');
            newItemRow.className = 'flex space-x-2 mb-2';
            newItemRow.innerHTML = `
                <select name="products[${index}][product_id]" class="w-1/2 rounded-md border-gray-300 shadow-sm">
                     <option value="">Pilih Barang</option>
                     @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->unit }})</option>
                     @endforeach
                 </select>
                 <input type="number" name="products[${index}][quantity]" placeholder="Jumlah" class="w-1/4 rounded-md border-gray-300 shadow-sm" min="1">
            `;
            container.appendChild(newItemRow);
        });
    </script>
</x-app-layout>
