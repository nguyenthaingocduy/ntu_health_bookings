@extends('layouts.nvkt-new')

@section('title', 'Chi tiết ghi chú chuyên môn')

@section('header', 'Chi tiết ghi chú chuyên môn')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $note->title }}</h1>
            <p class="text-sm text-gray-500 mt-1">Chi tiết ghi chú chuyên môn</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('nvkt.notes.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-150 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
            <a href="{{ route('nvkt.notes.edit', $note->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors duration-150 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Chỉnh sửa
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $note->title }}</h2>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Ngày tạo: {{ $note->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <form action="{{ route('nvkt.notes.destroy', $note->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 flex items-center" onclick="return confirm('Bạn có chắc chắn muốn xóa ghi chú này?')">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Xóa
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Thông tin liên quan</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="mb-4">
                            <span class="block text-sm font-medium text-gray-700">Dịch vụ liên quan</span>
                            <span class="block mt-1 text-sm text-gray-900">
                                @if($note->service)
                                <a href="{{ route('nvkt.services.show', $note->service->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $note->service->name }}
                                </a>
                                @else
                                <span class="text-gray-500">Không có</span>
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-700">Khách hàng liên quan</span>
                            <span class="block mt-1 text-sm text-gray-900">
                                @if($note->customer)
                                <a href="{{ route('nvkt.customers.show', $note->customer->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $note->customer->first_name }} {{ $note->customer->last_name }}
                                </a>
                                @else
                                <span class="text-gray-500">Không có</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nội dung ghi chú</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="prose max-w-none">
                            {!! nl2br(e($note->content)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
