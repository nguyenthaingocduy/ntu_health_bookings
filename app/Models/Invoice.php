<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Invoice extends Model
{
    use HasFactory, HasUuids;
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'id',
        'invoice_number',
        'user_id',
        'appointment_id',
        'subtotal',
        'tax',
        'discount',
        'total',
        'payment_method',
        'payment_status',
        'notes',
        'created_by',
    ];
    
    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    
    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal, 0, ',', '.') . ' VNĐ';
    }
    
    public function getFormattedTaxAttribute()
    {
        return number_format($this->tax, 0, ',', '.') . ' VNĐ';
    }
    
    public function getFormattedDiscountAttribute()
    {
        return number_format($this->discount, 0, ',', '.') . ' VNĐ';
    }
    
    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 0, ',', '.') . ' VNĐ';
    }
    
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'paid' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-gray-100 text-gray-800',
        ];
        
        $statusText = [
            'pending' => 'Chờ thanh toán',
            'paid' => 'Đã thanh toán',
            'cancelled' => 'Đã hủy',
            'refunded' => 'Đã hoàn tiền',
        ];
        
        $class = $badges[$this->payment_status] ?? 'bg-gray-100 text-gray-800';
        $text = $statusText[$this->payment_status] ?? 'Không xác định';
        
        return '<span class="px-2 py-1 rounded-full text-xs font-medium ' . $class . '">' . $text . '</span>';
    }
}
