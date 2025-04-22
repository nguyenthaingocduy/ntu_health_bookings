<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserPermission extends Model
{
    use HasFactory, HasUuids;
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'id',
        'user_id',
        'permission_id',
        'can_view',
        'can_create',
        'can_edit',
        'can_delete',
        'granted_by',
    ];
    
    protected $casts = [
        'can_view' => 'boolean',
        'can_create' => 'boolean',
        'can_edit' => 'boolean',
        'can_delete' => 'boolean',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
    
    public function grantor()
    {
        return $this->belongsTo(User::class, 'granted_by');
    }
}
