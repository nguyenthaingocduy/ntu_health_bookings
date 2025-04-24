<?php

namespace App\Services;

use App\Models\EmailNotification;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class EmailNotificationService
{
    /**
     * Send account registration confirmation email
     *
     * @param User $user
     * @return EmailNotification
     */
    public function sendRegistrationConfirmation(User $user)
    {
        $subject = 'Welcome to ' . config('app.name') . ' - Registration Successful';
        $message = $this->getRegistrationEmailContent($user);

        return $this->createAndSendEmail([
            'type' => 'registration',
            'user_id' => $user->id,
            'email' => $user->email,
            'subject' => $subject,
            'message' => $message,
            'data' => [
                'user_name' => $user->first_name . ' ' . $user->last_name,
                'user_email' => $user->email,
            ],
        ]);
    }

    /**
     * Send booking confirmation email
     *
     * @param Appointment $appointment
     * @return EmailNotification
     */
    public function sendBookingConfirmation(Appointment $appointment)
    {
        $user = User::find($appointment->customer_id);
        $service = $appointment->service;

        if (!$user) {
            if (config('app.debug')) {
                Log::error('User not found for appointment: ' . $appointment->id);
            }
            return null;
        }

        $subject = 'Your Appointment Confirmation - ' . config('app.name');
        $message = $this->getBookingConfirmationEmailContent($appointment, $user, $service);

        return $this->createAndSendEmail([
            'type' => 'booking',
            'user_id' => $user->id,
            'appointment_id' => $appointment->id,
            'email' => $user->email,
            'subject' => $subject,
            'message' => $message,
            'data' => [
                'user_name' => $user->first_name . ' ' . $user->last_name,
                'service_name' => $service->name,
                'appointment_date' => $appointment->date_appointments,
                'appointment_time' => $appointment->timeAppointment->started_time ?? 'N/A',
            ],
        ]);
    }

    /**
     * Send appointment reminder email
     *
     * @param Appointment $appointment
     * @return EmailNotification
     */
    public function sendAppointmentReminder(Appointment $appointment)
    {
        $user = User::find($appointment->customer_id);
        $service = $appointment->service;

        if (!$user) {
            if (config('app.debug')) {
                Log::error('User not found for appointment: ' . $appointment->id);
            }
            return null;
        }

        $subject = 'Reminder: Your Upcoming Appointment - ' . config('app.name');
        $message = $this->getAppointmentReminderEmailContent($appointment, $user, $service);

        return $this->createAndSendEmail([
            'type' => 'reminder',
            'user_id' => $user->id,
            'appointment_id' => $appointment->id,
            'email' => $user->email,
            'subject' => $subject,
            'message' => $message,
            'data' => [
                'user_name' => $user->first_name . ' ' . $user->last_name,
                'service_name' => $service->name,
                'appointment_date' => $appointment->date_appointments,
                'appointment_time' => $appointment->timeAppointment->started_time ?? 'N/A',
            ],
        ]);
    }

    /**
     * Create and send an email notification
     *
     * @param array $data
     * @return EmailNotification
     */
    protected function createAndSendEmail(array $data)
    {
        try {
            // Log the attempt (chỉ trong môi trường debug)
            if (config('app.debug')) {
                Log::info('Attempting to send email', [
                    'type' => $data['type'],
                    'email' => $data['email'],
                    'subject' => $data['subject'],
                ]);
            }

            // Create the notification record
            $notification = EmailNotification::create($data);

            // Log mail configuration with more details (chỉ trong môi trường debug)
            if (config('app.debug')) {
                Log::info('Mail configuration', [
                    'driver' => config('mail.default'),
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'username' => config('mail.mailers.smtp.username'),
                    'password_set' => !empty(config('mail.mailers.smtp.password')),
                    'password_length' => !empty(config('mail.mailers.smtp.password')) ? strlen(config('mail.mailers.smtp.password')) : 0,
                    'from_address' => config('mail.from.address'),
                    'from_name' => config('mail.from.name'),
                    'encryption' => config('mail.mailers.smtp.encryption'),
                    'to_email' => $data['email'],
                    'subject' => $data['subject'],
                ]);
            }

            // Send the email
            try {
                // Add a retry mechanism for better reliability
                $maxRetries = 3;
                $retryCount = 0;

                while ($retryCount < $maxRetries) {
                    try {
                        Mail::html($notification->message, function ($message) use ($notification) {
                            $message->to($notification->email)
                                ->subject($notification->subject);

                            $message->from(config('mail.from.address'), config('mail.from.name'));
                        });

                        // If we get here, the email was sent successfully
                        if (config('app.debug')) {
                            Log::info('Email sent successfully on attempt ' . ($retryCount + 1));
                        }
                        break;
                    } catch (\Exception $e) {
                        $retryCount++;

                        if ($retryCount < $maxRetries) {
                            if (config('app.debug')) {
                                Log::warning('Email sending failed on attempt ' . $retryCount . ', retrying... Error: ' . $e->getMessage());
                            }
                            sleep(2); // Wait 2 seconds before retrying
                        } else {
                            if (config('app.debug')) {
                                Log::error('Email sending failed after ' . $maxRetries . ' attempts. Error: ' . $e->getMessage());
                            }
                            throw $e;
                        }
                    }
                }
            } catch (\Exception $e) {
                if (config('app.debug')) {
                    Log::error('Mail sending error: ' . $e->getMessage());
                }
                throw $e;
            }

            // Update the notification status
            $notification->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            if (config('app.debug')) {
                Log::info('Email sent successfully', [
                    'type' => $notification->type,
                    'email' => $notification->email,
                    'subject' => $notification->subject,
                ]);
            }

            return $notification;
        } catch (Exception $e) {
            if (config('app.debug')) {
                Log::error('Failed to send email', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data,
                ]);
            }

            // If notification was created, update its status
            if (isset($notification)) {
                $notification->update([
                    'status' => 'failed',
                ]);
            }

            return $notification ?? null;
        }
    }

    /**
     * Get registration email content
     *
     * @param User $user
     * @return string
     */
    protected function getRegistrationEmailContent(User $user)
    {
        $userName = $user->first_name . ' ' . $user->last_name;

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Welcome to ' . config('app.name') . '</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    max-width: 600px;
                    margin: 0 auto;
                }
                .header {
                    background-color: #f8f9fa;
                    padding: 20px;
                    text-align: center;
                    border-bottom: 3px solid #7c4dff;
                }
                .content {
                    padding: 20px;
                }
                .footer {
                    background-color: #f8f9fa;
                    padding: 15px;
                    text-align: center;
                    font-size: 12px;
                    color: #666;
                }
                h1 {
                    color: #7c4dff;
                }
                .button {
                    display: inline-block;
                    background-color: #7c4dff;
                    color: white;
                    text-decoration: none;
                    padding: 10px 20px;
                    border-radius: 4px;
                    margin-top: 15px;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>' . config('app.name') . '</h1>
            </div>
            <div class="content">
                <h2>Welcome, ' . $userName . '!</h2>
                <p>Thank you for registering with ' . config('app.name') . '. Your account has been successfully created.</p>
                <p>You can now log in to your account and start booking beauty services that suit your needs.</p>
                <p>Here are your account details:</p>
                <ul>
                    <li><strong>Name:</strong> ' . $userName . '</li>
                    <li><strong>Email:</strong> ' . $user->email . '</li>
                </ul>
                <p>If you have any questions or need assistance, please don\'t hesitate to contact our support team.</p>
                <a href="' . url('/login') . '" class="button">Login to Your Account</a>
            </div>
            <div class="footer">
                <p>&copy; ' . date('Y') . ' ' . config('app.name') . '. All rights reserved.</p>
                <p>This email was sent to you because you registered on our website.</p>
            </div>
        </body>
        </html>';
    }

    /**
     * Get booking confirmation email content
     *
     * @param Appointment $appointment
     * @param User $user
     * @param mixed $service
     * @return string
     */
    protected function getBookingConfirmationEmailContent($appointment, $user, $service)
    {
        $userName = $user->first_name . ' ' . $user->last_name;
        $appointmentDate = date('l, F j, Y', strtotime($appointment->date_appointments));
        $appointmentTime = $appointment->timeAppointment->started_time ?? 'N/A';

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Appointment Confirmation</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    max-width: 600px;
                    margin: 0 auto;
                }
                .header {
                    background-color: #f8f9fa;
                    padding: 20px;
                    text-align: center;
                    border-bottom: 3px solid #7c4dff;
                }
                .content {
                    padding: 20px;
                }
                .appointment-details {
                    background-color: #f9f9f9;
                    padding: 15px;
                    border-radius: 5px;
                    margin: 20px 0;
                }
                .footer {
                    background-color: #f8f9fa;
                    padding: 15px;
                    text-align: center;
                    font-size: 12px;
                    color: #666;
                }
                h1, h2 {
                    color: #7c4dff;
                }
                .button {
                    display: inline-block;
                    background-color: #7c4dff;
                    color: white;
                    text-decoration: none;
                    padding: 10px 20px;
                    border-radius: 4px;
                    margin-top: 15px;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>' . config('app.name') . '</h1>
            </div>
            <div class="content">
                <h2>Appointment Confirmation</h2>
                <p>Dear ' . $userName . ',</p>
                <p>Your appointment has been successfully booked. Here are the details:</p>

                <div class="appointment-details">
                    <p><strong>Service:</strong> ' . $service->name . '</p>
                    <p><strong>Date:</strong> ' . $appointmentDate . '</p>
                    <p><strong>Time:</strong> ' . $appointmentTime . '</p>
                    <p><strong>Duration:</strong> ' . $service->duration . ' minutes</p>
                </div>

                <p>Please arrive 10 minutes before your scheduled appointment time.</p>
                <p>If you need to reschedule or cancel your appointment, please do so at least 24 hours in advance.</p>

                <a href="' . url('/my-appointments') . '" class="button">View My Appointments</a>
            </div>
            <div class="footer">
                <p>&copy; ' . date('Y') . ' ' . config('app.name') . '. All rights reserved.</p>
                <p>This email was sent to confirm your appointment booking.</p>
            </div>
        </body>
        </html>';
    }

    /**
     * Get appointment reminder email content
     *
     * @param Appointment $appointment
     * @param User $user
     * @param mixed $service
     * @return string
     */
    protected function getAppointmentReminderEmailContent($appointment, $user, $service)
    {
        $userName = $user->first_name . ' ' . $user->last_name;
        $appointmentDate = date('l, F j, Y', strtotime($appointment->date_appointments));
        $appointmentTime = $appointment->timeAppointment->started_time ?? 'N/A';

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Appointment Reminder</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    max-width: 600px;
                    margin: 0 auto;
                }
                .header {
                    background-color: #f8f9fa;
                    padding: 20px;
                    text-align: center;
                    border-bottom: 3px solid #7c4dff;
                }
                .content {
                    padding: 20px;
                }
                .appointment-details {
                    background-color: #f9f9f9;
                    padding: 15px;
                    border-radius: 5px;
                    margin: 20px 0;
                }
                .footer {
                    background-color: #f8f9fa;
                    padding: 15px;
                    text-align: center;
                    font-size: 12px;
                    color: #666;
                }
                h1, h2 {
                    color: #7c4dff;
                }
                .button {
                    display: inline-block;
                    background-color: #7c4dff;
                    color: white;
                    text-decoration: none;
                    padding: 10px 20px;
                    border-radius: 4px;
                    margin-top: 15px;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>' . config('app.name') . '</h1>
            </div>
            <div class="content">
                <h2>Appointment Reminder</h2>
                <p>Dear ' . $userName . ',</p>
                <p>This is a friendly reminder about your upcoming appointment:</p>

                <div class="appointment-details">
                    <p><strong>Service:</strong> ' . $service->name . '</p>
                    <p><strong>Date:</strong> ' . $appointmentDate . '</p>
                    <p><strong>Time:</strong> ' . $appointmentTime . '</p>
                    <p><strong>Duration:</strong> ' . $service->duration . ' minutes</p>
                </div>

                <p>Please arrive 10 minutes before your scheduled appointment time.</p>
                <p>If you need to reschedule or cancel your appointment, please do so at least 24 hours in advance.</p>

                <a href="' . url('/my-appointments') . '" class="button">View My Appointments</a>
            </div>
            <div class="footer">
                <p>&copy; ' . date('Y') . ' ' . config('app.name') . '. All rights reserved.</p>
                <p>This email was sent as a reminder for your upcoming appointment.</p>
            </div>
        </body>
        </html>';
    }
}
