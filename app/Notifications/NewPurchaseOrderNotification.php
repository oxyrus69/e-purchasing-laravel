<?php
namespace App\Notifications;
use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
class NewPurchaseOrderNotification extends Notification
{
    use Queueable;
    public $purchaseOrder;
    public function __construct(PurchaseOrder $purchaseOrder) { $this->purchaseOrder = $purchaseOrder; }
    public function via(object $notifiable): array { return ['database']; }
    public function toArray(object $notifiable): array {
        return [
            'type' => 'new_po',
            'url' => route('purchase-orders.show', $this->purchaseOrder->id),
            'message' => 'PO baru (' . $this->purchaseOrder->po_number . ') untuk supplier ' . $this->purchaseOrder->supplier->name . ' telah dibuat.'
        ];
    }
}