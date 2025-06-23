<!-- File: resources/views/goods_receipt_notes/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Catat Penerimaan Barang dari PO: {{ $purchaseOrder->po_number }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('goods-receipt-notes.store') }}">
                        @csrf
                        <input type="hidden" name="purchase_order_id" value="{{ $purchaseOrder->id }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Terima <span class="text-red-500">*</span></label>
                                <input type="date" name="received_date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold border-t pt-4 mb-2">Barang Diterima</h3>
                        <table class="min-w-full">
                            <thead><tr><th class="text-left py-2">Nama Barang</th><th class="text-left py-2">Jumlah Pesan</th><th class="text-left py-2">Jumlah Diterima</th></tr></thead>
                            <tbody>
                                @foreach($purchaseOrder->items as $index => $item)
                                    <tr class="border-b"><td class="py-2">{{ $item->product->name }}<input type="hidden" name="items[{{$index}}][product_id]" value="{{$item->product_id}}"></td><td class="py-2">{{ $item->quantity }} {{ $item->product->unit }}</td><td class="py-2"><input type="number" name="items[{{$index}}][quantity_received]" value="0" min="0" max="{{$item->quantity}}" class="w-32 rounded-md border-gray-300 shadow-sm"></td></tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('purchase-orders.show', $purchaseOrder->id) }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border-transparent rounded-md font-semibold text-xs text-white uppercase">Simpan GRN</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>