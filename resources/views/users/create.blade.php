<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Pengguna Baru</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700">Nama</label>
                                <input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name') }}" required>
                            </div>
                             <div>
                                <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                                <input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email') }}" required>
                            </div>
                            <div>
                                <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                                <input id="password" name="password" type="password" class="mt-1 block w-full" required>
                            </div>
                            <div>
                                <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Konfirmasi Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required>
                            </div>
                        </div>
                        <div class="mt-6">
                            <label class="block font-medium text-sm text-gray-700">Peran (Roles)</label>
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach ($roles as $role)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="roles[]" value="{{ $role }}" class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                        <span class="ml-2 text-sm text-gray-600">{{ $role }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('users.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border-transparent rounded-md font-semibold text-xs text-white uppercase">Simpan Pengguna</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>