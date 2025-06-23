<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Pembelian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Form Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form action="{{ route('reports.generate') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="report_type" class="block text-sm font-medium text-gray-700">Jenis Laporan</label>
                                <select name="report_type" id="report_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @can('view-po-report')<option value="purchase_order" @selected(isset($reportType) && $reportType == 'purchase_order')>Laporan Purchase Order</option>@endcan
                                    @can('view-pr-report')<option value="purchase_request" @selected(isset($reportType) && $reportType == 'purchase_request')>Laporan Purchase Request</option>@endcan
                                    @can('view-grn-report')<option value="goods_receipt_note" @selected(isset($reportType) && $reportType == 'goods_receipt_note')>Laporan Penerimaan Barang</option>@endcan
                                </select>
                            </div>
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                                <input type="date" name="start_date" id="start_date" value="{{ $startDate ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                                <input type="date" name="end_date" id="end_date" value="{{ $endDate ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-800 border-transparent rounded-md font-semibold text-xs text-white uppercase">
                                    Buat Laporan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Hasil Laporan -->
            @if(isset($results))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div id="report-content" class="p-6">
                    <div class="mb-4 text-center">
                        <h3 class="text-xl font-bold">{{ $reportTitle }}</h3>
                        <p class="text-sm text-gray-600">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
                    </div>

                    {{-- Tabel Laporan PO --}}
                    @if($reportType == 'purchase_order')
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left">Tanggal</th><th class="px-6 py-3 text-left">No. PO</th><th class="px-6 py-3 text-left">Supplier</th><th class="px-6 py-3 text-right">Total</th><th class="px-6 py-3 text-center">Status</th></tr></thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($results as $po)
                            <tr><td class="px-6 py-4">{{ \Carbon\Carbon::parse($po->order_date)->format('d-m-Y') }}</td><td class="px-6 py-4">{{ $po->po_number }}</td><td class="px-6 py-4">{{ $po->supplier->name }}</td><td class="px-6 py-4 text-right">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td><td class="px-6 py-4 text-center">{{ $po->status }}</td></tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4">Tidak ada data untuk periode ini.</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-100 font-bold"><tr><td colspan="3" class="px-6 py-3 text-right">Grand Total:</td><td class="px-6 py-3 text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td><td></td></tr></tfoot>
                    </table>
                    @endif

                    {{-- Tabel Laporan PR --}}
                    @if($reportType == 'purchase_request')
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left">Tanggal</th><th class="px-6 py-3 text-left">No. PR</th><th class="px-6 py-3 text-left">Pemohon</th><th class="px-6 py-3 text-center">Status</th></tr></thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($results as $pr)
                            <tr><td class="px-6 py-4">{{ \Carbon\Carbon::parse($pr->request_date)->format('d-m-Y') }}</td><td class="px-6 py-4">{{ $pr->pr_number }}</td><td class="px-6 py-4">{{ $pr->requester->name }}</td><td class="px-6 py-4 text-center">{{ $pr->status }}</td></tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4">Tidak ada data untuk periode ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    @endif
                    
                    {{-- Tabel Laporan GRN --}}
                    @if($reportType == 'goods_receipt_note')
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left">Tanggal</th><th class="px-6 py-3 text-left">No. GRN</th><th class="px-6 py-3 text-left">No. PO</th><th class="px-6 py-3 text-left">Penerima</th></tr></thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($results as $grn)
                            <tr><td class="px-6 py-4">{{ \Carbon\Carbon::parse($grn->received_date)->format('d-m-Y') }}</td><td class="px-6 py-4">{{ $grn->grn_number }}</td><td class="px-6 py-4">{{ $grn->purchaseOrder->po_number }}</td><td class="px-6 py-4">{{ $grn->receiver->name }}</td></tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4">Tidak ada data untuk periode ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    @endif
                </div>
                <div class="p-6 border-t">
                    <div class="flex items-center space-x-4">
                        <button onclick="printReport()" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase">
                            Cetak Laporan
                        </button>
                        
                        <form action="{{ route('reports.export') }}" method="GET" class="m-0">
                            <input type="hidden" name="report_type" value="{{ $reportType }}">
                            <input type="hidden" name="start_date" value="{{ $startDate }}">
                            <input type="hidden" name="end_date" value="{{ $endDate }}">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase">
                                Ekspor ke CSV
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <script>
        function printReport() {
             // !! PERUBAHAN DI SINI !!
            // Mengambil judul laporan secara dinamis
            const reportTitle = "{{ $reportTitle ?? 'Laporan' }}"; 

            const companyLogoUrl = "{{ asset('images/online-shop.png') }}";
            const reportPeriod = "Periode: {{ isset($startDate) ? \Carbon\Carbon::parse($startDate)->format('d M Y') : '' }} - {{ isset($endDate) ? \Carbon\Carbon::parse($endDate)->format('d M Y') : '' }}";
            const preparedBy = "{{ Auth::user()->name }}";
            const printDate = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            
            // Mengambil konten HTML dari tabel yang sesuai, termasuk tfoot jika ada
            let tableContent = '';
            const reportTable = document.querySelector("#report-content table");
            if(reportTable) {
                tableContent = reportTable.outerHTML;
            }

            // Template HTML formal untuk jendela cetak
            const printTemplate = `
                <html>
                <head>
                    <title>Cetak ${reportTitle}</title>
                    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
                    <style>
                        body { font-family: sans-serif; }
                        @media print {
                            body { -webkit-print-color-adjust: exact; }
                            .no-print { display: none; }
                        }
                        .signature-box {
                            border-top: 1px solid #ccc;
                            padding-top: 5px;
                            margin-top: 60px;
                            text-align: center;
                        }
                    </style>
                </head>
                <body class="p-8">
                    <!-- KOP SURAT -->
                    <div class="flex items-center justify-between border-b-2 pb-4">
                        <div class="flex items-center">
                            <img src="${companyLogoUrl}" alt="Logo" class="h-16 mr-4">
                            <div>
                                <h1 class="text-2xl font-bold">NAMA PERUSAHAAN</h1>
                                <p class="text-sm">Alamat Perusahaan, Kota, Kode Pos</p>
                                <p class="text-sm">Telepon: (021) 123456 | Email: info@perusahaan.com</p>
                            </div>
                        </div>
                    </div>

                    <!-- JUDUL LAPORAN -->
                    <div class="text-center my-8">
                        <h2 class="text-xl font-bold underline uppercase">${reportTitle}</h2>
                        <p>${reportPeriod}</p>
                    </div>

                    <!-- KONTEN TABEL -->
                    ${tableContent}

                    <!-- TANDA TANGAN -->
                    <div class="mt-12 grid grid-cols-2 gap-16">
                        <div class="text-center">
                            <p>Garut, ${printDate}</p>
                            <p>Dibuat Oleh,</p>
                            <div class="signature-box">
                                <p class="font-semibold">${preparedBy}</p>
                                <p class="text-sm">(Penanggung Jawab Laporan)</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <p>&nbsp;</p> {{-- Spacer --}}
                            <p>Disetujui Oleh,</p>
                            <div class="signature-box">
                                <p class="font-semibold">(___________________)</p>
                                <p class="text-sm">(Penerima Laporan)</p>
                            </div>
                        </div>
                    </div>
                </body>
                </html>
            `;

            const printWindow = window.open('', '', 'height=800,width=1000');
            printWindow.document.write(printTemplate);
            printWindow.document.close();
            setTimeout(() => {
                printWindow.focus();
                printWindow.print();
            }, 500);
        }
    </script>
</x-app-layout>