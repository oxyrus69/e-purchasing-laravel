<?php

// File: database/migrations/xxxx_xx_xx_xxxxxx_create_suppliers_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_person')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('suppliers'); }
};

// File: database/migrations/xxxx_xx_xx_xxxxxx_create_products_table.php
// ... (Lakukan hal yang sama untuk tabel products)
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unit'); // e.g., Pcs, Kg, Box
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('products'); }
};


// File: database/migrations/xxxx_xx_xx_xxxxxx_create_purchase_requests_table.php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('pr_number')->unique();
            $table->foreignId('requester_id')->constrained('users'); // User who created the PR
            $table->foreignId('department_id')->constrained('departments');
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

// File: database/migrations/xxxx_xx_xx_xxxxxx_create_purchase_request_items_table.php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('purchase_request_items'); }
};


// File: database/migrations/xxxx_xx_xx_xxxxxx_create_purchase_orders_table.php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('purchase_request_id')->constrained();
            $table->foreignId('supplier_id')->constrained();
            $table->foreignId('order_by_id')->constrained('users'); // Purchasing staff
            $table->date('order_date');
            $table->enum('status', ['draft', 'sent', 'partially_received', 'fully_received', 'invoiced', 'canceled'])->default('draft');
            $table->decimal('total_amount', 15, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('purchase_orders'); }
};

// File: database/migrations/xxxx_xx_xx_xxxxxx_create_purchase_order_items_table.php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->decimal('total', 15, 2);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('purchase_order_items'); }
};
