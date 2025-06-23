<!-- File: resources/views/purchase_orders/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Purchase Order Baru dari PR: {{ $purchaseRequest->pr_number }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('purchase-orders.store') }}">
                        @csrf
                        <input type="hidden" name="purchase_request_id" value="{{ $purchaseRequest->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label for="order_date" class="block text-sm font-medium text-gray-700">Tanggal PO <span class="text-red-500">*</span></label>
                                <input type="date" name="order_date" id="order_date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                            <div class="md:col-span-2">
                                <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier <span class="text-red-500">*</span></label>
                                <select name="supplier_id" id="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <h3 class="text-lg font-semibold border-t pt-4 mb-2">Detail Barang</h3>
                        <div class="overflow-x-auto">
                             <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Nama Barang</th>
                                        <th class="px-4 py-2 text-left">Jumlah</th>
                                        <th class="px-4 py-2 text-left">Harga Satuan (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchaseRequest->items as $index => $item)
                                    <tr class="border-b">
                                        <td class="py-2 px-4">
                                            {{ $item->product->name }}
                                            <input type="hidden" name="items[{{$index}}][product_id]" value="{{ $item->product_id }}">
                                        </td>
                                        <td class="py-2 px-4">
                                            <input type="number" readonly value="{{ $item->quantity }}" name="items[{{$index}}][quantity]" class="w-24 bg-gray-100 rounded-md border-gray-300 shadow-sm">
                                        </td>
                                        <td class="py-2 px-4">
                                            <input type="number" name="items[{{$index}}][price]" value="0" min="0" step="100" class="w-48 rounded-md border-gray-300 shadow-sm" required>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                             <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('purchase-requests.show', $purchaseRequest->id) }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Simpan Purchase Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>