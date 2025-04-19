<?php

namespace App\Console\Commands;

use App\Models\CustomerType;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CreateUserAccountsForEmployees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-user-accounts-for-employees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user accounts for existing employees';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $employees = Employee::all();
        $this->info("Found {$employees->count()} employees");

        $created = 0;
        $skipped = 0;

        foreach ($employees as $employee) {
            // Check if a user with this email already exists
            $existingUser = User::where('email', $employee->email)->first();

            if ($existingUser) {
                $this->warn("User account already exists for employee {$employee->name} ({$employee->email})");
                $skipped++;
                continue;
            }

            // Create a new user account
            $defaultPassword = 'password123';

            // Get a default customer type ID
            $defaultTypeId = null;
            try {
                $defaultType = CustomerType::first();
                if ($defaultType) {
                    $defaultTypeId = $defaultType->id;
                }
            } catch (\Exception $e) {
                $this->error("Error getting default customer type: {$e->getMessage()}");
            }

            $user = User::create([
                'id' => Str::uuid(),
                'first_name' => $employee->first_name,
                'last_name' => $employee->last_name,
                'email' => $employee->email,
                'phone' => $employee->phone,
                'address' => $employee->address,
                'gender' => $employee->gender,
                'birthday' => $employee->birthday,
                'password' => Hash::make($defaultPassword),
                'role_id' => $employee->role_id,
                'type_id' => $defaultTypeId,
                'status' => $employee->status,
            ]);

            Log::info('Created user account for existing employee', [
                'employee_id' => $employee->id,
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            $this->info("Created user account for employee {$employee->name} ({$employee->email})");
            $created++;
        }

        $this->info("Created {$created} user accounts, skipped {$skipped} existing accounts");

        return Command::SUCCESS;
    }
}
