<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class StockRequisition extends Model {
    use HasFactory;
    protected $guarded = ['id'];
    public function requester() { return $this->belongsTo(User::class, 'requester_id'); }
    public function approver() { return $this->belongsTo(User::class, 'approver_id'); }
    public function items() { return $this->hasMany(StockRequisitionItem::class); }
}