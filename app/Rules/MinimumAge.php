<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Carbon\Carbon;

class MinimumAge implements ValidationRule
{
    protected $minAge;

    /**
     * Create a new rule instance.
     *
     * @param int $minAge
     * @return void
     */
    public function __construct(int $minAge = 18)
    {
        $this->minAge = $minAge;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $date = Carbon::parse($value);
            $minAgeDate = Carbon::now()->subYears($this->minAge);
            
            if ($date->isAfter($minAgeDate)) {
                $fail("Bạn phải đủ {$this->minAge} tuổi trở lên để đăng ký tài khoản.");
            }
        } catch (\Exception $e) {
            $fail('Ngày sinh không hợp lệ.');
        }
    }
}
