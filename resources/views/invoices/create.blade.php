<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Faktur dari PO: {{ $purchaseOrder->po_number }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('invoices.store') }}">
                        @csrf
                        <input type="hidden" name="purchase_order_id" value="{{ $purchaseOrder->id }}">
                        <input type="hidden" name="supplier_id" value="{{ $purchaseOrder->supplier_id }}">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="invoice_number" class="block font-medium text-sm text-gray-700">Nomor Faktur</label>
                                <input id="invoice_number" name="invoice_number" type="text" class="mt-1 block w-full" required>
                            </div>
                             <div>
                                <label class="block font-medium text-sm text-gray-700">Supplier</label>
                                <input type="text" class="mt-1 block w-full bg-gray-100" value="{{ $purchaseOrder->supplier->name }}" readonly>
                            </div>
                            <div>
                                <label for="invoice_date" class="block font-medium text-sm text-gray-700">Tanggal Faktur</label>
                                <input id="invoice_date" name="invoice_date" type="date" class="mt-1 block w-full" required>
                            </div>
                             <div>
                                <label for="due_date" class="block font-medium text-sm text-gray-700">Tanggal Jatuh Tempo</label>
                                <input id="due_date" name="due_date" type="date" class="mt-1 block w-full" required>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h3 class="font-semibold">Items berdasarkan PO:</h3>
                            <p class="text-lg font-bold">Total: Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md">Simpan Faktur</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>