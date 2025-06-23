<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class StockRequisitionItem extends Model {
    use HasFactory;
    protected $guarded = ['id'];
    public function stockRequisition() { return $this->belongsTo(StockRequisition::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
