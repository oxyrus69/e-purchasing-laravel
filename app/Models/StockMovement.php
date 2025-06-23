<?php
// File: app/Models/StockMovement.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class StockMovement extends Model {
    use HasFactory;
    protected $guarded = ['id'];
    public function product() { return $this->belongsTo(Product::class); }
    public function reference() { return $this->morphTo(); }
}