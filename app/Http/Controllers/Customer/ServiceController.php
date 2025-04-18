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

        // Lấy dữ liệu thời gian từ model Time hoặc tạo dữ liệu giả
        $times = \App\Models\Time::all();

        // Nếu không có dữ liệu thời gian, tạo dữ liệu giả
        if ($times->isEmpty()) {
            $timeSlots = [
                ['time' => '08:00', 'capacity' => 20, 'booked' => 10],
                ['time' => '09:00', 'capacity' => 20, 'booked' => 5],
                ['time' => '10:00', 'capacity' => 20, 'booked' => 8],
                ['time' => '11:00', 'capacity' => 20, 'booked' => 12],
                ['time' => '13:00', 'capacity' => 20, 'booked' => 3],
                ['time' => '14:00', 'capacity' => 20, 'booked' => 7],
                ['time' => '15:00', 'capacity' => 20, 'booked' => 15],
                ['time' => '16:00', 'capacity' => 20, 'booked' => 9],
            ];
        } else {
            // Chuyển đổi dữ liệu từ model Time sang định dạng phù hợp
            $timeSlots = $times->map(function($time) {
                return [
                    'time' => $time->started_time,
                    'capacity' => $time->capacity,
                    'booked' => $time->booked_count,
                    'available' => $time->getAvailableSlotsAttribute(),
                    'id' => $time->id
                ];
            })->toArray();
        }

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
            (object) [
                'question' => 'Dịch vụ này có phù hợp với mọi loại da không?',
                'answer' => 'Dịch vụ của chúng tôi được thiết kế để phù hợp với nhiều loại da khác nhau. Tuy nhiên, chuyên viên sẽ tư vấn cụ thể dựa trên tình trạng da của bạn.'
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