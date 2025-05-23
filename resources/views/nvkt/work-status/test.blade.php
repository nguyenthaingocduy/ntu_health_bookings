<!DOCTYPE html>
<html>
<head>
    <title>Test Work Status</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Test Work Status Page</h1>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Pending Appointments</h2>
            
            @if(isset($pendingAppointments) && $pendingAppointments->count() > 0)
                <div class="space-y-4">
                    @foreach($pendingAppointments as $appointment)
                        <div class="border p-4 rounded">
                            <p><strong>Customer:</strong> {{ $appointment->customer->first_name ?? 'N/A' }} {{ $appointment->customer->last_name ?? '' }}</p>
                            <p><strong>Service:</strong> {{ $appointment->service->name ?? 'N/A' }}</p>
                            <p><strong>Date:</strong> {{ $appointment->date_appointments ? $appointment->date_appointments->format('d/m/Y H:i') : 'N/A' }}</p>
                            <p><strong>Status:</strong> {{ $appointment->status }}</p>
                            <p><strong>Time Slot:</strong> 
                                @if($appointment->timeAppointment)
                                    {{ $appointment->timeAppointment->started_time }} - {{ $appointment->timeAppointment->ended_time }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No pending appointments found.</p>
            @endif
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <h2 class="text-lg font-semibold mb-4">In Progress Appointments</h2>
            
            @if(isset($inProgressAppointments) && $inProgressAppointments->count() > 0)
                <div class="space-y-4">
                    @foreach($inProgressAppointments as $appointment)
                        <div class="border p-4 rounded">
                            <p><strong>Customer:</strong> {{ $appointment->customer->first_name ?? 'N/A' }} {{ $appointment->customer->last_name ?? '' }}</p>
                            <p><strong>Service:</strong> {{ $appointment->service->name ?? 'N/A' }}</p>
                            <p><strong>Date:</strong> {{ $appointment->date_appointments ? $appointment->date_appointments->format('d/m/Y H:i') : 'N/A' }}</p>
                            <p><strong>Status:</strong> {{ $appointment->status }}</p>
                            <p><strong>Check-in:</strong> {{ $appointment->check_in_time ? $appointment->check_in_time->format('H:i') : 'N/A' }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No in-progress appointments found.</p>
            @endif
        </div>
        
        <div class="mt-6">
            <p><strong>Current User ID:</strong> {{ Auth::id() ?? 'Not logged in' }}</p>
            <p><strong>Current User:</strong> {{ Auth::user()->first_name ?? 'N/A' }} {{ Auth::user()->last_name ?? '' }}</p>
        </div>
    </div>
</body>
</html>
