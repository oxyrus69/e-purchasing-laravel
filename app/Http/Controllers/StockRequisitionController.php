<?php
// File: app/Http/Controllers/StockRequisitionController.php
namespace App\Http\Controllers;

use App\Services\StockService;
use App\Models\Product;
use App\Models\StockRequisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

class StockRequisitionController extends Controller
{
    public function index()
    {
        $requisitions = StockRequisition::with('requester')->latest()->paginate(10);
        return view('stock-requisitions.index', compact('requisitions'));
    }

    public function create()
    {
        $this->authorize('create-stock-requisition');
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        return view('stock-requisitions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-stock-requisition');
        $request->validate(['request_date' => 'required|date', 'items' => 'required|array']);

        foreach ($request->items as $itemData) {
            $product = Product::find($itemData['product_id']);
            if ($product->stock < $itemData['quantity']) {
                return redirect()->back()->withInput()->with('error', "Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}");
            }
        }
        
        $requisition = StockRequisition::create([
            'requisition_number' => 'REQ-' . time(),
            'requester_id' => Auth::id(),
            'request_date' => $request->request_date,
        ]);
        $requisition->items()->createMany($request->items);

        return redirect()->route('stock-requisitions.index')->with('success', 'Permintaan barang berhasil dibuat.');
    }

    public function show(StockRequisition $stockRequisition)
    {
        $stockRequisition->load('requester', 'approver', 'items.product');
        return view('stock-requisitions.show', compact('stockRequisition'));
    }

    public function approve(StockRequisition $stockRequisition)
    {
        $this->authorize('approve-stock-requisition');
        if ($stockRequisition->status !== 'pending') {
            return redirect()->back()->with('error', 'Permintaan ini sudah diproses.');
        }

        try {
            DB::beginTransaction();
            foreach ($stockRequisition->items as $item) {
                $this->stockService->reduceStock($item->product, $item->quantity, 'Pengeluaran untuk No. Req: ' . $stockRequisition->requisition_number, $stockRequisition);
                
                $product = Product::find($item->product_id);
                if ($product->stock < $item->quantity) {
                    throw new Exception("Stok {$product->name} tidak mencukupi untuk disetujui.");
                }
                $newStock = $product->stock - $item->quantity;
                $product->update(['stock' => $newStock]);

                $product->stockMovements()->create([
                    'type' => 'out', 'quantity' => $item->quantity, 'balance_after' => $newStock,
                    'description' => 'Pengeluaran untuk No. Req: ' . $stockRequisition->requisition_number,
                    'reference_id' => $stockRequisition->id, 'reference_type' => StockRequisition::class,
                ]);
            }
            $stockRequisition->update([
                'status' => 'approved', 'approver_id' => Auth::id(), 'approved_date' => Carbon::now()
            ]);
            DB::commit();
            return redirect()->route('stock-requisitions.show', $stockRequisition)->with('success', 'Barang berhasil dikeluarkan dan stok diperbarui.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}