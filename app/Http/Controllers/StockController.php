<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-stock'); // Otorisasi berdasarkan izin

        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        $products = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('stocks.index', compact('products'));
    }

    public function show(Product $stock)
{
    $this->authorize('view-stock');
    
    // Gunakan nama variabel $product agar konsisten
    $product = $stock->load('stockMovements');
    
    return view('stocks.show', compact('product'));
}
}