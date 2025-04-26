<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Service;

class UpdateServicePromotions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'services:update-promotions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật khuyến mãi cho các dịch vụ để kiểm tra';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $services = Service::where('status', 'active')->get();
        
        $count = 0;
        foreach ($services as $index => $service) {
            // Chỉ cập nhật một số dịch vụ để kiểm tra
            if ($index % 3 == 0) {
                $promotion = rand(5, 30); // Khuyến mãi từ 5% đến 30%
                $service->promotion = $promotion;
                $service->save();
                
                $this->info("Đã cập nhật dịch vụ '{$service->name}' với khuyến mãi {$promotion}%");
                $count++;
            }
        }
        
        $this->info("Đã cập nhật {$count} dịch vụ với khuyến mãi.");
    }
}
