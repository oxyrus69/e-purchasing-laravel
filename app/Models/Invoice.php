<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Invoice extends Model {
    use HasFactory;
    protected $guarded = ['id'];
    public function purchaseOrder() { return $this->belongsTo(PurchaseOrder::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function items() { return $this->hasMany(InvoiceItem::class); }
}