<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CustomerType;
use Illuminate\Support\Str;

class CustomerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'id' => Str::uuid(),
                'type_name' => 'Regular',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'type_name' => 'VIP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'type_name' => 'Premium',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        CustomerType::insert($types);
    }
}
