<!-- File: resources/views/goods_receipt_notes/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail GRN: {{ $goodsReceiptNote->grn_number }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-6">
                        <div><p class="text-sm text-gray-500">No. GRN</p><p class="font-semibold">{{ $goodsReceiptNote->grn_number }}</p></div>
                        <div><p class="text-sm text-gray-500">Tanggal Terima</p><p class="font-semibold">{{ \Carbon\Carbon::parse($goodsReceiptNote->received_date)->format('d F Y') }}</p></div>
                        <div><p class="text-sm text-gray-500">Diterima Oleh</p><p class="font-semibold">{{ $goodsReceiptNote->receiver->name }}</p></div>
                        <div class="col-span-2"><p class="text-sm text-gray-500">No. PO</p><p class="font-semibold">{{ $goodsReceiptNote->purchaseOrder->po_number }}</p></div>
                        <div><p class="text-sm text-gray-500">Supplier</p><p class="font-semibold">{{ $goodsReceiptNote->purchaseOrder->supplier->name }}</p></div>
                        <div class="col-span-full"><p class="text-sm text-gray-500">Catatan</p><p>{{ $goodsReceiptNote->notes ?? '-' }}</p></div>
                    </div>
                    <h3 class="text-lg font-semibold border-t pt-4 mb-2">Barang yang Diterima</h3>
                    <table class="min-w-full">
                        <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left">Nama Barang</th><th class="px-6 py-3 text-right">Jumlah Diterima</th></tr></thead>
                        <tbody>
                            @foreach($goodsReceiptNote->items as $item)
                                <tr class="border-b"><td class="px-6 py-4">{{ $item->product->name }}</td><td class="px-6 py-4 text-right">{{ $item->quantity_received }} {{ $item->product->unit }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('goods-receipt-notes.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Kembali ke Daftar GRN</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>