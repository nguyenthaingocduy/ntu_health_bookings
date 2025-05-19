@extends('layouts.admin')

@section('title', 'Phân công lịch làm việc theo tuần')

@section('header', 'Phân công lịch làm việc theo tuần')

@push('styles')
<style>
    .day-checkbox:checked + label {
        background-color: #3b82f6;
        color: white;
    }
    .day-checkbox:not(:checked) + label {
        background-color: #f3f4f6;
        color: #374151;
    }
    .day-checkbox:checked + label:hover {
        background-color: #2563eb;
    }
    .day-checkbox:not(:checked) + label:hover {
        background-color: #e5e7eb;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Phân công lịch làm việc theo tuần</h1>
            <p class="text-sm text-gray-500 mt-1">Thiết lập lịch làm việc cho nhân viên kỹ thuật theo ngày trong tuần</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.work-schedules.index') }}" class="flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150">
                <i class="fas fa-calendar-alt mr-2"></i>
                Quay lại
            </a>
            <a href="{{ route('admin.work-schedules.view-week') }}" class="flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-150">
                <i class="fas fa-calendar-week mr-2"></i>
                Xem lịch tuần
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                LỊCH LÀM VIỆC CỦA NHÂN VIÊN KỸ THUẬT
            </h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.work-schedules.store-weekly-assignment') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="technician_id" class="block text-sm font-medium text-gray-700 mb-2">Chọn nhân viên kỹ thuật:</label>
                    <select id="technician_id" name="technician_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Chọn nhân viên --</option>
                        @foreach($technicians as $technician)
                            <option value="{{ $technician->id }}">{{ $technician->first_name }} {{ $technician->last_name }} - {{ $technician->years_experience ?? 0 }} năm kinh nghiệm</option>
                        @endforeach
                    </select>
                    @error('technician_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <h4 class="text-lg font-medium text-gray-700 mb-2">NGÀY TRONG TUẦN</h4>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ngày trong tuần
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Giờ bắt đầu
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Giờ kết thúc
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Số KH tối đa
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ngày nghỉ
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($daysOfWeek as $dayNum => $dayName)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $dayName }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="time" name="start_time[{{ $dayNum }}]" value="08:00" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="time" name="end_time[{{ $dayNum }}]" value="17:00" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" name="max_customers[{{ $dayNum }}]" value="4" min="1" max="10" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center">
                                            <input type="checkbox" id="day_{{ $dayNum }}" name="days[{{ $dayNum }}]" value="1" class="day-checkbox hidden" {{ $dayNum == 0 ? '' : 'checked' }}>
                                            <label for="day_{{ $dayNum }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full cursor-pointer transition-colors duration-150">
                                                <i class="fas {{ $dayNum == 0 ? 'fa-times' : 'fa-check' }}"></i>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" onclick="window.history.back()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-150 mr-2">
                        Hủy
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-150">
                        <i class="fas fa-save mr-2"></i>
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý khi click vào checkbox ngày nghỉ
        const dayCheckboxes = document.querySelectorAll('.day-checkbox');
        dayCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const label = this.nextElementSibling;
                const icon = label.querySelector('i');

                if (this.checked) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-check');
                } else {
                    icon.classList.remove('fa-check');
                    icon.classList.add('fa-times');
                }
            });
        });
    });
</script>
@endpush
