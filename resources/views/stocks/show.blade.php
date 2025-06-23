<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kartu Stok: ') . $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <a href="{{ route('stocks.index') }}" class="text-sm text-blue-600 hover:text-blue-800">&larr; Kembali ke Manajemen Stok</a>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div><strong>Kode Produk:</strong> {{ $product->code }}</div>
                        <div><strong>Satuan:</strong> {{ $product->unit }}</div>
                        <div><strong>Stok Saat Ini:</strong> <span class="font-bold text-2xl">{{ $product->stock }}</span></div>
                    </div>
                    
                    <h3 class="text-lg font-semibold border-t pt-4 mb-2">Riwayat Pergerakan Stok</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left">Tanggal</th>
                                    <th class="px-6 py-3 text-left">Deskripsi</th>
                                    <th class="px-6 py-3 text-center">Tipe</th>
                                    <th class="px-6 py-3 text-right">Jumlah</th>
                                    <th class="px-6 py-3 text-right">Saldo Akhir</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($product->stockMovements as $movement)
                                    <tr>
                                        <td class="px-6 py-4">{{ $movement->created_at->format('d M Y, H:i') }}</td>
                                        <td class="px-6 py-4">{{ $movement->description }}</td>
                                        <td class="px-6 py-4 text-center">
                                            @if($movement->type == 'in') <span class="text-green-600 font-semibold">IN</span> @endif
                                            @if($movement->type == 'out') <span class="text-red-600 font-semibold">OUT</span> @endif
                                            @if($movement->type == 'adjustment') <span class="text-yellow-600 font-semibold">ADJ</span> @endif
                                        </td>
                                        <td class="px-6 py-4 text-right font-semibold">{{ $movement->quantity }}</td>
                                        <td class="px-6 py-4 text-right font-bold">{{ $movement->balance_after }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-4">Belum ada pergerakan stok untuk produk ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>