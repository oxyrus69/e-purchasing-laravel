<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GoodsReceiptNoteCreationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Jalankan seeder untuk memastikan semua peran dan izin tersedia
        $this->seed(RoleAndPermissionSeeder::class);
    }

    /**
     * Tes untuk memastikan status PO menjadi 'partially_received'.
     */
    public function test_po_status_becomes_partially_received()
    {
        // 1. ARRANGE
        $gudangUser = User::factory()->create();
        $gudangUser->assignRole('Gudang');

        $product = Product::factory()->create(['stock' => 0]);
        $po = PurchaseOrder::factory()->create();
        
        // Buat item PO dengan kuantitas 20
        $po->items()->create([
            'product_id' => $product->id,
            'quantity' => 20,
            'price' => 1000,
            'total' => 20000,
        ]);

        // Siapkan data form GRN untuk menerima 15 dari 20 item
        $grnData = [
            'purchase_order_id' => $po->id,
            'received_date' => now()->format('Y-m-d'),
            'items' => [
                ['product_id' => $product->id, 'quantity_received' => 15]
            ]
        ];

        // 2. ACT
        $response = $this->actingAs($gudangUser)->post(route('goods-receipt-notes.store'), $grnData);
        
        // 3. ASSERT
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        
        // Pastikan GRN dan itemnya tersimpan
        $this->assertDatabaseHas('goods_receipt_notes', ['purchase_order_id' => $po->id]);
        $this->assertDatabaseHas('goods_receipt_note_items', ['product_id' => $product->id, 'quantity_received' => 15]);

        // Pastikan stok produk bertambah 15
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 15]);

        // Pastikan ada catatan 'in' di kartu stok
        $this->assertDatabaseHas('stock_movements', ['product_id' => $product->id, 'type' => 'in', 'quantity' => 15, 'balance_after' => 15]);

        // Pastikan status PO menjadi 'partially_received'
        $this->assertDatabaseHas('purchase_orders', ['id' => $po->id, 'status' => 'partially_received']);
    }

    /**
     * Tes untuk memastikan status PO menjadi 'fully_received'.
     */
    public function test_po_status_becomes_fully_received()
    {
        // 1. ARRANGE
        $gudangUser = User::factory()->create();
        $gudangUser->assignRole('Gudang');

        $product = Product::factory()->create(['stock' => 0]);
        $po = PurchaseOrder::factory()->create();
        $po->items()->create(['product_id' => $product->id, 'quantity' => 20, 'price' => 1000, 'total' => 20000]);

        // Siapkan data form GRN untuk menerima 20 dari 20 item
        $grnData = [
            'purchase_order_id' => $po->id,
            'received_date' => now()->format('Y-m-d'),
            'items' => [
                ['product_id' => $product->id, 'quantity_received' => 20]
            ]
        ];

        // 2. ACT
        $response = $this->actingAs($gudangUser)->post(route('goods-receipt-notes.store'), $grnData);
        
        // 3. ASSERT
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 20]);

        // Pastikan status PO menjadi 'fully_received'
        $this->assertDatabaseHas('purchase_orders', ['id' => $po->id, 'status' => 'fully_received']);
    }
}