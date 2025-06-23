<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Penyesuaian Stok (Stock Opname)</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('stock-adjustments.store') }}">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label for="product_id" class="block font-medium text-sm text-gray-700">Pilih Produk</label>
                                <select name="product_id" id="product_id" class="mt-1 block w-full form-select rounded-md" required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-stock="{{ $product->stock }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="current_stock" class="block font-medium text-sm text-gray-700">Stok Sistem Saat Ini</label>
                                <input id="current_stock" type="text" class="mt-1 block w-full bg-gray-100 form-input rounded-md" readonly>
                            </div>
                            <div>
                                <label for="new_stock" class="block font-medium text-sm text-gray-700">Stok Fisik Sebenarnya (Jumlah Baru)</label>
                                <input id="new_stock" name="new_stock" type="number" class="mt-1 block w-full form-input rounded-md" min="0" required>
                            </div>
                            <div>
                                <label for="notes" class="block font-medium text-sm text-gray-700">Alasan Penyesuaian</label>
                                <input id="notes" name="notes" type="text" placeholder="Contoh: Hasil Stock Opname, Barang Rusak" class="mt-1 block w-full form-input rounded-md" required>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border-transparent rounded-md font-semibold text-xs text-white uppercase">Simpan Penyesuaian</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('product_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const stock = selectedOption.dataset.stock;
            document.getElementById('current_stock').value = stock ? stock : 'Pilih produk terlebih dahulu';
        });
    </script>
</x-app-layout>