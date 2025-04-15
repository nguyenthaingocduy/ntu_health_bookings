<?php

namespace App\Traits;

trait HasRole
{
    public function hasRole($roleName)
    {
        if (!$this->role) {
            return false;
        }
        return strtolower($this->role->name) === strtolower($roleName);
    }
} 