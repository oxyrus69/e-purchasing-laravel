<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Permintaan Barang Internal</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @can('create-stock-requisition')
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('stock-requisitions.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                            Buat Permintaan Baru
                        </a>
                    </div>
                    @endcan
                    @if (session('success')) <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">{{ session('success') }}</div> @endif
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left">No. Req</th><th class="px-6 py-3 text-left">Tanggal</th><th class="px-6 py-3 text-left">Pemohon</th><th class="px-6 py-3 text-center">Status</th><th class="px-6 py-3 text-right">Aksi</th></tr></thead>
                            <tbody>
                                @forelse($requisitions as $req)
                                <tr>
                                    <td class="px-6 py-4">{{ $req->requisition_number }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($req->request_date)->format('d-m-Y') }}</td>
                                    <td class="px-6 py-4">{{ $req->requester->name }}</td>
                                    <td class="px-6 py-4 text-center"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($req->status == 'approved') bg-green-100 text-green-800 @else bg-yellow-100 text-yellow-800 @endif">{{ $req->status }}</span></td>
                                    <td class="px-6 py-4 text-right"><a href="{{ route('stock-requisitions.show', $req->id) }}" class="text-indigo-600 hover:text-indigo-900">Lihat</a></td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-4">Belum ada data permintaan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>