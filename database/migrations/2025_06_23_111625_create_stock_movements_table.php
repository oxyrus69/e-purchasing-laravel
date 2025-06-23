<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['in', 'out', 'adjustment']);
            $table->integer('quantity');
            $table->integer('balance_after');
            $table->string('description');
            $table->morphs('reference'); // Untuk referensi ke GRN, dll.
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('stock_movements'); }
};