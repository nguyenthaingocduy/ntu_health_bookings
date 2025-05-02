@props(['title', 'icon', 'permission' => null])

@php
    $canAccess = true;

    if ($permission) {
        $canAccess = auth()->user()->can($permission);
    }

    // Kiểm tra xem có mục con nào đang active không
    $hasActiveChild = false;
    foreach ($attributes->get('routes', []) as $route) {
        if (request()->routeIs($route)) {
            $hasActiveChild = true;
            break;
        }
    }

    // Mặc định mở dropdown nếu có mục con đang active
    $isOpen = $hasActiveChild;
@endphp

@if($canAccess)
<div class="relative">
    <button onclick="toggleDropdown('{{ str_replace(' ', '-', strtolower($title)) }}')" class="w-full flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200">
        <span class="w-6 h-6 flex items-center justify-center mr-3">
            {!! $icon !!}
        </span>
        <span class="flex-1 text-left">{{ $title }}</span>
        <svg id="arrow-{{ str_replace(' ', '-', strtolower($title)) }}" class="w-4 h-4 transition-transform duration-200 {{ $isOpen ? 'transform rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div id="{{ str_replace(' ', '-', strtolower($title)) }}" class="pl-8 {{ $isOpen ? 'block' : 'hidden' }}">
        {{ $slot }}
    </div>
</div>
@endif
