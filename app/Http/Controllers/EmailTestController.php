<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EmailService;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmailTestController extends Controller
{
    /**
     * Show the email testing dashboard
     */
    public function index()
    {
        return view('email-test.index');
    }

    /**
     * Send a test email
     */
    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $emailService = new EmailService();
        $result = $emailService->sendTestEmail($request->email);

        if ($result) {
            return back()->with('success', 'Test email sent successfully to ' . $request->email);
        } else {
            return back()->with('error', 'Failed to send test email. Check the logs for more information.');
        }
    }

    /**
     * Send a registration confirmation email
     */
    public function sendRegistrationEmail(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return back()->with('error', 'You must be logged in to test this feature.');
        }

        $emailService = new EmailService();
        $result = $emailService->sendRegistrationConfirmation($user);

        if ($result) {
            return back()->with('success', 'Registration confirmation email sent successfully to ' . $user->email);
        } else {
            return back()->with('error', 'Failed to send registration confirmation email. Check the logs for more information.');
        }
    }

    /**
     * Send an appointment confirmation email
     */
    public function sendAppointmentEmail(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return back()->with('error', 'You must be logged in to test this feature.');
        }

        // Get the user's latest appointment
        $appointment = Appointment::where('customer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$appointment) {
            return back()->with('error', 'You don\'t have any appointments to test with.');
        }

        $emailService = new EmailService();
        $result = $emailService->sendAppointmentConfirmation($appointment);

        if ($result) {
            return back()->with('success', 'Appointment confirmation email sent successfully to ' . $user->email);
        } else {
            return back()->with('error', 'Failed to send appointment confirmation email. Check the logs for more information.');
        }
    }

    /**
     * Send an appointment reminder email
     */
    public function sendReminderEmail(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return back()->with('error', 'You must be logged in to test this feature.');
        }

        // Get the user's latest appointment
        $appointment = Appointment::where('customer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$appointment) {
            return back()->with('error', 'You don\'t have any appointments to test with.');
        }

        $emailService = new EmailService();
        $result = $emailService->sendAppointmentReminder($appointment);

        if ($result) {
            return back()->with('success', 'Appointment reminder email sent successfully to ' . $user->email);
        } else {
            return back()->with('error', 'Failed to send appointment reminder email. Check the logs for more information.');
        }
    }
}
