<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách users có role là Customer
        $customers = User::whereHas('role', function($query) {
            $query->where('name', 'Customer');
        })->get();
        
        if ($customers->isEmpty()) {
            $this->command->info('Không có khách hàng nào trong cơ sở dữ liệu.');
            return;
        }
        
        // Lấy danh sách dịch vụ
        $services = Service::all();
        
        if ($services->isEmpty()) {
            $this->command->info('Không có dịch vụ nào trong cơ sở dữ liệu.');
            return;
        }
        
        // Mảng các trạng thái cuộc hẹn
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        
        // Mẫu các thời gian cuộc hẹn
        $appointmentTimes = ['08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'];
        
        // Tạo một số cuộc hẹn mẫu
        $appointmentsData = [];
        
        for ($i = 0; $i < 10; $i++) {
            $customer = $customers->random();
            $service = $services->random();
            $date = Carbon::now()->addDays(rand(1, 30))->format('Y-m-d');
            $status = $statuses[array_rand($statuses)];
            $timeId = $appointmentTimes[array_rand($appointmentTimes)];
            
            $appointment = [
                'id' => Str::uuid(),
                'customer_id' => $customer->id,
                'service_id' => $service->id,
                'date_appointments' => $date,
                'time_appointments_id' => $timeId,
                'date_register' => now(),
                'status' => $status,
                'employee_id' => null,
                'notes' => 'Ghi chú cuộc hẹn ' . ($i + 1),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            try {
                Appointment::create($appointment);
                $this->command->info('Tạo cuộc hẹn thành công: ' . $customer->first_name . ' ' . $customer->last_name . ' - ' . $service->name . ' - ' . $date);
            } catch (\Exception $e) {
                $this->command->error('Lỗi tạo cuộc hẹn: ' . $e->getMessage());
            }
        }
    }
}
