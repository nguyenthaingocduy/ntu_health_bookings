@props(['permission', 'action' => 'view'])

@php
    $user = auth()->user();
    $hasPermission = false;

    if ($user) {
        // Parse permission name to get resource and action
        if (str_contains($permission, '.')) {
            $parts = explode('.', $permission);
            $resource = $parts[0];
            $actionToCheck = $parts[1] ?? $action;
        } else {
            $resource = $permission;
            $actionToCheck = $action;
        }

        // Use the improved hasAnyPermission method
        $hasPermission = $user->hasAnyPermission($resource, $actionToCheck);
    }
@endphp

@if($hasPermission)
    {{ $slot }}
@endif
