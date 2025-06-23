<?php

namespace Tests\Unit\Services;

use App\Models\Product;
use App\Models\User;
use App\Models\StockRequisition; // Diperlukan untuk referensi
use App\Notifications\MinimumStockNotification;
use App\Services\StockService;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class StockServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $stockService;
    protected $product;

    // Method setUp() akan berjalan sebelum setiap tes di dalam kelas ini
    protected function setUp(): void
    {
        parent::setUp();

        // Buat instance dari StockService yang akan kita uji
        $this->stockService = new StockService();

        // Siapkan data awal untuk setiap tes
        $this->product = Product::factory()->create([
            'name' => 'Produk Tes',
            'stock' => 100,
            'minimum_stock' => 10,
        ]);
        
        // Jalankan seeder untuk memastikan peran dan izin ada
        $this->seed(RoleAndPermissionSeeder::class);
    }

    /**
     * Skenario 1: Tes apakah stok berhasil berkurang dengan benar.
     */
    public function test_it_correctly_reduces_stock()
    {
        // ARRANGE (Persiapan)
        $requester = User::factory()->create();
        $requisition = StockRequisition::factory()->create(['requester_id' => $requester->id]);

        // ACT (Aksi)
        $this->stockService->reduceStock($this->product, 20, 'Tes Pengurangan', $requisition);
        
        // ASSERT (Verifikasi)
        // 1. Pastikan stok produk di database sudah menjadi 80
        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'stock' => 80,
        ]);

        // 2. Pastikan ada catatan 'out' di kartu stok
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'type' => 'out',
            'quantity' => 20,
            'balance_after' => 80,
        ]);
    }

    /**
     * Skenario 2: Tes apakah sistem melempar error jika stok tidak mencukupi.
     */
    public function test_it_throws_exception_for_insufficient_stock()
    {
        // Kita mengharapkan sebuah Exception (error) akan terjadi
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Stok Produk Tes tidak mencukupi. Stok tersedia: 100");

        // ARRANGE
        $requester = User::factory()->create();
        $requisition = StockRequisition::factory()->create(['requester_id' => $requester->id]);

        // ACT: Coba kurangi 150 dari stok yang hanya 100
        $this->stockService->reduceStock($this->product, 150, 'Tes Stok Kurang', $requisition);
    }

    /**
     * Skenario 3: Tes apakah notifikasi stok minimum terkirim.
     */
    public function test_it_sends_notification_when_stock_reaches_minimum()
    {
        // Gunakan fitur "Fake" dari Laravel untuk "menangkap" notifikasi
        Notification::fake();

        // ARRANGE
        // Buat user dengan peran 'Purchasing' yang akan menerima notifikasi
        $purchasingUser = User::factory()->create();
        $purchasingUser->assignRole('Purchasing');

        $requester = User::factory()->create();
        $requisition = StockRequisition::factory()->create(['requester_id' => $requester->id]);

        // ACT: Kurangi stok dari 100 menjadi 5 (di bawah minimum_stock 10)
        $this->stockService->reduceStock($this->product, 95, 'Tes Notifikasi Stok', $requisition);

        // ASSERT
        // Pastikan notifikasi MinimumStockNotification telah dikirim ke user 'Purchasing'
        Notification::assertSentTo(
            [$purchasingUser], MinimumStockNotification::class
        );
    }
}