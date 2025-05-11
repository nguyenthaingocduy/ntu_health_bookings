<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The appointment instance.
     *
     * @var \App\Models\Appointment
     */
    public $appointment;

    /**
     * The reminder message.
     *
     * @var string
     */
    public $message;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment, string $message)
    {
        $this->appointment = $appointment;
        $this->message = $message;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nhắc nhở lịch hẹn tại Beauty Spa',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $appointment = $this->appointment;
        $service = $appointment->service;
        $customer = $appointment->customer;

        return new Content(
            view: 'emails.appointment-reminder',
            with: [
                'appointment' => $appointment,
                'message' => $this->message,
                'app_name' => config('app.name', 'Beauty Spa'),
                'user_name' => $customer->first_name . ' ' . $customer->last_name,
                'service_name' => $service->name,
                'appointment_date' => $appointment->date_appointments,
                'appointment_time' => $appointment->timeSlot ? $appointment->timeSlot->start_time : ($appointment->timeAppointment ? $appointment->timeAppointment->started_time : 'N/A'),
                'appointment_url' => route('customer.appointments.show', $appointment->id),
                'dashboard_url' => route('customer.appointments.index'),
                'current_year' => date('Y'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
