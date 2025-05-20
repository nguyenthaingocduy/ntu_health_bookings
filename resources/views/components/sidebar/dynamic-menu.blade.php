@props(['title', 'icon', 'permissionGroup', 'routes' => []])

@php
    // Lấy danh sách quyền của người dùng
    $user = auth()->user();
    $userPermissions = [];
    
    // Lấy quyền từ vai trò
    if ($user->role) {
        $rolePermissions = $user->role->permissions()->pluck('name')->toArray();
        $userPermissions = array_merge($userPermissions, $rolePermissions);
    }
    
    // Lấy quyền trực tiếp của người dùng (nếu có)
    if (method_exists($user, 'userPermissions')) {
        $directPermissions = $user->userPermissions()->with('permission')->get();
        foreach ($directPermissions as $perm) {
            if ($perm->can_view || $perm->can_create || $perm->can_edit || $perm->can_delete) {
                $userPermissions[] = $perm->permission->name;
            }
        }
    }
    
    // Kiểm tra xem người dùng có bất kỳ quyền nào trong nhóm không
    $hasAnyPermission = false;
    $permissionPrefixes = [
        $permissionGroup . '.view',
        $permissionGroup . '.create',
        $permissionGroup . '.edit',
        $permissionGroup . '.delete'
    ];
    
    foreach ($permissionPrefixes as $prefix) {
        if (in_array($prefix, $userPermissions)) {
            $hasAnyPermission = true;
            break;
        }
    }
    
    // Admin luôn có tất cả các quyền
    if ($user->role && strtolower($user->role->name) === 'admin') {
        $hasAnyPermission = true;
    }
    
    // Kiểm tra xem có route nào đang active không
    $hasActiveRoute = false;
    foreach ($routes as $route) {
        if (request()->routeIs($route)) {
            $hasActiveRoute = true;
            break;
        }
    }
    
    // Mặc định mở dropdown nếu có route đang active
    $isOpen = $hasActiveRoute;
    $dropdownId = 'dropdown-' . str_replace(' ', '-', strtolower($title));
@endphp

@if($hasAnyPermission)
<div class="sidebar-dropdown-container">
    <div class="sidebar-menu-item" onclick="toggleDropdown('{{ $dropdownId }}')">
        {!! $icon !!}
        <span class="flex-1">{{ $title }}</span>
        <svg id="{{ $dropdownId }}-icon" class="sidebar-dropdown-icon w-4 h-4 {{ $isOpen ? 'active' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </div>
    <div id="{{ $dropdownId }}" class="sidebar-dropdown" style="{{ $isOpen ? 'height: auto;' : 'height: 0;' }}">
        {{ $slot }}
    </div>
</div>
@endif
