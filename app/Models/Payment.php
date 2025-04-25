<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appointment_id',
        'customer_id',
        'amount',
        'payment_method',
        'payment_status',
        'transaction_id',
        'notes',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the appointment that owns the payment.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the customer that owns the payment.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the user that created the payment.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user that updated the payment.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
