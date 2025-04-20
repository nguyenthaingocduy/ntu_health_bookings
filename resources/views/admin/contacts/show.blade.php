@extends('layouts.admin')

@section('title', 'Chi tiết tin nhắn liên hệ')

@section('header', 'Chi tiết tin nhắn liên hệ')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 text-right">
        <a href="{{ route('admin.contacts.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Quay lại danh sách
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $contact->subject }}</h2>
                <div class="flex items-center text-gray-500 text-sm">
                    <span class="mr-4">
                        <i class="fas fa-calendar-alt mr-1"></i> {{ $contact->created_at->format('d/m/Y H:i') }}
                    </span>
                    <span>
                        <i class="fas fa-circle {{ $contact->is_read ? 'text-green-500' : 'text-blue-500' }} mr-1"></i>
                        {{ $contact->is_read ? 'Đã đọc' : 'Chưa đọc' }}
                    </span>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4 mb-6">
                <h3 class="text-lg font-medium text-gray-800 mb-2">Thông tin người gửi</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Họ tên</p>
                        <p class="font-medium">{{ $contact->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium">{{ $contact->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Số điện thoại</p>
                        <p class="font-medium">{{ $contact->phone ?? 'Không có' }}</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4">
                <h3 class="text-lg font-medium text-gray-800 mb-2">Nội dung tin nhắn</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="whitespace-pre-line">{{ $contact->message }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-4 flex justify-between">
            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa tin nhắn này?')">
                    Xóa tin nhắn
                </button>
            </form>

            <a href="mailto:{{ $contact->email }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                Phản hồi qua email
            </a>
        </div>
    </div>
</div>
@endsection
