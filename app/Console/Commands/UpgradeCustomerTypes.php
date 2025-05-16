<?php

namespace App\Console\Commands;

use App\Models\CustomerType;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CustomerTypeUpgraded;

class UpgradeCustomerTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers:upgrade-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upgrade customer types based on their spending';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting customer type upgrade process...');
        
        // Lấy tất cả loại khách hàng, sắp xếp theo mức chi tiêu tối thiểu giảm dần
        $customerTypes = CustomerType::where('is_active', true)
            ->orderBy('min_spending', 'desc')
            ->get();
            
        if ($customerTypes->isEmpty()) {
            $this->error('No active customer types found!');
            return 1;
        }
        
        // Lấy tất cả khách hàng
        $customers = User::whereHas('role', function($query) {
                $query->where('name', 'Customer');
            })
            ->where('status', 'active')
            ->get();
            
        $this->info("Found {$customers->count()} active customers to process.");
        
        $upgradedCount = 0;
        $downgradedCount = 0;
        
        foreach ($customers as $customer) {
            // Tính tổng chi tiêu của khách hàng
            $totalSpending = Invoice::where('user_id', $customer->id)
                ->where('status', 'paid')
                ->sum('total_amount');
                
            // Loại khách hàng hiện tại
            $currentType = CustomerType::find($customer->type_id);
            
            if (!$currentType) {
                $this->warn("Customer {$customer->id} has invalid type_id. Skipping...");
                continue;
            }
            
            // Tìm loại khách hàng phù hợp với mức chi tiêu
            $newType = null;
            foreach ($customerTypes as $type) {
                if ($totalSpending >= $type->min_spending) {
                    $newType = $type;
                    break;
                }
            }
            
            // Nếu không tìm thấy loại phù hợp, sử dụng loại có mức chi tiêu thấp nhất
            if (!$newType) {
                $newType = $customerTypes->last();
            }
            
            // Nếu loại khách hàng thay đổi
            if ($currentType->id !== $newType->id) {
                $oldTypeName = $currentType->type_name;
                
                // Cập nhật loại khách hàng
                $customer->type_id = $newType->id;
                $customer->save();
                
                // Ghi log
                $logMessage = "Customer {$customer->first_name} {$customer->last_name} (ID: {$customer->id}) ";
                
                if ($newType->priority_level > $currentType->priority_level) {
                    $logMessage .= "upgraded from {$oldTypeName} to {$newType->type_name}";
                    $upgradedCount++;
                    
                    // Gửi thông báo cho khách hàng
                    try {
                        Notification::send($customer, new CustomerTypeUpgraded($newType));
                    } catch (\Exception $e) {
                        Log::error("Failed to send notification to customer {$customer->id}: " . $e->getMessage());
                    }
                } else {
                    $logMessage .= "downgraded from {$oldTypeName} to {$newType->type_name}";
                    $downgradedCount++;
                }
                
                Log::info($logMessage);
                $this->line($logMessage);
            }
        }
        
        $this->info("Customer type upgrade process completed.");
        $this->info("Upgraded customers: {$upgradedCount}");
        $this->info("Downgraded customers: {$downgradedCount}");
        
        return 0;
    }
}
