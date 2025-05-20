@props(['route', 'text', 'icon', 'permission'])

@php
    $isActive = request()->routeIs($route);
    $activeClass = $isActive ? 'active' : '';
    
    // Kiểm tra quyền
    $user = auth()->user();
    $hasPermission = false;
    
    // Admin luôn có tất cả các quyền
    if ($user->role && strtolower($user->role->name) === 'admin') {
        $hasPermission = true;
    } 
    // Kiểm tra quyền từ vai trò
    else if ($user->role) {
        $rolePermissions = $user->role->permissions()->pluck('name')->toArray();
        $hasPermission = in_array($permission, $rolePermissions);
    }
    
    // Kiểm tra quyền trực tiếp của người dùng (nếu có)
    if (!$hasPermission && method_exists($user, 'userPermissions')) {
        $directPermissions = $user->userPermissions()->with('permission')->get();
        foreach ($directPermissions as $perm) {
            if ($perm->permission->name === $permission && ($perm->can_view || $perm->can_create || $perm->can_edit || $perm->can_delete)) {
                $hasPermission = true;
                break;
            }
        }
    }
@endphp

@if($hasPermission)
<a href="{{ route($route) }}" class="sidebar-submenu-item {{ $activeClass }}">
    {!! $icon !!}
    <span>{{ $text }}</span>
</a>
@endif
