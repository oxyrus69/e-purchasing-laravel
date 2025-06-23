<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// File: app/Models/Product.php
class Product extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function stockMovements()
    {
    return $this->hasMany(StockMovement::class)->orderBy('created_at', 'desc');
    }
    protected $fillable = [
        'code', 'name', 'description', 'unit', 'stock', 'minimum_stock' // Tambahkan minimum_stock
    ];
}

