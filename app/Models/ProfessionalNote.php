<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Service;
use App\Models\Appointment;

class ProfessionalNote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'appointment_id',
        'service_id',
        'title',
        'content',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the customer that owns the note.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the appointment that owns the note.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the user that created the note.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user that updated the note.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the service directly associated with the note.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the service associated with the note through the appointment.
     */
    public function appointmentService()
    {
        return $this->hasOneThrough(
            Service::class,
            Appointment::class,
            'id', // Foreign key on the appointments table...
            'id', // Foreign key on the services table...
            'appointment_id', // Local key on the professional_notes table...
            'service_id' // Local key on the appointments table...
        );
    }
}
