<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('stock_requisition_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_requisition_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('stock_requisition_items'); }
};