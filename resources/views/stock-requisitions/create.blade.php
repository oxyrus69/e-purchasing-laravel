<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Permintaan Barang Internal</h2></x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('stock-requisitions.store') }}">
                        @csrf
                        <div class="mb-4"><label for="request_date">Tanggal Permintaan</label><input type="date" name="request_date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md" required></div>
                        <div class="border-t pt-4"><h3 class="font-semibold mb-2">Barang yang Diminta</h3><div id="items-container" class="space-y-3"><div class="flex items-center space-x-2 item-row"><select name="items[0][product_id]" class="w-1/2 form-select rounded-md" required><option value="">Pilih Barang</option>@foreach($products as $product)<option value="{{$product->id}}">{{$product->name}} (Stok: {{$product->stock}})</option>@endforeach</select><input type="number" name="items[0][quantity]" placeholder="Jumlah" class="w-1/4 form-input rounded-md" min="1" required></div></div><button type="button" id="add-item-btn" class="mt-4 text-sm text-blue-600 hover:text-blue-900 font-semibold"><i class="fas fa-plus-circle mr-1"></i>Tambah Barang</button></div>
                        <div class="flex items-center justify-end mt-4"><button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md">Simpan</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let itemIndex = 0; const container = document.getElementById('items-container');
            document.getElementById('add-item-btn').addEventListener('click', function() {
                itemIndex++; const newItemRow = document.createElement('div');
                newItemRow.className = 'flex items-center space-x-2 item-row';
                newItemRow.innerHTML = `<select name="items[${itemIndex}][product_id]" class="w-1/2 form-select rounded-md" required><option value="">Pilih Barang</option>@foreach($products as $product)<option value="{{$product->id}}">{{$product->name}} (Stok: {{$product->stock}})</option>@endforeach</select><input type="number" name="items[${itemIndex}][quantity]" placeholder="Jumlah" class="w-1/4 form-input rounded-md" min="1" required><button type="button" class="remove-item-btn text-red-500 hover:text-red-700 p-2"><i class="fas fa-trash-alt"></i></button>`;
                container.appendChild(newItemRow);
            });
            container.addEventListener('click', function(event) {
                const removeButton = event.target.closest('.remove-item-btn'); if (removeButton) { removeButton.closest('.item-row').remove(); }
            });
        });
    </script>
</x-app-layout>