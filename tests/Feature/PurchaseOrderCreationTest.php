<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseOrderCreationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tes untuk memastikan pengguna yang berwenang dapat membuat PO dari PR yang disetujui.
     *
     * @return void
     */
    public function test_authorized_user_can_create_po_from_approved_pr()
    {
        // 1. ARRANGE (Persiapan)
        $this->seed(RoleAndPermissionSeeder::class);

        // Buat pengguna dengan peran yang bisa membuat PO (misal, Manager)
        $managerUser = User::factory()->create();
        $managerUser->assignRole('Manager');

        // Buat produk, supplier, dan PR awal
        $product = Product::factory()->create();
        $supplier = Supplier::factory()->create();
        $pr = PurchaseRequest::factory()->create(['status' => 'approved']);
        
        // Tambahkan item ke dalam PR
        $pr->items()->create([
            'product_id' => $product->id,
            'quantity' => 10,
        ]);

        // Siapkan data yang akan dikirim melalui form pembuatan PO
        $poData = [
            'purchase_request_id' => $pr->id,
            'supplier_id' => $supplier->id,
            'order_date' => now()->format('Y-m-d'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 10,
                    'price' => 5000,
                ]
            ]
        ];

        // 2. ACT (Aksi)
        // Simulasi Manager login dan mengirimkan data form untuk membuat PO
        $response = $this->actingAs($managerUser)
                         ->post(route('purchase-orders.store'), $poData);
        
        // 3. ASSERT (Verifikasi)
        // a. Pastikan tidak ada error validasi dan prosesnya berhasil redirect
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('purchase-orders.index'));

        // b. Pastikan data PO tersimpan di database
        $this->assertDatabaseHas('purchase_orders', [
            'purchase_request_id' => $pr->id,
            'supplier_id' => $supplier->id,
            'total_amount' => 50000, // 10 * 5000
        ]);

        // c. Pastikan item PO juga tersimpan di database
        $this->assertDatabaseHas('purchase_order_items', [
            'product_id' => $product->id,
            'quantity' => 10,
            'price' => 5000,
        ]);

        // d. Pastikan status PR yang asli telah berubah menjadi 'processed'
        $this->assertDatabaseHas('purchase_requests', [
            'id' => $pr->id,
            'status' => 'processed',
        ]);
    }
}