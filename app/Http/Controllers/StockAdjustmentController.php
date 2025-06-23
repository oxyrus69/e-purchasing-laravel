<?php
// File: app/Http/Controllers/StockAdjustmentController.php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class StockAdjustmentController extends Controller
{
    protected $stockService;

    // Inject StockService melalui constructor
    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function create()
    {
        $this->authorize('adjust-stock');
        $products = Product::orderBy('name')->get();
        return view('stock-adjustments.create', compact('products'));
    }

    public function store(Request $request)
    {
        $this->authorize('adjust-stock');
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'new_stock' => 'required|integer|min:0',
            'notes' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            $product = Product::find($request->product_id);
            $oldStock = $product->stock;
            $newStock = (int)$request->new_stock;

            if ($oldStock == $newStock) {
                return redirect()->route('stocks.show', $product->id)->with('info', 'Tidak ada perubahan stok.');
            }

            $quantityChange = $newStock - $oldStock;

            // Logika baru menggunakan StockService
            if ($quantityChange > 0) { // Jika stok bertambah
                // Logika untuk menambah stok (jika diperlukan service terpisah)
                // Untuk sekarang, kita update langsung dan catat di kartu stok
                $product->update(['stock' => $newStock]);
                $product->stockMovements()->create([
                    'type' => 'adjustment',
                    'quantity' => $quantityChange,
                    'balance_after' => $newStock,
                    'description' => $request->notes . " (Stok ditambah dari {$oldStock} menjadi {$newStock})",
                    'reference_id' => auth()->id(),
                    'reference_type' => User::class,
                ]);
            } else { // Jika stok berkurang
                // Gunakan reduceStock dari StockService
                $this->stockService->reduceStock(
                    $product, 
                    abs($quantityChange), // jumlah yang dikurangi (nilai positif)
                    $request->notes . " (Stok dikurangi dari {$oldStock} menjadi {$newStock})", 
                    auth()->user() // Referensi ke user yang melakukan
                );
            }

            DB::commit();
            return redirect()->route('stocks.show', $product->id)->with('success', 'Stok berhasil disesuaikan.');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyesuaikan stok: ' . $e->getMessage())->withInput();
        }
    }
}