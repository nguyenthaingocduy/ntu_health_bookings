@extends('layouts.app')

@section('title', 'Email Testing')

@section('content')
<section class="bg-gray-900 text-white py-16">
    <div class="container mx-auto px-6">
        <h1 class="text-4xl font-bold mb-4">Email Testing Dashboard</h1>
        <p class="text-xl text-gray-300">Test email functionality for the Beauty Spa Booking system.</p>
    </div>
</section>

<section class="py-16">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
            @endif
            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
            @endif

            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold mb-6">Email Configuration</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="p-4 bg-gray-100 rounded-lg">
                        <p><strong>MAIL_MAILER:</strong> {{ config('mail.default') }}</p>
                        <p><strong>MAIL_HOST:</strong> {{ config('mail.mailers.smtp.host') }}</p>
                        <p><strong>MAIL_PORT:</strong> {{ config('mail.mailers.smtp.port') }}</p>
                        <p><strong>MAIL_USERNAME:</strong> {{ config('mail.mailers.smtp.username') }}</p>
                    </div>
                    <div class="p-4 bg-gray-100 rounded-lg">
                        <p><strong>MAIL_ENCRYPTION:</strong> {{ config('mail.mailers.smtp.encryption') }}</p>
                        <p><strong>MAIL_FROM_ADDRESS:</strong> {{ config('mail.from.address') }}</p>
                        <p><strong>MAIL_FROM_NAME:</strong> {{ config('mail.from.name') }}</p>
                        <p><strong>MAIL_PASSWORD:</strong> {{ config('mail.mailers.smtp.password') ? '[SET]' : '[NOT SET]' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold mb-6">Send Test Email</h2>
                
                <form action="{{ route('email.test.send') }}" method="POST" class="mb-6">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', Auth::user() ? Auth::user()->email : '') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                            required>
                    </div>
                    
                    <button type="submit" class="bg-pink-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-pink-600 transition">
                        Send Test Email
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold mb-6">Test Email Templates</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 border rounded-lg">
                        <h3 class="font-semibold mb-2">Registration Email</h3>
                        <p class="text-sm text-gray-600 mb-4">Test the registration confirmation email template.</p>
                        <form action="{{ route('email.test.registration') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-pink-600 transition text-sm">
                                Send Registration Email
                            </button>
                        </form>
                    </div>
                    
                    <div class="p-4 border rounded-lg">
                        <h3 class="font-semibold mb-2">Appointment Confirmation</h3>
                        <p class="text-sm text-gray-600 mb-4">Test the appointment confirmation email template.</p>
                        <form action="{{ route('email.test.appointment') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-pink-600 transition text-sm">
                                Send Appointment Email
                            </button>
                        </form>
                    </div>
                    
                    <div class="p-4 border rounded-lg">
                        <h3 class="font-semibold mb-2">Appointment Reminder</h3>
                        <p class="text-sm text-gray-600 mb-4">Test the appointment reminder email template.</p>
                        <form action="{{ route('email.test.reminder') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-pink-600 transition text-sm">
                                Send Reminder Email
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold mb-6">Email Logs</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">To</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Subject</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Template</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Attempts</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Sent At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\EmailLog::orderBy('created_at', 'desc')->take(10)->get() as $log)
                            <tr>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $log->id }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $log->to }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $log->subject }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $log->template }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">
                                    @if($log->status == 'sent')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Sent</span>
                                    @elseif($log->status == 'pending')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Failed</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $log->attempts }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $log->sent_at ? $log->sent_at->format('Y-m-d H:i:s') : '-' }}</td>
                            </tr>
                            @endforeach
                            
                            @if(\App\Models\EmailLog::count() == 0)
                            <tr>
                                <td colspan="7" class="py-4 px-4 text-center text-gray-500">No email logs found.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
