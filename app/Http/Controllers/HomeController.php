<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Contact;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        $featuredServices = Service::with('clinic')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        $clinics = Clinic::all();

        // Thêm dữ liệu mẫu tạm thời cho testimonials
        $testimonials = collect([
            (object) [
                'name' => 'Nguyễn Văn A',
                'avatar_url' => 'https://randomuser.me/api/portraits/men/1.jpg',
                'rating' => 5,
                'content' => 'Dịch vụ rất tuyệt vời, tôi rất hài lòng với kết quả.'
            ],
            (object) [
                'name' => 'Trần Thị B',
                'avatar_url' => 'https://randomuser.me/api/portraits/women/2.jpg',
                'rating' => 4,
                'content' => 'Nhân viên phục vụ rất nhiệt tình và chu đáo.'
            ],
            (object) [
                'name' => 'Lê Văn C',
                'avatar_url' => 'https://randomuser.me/api/portraits/men/3.jpg',
                'rating' => 5,
                'content' => 'Tôi đã thử nhiều spa nhưng đây là nơi tôi hài lòng nhất.'
            ],
        ]);

        return view('home.index', compact('featuredServices', 'clinics', 'testimonials'));
    }

    public function index2()
    {
        $services = Service::with('clinic')
            ->where('status', 'active')
            ->paginate(9);

        return view('home.services', compact('services'));
    }

    public function show($id)
    {
        $service = Service::with('clinic')->findOrFail($id);

        return view('home.service_details', compact('service'));
    }

    public function about()
    {
        return view('home.about');
    }

    public function contact()
    {
        return view('home.contact');
    }

    /**
     * Handle contact form submission
     */
    public function submitContact(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            // Save the contact message to database
            Contact::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
            ]);

            if (config('app.debug')) {
                Log::info('New contact form submission', [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                ]);
            }

            // You could also send an email notification here

            return redirect()->back()->with('success', 'Cảm ơn bạn đã liên hệ với chúng tôi. Chúng tôi sẽ phản hồi trong thời gian sớm nhất!');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                Log::error('Error saving contact form', [
                    'error' => $e->getMessage(),
                ]);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi gửi tin nhắn. Vui lòng thử lại sau.');
        }
    }
}
