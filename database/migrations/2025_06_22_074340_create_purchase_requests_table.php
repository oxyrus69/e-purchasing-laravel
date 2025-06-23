// File: ...create_purchase_requests_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('pr_number')->unique();
            $table->foreignId('requester_id')->constrained('users');
            $table->unsignedBigInteger('department_id')->nullable(); // Dibuat nullable untuk sementara
            $table->date('request_date');
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'rejected', 'processed'])->default('draft');
            $table->foreignId('approver_id')->nullable()->constrained('users');
            $table->date('approved_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('purchase_requests'); }
};