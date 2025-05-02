<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Service;
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
Route::get('/active-promotions', [\App\Http\Controllers\Api\PromotionController::class, 'getActivePromotions']);

Route::post('/validate-promotion', [\App\Http\Controllers\Api\PromotionController::class, 'validateCode']);

// API cho nhân viên kỹ thuật
Route::middleware('auth:sanctum')->prefix('nvkt')->group(function () {
    Route::get('/appointments', [\App\Http\Controllers\Api\NVKTApiController::class, 'getAppointments']);
    Route::get('/appointments/{id}', [\App\Http\Controllers\Api\NVKTApiController::class, 'getAppointmentDetail']);
    Route::put('/appointments/{id}/status', [\App\Http\Controllers\Api\NVKTApiController::class, 'updateAppointmentStatus']);
    Route::post('/professional-notes', [\App\Http\Controllers\Api\NVKTApiController::class, 'addProfessionalNote']);
});

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