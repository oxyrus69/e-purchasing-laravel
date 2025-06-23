<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Permintaan: {{ $stockRequisition->requisition_number }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if (session('success')) <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">{{ session('success') }}</div> @endif
                    @if (session('error')) <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-md">{{ session('error') }}</div> @endif
                    <div class="flex justify-end mb-4">@if($stockRequisition->status == 'pending') @can('approve-stock-requisition')<form action="{{ route('stock-requisitions.approve', $stockRequisition->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin mengeluarkan barang ini? Stok akan dikurangi.');">@csrf @method('PATCH')<button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md">Keluarkan Barang</button></form>@endcan @endif</div>
                    <div class="grid grid-cols-2 gap-4 mb-6"><div><p class="text-sm">No. Permintaan</p><p class="font-semibold">{{ $stockRequisition->requisition_number }}</p></div><div><p class="text-sm">Tgl. Permintaan</p><p class="font-semibold">{{ \Carbon\Carbon::parse($stockRequisition->request_date)->format('d F Y') }}</p></div><div><p class="text-sm">Pemohon</p><p class="font-semibold">{{ $stockRequisition->requester->name }}</p></div><div><p class="text-sm">Status</p><p class="font-semibold">{{ $stockRequisition->status }}</p></div>@if($stockRequisition->approver)<div><p class="text-sm">Disetujui Oleh</p><p class="font-semibold">{{ $stockRequisition->approver->name }}</p></div><div><p class="text-sm">Tgl. Disetujui</p><p class="font-semibold">{{ \Carbon\Carbon::parse($stockRequisition->approved_date)->format('d F Y') }}</p></div>@endif</div>
                    <h3 class="font-semibold border-t pt-4 mb-2">Detail Barang Diminta</h3>
                    <table class="min-w-full"><thead><tr><th class="text-left py-2">Nama Barang</th><th class="text-right py-2">Jumlah</th></tr></thead><tbody>@foreach($stockRequisition->items as $item)<tr class="border-b"><td class="py-2">{{ $item->product->name }}</td><td class="text-right py-2">{{ $item->quantity }} {{ $item->product->unit }}</td></tr>@endforeach</tbody></table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>