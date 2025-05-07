<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceConsultation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'service_id',
        'notes',
        'recommended_date',
        'created_by',
        'updated_by',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'recommended_date' => 'datetime',
    ];

    /**
     * Get the customer that owns the consultation.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the service that owns the consultation.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the user that created the consultation.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user that updated the consultation.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the appointment associated with the consultation.
     */
    public function appointment()
    {
        return $this->hasOne(Appointment::class);
    }
}
