<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung data untuk ditampilkan di kartu statistik
        $pendingPRCount = PurchaseRequest::where('status', 'pending_approval')->count();
        $activePOCount = PurchaseOrder::whereIn('status', ['sent', 'partially_received'])->count();
        $supplierCount = Supplier::count();
        $productCount = Product::count();
        
        // Mengambil data terbaru untuk ditampilkan di tabel ringkasan
        $latestPOs = PurchaseOrder::with('supplier')->latest()->take(5)->get();

        // Mengirim semua data ke view
        return view('dashboard', [
            'pendingPRCount' => $pendingPRCount,
            'activePOCount' => $activePOCount,
            'supplierCount' => $supplierCount,
            'productCount' => $productCount,
            'latestPOs' => $latestPOs,
        ]);
    }
}
