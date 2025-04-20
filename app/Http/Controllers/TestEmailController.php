<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use App\Services\EmailNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TestEmailController extends Controller
{
    /**
     * Test sending a registration confirmation email
     */
    public function testRegistrationEmail()
    {
        // Get the authenticated user
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        
        try {
            $emailService = new EmailNotificationService();
            $notification = $emailService->sendRegistrationConfirmation($user);
            
            if ($notification && $notification->status === 'sent') {
                return response()->json([
                    'success' => true,
                    'message' => 'Registration confirmation email sent successfully',
                    'notification' => $notification
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send registration confirmation email',
                    'notification' => $notification
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error sending test registration email', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error sending registration confirmation email: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Test sending a booking confirmation email
     */
    public function testBookingEmail(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        
        // Get the latest appointment for the user
        $appointment = Appointment::with(['service', 'customer', 'timeAppointment'])
            ->where('customer_id', $user->id)
            ->latest('created_at')
            ->first();
        
        if (!$appointment) {
            return response()->json(['error' => 'No appointments found for this user'], 404);
        }
        
        try {
            $emailService = new EmailNotificationService();
            $notification = $emailService->sendBookingConfirmation($appointment);
            
            if ($notification && $notification->status === 'sent') {
                return response()->json([
                    'success' => true,
                    'message' => 'Booking confirmation email sent successfully',
                    'notification' => $notification
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send booking confirmation email',
                    'notification' => $notification
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error sending test booking email', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'appointment_id' => $appointment->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error sending booking confirmation email: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Test sending an appointment reminder email
     */
    public function testReminderEmail(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        
        // Get the latest appointment for the user
        $appointment = Appointment::with(['service', 'customer', 'timeAppointment'])
            ->where('customer_id', $user->id)
            ->latest('created_at')
            ->first();
        
        if (!$appointment) {
            return response()->json(['error' => 'No appointments found for this user'], 404);
        }
        
        try {
            $emailService = new EmailNotificationService();
            $notification = $emailService->sendAppointmentReminder($appointment);
            
            if ($notification && $notification->status === 'sent') {
                return response()->json([
                    'success' => true,
                    'message' => 'Appointment reminder email sent successfully',
                    'notification' => $notification
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send appointment reminder email',
                    'notification' => $notification
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error sending test reminder email', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'appointment_id' => $appointment->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error sending appointment reminder email: ' . $e->getMessage()
            ]);
        }
    }
}
