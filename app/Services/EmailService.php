<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Message;
use App\Models\User;
use App\Models\Appointment;
use App\Models\EmailLog;

class EmailService
{
    /**
     * Send an email with error handling and logging
     *
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $view View template for email
     * @param array $data Data to pass to the view
     * @param array $options Additional options (cc, bcc, attachments, etc.)
     * @return bool Whether the email was sent successfully
     */
    public function send(string $to, string $subject, string $view, array $data = [], array $options = []): bool
    {
        // Log the email attempt
        Log::info('Attempting to send email', [
            'to' => $to,
            'subject' => $subject,
            'view' => $view,
            'mail_config' => [
                'driver' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'username' => config('mail.mailers.smtp.username'),
                'encryption' => config('mail.mailers.smtp.encryption'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name'),
            ]
        ]);

        // Create an email log entry
        $emailLog = EmailLog::create([
            'to' => $to,
            'subject' => $subject,
            'template' => $view,
            'data' => json_encode($data),
            'status' => 'pending',
            'attempts' => 0,
        ]);

        try {
            // Add retry mechanism for better reliability
            $maxRetries = 3;
            $retryCount = 0;
            $success = false;
            $lastError = null;

            while ($retryCount < $maxRetries && !$success) {
                try {
                    // Increment attempt counter
                    $emailLog->increment('attempts');
                    
                    // Send the email
                    Mail::send($view, $data, function (Message $message) use ($to, $subject, $options) {
                        $message->to($to)->subject($subject);
                        
                        // Add CC recipients if specified
                        if (!empty($options['cc'])) {
                            $message->cc($options['cc']);
                        }
                        
                        // Add BCC recipients if specified
                        if (!empty($options['bcc'])) {
                            $message->bcc($options['bcc']);
                        }
                        
                        // Add attachments if specified
                        if (!empty($options['attachments'])) {
                            foreach ($options['attachments'] as $attachment) {
                                if (isset($attachment['path']) && file_exists($attachment['path'])) {
                                    $message->attach($attachment['path'], $attachment['options'] ?? []);
                                }
                            }
                        }
                        
                        // Set from address if specified, otherwise use default
                        if (!empty($options['from'])) {
                            $message->from($options['from']['address'], $options['from']['name'] ?? null);
                        }
                    });
                    
                    // If we get here, the email was sent successfully
                    $success = true;
                    Log::info('Email sent successfully on attempt ' . ($retryCount + 1), [
                        'to' => $to,
                        'subject' => $subject
                    ]);
                    
                    // Update email log
                    $emailLog->update([
                        'status' => 'sent',
                        'sent_at' => now(),
                    ]);
                    
                } catch (\Exception $e) {
                    $lastError = $e;
                    $retryCount++;
                    
                    if ($retryCount < $maxRetries) {
                        Log::warning('Email sending failed on attempt ' . $retryCount . ', retrying...', [
                            'error' => $e->getMessage(),
                            'to' => $to,
                            'subject' => $subject
                        ]);
                        sleep(2); // Wait 2 seconds before retrying
                    }
                }
            }
            
            // If all retries failed, log the error and update the email log
            if (!$success) {
                Log::error('Email sending failed after ' . $maxRetries . ' attempts', [
                    'error' => $lastError ? $lastError->getMessage() : 'Unknown error',
                    'to' => $to,
                    'subject' => $subject
                ]);
                
                // Update email log
                $emailLog->update([
                    'status' => 'failed',
                    'error' => $lastError ? $lastError->getMessage() : 'Unknown error',
                ]);
                
                return false;
            }
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Email sending error', [
                'error' => $e->getMessage(),
                'to' => $to,
                'subject' => $subject
            ]);
            
            // Update email log
            $emailLog->update([
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }

    /**
     * Send a registration confirmation email
     *
     * @param User $user The user who registered
     * @return bool Whether the email was sent successfully
     */
    public function sendRegistrationConfirmation(User $user): bool
    {
        $subject = 'Chào mừng bạn đến với Beauty Spa Booking';
        
        $data = [
            'user' => $user,
            'login_url' => route('login'),
            'app_name' => config('app.name'),
            'current_year' => date('Y'),
        ];
        
        return $this->send($user->email, $subject, 'emails.registration', $data);
    }

    /**
     * Send an appointment confirmation email
     *
     * @param Appointment $appointment The appointment that was booked
     * @return bool Whether the email was sent successfully
     */
    public function sendAppointmentConfirmation(Appointment $appointment): bool
    {
        // Load the appointment with its relationships
        $appointment->load(['service', 'customer', 'timeAppointment']);
        
        $subject = 'Xác nhận đặt lịch - Beauty Spa Booking';
        
        $data = [
            'appointment' => $appointment,
            'user_name' => $appointment->customer->first_name . ' ' . $appointment->customer->last_name,
            'service_name' => $appointment->service->name,
            'appointment_date' => $appointment->date_appointments,
            'appointment_time' => $appointment->timeAppointment->time,
            'appointment_url' => route('customer.appointments.show', $appointment->id),
            'app_name' => config('app.name'),
            'current_year' => date('Y'),
        ];
        
        return $this->send($appointment->customer->email, $subject, 'emails.appointment-confirmation', $data);
    }

    /**
     * Send an appointment reminder email
     *
     * @param Appointment $appointment The appointment to remind about
     * @return bool Whether the email was sent successfully
     */
    public function sendAppointmentReminder(Appointment $appointment): bool
    {
        // Load the appointment with its relationships
        $appointment->load(['service', 'customer', 'timeAppointment']);
        
        $subject = 'Nhắc nhở lịch hẹn - Beauty Spa Booking';
        
        $data = [
            'appointment' => $appointment,
            'user_name' => $appointment->customer->first_name . ' ' . $appointment->customer->last_name,
            'service_name' => $appointment->service->name,
            'appointment_date' => $appointment->date_appointments,
            'appointment_time' => $appointment->timeAppointment->time,
            'appointment_url' => route('customer.appointments.show', $appointment->id),
            'app_name' => config('app.name'),
            'current_year' => date('Y'),
        ];
        
        return $this->send($appointment->customer->email, $subject, 'emails.appointment-reminder', $data);
    }

    /**
     * Send a test email
     *
     * @param string $to Recipient email address
     * @return bool Whether the email was sent successfully
     */
    public function sendTestEmail(string $to): bool
    {
        $subject = 'Test Email from Beauty Spa Booking';
        
        $data = [
            'app_name' => config('app.name'),
            'current_year' => date('Y'),
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ];
        
        return $this->send($to, $subject, 'emails.test', $data);
    }
}
