<?php
namespace App\Notifications;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
class MinimumStockNotification extends Notification
{
    use Queueable;
    public $product;
    public function __construct(Product $product) { $this->product = $product; }
    public function via(object $notifiable): array { return ['database']; }
    public function toArray(object $notifiable): array {
        return [
            'type' => 'minimum_stock',
            'url' => route('stocks.show', $this->product->id),
            'message' => "Stok produk {$this->product->name} telah mencapai batas minimum ({$this->product->stock} dari {$this->product->minimum_stock})."
        ];
    }
}