<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceiptNote;
use App\Models\PurchaseOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class GoodsReceiptNoteController extends Controller
{
    public function index(Request $request)
    {
        $query = GoodsReceiptNote::with('purchaseOrder.supplier');

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('grn_number', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('purchaseOrder', function ($q) use ($searchTerm) {
                      $q->where('po_number', 'like', '%' . $searchTerm . '%');
                  });
        }

        $grns = $query->latest()->paginate(10)->withQueryString();
        return view('goods_receipt_notes.index', compact('grns'));
    }

    public function create(Request $request)
    {
        $po_id = $request->query('po');
        if (!$po_id) {
            return redirect()->route('purchase-orders.index')->with('error', 'Purchase Order tidak valid.');
        }

        $purchaseOrder = PurchaseOrder::with('items.product', 'goodsReceiptNotes.items')->findOrFail($po_id);

        return view('goods_receipt_notes.create', compact('purchaseOrder'));
    }

    public function store(Request $request)
{
    $request->validate([
        'purchase_order_id' => 'required|exists:purchase_orders,id',
        'received_date' => 'required|date',
        'items' => 'required|array',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity_received' => 'required|integer|min:0',
    ]);

    $totalReceivedInForm = collect($request->items)->sum('quantity_received');
    if ($totalReceivedInForm <= 0) {
        return redirect()->back()->withInput()->with('error', 'Jumlah diterima tidak boleh kosong. Harap isi minimal satu item.');
    }

    try {
        DB::beginTransaction();

        $po = PurchaseOrder::with('items', 'goodsReceiptNotes.items')->findOrFail($request->purchase_order_id);
        
        // Validasi agar tidak over-receive
        foreach($po->items as $poItem) {
            $totalPreviouslyReceived = $po->goodsReceiptNotes->flatMap->items->where('product_id', $poItem->product_id)->sum('quantity_received');
            $quantityInForm = collect($request->items)->firstWhere('product_id', $poItem->product_id)['quantity_received'] ?? 0;
            
            if (($totalPreviouslyReceived + $quantityInForm) > $poItem->quantity) {
                throw new Exception("Jumlah diterima untuk produk '{$poItem->product->name}' melebihi jumlah yang dipesan.");
            }
        }

        $grn = GoodsReceiptNote::create([
            'grn_number' => 'GRN-' . time(),
            'purchase_order_id' => $po->id,
            'received_by_id' => Auth::id(),
            'received_date' => $request->received_date,
            'notes' => $request->notes,
        ]);

        foreach ($request->items as $itemData) {
            if ($itemData['quantity_received'] > 0) {
                $grn->items()->create([
                    'product_id' => $itemData['product_id'],
                    'quantity_received' => $itemData['quantity_received'],
                ]);

                // !! LOGIKA UTAMA: HANYA SATU CARA UNTUK UPDATE STOK !!
                $product = Product::find($itemData['product_id']);
                if ($product) {
                    $newStock = $product->stock + $itemData['quantity_received'];
                    $product->update(['stock' => $newStock]);

                    // Buat catatan di kartu stok
                    $product->stockMovements()->create([
                        'type' => 'in',
                        'quantity' => $itemData['quantity_received'],
                        'balance_after' => $newStock,
                        'description' => 'Penerimaan dari PO #' . $po->po_number,
                        'reference_id' => $grn->id,
                        'reference_type' => GoodsReceiptNote::class,
                    ]);
                }
            }
        }

        $this->updatePurchaseOrderStatus($po);
        
        DB::commit();
        return redirect()->route('goods-receipt-notes.index')->with('success', 'Penerimaan Barang & Stok berhasil dicatat.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
    }
}

    public function show(GoodsReceiptNote $goodsReceiptNote)
    {
        $goodsReceiptNote->load('purchaseOrder.supplier', 'receiver', 'items.product');
        return view('goods_receipt_notes.show', compact('goodsReceiptNote'));
    }
    
    private function updatePurchaseOrderStatus(PurchaseOrder $po)
    {
        // !! LOGIKA DIPERBARUI !!
        // Muat ulang relasi dari DB untuk mendapatkan data GRN terbaru, termasuk yang baru saja dibuat
        $po->load('items', 'goodsReceiptNotes.items');

        $isFullyReceived = true;

        foreach ($po->items as $poItem) {
            $totalReceived = $po->goodsReceiptNotes
                ->flatMap->items
                ->where('product_id', $poItem->product_id)
                ->sum('quantity_received');

            if ($totalReceived < $poItem->quantity) {
                $isFullyReceived = false;
                break; // Jika satu item saja belum lunas, tidak perlu cek yang lain
            }
        }

        $totalReceivedEver = $po->goodsReceiptNotes->flatMap->items->sum('quantity_received');

        if ($isFullyReceived) {
            $po->status = 'fully_received';
        } elseif ($totalReceivedEver > 0) {
            $po->status = 'partially_received';
        }

        $po->save();
    }
}
