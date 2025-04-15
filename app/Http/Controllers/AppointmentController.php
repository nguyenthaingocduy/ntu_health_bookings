<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = auth()->user()->appointments()
            ->with('service')
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $services = Service::where('is_active', true)->get();
        return view('appointments.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after:today',
            'notes' => 'nullable|string|max:500'
        ]);

        $appointment = auth()->user()->appointments()->create($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Đặt lịch khám thành công!');
    }
}