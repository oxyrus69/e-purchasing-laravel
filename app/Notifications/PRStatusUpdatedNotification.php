<?php
namespace App\Notifications;
use App\Models\PurchaseRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
class PRStatusUpdatedNotification extends Notification
{
    use Queueable;
    public $purchaseRequest;
    public function __construct(PurchaseRequest $purchaseRequest) { $this->purchaseRequest = $purchaseRequest; }
    public function via(object $notifiable): array { return ['database']; }
    public function toArray(object $notifiable): array {
        return [
            'type' => 'pr_status_updated',
            'url' => route('purchase-requests.show', $this->purchaseRequest->id),
            'message' => 'Status PR (' . $this->purchaseRequest->pr_number . ') Anda telah diubah menjadi ' . strtoupper($this->purchaseRequest->status) . '.'
        ];
    }
}