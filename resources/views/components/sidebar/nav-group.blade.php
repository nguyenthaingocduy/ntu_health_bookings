@props(['title', 'icon', 'active' => false, 'permission' => null])

@php
    $isActive = $active;
    $activeClass = $isActive ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white';
    $canAccess = true;

    if ($permission) {
        $canAccess = auth()->user()->can($permission);
    }

    // Mặc định mở tất cả các dropdown
    $isOpen = true;
@endphp

@if($canAccess)
<div class="nav-group mb-2">
    <button onclick="toggleDropdown('{{ str_replace(' ', '-', strtolower($title)) }}')" class="w-full flex items-center justify-between px-4 py-3 transition-colors duration-200 {{ $activeClass }}">
        <div class="flex items-center">
            <span class="w-6 h-6 flex items-center justify-center mr-3">
                {!! $icon !!}
            </span>
            <span>{{ $title }}</span>
        </div>
        <svg id="arrow-{{ str_replace(' ', '-', strtolower($title)) }}" class="w-4 h-4 transition-transform duration-200 {{ $isOpen ? 'transform rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div id="{{ str_replace(' ', '-', strtolower($title)) }}" class="nav-group-items pl-4 bg-gray-900 bg-opacity-50 block">
        {{ $slot }}
    </div>
</div>
@endif
