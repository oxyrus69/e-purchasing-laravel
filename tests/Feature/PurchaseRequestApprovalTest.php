<?php

namespace Tests\Feature;

use App\Models\PurchaseRequest;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseRequestApprovalTest extends TestCase
{
    // Trait ini akan secara otomatis menjalankan migrasi pada database 'in-memory'
    // setiap kali sebuah tes dijalankan.
    use RefreshDatabase;

    /**
     * Tes untuk memastikan pengguna tanpa izin tidak bisa menyetujui PR.
     *
     * @return void
     */
    public function test_unauthorized_user_cannot_approve_purchase_request(): void
    {
        // 1. SETUP (Persiapan)
        // Jalankan seeder untuk membuat peran dan izin
        $this->seed(RoleAndPermissionSeeder::class);

        // Buat satu pengguna dan berikan peran 'Staff'
        $staffUser = User::factory()->create();
        $staffUser->assignRole('Staff');

        // Buat satu pengguna lain sebagai pembuat PR
        $requester = User::factory()->create();

        // Buat sebuah Purchase Request
        $purchaseRequest = PurchaseRequest::factory()->create([
            'requester_id' => $requester->id,
            'status' => 'pending_approval',
        ]);

        // 2. ACTION (Aksi)
        // Simulasi pengguna 'Staff' mencoba mengakses rute approve
        $response = $this->actingAs($staffUser)
                         ->patch(route('purchase-requests.approve', $purchaseRequest));

        // 3. ASSERTION (Penegasan/Verifikasi)
        // Kita tegaskan bahwa respons yang diharapkan adalah error 403 (Forbidden)
        $response->assertStatus(403);

        // Kita juga pastikan status PR di database tidak berubah menjadi 'approved'
        $this->assertDatabaseHas('purchase_requests', [
            'id' => $purchaseRequest->id,
            'status' => 'pending_approval',
        ]);
    }

    /**
     * Tes untuk memastikan pengguna yang berwenang BISA menyetujui PR.
     *
     * @return void
     */
    public function test_authorized_user_can_approve_purchase_request(): void
    {
        // 1. SETUP
        $this->seed(RoleAndPermissionSeeder::class);

        // Buat pengguna dan berikan peran 'Manager'
        $managerUser = User::factory()->create();
        $managerUser->assignRole('Manager');

        $requester = User::factory()->create();
        $purchaseRequest = PurchaseRequest::factory()->create([
            'requester_id' => $requester->id,
            'status' => 'pending_approval',
        ]);

        // 2. ACTION
        // Simulasi 'Manager' mengakses rute approve
        $response = $this->actingAs($managerUser)
                         ->patch(route('purchase-requests.approve', $purchaseRequest));

        // 3. ASSERTION
        // Kita harapkan prosesnya berhasil dan diarahkan kembali (redirect)
        $response->assertRedirect();

        // Kita pastikan status PR di database telah berubah menjadi 'approved'
        $this->assertDatabaseHas('purchase_requests', [
            'id' => $purchaseRequest->id,
            'status' => 'approved',
        ]);
    }
}
