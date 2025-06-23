<!-- File: resources/views/dashboard.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Pending PR Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-4">
                        <i class="fas fa-file-alt fa-2x text-white"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 truncate">PR Menunggu Persetujuan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $pendingPRCount }}</p>
                    </div>
                </div>
            </div>
            <!-- Active PO Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-4">
                        <i class="fas fa-file-invoice-dollar fa-2x text-white"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 truncate">PO Aktif</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $activePOCount }}</p>
                    </div>
                </div>
            </div>
            <!-- Total Suppliers Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-4">
                        <i class="fas fa-truck fa-2x text-white"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 truncate">Total Supplier</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $supplierCount }}</p>
                    </div>
                </div>
            </div>
            <!-- Total Products Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex items-center">
                    <div class="flex-shrink-0 bg-red-500 rounded-md p-4">
                        <i class="fas fa-box-open fa-2x text-white"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 truncate">Total Produk</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $productCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Purchase Orders Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">5 Purchase Order Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. PO</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($latestPOs as $po)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                        <a href="{{ route('purchase-orders.show', $po->id) }}">{{ $po->po_number }}</a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $po->supplier->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($po->order_date)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($po->status == 'sent') bg-blue-100 text-blue-800 
                                            @elseif($po->status == 'fully_received') bg-green-100 text-green-800 
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ str_replace('_', ' ', Str::title($po->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Belum ada Purchase Order.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
