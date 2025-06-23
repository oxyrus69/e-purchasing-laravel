<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Permintaan Pembelian: ') . $purchaseRequest->pr_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Notifikasi -->
                    @if (session('success')) <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">{{ session('success') }}</div> @endif
                    @if (session('error')) <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-md">{{ session('error') }}</div> @endif

                    <!-- Tombol Aksi -->
                    <div class="flex justify-end mb-4 space-x-2">
                        
                        @if($purchaseRequest->status == 'pending_approval')
                            @can('approve-pr')
                                <form action="{{ route('purchase-requests.approve', $purchaseRequest) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menyetujui permintaan ini?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 text-sm font-semibold">Setujui</button>
                                </form>
                            @endcan
                            @can('reject-pr')
                                <form action="{{ route('purchase-requests.reject', $purchaseRequest) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menolak permintaan ini?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm font-semibold">Tolak</button>
                                </form>
                            @endcan
                        @endif

                        {{-- !! PERUBAHAN DI SINI !! --}}
                        {{-- Tombol hanya muncul jika PR disetujui DAN user punya izin 'create-po' --}}
                        @if($purchaseRequest->status == 'approved')
                            @can('create-po')
                                <a href="{{ route('purchase-orders.create', ['pr' => $purchaseRequest->id]) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-semibold">
                                    Buat Purchase Order
                                </a>
                            @endcan
                        @endif
                        
                        <a href="{{ route('purchase-requests.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 text-sm font-semibold">Kembali</a>
                    </div>
                
                    <!-- Detail Header PR -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div><p class="text-sm text-gray-500">Nomor PR</p><p class="font-semibold">{{ $purchaseRequest->pr_number }}</p></div>
                        <div><p class="text-sm text-gray-500">Tanggal Permintaan</p><p class="font-semibold">{{ \Carbon\Carbon::parse($purchaseRequest->request_date)->format('d F Y') }}</p></div>
                        <div><p class="text-sm text-gray-500">Pemohon</p><p class="font-semibold">{{ $purchaseRequest->requester->name }}</p></div>
                         <div><p class="text-sm text-gray-500">Status</p><p class="font-semibold text-{{ $purchaseRequest->status == 'approved' ? 'green' : ($purchaseRequest->status == 'rejected' ? 'red' : 'yellow') }}-600">{{ str_replace('_', ' ', Str::title($purchaseRequest->status)) }}</p></div>
                        
                        @if($purchaseRequest->approver_id)
                         <div><p class="text-sm text-gray-500">Disetujui/Ditolak Oleh</p><p class="font-semibold">{{ $purchaseRequest->approver->name }}</p></div>
                         <div><p class="text-sm text-gray-500">Tanggal Persetujuan</p><p class="font-semibold">{{ \Carbon\Carbon::parse($purchaseRequest->approved_date)->format('d F Y') }}</p></div>
                        @endif

                        <div class="col-span-2"><p class="text-sm text-gray-500">Catatan</p><p class="font-semibold">{{ $purchaseRequest->notes ?? '-' }}</p></div>
                    </div>

                    <!-- Detail Item -->
                    <h3 class="text-lg font-semibold border-t pt-4 mb-2">Barang yang Diminta</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left">Nama Barang</th><th class="px-6 py-3 text-left">Jumlah</th><th class="px-6 py-3 text-left">Satuan</th></tr></thead>
                        <tbody>
                            @foreach($purchaseRequest->items as $item)
                            <tr><td class="px-6 py-4">{{ $item->product->name }}</td><td class="px-6 py-4">{{ $item->quantity }}</td><td class="px-6 py-4">{{ $item->product->unit }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>