<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Time;
use App\Http\Controllers\Api\TimeSlotController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route API sử dụng controller
Route::get('/timeslots/available', [TimeSlotController::class, 'checkAvailableSlots']);

// Route API sử dụng controller - đường dẫn cũ để tương thích
Route::get('/check-available-slots', [TimeSlotController::class, 'checkAvailableSlots']);

// Route to get all time slots
Route::get('/time-slots', [TimeSlotController::class, 'getAllTimeSlots']);

// Customer search for staff appointment booking
Route::get('/customers/search', [\App\Http\Controllers\Api\CustomerController::class, 'search']);

// Promotions API
Route::get('/active-promotions', function() {
    // Get all active promotions with associated services
    $promotions = \App\Models\Promotion::where('is_active', true)
        ->where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->with('services')
        ->orderBy('created_at', 'desc') // Get newest promotions first
        ->get();

    // Log for debugging
    \Illuminate\Support\Facades\Log::info('Active promotions found: ' . $promotions->count());

    $formattedPromotions = $promotions->map(function($promotion) {
        // Get all services for this promotion
        $services = $promotion->services;

        // Format discount value for display
        $formattedDiscountValue = '';
        if ($promotion->discount_type === 'percentage') {
            $formattedDiscountValue = $promotion->discount_value . '%';
        } else if ($promotion->discount_type === 'fixed') {
            $formattedDiscountValue = number_format($promotion->discount_value, 0, ',', '.') . 'đ';
        }

        // Format the main promotion data
        $result = [
            'id' => $promotion->id,
            'title' => $promotion->title, // Ensure title is included
            'name' => $promotion->title, // Keep name for backward compatibility
            'description' => $promotion->description,
            'discount_type' => $promotion->discount_type,
            'discount_value' => $promotion->discount_value,
            'formatted_discount_value' => $formattedDiscountValue,
            'start_date' => $promotion->start_date,
            'end_date' => $promotion->end_date,
            'code' => $promotion->code,
            'usage_limit' => $promotion->usage_limit,
            'usage_count' => $promotion->usage_count,
            'minimum_purchase' => $promotion->minimum_purchase,
        ];

        // If promotion has services, include the first service for display
        if ($services->isNotEmpty()) {
            $service = $services->first();

            $result['service'] = [
                'id' => $service->id,
                'name' => $service->name,
                'price' => $service->price,
                'image_url' => $service->image_url,
                'description' => $service->description,
                'category_id' => $service->category_id,
                'duration' => $service->duration
            ];

            // Calculate discounted price
            if ($promotion->discount_type === 'percentage') {
                $discountedPrice = $service->price - ($service->price * $promotion->discount_value / 100);
            } else {
                $discountedPrice = $service->price - $promotion->discount_value;
            }

            $result['service']['discounted_price'] = max(0, $discountedPrice);
        }

        // Log for debugging
        \Illuminate\Support\Facades\Log::info('Formatted promotion: ' . json_encode($result));

        return $result;
    });

    return response()->json([
        'promotions' => $formattedPromotions
    ]);
});

Route::post('/validate-promotion', [\App\Http\Controllers\Api\PromotionController::class, 'validateCode']);

// Service promotion popup routes
Route::get('/random-promotion', function() {
    // Get a random active promotion with associated services
    $promotion = \App\Models\Promotion::where('is_active', true)
        ->where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->with('services')
        ->inRandomOrder()
        ->first();

    // If no promotion is found, get a random active service
    if (!$promotion || $promotion->services->isEmpty()) {
        $service = Service::where('status', 'active')
            ->inRandomOrder()
            ->first();

        return response()->json([
            'type' => 'service',
            'data' => $service
        ]);
    }

    // Get a random service from the promotion
    $service = $promotion->services->random();

    return response()->json([
        'type' => 'promotion',
        'data' => [
            'promotion' => $promotion,
            'service' => $service
        ]
    ]);
});

// Handle promotion signup
Route::post('/promotion-signup', function(Request $request) {
    // Validate the request
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'clinic_id' => 'required|exists:clinics,id',
        'service_id' => 'required|exists:services,id',
    ]);

    // Create a new appointment or lead record
    // This is simplified - you would typically create a proper appointment or lead record
    // and send notifications to staff

    // Log the promotion signup (chỉ trong môi trường debug)
    if (config('app.debug')) {
        \Illuminate\Support\Facades\Log::info('Promotion signup', $validated);
    }

    return response()->json(['success' => true]);
});