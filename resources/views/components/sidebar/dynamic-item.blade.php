@props(['route', 'text', 'icon', 'permission'])

@php
    $isActive = request()->routeIs($route);
    $activeClass = $isActive ? 'active' : '';

    // Kiểm tra quyền
    $user = auth()->user();
    $hasPermission = false;

    if ($user) {
        // Parse permission name to get resource and action
        if (str_contains($permission, '.')) {
            $parts = explode('.', $permission);
            $resource = $parts[0];
            $action = $parts[1] ?? 'view';
        } else {
            $resource = $permission;
            $action = 'view';
        }

        // Use the improved hasAnyPermission method
        $hasPermission = $user->hasAnyPermission($resource, $action);
    }
@endphp

@if($hasPermission)
<a href="{{ route($route) }}" class="sidebar-submenu-item {{ $activeClass }}">
    {!! $icon !!}
    <span>{{ $text }}</span>
</a>
@endif
