<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Get service details.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $service = Service::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'service' => [
                    'id' => $service->id,
                    'name' => $service->name,
                    'description' => $service->description,
                    'price' => $service->price,
                    'duration' => $service->duration,
                    'is_health_checkup' => $service->is_health_checkup,
                    'required_tests' => $service->required_tests,
                    'preparation_instructions' => $service->preparation_instructions,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dịch vụ không tồn tại hoặc đã bị xóa.'
            ], 404);
        }
    }
}
