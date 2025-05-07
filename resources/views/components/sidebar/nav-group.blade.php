@props(['title', 'icon', 'active' => false, 'permission' => null])

@php
    $isActive = $active;
    $activeClass = $isActive ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white';
    $canAccess = true;

    if ($permission) {
        $canAccess = auth()->user()->can($permission);
    }
@endphp

@if($canAccess)
<div x-data="{ open: {{ $isActive ? 'true' : 'false' }} }" class="nav-group">
    <button
        type="button"
        class="w-full flex items-center justify-between px-4 py-3 transition-colors duration-200"
        :class="open || {{ $isActive ? 'true' : 'false' }} ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'"
        @click="open = !open"
    >
        <div class="flex items-center">
            <span class="w-6 h-6 flex items-center justify-center mr-3">
                {!! $icon !!}
            </span>
            <span>{{ $title }}</span>
        </div>
        <svg class="w-4 h-4 transform transition-transform duration-200"
            :class="open ? 'rotate-180' : ''"
            fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="nav-group-items pl-4 bg-gray-900 bg-opacity-50"
    >
        {{ $slot }}
    </div>
</div>
@endif
