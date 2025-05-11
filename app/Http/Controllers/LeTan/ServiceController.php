<?php

namespace App\Http\Controllers\LeTan;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Category;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ServiceController extends Controller
{
    /**
     * Hiển thị danh sách dịch vụ
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Khởi tạo query builder
        $query = Service::with('category');

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

        // Lấy danh sách dịch vụ và phân trang
        $services = $query->paginate(12);

        // Lấy danh sách danh mục
        $categories = Category::withCount('services')->get();

        // Lấy danh sách khuyến mãi đang hoạt động
        $activePromotions = Promotion::where('is_active', true)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->get();

        return view('le-tan.services.index', compact('services', 'categories', 'activePromotions'));
    }

    /**
     * Hiển thị chi tiết dịch vụ
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::with(['category', 'promotions' => function($query) {
            $query->where('is_active', true)
                ->where('start_date', '<=', Carbon::now())
                ->where('end_date', '>=', Carbon::now());
        }])->findOrFail($id);

        // Lấy các dịch vụ liên quan (cùng danh mục)
        $relatedServices = Service::where('category_id', $service->category_id)
            ->where('id', '!=', $service->id)
            ->limit(4)
            ->get();

        return view('le-tan.services.show', compact('service', 'relatedServices'));
    }
}
