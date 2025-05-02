<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ProfessionalNote;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NVKTApiController extends Controller
{
    /**
     * Lấy danh sách lịch hẹn của nhân viên kỹ thuật
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAppointments(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'date' => 'nullable|date',
                'status' => 'nullable|in:pending,confirmed,in_progress,completed,cancelled',
                'limit' => 'nullable|integer|min:1|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = Appointment::with(['customer', 'service', 'timeAppointment'])
                ->where('employee_id', Auth::id());

            if ($request->has('date')) {
                $query->whereDate('date_appointments', $request->date);
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $limit = $request->input('limit', 10);
            $appointments = $query->orderBy('date_appointments')
                ->orderBy('time_appointments_id')
                ->paginate($limit);

            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách lịch hẹn thành công',
                'data' => $appointments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage(),
                'error_type' => get_class($e),
                'error_line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Lấy thông tin chi tiết lịch hẹn
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAppointmentDetail($id)
    {
        try {
            $appointment = Appointment::with([
                'customer', 
                'service', 
                'timeAppointment', 
                'professionalNotes'
            ])->findOrFail($id);

            // Kiểm tra quyền truy cập
            if ($appointment->employee_id != Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền xem phiên làm việc này'
                ], 403);
            }

            // Lấy lịch sử dịch vụ của khách hàng
            $pastAppointments = Appointment::with(['service', 'timeAppointment'])
                ->where('customer_id', $appointment->customer_id)
                ->where('id', '!=', $appointment->id)
                ->where('status', 'completed')
                ->orderBy('date_appointments', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Lấy thông tin chi tiết lịch hẹn thành công',
                'data' => [
                    'appointment' => $appointment,
                    'past_appointments' => $pastAppointments
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage(),
                'error_type' => get_class($e),
                'error_line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Cập nhật trạng thái buổi chăm sóc
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAppointmentStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            $appointment = Appointment::where('employee_id', Auth::id())->findOrFail($id);
            $oldStatus = $appointment->status;
            $appointment->status = $request->status;
            
            if ($request->has('notes')) {
                $appointment->notes = $request->notes;
            }
            
            // Cập nhật thời gian bắt đầu và kết thúc
            if ($request->status == 'in_progress' && $oldStatus != 'in_progress') {
                $appointment->check_in_time = Carbon::now();
            } elseif ($request->status == 'completed' && $oldStatus != 'completed') {
                $appointment->check_out_time = Carbon::now();
            }
            
            $appointment->updated_by = Auth::id();
            $appointment->save();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái buổi chăm sóc thành công',
                'data' => $appointment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage(),
                'error_type' => get_class($e),
                'error_line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Thêm ghi chú chuyên môn
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addProfessionalNote(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'customer_id' => 'required|exists:users,id',
                'appointment_id' => 'nullable|exists:appointments,id',
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            $note = new ProfessionalNote();
            $note->customer_id = $request->customer_id;
            $note->appointment_id = $request->appointment_id;
            $note->title = $request->title;
            $note->content = $request->content;
            $note->created_by = Auth::id();
            $note->save();

            return response()->json([
                'success' => true,
                'message' => 'Thêm ghi chú chuyên môn thành công',
                'data' => $note
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage(),
                'error_type' => get_class($e),
                'error_line' => $e->getLine()
            ], 500);
        }
    }
}
