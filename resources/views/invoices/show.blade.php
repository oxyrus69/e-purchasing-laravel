<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Faktur: {{ $invoice->invoice_number }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-6">
                        <div><p class="text-sm text-gray-500">No. Faktur</p><p class="font-semibold">{{ $invoice->invoice_number }}</p></div>
                        <div><p class="text-sm text-gray-500">Tgl. Faktur</p><p class="font-semibold">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d F Y') }}</p></div>
                        <div><p class="text-sm text-gray-500">Tgl. Jatuh Tempo</p><p class="font-semibold">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d F Y') }}</p></div>
                        <div class="col-span-2"><p class="text-sm text-gray-500">Supplier</p><p class="font-semibold">{{ $invoice->supplier->name }}</p></div>
                        <div><p class="text-sm text-gray-500">Status</p><p class="font-semibold">{{ Str::title($invoice->status) }}</p></div>
                        <div class="col-span-full"><p class="text-sm text-gray-500">Referensi No. PO</p><p class="font-semibold">{{ $invoice->purchaseOrder->po_number }}</p></div>
                    </div>

                    <h3 class="text-lg font-semibold border-t pt-4 mb-2">Detail Tagihan</h3>
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
                            @foreach($invoice->items as $item)
                            <tr>
                                <td class="px-6 py-4">{{ $item->product->name }}</td>
                                <td class="px-6 py-4 text-right">{{ $item->quantity }} {{ $item->product->unit }}</td>
                                <td class="px-6 py-4 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-3 text-right font-bold">TOTAL TAGIHAN</td>
                                <td class="px-6 py-3 text-right font-bold">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="flex items-center justify-end mt-6 space-x-4">
                     @can('mark-invoice-paid')
                            @if($invoice->status == 'unpaid')
                                <form action="{{ route('invoices.markAsPaid', $invoice->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menandai faktur ini sebagai Lunas?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                                        Tandai Lunas
                                    </button>
                                </form>
                            @endif
                        @endcan
                        <a href="{{ route('invoices.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Kembali ke Daftar Faktur</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>