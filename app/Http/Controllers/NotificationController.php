<?php
// File: app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Mengambil semua notifikasi yang belum dibaca milik pengguna.
     */
    public function getUnread()
    {
        return response()->json(auth()->user()->unreadNotifications);
    }

    /**
     * Menandai notifikasi sebagai sudah dibaca dan mengarahkan ke URL tujuan.
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();

            // Arahkan ke URL yang tersimpan di dalam data notifikasi
            if (isset($notification->data['url'])) {
                return redirect($notification->data['url']);
            }
        }

        // Jika tidak ada URL, kembali ke dashboard
        return redirect()->route('dashboard');
    }
}