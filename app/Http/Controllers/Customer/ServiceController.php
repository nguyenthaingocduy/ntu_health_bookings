<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Service; // <-- Thêm dòng này để import Model Service
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Hiển thị danh sách các dịch vụ.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Khởi tạo query builder
        $query = Service::with('category')->where('status', 'active');

        // Áp dụng bộ lọc danh mục nếu có
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Áp dụng tìm kiếm nếu có
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Áp dụng sắp xếp nếu có
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name':
                default:
                    $query->orderBy('name', 'asc');
                    break;
            }
        } else {
            // Mặc định sắp xếp theo tên
            $query->orderBy('name', 'asc');
        }

        // Kiểm tra xem có yêu cầu hiển thị tất cả không
        if ($request->has('all') && $request->all == 'true') {
            // Lấy tất cả dịch vụ không phân trang
            $services = $query->get();
            $showAll = true;
        } else {
            // Phân trang với 12 dịch vụ mỗi trang
            $services = $query->paginate(12);
            $showAll = false;
        }

        // Lấy danh sách danh mục
        $categories = \App\Models\Category::withCount('services')->get();

        // Trả về view để hiển thị danh sách dịch vụ
        return view('services.index', compact('services', 'categories', 'showAll'));
    }
    public function show($id)
    {
        // Lấy mã khuyến mãi từ query string nếu có
        $promotionCode = request()->query('promotion_code');

        // Sử dụng with('category') để tải kèm dữ liệu category
        $service = Service::with('category')->findOrFail($id);

        // Tính giá sau khuyến mãi nếu có mã khuyến mãi
        if ($promotionCode) {
            // Tính giá sau khuyến mãi
            $discountedPrice = $service->calculatePriceWithPromotion($promotionCode);

            // Log để debug
            \Illuminate\Support\Facades\Log::info('Tính giá với mã khuyến mãi trong ServiceController', [
                'service_id' => $service->id,
                'service_name' => $service->name,
                'original_price' => $service->price,
                'promotion_code' => $promotionCode,
                'discounted_price' => $discountedPrice
            ]);
        }

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

        // Sử dụng view mới tại 'resources/views/services/show.blade.php'
        return view('services.show', compact('service', 'relatedServices', 'timeSlots', 'faqs'));
    }

    // Bạn có thể thêm các phương thức khác vào đây sau nếu cần (ví dụ: show, create, store, ...)
}