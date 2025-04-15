use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Appointment;
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