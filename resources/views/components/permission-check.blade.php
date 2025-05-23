@props(['permission', 'action' => 'view'])

@php
    $user = auth()->user();
    $hasPermission = false;

    if ($user) {
        // Sử dụng method hasAnyPermission để kiểm tra cả quyền vai trò và cá nhân
        $permissionName = str_replace('.view', '', str_replace('.create', '', str_replace('.edit', '', str_replace('.delete', '', $permission))));
        $hasPermission = $user->hasAnyPermission($permissionName, $action);
    }
@endphp

@if($hasPermission)
    {{ $slot }}
@endif
