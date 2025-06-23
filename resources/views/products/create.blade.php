<!-- File: resources/views/products/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Tambah Produk Baru') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('products.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700">Kode Produk <span class="text-red-600">*</span></label>
                                <input type="text" name="code" id="code" value="{{ old('code') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                @error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk <span class="text-red-600">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="unit" class="block text-sm font-medium text-gray-700">Satuan (Unit) <span class="text-red-600">*</span></label>
                                <input type="text" name="unit" id="unit" value="{{ old('unit') }}" placeholder="Contoh: Pcs, Kg, Box, Unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                @error('unit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="minimum_stock" class="block text-sm font-medium text-gray-700">Stok Minimum</label>
                                <input type="number" name="minimum_stock" id="minimum_stock" value="{{ old('minimum_stock', 5) }}" class="mt-1 block w-full rounded-md" required>
                            </div>
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description') }}</textarea>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>