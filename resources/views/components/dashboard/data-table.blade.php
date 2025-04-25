@props(['title', 'icon', 'color', 'link' => null, 'linkText' => 'Xem tất cả', 'emptyText' => 'Không có dữ liệu'])

@php
    $gradientFrom = "from-{$color}-50";
    $gradientTo = "to-{$color}-100";
    $textColor = "text-{$color}-500";
    $bgColor = "bg-{$color}-100";
    $hoverBgColor = "hover:bg-{$color}-200";
    $textButtonColor = "text-{$color}-700";
@endphp

<div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
    <div class="bg-gradient-to-r {{ $gradientFrom }} {{ $gradientTo }} px-6 py-4 border-b border-gray-200">
        <h3 class="font-semibold text-gray-800 flex items-center">
            <span class="w-5 h-5 mr-2 {{ $textColor }}">{!! $icon !!}</span>
            {{ $title }}
        </h3>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            {{ $slot }}
        </div>
        @if($link)
        <div class="mt-4">
            <a href="{{ $link }}" class="block w-full text-center px-4 py-2 {{ $bgColor }} {{ $textButtonColor }} rounded-lg {{ $hoverBgColor }} transition-colors duration-150">
                {{ $linkText }}
            </a>
        </div>
        @endif
    </div>
</div>
