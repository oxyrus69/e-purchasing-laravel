<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceiptNote;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string|in:purchase_order,purchase_request,goods_receipt_note',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $reportType = $request->report_type;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        $results = [];
        $grandTotal = 0;
        $reportTitle = '';

        switch ($reportType) {
            case 'purchase_order':
                Gate::authorize('view-po-report');
                $reportTitle = 'Laporan Purchase Order';
                $results = PurchaseOrder::with('supplier')
                    ->whereBetween('order_date', [$startDate, $endDate])
                    ->orderBy('order_date')->get();
                $grandTotal = $results->sum('total_amount');
                break;
            
            case 'purchase_request':
                Gate::authorize('view-pr-report');
                $reportTitle = 'Laporan Purchase Request';
                $results = PurchaseRequest::with('requester')
                    ->whereBetween('request_date', [$startDate, $endDate])
                    ->orderBy('request_date')->get();
                break;

            case 'goods_receipt_note':
                Gate::authorize('view-grn-report');
                $reportTitle = 'Laporan Penerimaan Barang';
                $results = GoodsReceiptNote::with('purchaseOrder.supplier', 'receiver')
                    ->whereBetween('received_date', [$startDate, $endDate])
                    ->orderBy('received_date')->get();
                break;
        }

        return view('reports.index', compact('results', 'reportType', 'reportTitle', 'startDate', 'endDate', 'grandTotal'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $reportType = $request->report_type;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $fileName = "Laporan_{$reportType}_{$startDate}_hingga_{$endDate}.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($reportType, $startDate, $endDate) {
            $file = fopen('php://output', 'w');

            if ($reportType == 'purchase_order') {
                fputcsv($file, ['Tanggal', 'No. PO', 'Supplier', 'Total', 'Status']);
                $data = PurchaseOrder::with('supplier')->whereBetween('order_date', [$startDate, $endDate])->get();
                foreach ($data as $row) {
                    fputcsv($file, [
                        $row->order_date,
                        $row->po_number,
                        $row->supplier->name,
                        $row->total_amount,
                        $row->status,
                    ]);
                }
            } elseif ($reportType == 'purchase_request') {
                fputcsv($file, ['Tanggal', 'No. PR', 'Pemohon', 'Status']);
                $data = PurchaseRequest::with('requester')->whereBetween('request_date', [$startDate, $endDate])->get();
                foreach ($data as $row) {
                    fputcsv($file, [
                        $row->request_date,
                        $row->pr_number,
                        $row->requester->name,
                        $row->status,
                    ]);
                }
            } // Anda bisa menambahkan logika untuk 'goods_receipt_note' di sini

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}