<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class InvoiceItem extends Model
{
    use HasFactory, HasUuids;
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'id',
        'invoice_id',
        'service_id',
        'item_name',
        'item_description',
        'quantity',
        'unit_price',
        'discount',
        'total',
    ];
    
    protected $casts = [
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];
    
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    
    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 0, ',', '.') . ' VNĐ';
    }
    
    public function getFormattedDiscountAttribute()
    {
        return number_format($this->discount, 0, ',', '.') . ' VNĐ';
    }
    
    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 0, ',', '.') . ' VNĐ';
    }
}
