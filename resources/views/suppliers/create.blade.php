<!-- File: resources/views/suppliers/create.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Supplier Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('suppliers.store') }}">
                        @csrf
                        
                        <!-- Kode Supplier -->
                        <div class="mb-4">
                            <label for="code" class="block text-sm font-medium text-gray-700">Kode Supplier <span class="text-red-600">*</span></label>
                            <input type="text" name="code" id="code" value="{{ old('code') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Nama Supplier -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Supplier <span class="text-red-600">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Alamat -->
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('address') }}</textarea>
                        </div>
                        
                        <!-- Telepon & Email -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                             <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Telepon</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <!-- Kontak Person -->
                         <div class="mb-4">
                            <label for="contact_person" class="block text-sm font-medium text-gray-700">Kontak Person</label>
                            <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>


                        <div class="flex items-center justify-end mt-4">
                             <a href="{{ route('suppliers.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
