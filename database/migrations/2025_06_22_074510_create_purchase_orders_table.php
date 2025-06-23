// File: ...create_purchase_orders_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('purchase_request_id')->constrained();
            $table->foreignId('supplier_id')->constrained();
            $table->foreignId('order_by_id')->constrained('users');
            $table->date('order_date');
            $table->enum('status', ['draft', 'sent', 'partially_received', 'fully_received', 'invoiced', 'canceled'])->default('draft');
            $table->decimal('total_amount', 15, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('purchase_orders'); }
};