@props(['title', 'icon', 'permissionGroup', 'routes' => []])

@php
    $user = auth()->user();
    $hasAnyPermission = false;

    if ($user) {
        // Kiểm tra xem người dùng có bất kỳ quyền nào trong nhóm không
        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($actions as $action) {
            if ($user->hasAnyPermission($permissionGroup, $action)) {
                $hasAnyPermission = true;
                break;
            }
        }
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
