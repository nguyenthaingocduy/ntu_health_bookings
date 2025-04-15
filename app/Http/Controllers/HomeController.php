<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Service;
use Illuminate\Http\Request;

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
}
