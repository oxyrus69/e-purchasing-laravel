<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\NewPurchaseOrderNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
    $query = PurchaseOrder::with('supplier');

    // Filter pencarian
    if ($request->has('search') && $request->search != '') {
        $query->where('po_number', 'like', '%' . $request->search . '%');
    }

    // Filter status
    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    $purchaseOrders = $query->latest()->paginate(10)->withQueryString();

    return view('purchase_orders.index', compact('purchaseOrders'));
    }

    public function create(Request $request)
    {
        // Ambil ID PR dari URL
        $pr_id = $request->query('pr');
        if (!$pr_id) {
            return redirect()->route('purchase-requests.index')->with('error', 'Permintaan pembelian tidak valid.');
        }

        // Ambil data PR beserta item-itemnya
        $purchaseRequest = PurchaseRequest::with('items.product')->findOrFail($pr_id);

        // Pastikan PR sudah disetujui dan belum diproses
        if ($purchaseRequest->status !== 'approved') {
             return redirect()->route('purchase-requests.show', $purchaseRequest->id)->with('error', 'Hanya PR yang berstatus "Approved" yang bisa dibuatkan PO.');
        }

        // Ambil semua data supplier untuk pilihan
        $suppliers = Supplier::orderBy('name')->get();

        return view('purchase_orders.create', compact('purchaseRequest', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_request_id' => 'required|exists:purchase_requests,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        // Gunakan DB Transaction untuk memastikan semua query berhasil
        try {
            DB::beginTransaction();

            $purchaseRequest = PurchaseRequest::findOrFail($request->purchase_request_id);

            // Buat header PO
            $po = PurchaseOrder::create([
                'po_number' => 'PO-' . time(), // Nanti bisa diganti format yang lebih baik
                'purchase_request_id' => $purchaseRequest->id,
                'supplier_id' => $request->supplier_id,
                'order_by_id' => Auth::id(),
                'order_date' => $request->order_date,
                'status' => 'sent',
                'notes' => $request->notes,
                'total_amount' => 0, // Dihitung nanti
            ]);

            $totalAmount = 0;

            // Buat item-item PO
            foreach ($request->items as $itemData) {
                $quantity = $itemData['quantity'];
                $price = $itemData['price'];
                $total = $quantity * $price;
                $totalAmount += $total;

                $po->items()->create([
                    'product_id' => $itemData['product_id'],
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $total,
                ]);
            }

            // Update total amount di header PO
            $po->total_amount = $totalAmount;
            $po->save();

            // Update status PR menjadi 'processed'
            $purchaseRequest->status = 'processed';
            $purchaseRequest->save();
            
            $usersToNotify = User::role('Gudang')->get();
            if ($usersToNotify->isNotEmpty()) {
            Notification::send($usersToNotify, new NewPurchaseOrderNotification($po));
            }

            DB::commit(); // Simpan semua perubahan jika berhasil

            return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua jika ada error
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat PO: ' . $e->getMessage())->withInput();
        }
    }
    
    public function show(PurchaseOrder $purchaseOrder)
    {
        // Load relasi yang dibutuhkan
        $purchaseOrder->load('supplier', 'creator', 'items.product');
        return view('purchase_orders.show', compact('purchaseOrder'));
    }
}
