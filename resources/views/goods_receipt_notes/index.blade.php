<!-- File: resources/views/goods_receipt_notes/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Daftar Penerimaan Barang (GRN)') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                <div class="flex items-center justify-between mb-4">
                        <form action="{{ route('goods-receipt-notes.index') }}" method="GET" class="w-1/3">
                            <input type="text" name="search" placeholder="Cari No. GRN atau No. PO..." class="form-input rounded-md shadow-sm w-full" value="{{ request('search') }}">
                        </form>
                    </div>
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-200 rounded-md">{{ session('success') }}</div>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left">No. GRN</th>
                                    <th class="px-6 py-3 text-left">No. PO</th>
                                    <th class="px-6 py-3 text-left">Tanggal Terima</th>
                                    <th class="px-6 py-3 text-left">Supplier</th>
                                    <th class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($grns as $grn)
                                    <tr>
                                        <td class="px-6 py-4">{{ $grn->grn_number }}</td>
                                        <td class="px-6 py-4">{{ $grn->purchaseOrder->po_number }}</td>
                                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($grn->received_date)->format('d-m-Y') }}</td>
                                        <td class="px-6 py-4">{{ $grn->purchaseOrder->supplier->name }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('goods-receipt-notes.show', $grn->id) }}" class="text-indigo-600 hover:text-indigo-900">Lihat</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-6 py-4 text-center">Belum ada data penerimaan barang.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $grns->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>