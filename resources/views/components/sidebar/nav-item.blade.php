@props(['route', 'icon', 'text', 'permission' => null])

@php
    $isActive = request()->routeIs($route) || (isset($attributes['active']) && $attributes['active']);
    $activeClass = $isActive ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white';
    $canAccess = true;
    
    if ($permission) {
        $canAccess = auth()->user()->can($permission);
    }
@endphp

@if($canAccess)
<a href="{{ route($route) }}" class="flex items-center px-4 py-3 {{ $activeClass }} transition-colors duration-200">
    <span class="w-6 h-6 flex items-center justify-center mr-3">
        {!! $icon !!}
    </span>
    <span>{{ $text }}</span>
</a>
@endif
