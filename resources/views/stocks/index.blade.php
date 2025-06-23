<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Stok Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <form action="{{ route('stocks.index') }}" method="GET" class="w-1/3">
                            <input type="text" name="search" placeholder="Cari nama atau kode produk..." class="form-input rounded-md shadow-sm w-full" value="{{ request('search') }}">
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left">Kode Produk</th>
                                    <th class="px-6 py-3 text-left">Nama Produk</th>
                                    <th class="px-6 py-3 text-center">Jumlah Stok</th>
                                    <th class="px-6 py-3 text-left">Satuan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($products as $product)
                                <tr>
                                <td class="px-6 py-4">{{ $product->code }}</td>
                                <td class="px-6 py-4">
                                    {{-- Jadikan ini sebuah link --}}
                                    <a href="{{ route('stocks.show', $product->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-center font-bold">{{ $product->stock }}</td>
                                <td class="px-6 py-4">{{ $product->unit }}</td>
                            </tr>
                            @empty
                                    <tr><td colspan="4" class="text-center py-4">Data produk tidak ditemukan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>