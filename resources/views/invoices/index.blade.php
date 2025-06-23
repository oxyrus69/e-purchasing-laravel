<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Faktur (Invoice)</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <form action="{{ route('invoices.index') }}" method="GET" class="w-1/3">
                            <input type="text" name="search" placeholder="Cari No. Faktur atau No. PO..." class="form-input rounded-md shadow-sm w-full" value="{{ request('search') }}">
                        </form>
                    </div>

                    @if (session('success')) <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">{{ session('success') }}</div> @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left">No. Faktur</th>
                                    <th class="px-6 py-3 text-left">No. PO</th>
                                    <th class="px-6 py-3 text-left">Supplier</th>
                                    <th class="px-6 py-3 text-left">Tgl. Faktur</th>
                                    <th class="px-6 py-3 text-right">Total</th>
                                    <th class="px-6 py-3 text-center">Status</th>
                                    <th class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($invoices as $invoice)
                                    <tr>
                                        <td class="px-6 py-4">{{ $invoice->invoice_number }}</td>
                                        <td class="px-6 py-4">{{ $invoice->purchaseOrder->po_number }}</td>
                                        <td class="px-6 py-4">{{ $invoice->supplier->name }}</td>
                                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}</td>
                                        <td class="px-6 py-4 text-right">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($invoice->status == 'paid') bg-green-100 text-green-800 
                                                @elseif($invoice->status == 'unpaid') bg-yellow-100 text-yellow-800 
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ Str::title($invoice->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('invoices.show', $invoice->id) }}" class="text-indigo-600 hover:text-indigo-900">Lihat</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center py-4">Belum ada data faktur.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>