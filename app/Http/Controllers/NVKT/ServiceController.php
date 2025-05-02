<?php

namespace App\Http\Controllers\NVKT;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Hiển thị danh sách dịch vụ
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::with('category')
            ->orderBy('name')
            ->paginate(10);
            
        return view('nvkt.services.index', compact('services'));
    }

    /**
     * Hiển thị chi tiết dịch vụ
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::with('category')->findOrFail($id);
        
        return view('nvkt.services.show', compact('service'));
    }
}
