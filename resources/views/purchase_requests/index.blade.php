<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Permintaan Pembelian (PR)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                <div class="flex items-center justify-between mb-4">
                        <form action="{{ route('purchase-requests.index') }}" method="GET" class="flex items-center space-x-4">
                            <input type="text" name="search" placeholder="Cari No. PR..." class="form-input rounded-md shadow-sm" value="{{ request('search') }}">
                            <select name="status" class="form-select rounded-md shadow-sm">
                                <option value="">Semua Status</option>
                                <option value="pending_approval" @selected(request('status') == 'pending_approval')>Pending Approval</option>
                                <option value="approved" @selected(request('status') == 'approved')>Approved</option>
                                <option value="rejected" @selected(request('status') == 'rejected')>Rejected</option>
                                <option value="processed" @selected(request('status') == 'processed')>Processed</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-gray-700 text-white text-sm font-medium rounded-md">Filter</button>
                        </form>
                        <a href="{{ route('purchase-requests.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                            Buat PR Baru
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-200 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. PR</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($purchaseRequests as $pr)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $pr->pr_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($pr->request_date)->format('d-m-Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pr->requester->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($pr->status == 'approved') bg-green-100 text-green-800 @endif
                                                @if($pr->status == 'pending_approval') bg-yellow-100 text-yellow-800 @endif
                                                @if($pr->status == 'rejected') bg-red-100 text-red-800 @endif
                                                @if($pr->status == 'processed') bg-blue-100 text-blue-800 @endif
                                            ">
                                                {{ str_replace('_', ' ', Str::title($pr->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('purchase-requests.show', $pr->id) }}" class="text-indigo-600 hover:text-indigo-900">Lihat</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Belum ada data permintaan pembelian.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $purchaseRequests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>