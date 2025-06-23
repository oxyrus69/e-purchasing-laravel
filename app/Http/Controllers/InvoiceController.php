<?php
// File: app/Http/Controllers/InvoiceController.php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('supplier', 'purchaseOrder');
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where('invoice_number', 'like', "%{$searchTerm}%")
                  ->orWhereHas('purchaseOrder', function ($q) use ($searchTerm) {
                      $q->where('po_number', 'like', "%{$searchTerm}%");
                  });
        }
        $invoices = $query->latest()->paginate(10)->withQueryString();
        return view('invoices.index', compact('invoices'));
    }

    public function create(Request $request)
    {
        $po_id = $request->query('po');
        if (!$po_id) {
            return redirect()->route('purchase-orders.index')->with('error', 'Purchase Order tidak valid.');
        }
        $purchaseOrder = PurchaseOrder::with('items.product', 'supplier')->findOrFail($po_id);
        return view('invoices.create', compact('purchaseOrder'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_number' => 'required|string|unique:invoices',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
        ]);

        $po = PurchaseOrder::with('items')->findOrFail($request->purchase_order_id);

        try {
            DB::beginTransaction();
            $invoice = Invoice::create([
                'invoice_number' => $request->invoice_number,
                'purchase_order_id' => $po->id,
                'supplier_id' => $request->supplier_id,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'total_amount' => $po->total_amount, // Ambil total dari PO
                'notes' => $request->notes,
                'status' => 'unpaid',
            ]);

            foreach ($po->items as $poItem) {
                $invoice->items()->create([
                    'product_id' => $poItem->product_id,
                    'quantity' => $poItem->quantity,
                    'price' => $poItem->price,
                    'total' => $poItem->total,
                ]);
            }
            
            $po->status = 'invoiced';
            $po->save();
            
            DB::commit();
            return redirect()->route('invoices.index')->with('success', 'Faktur berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membuat faktur: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('supplier', 'purchaseOrder', 'items.product');
        return view('invoices.show', compact('invoice'));
    }
    public function markAsPaid(Invoice $invoice)
    {
        if ($invoice->status == 'unpaid') {
            $invoice->status = 'paid';
            $invoice->save();
            return redirect()->route('invoices.show', $invoice)->with('success', 'Status faktur berhasil diubah menjadi Lunas.');
        }

        return redirect()->route('invoices.show', $invoice)->with('error', 'Status faktur ini tidak dapat diubah.');
    }
}