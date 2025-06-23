<?php
namespace App\Services;

use App\Models\Product;
use App\Models\User;
use App\Notifications\MinimumStockNotification;
use Illuminate\Support\Facades\Notification;

class StockService
{
    public function reduceStock(Product $product, int $quantity, string $description, $reference)
    {
        if ($product->stock < $quantity) {
            throw new \Exception("Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}");
        }

        $newStock = $product->stock - $quantity;
        $product->update(['stock' => $newStock]);

        $product->stockMovements()->create([
            'type' => 'out',
            'quantity' => $quantity,
            'balance_after' => $newStock,
            'description' => $description,
            'reference_id' => $reference->id,
            'reference_type' => get_class($reference),
        ]);

        // Cek dan kirim notifikasi jika stok minimum tercapai
        if ($newStock <= $product->minimum_stock) {
            $this->sendLowStockNotification($product);
        }
    }

    protected function sendLowStockNotification(Product $product)
    {
        // Cari user dengan peran 'Purchasing' atau 'Admin'
        $usersToNotify = User::role(['Purchasing', 'Admin'])->get();
        if ($usersToNotify->isNotEmpty()) {
            Notification::send($usersToNotify, new MinimumStockNotification($product));
        }
    }
}