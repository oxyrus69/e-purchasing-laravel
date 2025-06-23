<!-- File: resources/views/purchase_orders/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Daftar Purchase Order (PO)') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                <div class="flex items-center justify-between mb-4">
                        <form action="{{ route('purchase-orders.index') }}" method="GET" class="flex items-center space-x-4">
                            <input type="text" name="search" placeholder="Cari No. PO..." class="form-input rounded-md shadow-sm" value="{{ request('search') }}">
                            <select name="status" class="form-select rounded-md shadow-sm">
                                <option value="">Semua Status</option>
                                <option value="draft" @selected(request('status') == 'draft')>Draft</option>
                                <option value="sent" @selected(request('status') == 'sent')>Sent</option>
                                <option value="partially_received" @selected(request('status') == 'partially_received')>Partial Received</option>
                                <option value="fully_received" @selected(request('status') == 'fully_received')>Fully Received</option>
                                <option value="invoiced" @selected(request('status') == 'invoiced')>Invoiced</option>
                                <option value="canceled" @selected(request('status') == 'canceled')>Canceled</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-gray-700 text-white text-sm font-medium rounded-md">Filter</button>
                        </form>
                    </div>
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-200 rounded-md">{{ session('success') }}</div>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. PO</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($purchaseOrders as $po)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $po->po_number }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($po->order_date)->format('d-m-Y') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $po->supplier->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ $po->status }}</span></td>
                                        <td class="px-6 py-4 text-right text-sm font-medium">
                                            <a href="{{ route('purchase-orders.show', $po->id) }}" class="text-indigo-600 hover:text-indigo-900">Lihat</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data Purchase Order.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $purchaseOrders->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>