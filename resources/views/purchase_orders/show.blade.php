<!-- File: resources/views/purchase_orders/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail PO: {{ $purchaseOrder->po_number }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    {{-- Tombol Aksi - Final --}}
                    <div class="flex items-center justify-end mb-6 space-x-4">
                    @if($purchaseOrder->status == 'fully_received' || $purchaseOrder->status == 'partially_received')
                <a href="{{ route('invoices.create', ['po' => $purchaseOrder->id]) }}" class="text-sm text-purple-600 hover:text-purple-800 font-semibold">
                    Buat Invoice
                    </a>
                     @endif
                        
                        {{-- Logika untuk menampilkan link "Terima Barang" --}}
                        @if(trim($purchaseOrder->status) == 'sent' || trim($purchaseOrder->status) == 'partially_received')
                            <a href="{{ route('goods-receipt-notes.create', ['po' => $purchaseOrder->id]) }}" class="text-sm text-green-600 hover:text-green-800 font-semibold">
                                Terima Barang
                            </a>
                        @endif

                        <a href="{{ route('purchase-orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Kembali ke Daftar PO</a>
                    </div>

                    {{-- Detail Header PO --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-6">
                        <div><p class="text-sm text-gray-500">No. PO</p><p class="font-semibold">{{ $purchaseOrder->po_number }}</p></div>
                        <div><p class="text-sm text-gray-500">Tanggal PO</p><p class="font-semibold">{{ \Carbon\Carbon::parse($purchaseOrder->order_date)->format('d F Y') }}</p></div>
                        <div><p class="text-sm text-gray-500">Status</p><p class="font-semibold">{{ $purchaseOrder->status }}</p></div>
                        <div class="col-span-2"><p class="text-sm text-gray-500">Supplier</p><p class="font-semibold">{{ $purchaseOrder->supplier->name }}</p></div>
                        <div><p class="text-sm text-gray-500">Dibuat Oleh</p><p class="font-semibold">{{ $purchaseOrder->creator->name }}</p></div>
                        <div class="col-span-full"><p class="text-sm text-gray-500">Catatan</p><p>{{ $purchaseOrder->notes ?? '-' }}</p></div>
                    </div>

                    {{-- Detail Item --}}
                    <h3 class="text-lg font-semibold border-t pt-4 mb-2">Detail Barang Pesanan</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">Nama Barang</th>
                                <th class="px-6 py-3 text-right">Jumlah</th>
                                <th class="px-6 py-3 text-right">Harga Satuan</th>
                                <th class="px-6 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($purchaseOrder->items as $item)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium">{{ $item->product->name }}</td>
                                <td class="px-6 py-4 text-sm text-right">{{ $item->quantity }} {{ $item->product->unit }}</td>
                                <td class="px-6 py-4 text-sm text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-3 text-right font-bold">TOTAL</td>
                                <td class="px-6 py-3 text-right font-bold">Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
