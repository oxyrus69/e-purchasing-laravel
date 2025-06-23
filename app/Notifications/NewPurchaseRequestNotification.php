<?php

namespace App\Notifications;

use App\Models\PurchaseRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPurchaseRequestNotification extends Notification
{
    use Queueable;

    public $purchaseRequest;

    public function __construct(PurchaseRequest $purchaseRequest)
    {
        $this->purchaseRequest = $purchaseRequest;
    }

    public function via(object $notifiable): array
    {
        // Kita akan menyimpan notifikasi ini di database
        return ['database'];
    }

    // Mendefinisikan data yang akan disimpan di tabel notifications
    public function toArray(object $notifiable): array
    {
        return [
            'pr_id' => $this->purchaseRequest->id,
            'pr_number' => $this->purchaseRequest->pr_number,
            'requester_name' => $this->purchaseRequest->requester->name,
            'message' => 'Permintaan Pembelian baru (' . $this->purchaseRequest->pr_number . ') telah dibuat oleh ' . $this->purchaseRequest->requester->name . ' dan menunggu persetujuan Anda.'
        ];
    }
}