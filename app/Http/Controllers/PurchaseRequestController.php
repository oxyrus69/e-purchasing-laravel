<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\User;
use App\Notifications\NewPurchaseRequestNotification;
use App\Notifications\PRStatusUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    $query = PurchaseRequest::with('requester');

    // Filter pencarian
    if ($request->has('search') && $request->search != '') {
        $query->where('pr_number', 'like', '%' . $request->search . '%');
    }

    // Filter status
    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    $purchaseRequests = $query->latest()->paginate(10)->withQueryString();

    return view('purchase_requests.index', compact('purchaseRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Shows the form to create a PR, passing necessary data like products
        $products = Product::orderBy('name')->get();
        return view('purchase_requests.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation logic
        $request->validate([
            'request_date' => 'required|date',
            'notes' => 'nullable|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        // Create the Purchase Request header
        $pr = PurchaseRequest::create([
            'pr_number' => 'PR-' . time(), // Generate a unique PR number
            'requester_id' => Auth::id(),
            'department_id' => Auth::user()->department_id, // Assuming user has department
            'request_date' => $request->request_date,
            'status' => 'pending_approval',
            'notes' => $request->notes,
        ]);

        // Create PR items
        foreach ($request->products as $item) {
            $pr->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        // Notifications PR
        $pr->load('requester'); 
        $usersToNotify = User::permission('approve-pr')->get();
        Notification::send($usersToNotify, new NewPurchaseRequestNotification($pr));
        return redirect()->route('purchase-requests.index')->with('success', 'Permintaan Pembelian berhasil dibuat.');
                
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseRequest $purchaseRequest)
    {
        // Show details of a specific PR
        return view('purchase_requests.show', compact('purchaseRequest'));
    }

    // ... (edit, update, destroy, approve methods would follow)
    /**
     * Approve the specified purchase request.
     */
    public function approve(PurchaseRequest $purchaseRequest)
    {
        // Untuk saat ini, kita belum punya role, jadi siapa saja bisa approve
        if ($purchaseRequest->status == 'pending_approval') {
            $purchaseRequest->status = 'approved';
            $purchaseRequest->approver_id = Auth::id();
            $purchaseRequest->approved_date = Carbon::now();
            $purchaseRequest->save();
            Notification::send($purchaseRequest->requester, new PRStatusUpdatedNotification($purchaseRequest));
            return redirect()->route('purchase-requests.show', $purchaseRequest)->with('success', 'Permintaan Pembelian berhasil disetujui.');
        }

        return redirect()->route('purchase-requests.show', $purchaseRequest)->with('error', 'Permintaan ini tidak dapat disetujui.');
    }

    /**
     * Reject the specified purchase request.
     */
    public function reject(PurchaseRequest $purchaseRequest)
    {
        // Untuk saat ini, kita belum punya role, jadi siapa saja bisa reject
        if ($purchaseRequest->status == 'pending_approval') {
            $purchaseRequest->status = 'rejected';
            $purchaseRequest->approver_id = Auth::id(); // Catat siapa yang me-reject
            $purchaseRequest->save();
            Notification::send($purchaseRequest->requester, new PRStatusUpdatedNotification($purchaseRequest));
            return redirect()->route('purchase-requests.show', $purchaseRequest)->with('success', 'Permintaan Pembelian telah ditolak.');
        }

        return redirect()->route('purchase-requests.show', $purchaseRequest)->with('error', 'Permintaan ini tidak dapat ditolak.');
    }

    public function edit(PurchaseRequest $purchaseRequest)
    {
        // Hanya bisa diedit jika statusnya pending approval
        if ($purchaseRequest->status !== 'pending_approval') {
            return redirect()->route('purchase-requests.show', $purchaseRequest->id)->with('error', 'Hanya PR yang masih pending yang bisa diedit.');
        }

        $products = Product::orderBy('name')->get();
        return view('purchase_requests.edit', compact('purchaseRequest', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        $request->validate([
            'request_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            // Update header PR
            $purchaseRequest->update([
                'request_date' => $request->request_date,
                'notes' => $request->notes,
            ]);

            $submittedItemIds = [];

            // Proses item yang ada dan yang baru
            foreach ($request->items as $key => $itemData) {
                if (isset($itemData['id'])) { // Ini adalah item lama
                    $item = PurchaseRequestItem::find($itemData['id']);
                    if ($item) {
                        $item->update([
                            'product_id' => $itemData['product_id'],
                            'quantity' => $itemData['quantity'],
                        ]);
                        $submittedItemIds[] = $item->id;
                    }
                } else { // Ini adalah item baru
                    $purchaseRequest->items()->create([
                        'product_id' => $itemData['product_id'],
                        'quantity' => $itemData['quantity'],
                    ]);
                }
            }

            // Hapus item yang tidak ada dalam submit
            $purchaseRequest->items()->whereNotIn('id', $submittedItemIds)->delete();

            DB::commit();
            return redirect()->route('purchase-requests.show', $purchaseRequest->id)->with('success', 'Permintaan Pembelian berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(PurchaseRequest $purchaseRequest)
    {
        // Hanya bisa dihapus jika statusnya pending approval
        if ($purchaseRequest->status !== 'pending_approval') {
            return redirect()->route('purchase-requests.show', $purchaseRequest->id)->with('error', 'Hanya PR yang masih pending yang bisa dihapus.');
        }

        try {
            // Hapus item-item terkait terlebih dahulu (best practice, meskipun onDelete('cascade') sudah ada)
            $purchaseRequest->items()->delete();
            // Hapus PR
            $purchaseRequest->delete();

            return redirect()->route('purchase-requests.index')->with('success', 'Permintaan Pembelian berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('purchase-requests.index')->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
