<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Service; // <-- Thêm dòng này để import Model Service
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Hiển thị danh sách các dịch vụ.
     *
     * @return \Illuminate\View\View
     */
    public function index() // <--- Thêm phương thức này vào
    {
        // Lấy danh sách dịch vụ (chỉ lấy các dịch vụ đang hoạt động và phân trang)
         $services = Service::where('status', 'active')
                           ->paginate(10); // Sử dụng paginate để phân trang (10 dịch vụ/trang)

        // Trả về view để hiển thị danh sách dịch vụ
        // Đảm bảo bạn đã tạo file view tại: resources/views/customer/services/index.blade.php
        return view('customer.services.index', compact('services'));
    }
    public function show($id)
    {
        // Sử dụng with('category') để tải kèm dữ liệu category
        $service = Service::with('category')->findOrFail($id);
        
        // Lấy các dịch vụ liên quan (cùng danh mục hoặc cùng phòng khám)
        $relatedServices = Service::where('id', '!=', $id)
            ->where(function($query) use ($service) {
                if ($service->category_id) {
                    $query->where('category_id', $service->category_id);
                } else {
                    $query->where('clinic_id', $service->clinic_id);
                }
            })
            ->where('status', 'active')
            ->limit(3)
            ->get();
        
        // Tạo dữ liệu giả cho các khung giờ và FAQs
        $timeSlots = ['08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'];
        
        $faqs = collect([
            (object) [
                'question' => 'Dịch vụ này có đau không?',
                'answer' => 'Chúng tôi sử dụng công nghệ tiên tiến và kỹ thuật viên có kinh nghiệm để đảm bảo quá trình diễn ra nhẹ nhàng, thoải mái.'
            ],
            (object) [
                'question' => 'Tôi cần chuẩn bị gì trước khi sử dụng dịch vụ?',
                'answer' => 'Bạn chỉ cần đến đúng giờ hẹn, đội ngũ nhân viên của chúng tôi sẽ hướng dẫn chi tiết cho bạn.'
            ],
            (object) [
                'question' => 'Có cần kiêng khem gì sau khi sử dụng dịch vụ không?',
                'answer' => 'Tùy vào từng dịch vụ cụ thể, chuyên viên sẽ tư vấn chi tiết cho bạn sau khi thực hiện.'
            ],
        ]);
        
        // Thêm các dữ liệu khuyết thiếu
        if (!isset($service->duration)) {
            $service->duration = '60'; // Thời gian mặc định 60 phút
        }
        
        // Đảm bảo bạn có view tại 'resources/views/customer/services/show.blade.php'
        return view('customer.services.show', compact('service', 'relatedServices', 'timeSlots', 'faqs'));
    }

    // Bạn có thể thêm các phương thức khác vào đây sau nếu cần (ví dụ: show, create, store, ...)
}