@extends('layouts.app')

@section('title', 'Đặt lịch')

@section('content')
<section class="bg-gray-900 text-white py-16">
    <div class="container mx-auto px-6">
        <h1 class="text-4xl font-bold mb-4">Đặt lịch hẹn</h1>
        <p class="text-xl text-gray-300">Đặt lịch dễ dàng và nhanh chóng với Beauty Spa.</p>
    </div>
</section>

<section class="py-16">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <!-- Hiển thị mã khuyến mãi dịch vụ -->
            <div class="mb-8 bg-gradient-to-r from-pink-50 to-pink-100 rounded-lg p-6 border border-pink-200 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-pink-700 flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm2.5 3a1.5 1.5 0 100 3 1.5 1.5 0 000-3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h5a1 1 0 100-2h-5zm-8 4a1 1 0 100 2h8a1 1 0 100-2H4z" clip-rule="evenodd"></path>
                        </svg>
                        Mã khuyến mãi đang áp dụng
                    </h3>
                    <button onclick="fetchActivePromotions()" class="text-pink-500 hover:text-pink-700 flex items-center text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Làm mới
                    </button>
                </div>
                <div id="promotions-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex justify-center items-center h-24">
                        <div class="animate-pulse flex space-x-4">
                            <div class="flex-1 space-y-4 py-1">
                                <div class="h-4 bg-pink-200 rounded w-3/4"></div>
                                <div class="space-y-2">
                                    <div class="h-4 bg-pink-200 rounded"></div>
                                    <div class="h-4 bg-pink-200 rounded w-5/6"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <p class="text-sm text-pink-600">Nhập mã khuyến mãi khi đặt lịch để được giảm giá</p>
                </div>
            </div>

            @if(session('success')) {{-- Consistent naming --}}
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
            @endif
            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
            @endif

            <form action="{{ route('customer.appointments.store') }}" method="POST" class="bg-white rounded-lg shadow-lg p-8">
                @csrf

                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center">
                        <div class="step-circle w-8 h-8 bg-pink-500 text-white rounded-full flex items-center justify-center font-semibold" data-step="1">1</div>
                        <div class="ml-3"><p class="font-semibold step-text" data-step="1">Chọn dịch vụ</p></div>
                    </div>
                    <div class="flex-1 mx-4 border-t-2 border-gray-200 step-line" data-step="1"></div>
                    <div class="flex items-center">
                        <div class="step-circle w-8 h-8 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-semibold" data-step="2">2</div>
                        <div class="ml-3"><p class="font-semibold text-gray-600 step-text" data-step="2">Chọn thời gian</p></div>
                    </div>
                    <div class="flex-1 mx-4 border-t-2 border-gray-200 step-line" data-step="2"></div>
                    <div class="flex items-center">
                        <div class="step-circle w-8 h-8 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-semibold" data-step="3">3</div>
                        <div class="ml-3"><p class="font-semibold text-gray-600 step-text" data-step="3">Xác nhận</p></div>
                    </div>
                </div>

                <div class="mb-8" id="step-1-content">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Chọn dịch vụ</h3>
                        <a href="{{ route('services.index') }}" class="text-pink-500 hover:text-pink-700 flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Xem tất cả dịch vụ
                        </a>
                    </div>

                    <!-- Khuyến mãi đang diễn ra -->
                    <div id="active-promotions" class="mb-6 hidden">
                        <div class="bg-gradient-to-r from-pink-50 to-pink-100 rounded-lg p-4 border border-pink-200">
                            <h4 class="font-semibold text-pink-700 flex items-center mb-2">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm4.707 3.707a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L8.414 9H10a3 3 0 013 3v1a1 1 0 102 0v-1a5 5 0 00-5-5H8.414l1.293-1.293z" clip-rule="evenodd"></path>
                                </svg>
                                Khuyến mãi đang diễn ra
                            </h4>
                            <div id="promotions-list" class="space-y-2">
                                <!-- Danh sách khuyến mãi sẽ được thêm vào đây bằng JavaScript -->
                                <div class="text-center py-2">
                                    <div class="loading-spinner"></div>
                                    <p class="text-sm text-gray-500 mt-1">Đang tải khuyến mãi...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($services->isEmpty())
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
                        <p>Hiện tại không có dịch vụ nào khả dụng. Vui lòng quay lại sau.</p>
                    </div>
                    @else
                    {{-- Container for Event Delegation --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="service-list">
                        @foreach($services as $service)
                        <div class="service-card relative border rounded-lg p-4 cursor-pointer hover:border-pink-500 transition {{ (old('service_id') == $service->id || (isset($selectedService) && $selectedService->id == $service->id)) ? 'selected-service border-pink-500' : '' }}">
                            <label for="service-{{ $service->id }}" class="absolute inset-0 cursor-pointer z-10"></label>

                            <input type="radio" name="service_id" id="service-{{ $service->id }}" value="{{ $service->id }}"
                                {{ (old('service_id') == $service->id || (isset($selectedService) && $selectedService->id == $service->id)) ? 'checked' : '' }}
                                class="absolute opacity-0" required>

                            @if($service->hasActivePromotion())
                            <div class="absolute -top-2 -right-2 bg-pink-500 text-black text-xs px-2 py-1 rounded-full promotion-badge">
                                <div>{{ $service->promotion_value }}</div>
                                @if($service->promotion_details && !$service->promotion_details['is_direct'])
                                <div class="text-xs text-black">
                                    {{ $service->promotion_details['start_date'] }} - {{ $service->promotion_details['end_date'] }}
                                </div>
                                @endif
                            </div>
                            @endif

                            <div class="flex items-start relative z-0">
                                <div class="w-20 h-20 bg-pink-100 rounded flex items-center justify-center text-pink-500">
                                    <i class="fas fa-spa text-2xl"></i>
                                </div>

                                <div class="ml-4 flex-1">
                                    <h4 class="font-semibold">{{ $service->name }}</h4>
                                    <p class="text-gray-600 text-sm mb-2">{{ Str::limit($service->descriptive, 80) }}</p>
                                    <div class="flex items-center justify-between">
                                        @if(isset($service->price))
                                        <div>
                                            @if($service->hasActivePromotion())
                                            <span class="text-pink-500 font-semibold">{{ $service->formatted_discounted_price }}</span>
                                            <span class="text-gray-500 line-through text-sm ml-2">{{ number_format($service->price) }}đ</span>
                                            @else
                                            <span class="text-pink-500 font-semibold">{{ number_format($service->price) }}đ</span>
                                            @endif
                                        </div>
                                        @endif
                                        @if(isset($service->duration))
                                        <span class="text-gray-500">{{ $service->duration }} phút</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="absolute top-4 right-4 w-5 h-5 border-2 rounded-full flex items-center justify-center service-radio pointer-events-none">
                                <div class="w-3 h-3 bg-pink-500 rounded-full service-radio-dot" style="opacity: {{ (old('service_id') == $service->id || (isset($selectedService) && $selectedService->id == $service->id)) ? '1' : '0' }};"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @error('service_id')
                    <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-8" id="step-2-content">
                    <h3 class="text-xl font-semibold mb-4">Chọn thời gian</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date_appointments" class="block text-gray-700 mb-2">Ngày <span class="text-red-500">*</span></label>
                            <input type="date" name="date_appointments" required min="{{ date('Y-m-d') }}"
                                value="{{ old('date_appointments') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                                id="date_appointments">
                            {{-- Removed date format hint for brevity --}}
                            @error('date_appointments')
                            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Thời gian <span class="text-red-500">*</span></label>
                            {{-- Container for Event Delegation --}}
                            <div class="grid grid-cols-3 gap-2" id="time-slots-container">
                                <div class="col-span-3 text-gray-500 text-sm p-3 border rounded-lg bg-gray-50">
                                    <i class="fas fa-info-circle mr-1"></i> Vui lòng chọn dịch vụ và ngày để xem khung giờ.
                                    </div>
                            </div>
                            @error('time_appointments_id')
                            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4 p-4 border border-blue-200 rounded-lg bg-blue-50">
                        <div class="flex items-center text-blue-700 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            <p>Sau khi chọn ngày, hệ thống sẽ kiểm tra và hiển thị các khung giờ còn trống.</p>
                        </div>
                    </div>
                </div>

                <div class="mb-8" id="step-3-content">
                    <h3 class="text-xl font-semibold mb-4">Thông tin khách hàng</h3>
                    @php $user = auth()->user(); @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                             <label class="block text-gray-700 mb-2">Họ <span class="text-red-500">*</span></label>
                             <input type="text" readonly value="{{ $user->first_name }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                         </div>
                         <div>
                             <label class="block text-gray-700 mb-2">Tên <span class="text-red-500">*</span></label>
                             <input type="text" readonly value="{{ $user->last_name }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                         </div>
                         <div>
                             <label class="block text-gray-700 mb-2">Số điện thoại <span class="text-red-500">*</span></label>
                             <input type="tel" readonly value="{{ $user->phone }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                         </div>
                         <div>
                             <label class="block text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                             <input type="email" readonly value="{{ $user->email }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                        </div>
                        <div>
                             <label class="block text-gray-700 mb-2">Giới tính <span class="text-red-500">*</span></label>
                             <input type="text" readonly value="{{ $user->gender === 'male' ? 'Nam' : ($user->gender === 'female' ? 'Nữ' : 'Khác') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                        </div>
                         <div>
                             <label class="block text-gray-700 mb-2">Địa chỉ <span class="text-red-500">*</span></label>
                             <input type="text" readonly value="{{ $user->address ?: '-' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                        </div>
                        <div class="md:col-span-2">
                            <label for="promotion_code" class="block text-gray-700 mb-2">Mã khuyến mãi (nếu có)</label>
                            <div class="flex">
                                <input type="text" name="promotion_code" id="promotion_code"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                                    placeholder="Nhập mã khuyến mãi...">
                                <button type="button" id="apply-promotion" class="px-4 py-2 bg-pink-500 text-white rounded-r-lg hover:bg-pink-600 transition">
                                    Áp dụng
                                </button>
                            </div>
                            <div id="promotion-message" class="mt-2 text-sm hidden"></div>
                        </div>
                        <div class="md:col-span-2">
                             <label for="notes" class="block text-gray-700 mb-2">Ghi chú</label>
                             <textarea name="notes" id="notes" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                                 placeholder="Nhập yêu cầu đặc biệt nếu có...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-10">
                    <button type="submit" class="bg-pink-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-pink-600 transition" id="submit-button">
                        Đặt lịch ngay
                    </button>
                    <p class="text-sm text-gray-500 mt-2" id="submit-hint">Vui lòng chọn đủ Dịch vụ, Ngày và Giờ hẹn.</p>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="py-16 bg-gray-100">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-12">Tại sao chọn Beauty Spa?</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-pink-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Đặt lịch dễ dàng</h3>
                <p class="text-gray-600">Đặt lịch online nhanh chóng và tiện lợi chỉ với vài bước đơn giản.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-pink-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-md text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Chuyên gia giàu kinh nghiệm</h3>
                <p class="text-gray-600">Đội ngũ bác sĩ và chuyên viên được đào tạo chuyên sâu.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-pink-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-spa text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Dịch vụ chất lượng cao</h3>
                <p class="text-gray-600">Sử dụng các sản phẩm và thiết bị hiện đại nhất.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-pink-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-heart text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Chăm sóc tận tâm</h3>
                <p class="text-gray-600">Đặt sự hài lòng và thoải mái của khách hàng lên hàng đầu.</p>
            </div>
        </div>
    </div>
</section>

@endsection

{{-- CSS Styles remain the same as your last working visual version --}}
@push('styles')
<style>
    .service-card.selected-service {
        border-color: #ec4899;
        background-color: rgba(236, 72, 153, 0.05);
        box-shadow: 0 4px 6px -1px rgba(236, 72, 153, 0.1), 0 2px 4px -1px rgba(236, 72, 153, 0.06);
    }
    .service-card.selected-service .service-radio-dot {
        opacity: 1 !important;
    }
    .time-slot-div.selected-time { /* Target the div for visual selection */
        background-color: #ec4899;
        color: white;
        border-color: #ec4899;
        box-shadow: 0 4px 6px -1px rgba(236, 72, 153, 0.1), 0 2px 4px -1px rgba(236, 72, 153, 0.06);
    }
    .time-slot-div {
        transition: all 0.2s ease;
    }
    .time-slot-div:hover {
        border-color: #ec4899;
        transform: translateY(-1px);
    }
    .loading-spinner { display: inline-block; width: 1rem; height: 1rem; border: 2px solid rgba(236, 72, 153, 0.3); border-radius: 50%; border-top-color: #ec4899; animation: spin 1s ease-in-out infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    input[type="date"] { position: relative; }
    input[type="date"]::-webkit-calendar-picker-indicator { cursor: pointer; border-radius: 4px; margin-right: 2px; opacity: 0.8; filter: invert(0.8) sepia(100%) saturate(100%) hue-rotate(220deg); }
    input[type="date"]::-webkit-inner-spin-button, input[type="date"]::-webkit-clear-button { display: none; }
    .step-circle.active { background-color: #ec4899; color: white; }
    .step-circle.inactive { background-color: #e5e7eb; color: #4b5563; } /* gray-200, gray-600 */
    .step-text.active { color: #111827; } /* gray-900 */
    .step-text.inactive { color: #4b5563; } /* gray-600 */
    .step-line.active { border-color: #ec4899; }
    .step-line.inactive { border-color: #e5e7eb; } /* gray-200 */

</style>
@endpush

{{-- Updated JavaScript using addEventListener --}}
@push('scripts')
<script>
// Hàm toàn cục để chọn thời gian
function selectTimeSlot(slotId) {
    console.log('Chọn thời gian (click):', slotId);

    try {
        // Lấy radio button tương ứng
        const radio = document.getElementById('time-input-' + slotId);
        if (!radio) {
            console.error('Không tìm thấy radio button cho slot ID:', slotId);
            return;
        }

        // Đánh dấu radio button là đã chọn
        radio.checked = true;

        // Cập nhật hiển thị - bỏ highlight tất cả các slot
        document.querySelectorAll('.time-slot-btn').forEach(btn => {
            btn.classList.remove('bg-pink-500', 'text-white', 'border-pink-500');
        });

        // Highlight slot được chọn
        const slotBtn = document.getElementById('time-slot-btn-' + slotId);
        if (slotBtn) {
            slotBtn.classList.add('bg-pink-500', 'text-white', 'border-pink-500');
        } else {
            console.error('Không tìm thấy button cho slot ID:', slotId);
        }

        // Cập nhật biến toàn cục
        window.selectedTimeSlotId = slotId;

        // In ra console để debug
        console.log('Đã chọn thời gian:', {
            slotId: slotId,
            radioChecked: radio.checked,
            selectedTimeSlotId: window.selectedTimeSlotId,
            buttonHighlighted: slotBtn ? slotBtn.classList.contains('bg-pink-500') : false
        });

        // Kích hoạt sự kiện change để các listener khác biết rằng đã chọn thời gian
        radio.dispatchEvent(new Event('change'));

        // Kiểm tra nút Submit
        if (typeof checkSubmitButtonStatus === 'function') {
            checkSubmitButtonStatus();
        } else if (typeof checkSubmitButton === 'function') {
            checkSubmitButton();
        }
    } catch (error) {
        console.error('Lỗi khi chọn thời gian:', error);
    }
}

// Hàm toàn cục để kiểm tra trạng thái nút Submit
function checkSubmitButtonStatus() {
    const submitButton = document.getElementById('submit-button');
    const submitHint = document.getElementById('submit-hint');

    // Kiểm tra dịch vụ đã chọn
    const serviceRadio = document.querySelector('input[name="service_id"]:checked');
    const serviceSelected = !!serviceRadio;

    // Kiểm tra ngày đã chọn
    const dateInput = document.getElementById('date_appointments');
    const dateSelected = !!dateInput && !!dateInput.value;

    // Kiểm tra thời gian đã chọn
    const timeRadio = document.querySelector('input[name="time_appointments_id"]:checked');
    const timeSelected = !!timeRadio;

    // Cập nhật biến toàn cục nếu cần
    if (serviceRadio) window.selectedServiceId = serviceRadio.value;
    if (timeRadio) window.selectedTimeSlotId = timeRadio.value;

    // Kiểm tra tất cả đã chọn chưa
    const allSelected = serviceSelected && dateSelected && timeSelected;

    // Cập nhật trạng thái nút Submit
    if (submitButton) {
        submitButton.disabled = !allSelected;

        // Đảm bảo nút không bị vô hiệu hóa bởi CSS
        if (allSelected) {
            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
            submitButton.classList.add('hover:bg-pink-600');
            // Đảm bảo thuộc tính disabled được xóa
            submitButton.removeAttribute('disabled');
        } else {
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            submitButton.classList.remove('hover:bg-pink-600');
        }
    }

    // Cập nhật thông báo gợi ý
    if (submitHint) {
        submitHint.style.display = allSelected ? 'none' : 'block';
    }

    // In ra console để debug
    console.log('Trạng thái nút Submit:', {
        serviceSelected,
        serviceId: serviceRadio ? serviceRadio.value : null,
        dateSelected,
        dateValue: dateInput ? dateInput.value : null,
        timeSelected,
        timeId: timeRadio ? timeRadio.value : null,
        allSelected,
        buttonDisabled: submitButton ? submitButton.disabled : null,
        buttonHasDisabledAttr: submitButton ? submitButton.hasAttribute('disabled') : null
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Lấy các phần tử DOM
    const dateInput = document.getElementById('date_appointments');
    const timeSlotContainer = document.getElementById('time-slots-container');
    const submitButton = document.getElementById('submit-button');
    const submitHint = document.getElementById('submit-hint');
    const activePromotionsContainer = document.getElementById('active-promotions');
    const promotionsList = document.getElementById('promotions-list');
    const promotionCodeInput = document.getElementById('promotion_code');
    const applyPromotionButton = document.getElementById('apply-promotion');
    const promotionMessage = document.getElementById('promotion-message');

    // Biến để theo dõi số lần thử lại
    window.promotionRetryCount = 0;
    window.maxPromotionRetries = 3;

    // Thiết lập ngày tối thiểu là ngày hôm nay thay vì ngày mai
    const today = new Date();
    const todayStr = today.toISOString().split('T')[0];

    console.log('DEBUG DATE:');
    console.log('- Today: ' + today.toISOString());
    console.log('- Today string: ' + todayStr);
    console.log('- Current min attribute: ' + dateInput.min);

    dateInput.min = todayStr;
    console.log('- Set min to: ' + dateInput.min);

    // Lấy radio button được chọn ban đầu (nếu có)
    window.selectedServiceId = null;
    const checkedRadio = document.querySelector('input[name="service_id"]:checked');
    if (checkedRadio) {
        window.selectedServiceId = checkedRadio.value;
        console.log('Dịch vụ đã chọn ban đầu:', window.selectedServiceId);
        updateServiceDisplay(window.selectedServiceId);

        // Cuộn đến dịch vụ đã chọn
        setTimeout(() => {
            const selectedCard = document.querySelector('.service-card.selected-service');
            if (selectedCard) {
                selectedCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 500);
    } else {
        console.log('Không có dịch vụ nào được chọn ban đầu');
    }

    // Lấy mốc thời gian được chọn (nếu có)
    let selectedTimeSlotId = null;
    const checkedTimeRadio = document.querySelector('input[name="time_appointments_id"]:checked');
    if (checkedTimeRadio) {
        selectedTimeSlotId = checkedTimeRadio.value;
    }

    // Lấy danh sách khuyến mãi đang hoạt động
    fetchActivePromotions();

    // Xử lý sự kiện khi nhấn nút áp dụng mã khuyến mãi
    applyPromotionButton.addEventListener('click', function() {
        validatePromotionCode();
    });

    // Xử lý sự kiện khi nhấn Enter trong ô nhập mã khuyến mãi
    promotionCodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            validatePromotionCode();
        }
    });

    // Thêm sự kiện change cho các radio button dịch vụ
    document.querySelectorAll('input[name="service_id"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                window.selectedServiceId = this.value;
                console.log('Dịch vụ đã chọn (từ sự kiện change):', window.selectedServiceId);
                updateServiceDisplay(window.selectedServiceId);

                // Nếu đã chọn ngày, cập nhật các khung giờ
                if (dateInput.value) {
                    console.log('Đã chọn ngày, gọi fetchAvailableTimeSlots');
                    fetchAvailableTimeSlots(window.selectedServiceId, dateInput.value);
                } else {
                    console.log('Chưa chọn ngày, không gọi fetchAvailableTimeSlots');
                }
            }
        });
    });

    // Xử lý sự kiện click trên service-card (để hỗ trợ chọn cả khi click vào card)
    document.querySelectorAll('.service-card').forEach(card => {
        card.addEventListener('click', function(e) {
            // Chỉ xử lý khi click vào card, không phải label hoặc input
            if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'LABEL') {
                const radio = this.querySelector('input[name="service_id"]');
                if (radio) {
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change'));
                }
            }
        });
    });

    // Cập nhật giao diện theo dịch vụ được chọn
    function updateServiceDisplay(serviceId) {
        // Cập nhật trạng thái card
        document.querySelectorAll('.service-card').forEach(card => {
            const radio = card.querySelector('input[name="service_id"]');
            const dot = card.querySelector('.service-radio-dot');

            if (radio && radio.value === serviceId) {
                card.classList.add('selected-service', 'border-pink-500');
                if (dot) dot.style.opacity = '1';
            } else {
                card.classList.remove('selected-service', 'border-pink-500');
                if (dot) dot.style.opacity = '0';
            }
        });

        // Kiểm tra nút Submit
        checkSubmitButton();
    }

    // Khi chọn ngày
    dateInput.addEventListener('change', function() {
        const selectedDate = this.value;
        console.log('Ngày đã chọn:', selectedDate);

        if (!window.selectedServiceId) {
            console.log('Chưa chọn dịch vụ, hiển thị thông báo');
            timeSlotContainer.innerHTML = `
                <div class="col-span-3 text-yellow-600 p-4 rounded-lg border border-yellow-200 bg-yellow-50">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Vui lòng chọn dịch vụ trước khi chọn ngày.
                </div>
            `;
            return;
        }

        // Kiểm tra xem ngày chọn có lớn hơn hoặc bằng ngày hiện tại không
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const selectedDateObj = new Date(selectedDate);
        selectedDateObj.setHours(0, 0, 0, 0);

        console.log('So sánh ngày:');
        console.log('- Today:', today);
        console.log('- Selected:', selectedDateObj);
        console.log('- Difference (days):', Math.floor((selectedDateObj - today) / (1000 * 60 * 60 * 24)));

        if (selectedDateObj < today) {
            console.log('Ngày trong quá khứ, hiển thị thông báo');
            timeSlotContainer.innerHTML = `
                <div class="col-span-3 text-red-500 p-4 rounded-lg border border-red-200 bg-red-50">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Không thể chọn ngày trong quá khứ. Vui lòng chọn từ ngày hôm nay trở đi.
                </div>
            `;
            return;
        }

        console.log('Gọi fetchAvailableTimeSlots với serviceId:', window.selectedServiceId, 'và date:', selectedDate);
        fetchAvailableTimeSlots(window.selectedServiceId, selectedDate);
    });

    // Hàm lấy các khung giờ có sẵn
    function fetchAvailableTimeSlots(serviceId, date) {
        try {
            console.log('Bắt đầu fetchAvailableTimeSlots với:', { serviceId, date });

            if (!serviceId) {
                console.error('Không có serviceId được cung cấp');
                timeSlotContainer.innerHTML = `
                    <div class="col-span-3 text-yellow-600 p-4 rounded-lg border border-yellow-200 bg-yellow-50">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Vui lòng chọn dịch vụ trước khi chọn ngày.
                    </div>
                `;
                return;
            }

            if (!date) {
                console.error('Không có date được cung cấp');
                timeSlotContainer.innerHTML = `
                    <div class="col-span-3 text-yellow-600 p-4 rounded-lg border border-yellow-200 bg-yellow-50">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Vui lòng chọn ngày.
                    </div>
                `;
                return;
            }

            // Hiển thị loading
            timeSlotContainer.innerHTML = `
                <div class="col-span-3 text-gray-500 flex items-center justify-center py-4">
                    <div class="loading-spinner mr-3"></div> Đang kiểm tra khung giờ khả dụng...
                </div>
            `;

            // Gọi API để lấy khung giờ
            const apiUrl = `/api/check-available-slots?service_id=${serviceId}&date=${date}&customer_id={{ Auth::id() }}`;
            console.log('Gọi API:', apiUrl);

            fetch(apiUrl)
                .then(response => {
                    console.log('API status:', response.status);
                    if (!response.ok) {
                        throw new Error('Lỗi kết nối: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Dữ liệu từ API:', data);

                    // Kiểm tra xem data có tồn tại không
                    if (!data) {
                        throw new Error('Không nhận được dữ liệu từ API');
                    }

                    if (data.success && data.available_slots && data.available_slots.length > 0) {
                        // Hiển thị các khung giờ khả dụng
                        timeSlotContainer.innerHTML = `
                            <div class="col-span-3 mb-3">
                                <div class="bg-blue-50 border border-blue-200 text-blue-700 p-2 rounded-md">
                                    <div class="flex items-center text-xs">
                                        <svg class="h-2 w-2 text-blue-500 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        <span> Mỗi khách hàng chỉ đặt được 1 dịch vụ/khung giờ.</span>
                                    </div>
                                </div>
                            </div>
                        `;

                        // Thêm từng khung giờ vào container
                        data.available_slots.forEach(slot => {
                            try {
                                const availableText = slot.available_slots > 0
                                    ? `<span class="text-xs text-green-600 block mt-1">${slot.available_slots}/${slot.capacity} chỗ trống</span>`
                                    : '<span class="text-xs text-red-600 block mt-1">Đã đầy</span>';

                                const timeSlotHTML = `
                                    <div class="time-slot-wrapper mb-2">
                                        <button type="button"
                                            class="w-full text-center px-4 py-2 border rounded-lg cursor-pointer hover:border-pink-500 transition time-slot-btn block"
                                            id="time-slot-btn-${slot.id}"
                                            onclick="selectTimeSlot(${slot.id})">
                                            ${slot.time}
                                            ${availableText}
                                        </button>
                                        <input type="radio" name="time_appointments_id" id="time-input-${slot.id}" value="${slot.id}" class="hidden time-radio" required>
                                    </div>
                                `;

                                timeSlotContainer.insertAdjacentHTML('beforeend', timeSlotHTML);
                            } catch (slotError) {
                                console.error('Lỗi khi xử lý khung giờ:', slotError, slot);
                            }
                        });

                        // Thêm sự kiện click trực tiếp cho các button thời gian
                        document.querySelectorAll('.time-slot-btn').forEach(btn => {
                            btn.addEventListener('click', function() {
                                try {
                                    // Lấy ID từ ID của button (time-slot-btn-123 -> 123)
                                    const slotId = this.id.replace('time-slot-btn-', '');
                                    console.log('Click trực tiếp vào button thời gian:', slotId);

                                    // Gọi hàm chọn thời gian
                                    selectTimeSlot(slotId);
                                } catch (btnError) {
                                    console.error('Lỗi khi xử lý click button thời gian:', btnError);
                                }
                            });
                        });

                        console.log('Đã hiển thị', data.available_slots.length, 'khung giờ');
                    } else {
                        // Không có khung giờ khả dụng
                        timeSlotContainer.innerHTML = `
                            <div class="col-span-3 text-red-500 p-4 rounded-lg border border-red-200 bg-red-50">
                                <i class="fas fa-times-circle mr-2"></i>
                                Không có khung giờ nào khả dụng cho ngày này. Vui lòng chọn ngày khác.
                            </div>
                        `;
                        console.log('Không có khung giờ khả dụng');
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi gọi API:', error);
                    timeSlotContainer.innerHTML = `
                        <div class="col-span-3 text-red-500 p-4 rounded-lg border border-red-200 bg-red-50">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            Đã xảy ra lỗi khi kiểm tra khung giờ: ${error.message}. Vui lòng thử lại sau.
                        </div>
                    `;
                });
        } catch (error) {
            console.error('Lỗi tổng thể trong fetchAvailableTimeSlots:', error);
            timeSlotContainer.innerHTML = `
                <div class="col-span-3 text-red-500 p-4 rounded-lg border border-red-200 bg-red-50">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Đã xảy ra lỗi: ${error.message}. Vui lòng thử lại sau.
                </div>
            `;
        }
    }

    // Kiểm tra và cập nhật trạng thái nút Submit
    function checkSubmitButton() {
        // Sử dụng hàm toàn cục để đảm bảo tính nhất quán
        checkSubmitButtonStatus();
    }

    // Hàm kiểm tra mã khuyến mãi
    function validatePromotionCode() {
        const code = promotionCodeInput.value.trim();
        if (!code) {
            showPromotionMessage('Vui lòng nhập mã khuyến mãi', 'warning');
            return;
        }

        // Lấy giá dịch vụ đã chọn
        let servicePrice = 0;
        const selectedService = document.querySelector('input[name="service_id"]:checked');
        if (selectedService) {
            const serviceCard = selectedService.closest('.service-card');
            const priceElement = serviceCard.querySelector('.text-pink-500.font-semibold');
            if (priceElement) {
                // Chuyển đổi chuỗi "1,500,000đ" thành số 1500000
                servicePrice = parseInt(priceElement.textContent.replace(/[^\d]/g, ''));
            }
        }

        if (servicePrice === 0) {
            showPromotionMessage('Vui lòng chọn dịch vụ trước khi áp dụng mã khuyến mãi', 'warning');
            return;
        }

        // Hiển thị trạng thái đang tải
        promotionMessage.innerHTML = '<div class="flex items-center text-gray-500"><div class="loading-spinner mr-2"></div> Đang kiểm tra mã khuyến mãi...</div>';
        promotionMessage.classList.remove('hidden');

        // Gọi API để kiểm tra mã khuyến mãi
        fetch('/api/validate-promotion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                code: code,
                amount: servicePrice
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hiển thị thông tin khuyến mãi
                const discountAmount = data.data.formatted_discount;
                const newPrice = new Intl.NumberFormat('vi-VN').format(servicePrice - data.data.discount) + 'đ';

                showPromotionMessage(`
                    <div class="text-green-600 font-semibold">Mã khuyến mãi hợp lệ!</div>
                    <div class="mt-1">Giảm giá: <span class="font-semibold">${discountAmount}</span></div>
                    <div>Giá sau khuyến mãi: <span class="font-semibold text-pink-600 text-lg">${newPrice}</span></div>
                    <div class="mt-2 p-2 bg-green-50 border border-green-100 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Mã khuyến mãi sẽ được áp dụng khi đặt lịch</span>
                        </div>
                    </div>
                `, 'success');

                // Thêm input ẩn để lưu mã khuyến mãi
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'promotion_code';
                hiddenInput.value = code;
                document.querySelector('form').appendChild(hiddenInput);

                // Vô hiệu hóa input và nút áp dụng
                promotionCodeInput.disabled = true;
                applyPromotionButton.disabled = true;
                applyPromotionButton.classList.add('opacity-50');
            } else {
                // Hiển thị thông báo lỗi
                showPromotionMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Lỗi khi kiểm tra mã khuyến mãi:', error);
            showPromotionMessage('Đã xảy ra lỗi khi kiểm tra mã khuyến mãi. Vui lòng thử lại sau.', 'error');
        });
    }

    // Hàm hiển thị thông báo về mã khuyến mãi
    function showPromotionMessage(message, type) {
        promotionMessage.innerHTML = message;
        promotionMessage.classList.remove('hidden', 'text-yellow-600', 'text-red-600', 'text-green-600');

        switch (type) {
            case 'success':
                promotionMessage.classList.add('text-green-600');
                break;
            case 'error':
                promotionMessage.classList.add('text-red-600');
                break;
            case 'warning':
                promotionMessage.classList.add('text-yellow-600');
                break;
        }
    }

    // Hàm lấy danh sách khuyến mãi đang hoạt động
    function fetchActivePromotions() {
        // Lấy container khuyến mãi ở đầu trang
        const topPromotionsContainer = document.getElementById('promotions-container');

        // Tăng số lần thử
        window.promotionRetryCount++;

        // Hiển thị trạng thái đang tải
        topPromotionsContainer.innerHTML = `
            <div class="col-span-2 bg-white rounded-lg p-4 border border-pink-200 shadow-sm">
                <div class="text-center py-4">
                    <div class="loading-spinner mx-auto mb-3"></div>
                    <p class="text-gray-600">Đang tải thông tin khuyến mãi${window.promotionRetryCount > 1 ? ` (lần thử ${window.promotionRetryCount})` : ''}...</p>
                </div>
            </div>
        `;

        // Sử dụng đường dẫn tuyệt đối
        const apiUrl = '{{ url("/api/active-promotions") }}';
        console.log('Gọi API khuyến mãi:', apiUrl, '- Lần thử:', window.promotionRetryCount);

        fetch(apiUrl)
            .then(response => {
                console.log('API status:', response.status);
                if (!response.ok) {
                    throw new Error('Lỗi kết nối: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Dữ liệu khuyến mãi:', data);

                // Xóa nội dung cũ của container ở đầu trang
                topPromotionsContainer.innerHTML = '';

                console.log('Dữ liệu khuyến mãi chi tiết:', JSON.stringify(data));

                if (data.promotions && data.promotions.length > 0) {
                    // Hiển thị container khuyến mãi trong form
                    activePromotionsContainer.classList.remove('hidden');

                    // Xóa nội dung loading
                    promotionsList.innerHTML = '';

                    // Thêm các khuyến mãi vào cả hai container
                    data.promotions.forEach(promotion => {
                        try {
                            // Kiểm tra xem promotion có tồn tại và có các thuộc tính cần thiết không
                            if (!promotion || !promotion.id) {
                                console.error('Promotion data is invalid:', promotion);
                                return; // Skip this promotion
                            }

                            const discountText = promotion.discount_type === 'percentage'
                                ? `${promotion.discount_value}%`
                                : `${new Intl.NumberFormat('vi-VN').format(promotion.discount_value)}đ`;

                            const minimumText = promotion.minimum_purchase > 0
                                ? `<span class="text-xs text-gray-600">Áp dụng cho đơn hàng từ ${new Intl.NumberFormat('vi-VN').format(promotion.minimum_purchase)}đ</span>`
                                : '';

                            // Kiểm tra end_date có tồn tại không
                            let formattedDate = 'Không xác định';
                            if (promotion.end_date) {
                                try {
                                    const validUntil = new Date(promotion.end_date);
                                    formattedDate = new Intl.DateTimeFormat('vi-VN', {
                                        day: '2-digit',
                                        month: '2-digit',
                                        year: 'numeric'
                                    }).format(validUntil);
                                } catch (dateError) {
                                    console.error('Error formatting date:', dateError);
                                }
                            }

                            // HTML cho container trong form
                            const formPromotionHTML = `
                                <div class="bg-white rounded-lg p-3 border border-pink-100 shadow-sm">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="font-semibold text-pink-600">${promotion.title || promotion.name || 'Khuyến mãi'}</div>
                                            <div class="text-sm text-gray-600">Mã: <span class="font-mono font-semibold">${promotion.code || ''}</span></div>
                                            <div class="text-sm font-semibold text-green-600">Giảm ${discountText}</div>
                                            ${minimumText}
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500">Có hiệu lực đến</div>
                                            <div class="text-sm font-medium">${formattedDate}</div>
                                        </div>
                                    </div>
                                </div>
                            `;

                            // HTML cho container ở đầu trang
                            const topPromotionHTML = `
                                <div class="bg-white rounded-lg p-4 border border-pink-200 shadow-sm hover:shadow-md transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h5 class="font-bold text-gray-800 text-lg">${promotion.title || promotion.name || 'Khuyến mãi'}</h5>
                                            <p class="text-gray-600 mt-1">${promotion.description || 'Ưu đãi đặc biệt'}</p>
                                        </div>
                                        <div class="bg-pink-500 text-white px-3 py-1 rounded-full font-bold">
                                            ${discountText}
                                        </div>
                                    </div>
                                    <div class="mt-3 flex justify-between items-center">
                                        <div class="text-sm">
                                            Mã: <span class="font-mono font-bold text-pink-600 text-lg">${promotion.code || ''}</span>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Hạn sử dụng: ${formattedDate}
                                        </div>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-500">
                                        ${promotion.minimum_purchase > 0 ? 'Áp dụng cho đơn hàng từ ' + new Intl.NumberFormat('vi-VN').format(promotion.minimum_purchase) + 'đ' : 'Áp dụng cho tất cả đơn hàng'}
                                    </div>
                                </div>
                            `;

                            // Thêm vào container trong form
                            promotionsList.insertAdjacentHTML('beforeend', formPromotionHTML);

                            // Thêm vào container ở đầu trang
                            topPromotionsContainer.insertAdjacentHTML('beforeend', topPromotionHTML);
                        } catch (error) {
                            console.error('Error processing promotion:', error, promotion);
                        }
                    });
                }
                else {
                    // Ẩn container khuyến mãi trong form nếu không có khuyến mãi nào
                    activePromotionsContainer.classList.add('hidden');

                    // Hiển thị thông báo không có khuyến mãi ở đầu trang
                    topPromotionsContainer.innerHTML = `
                        <div class="col-span-2 bg-white rounded-lg p-4 border border-pink-200 shadow-sm">
                            <div class="text-center py-4">
                                <svg class="w-12 h-12 text-pink-300 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm4.707 3.707a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L8.414 9H10a3 3 0 013 3v1a1 1 0 102 0v-1a5 5 0 00-5-5H8.414l1.293-1.293z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-gray-600">Hiện tại không có khuyến mãi nào đang diễn ra</p>
                                <p class="text-sm text-gray-500 mt-1">Vui lòng quay lại sau để cập nhật các chương trình khuyến mãi mới nhất</p>
                            </div>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Lỗi khi lấy khuyến mãi:', error);
                activePromotionsContainer.classList.add('hidden');

                // Nếu chưa vượt quá số lần thử lại tối đa, tự động thử lại sau 2 giây
                if (window.promotionRetryCount < window.maxPromotionRetries) {
                    // Hiển thị thông báo đang thử lại
                    topPromotionsContainer.innerHTML = `
                        <div class="col-span-2 bg-white rounded-lg p-4 border border-yellow-200 shadow-sm">
                            <div class="text-center py-4">
                                <div class="loading-spinner mx-auto mb-3"></div>
                                <p class="text-yellow-600">Đang thử lại (${window.promotionRetryCount}/${window.maxPromotionRetries})...</p>
                                <p class="text-sm text-gray-500 mt-1">Lỗi trước đó: ${error.message || 'Không xác định'}</p>
                            </div>
                        </div>
                    `;

                    // Thử lại sau 2 giây
                    setTimeout(fetchActivePromotions, 2000);
                } else {
                    // Đã vượt quá số lần thử lại, hiển thị thông báo lỗi
                    topPromotionsContainer.innerHTML = `
                        <div class="col-span-2 bg-white rounded-lg p-4 border border-red-200 shadow-sm">
                            <div class="text-center py-4">
                                <svg class="w-12 h-12 text-red-300 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-red-600">Không thể tải thông tin khuyến mãi</p>
                                <p class="text-sm text-gray-500 mt-1 mb-3">Lỗi: ${error.message || 'Không xác định'}</p>
                                <button onclick="window.promotionRetryCount = 0; fetchActivePromotions()" class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                                    Thử lại
                                </button>
                            </div>
                        </div>
                    `;
                }
            });
    }

    // Kích hoạt kiểm tra lần đầu
    checkSubmitButton();

    // Thêm sự kiện change cho các radio button thời gian
    document.addEventListener('change', function(e) {
        if (e.target && e.target.name === 'time_appointments_id') {
            console.log('Thời gian đã thay đổi:', e.target.value);
            checkSubmitButtonStatus();
        }
    });

    // Thêm sự kiện submit cho form
    document.querySelector('form').addEventListener('submit', function(e) {
        const serviceRadio = document.querySelector('input[name="service_id"]:checked');
        const dateInput = document.getElementById('date_appointments');
        const timeRadio = document.querySelector('input[name="time_appointments_id"]:checked');

        if (!serviceRadio || !dateInput.value || !timeRadio) {
            e.preventDefault();
            alert('Vui lòng chọn đầy đủ dịch vụ, ngày và giờ hẹn.');
            return false;
        }

        // Đảm bảo nút submit không bị vô hiệu hóa
        const submitButton = document.getElementById('submit-button');
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.removeAttribute('disabled');
        }

        return true;
    });

    // Nếu đã có cả dịch vụ và ngày được chọn khi trang load, kiểm tra khung giờ
    if (window.selectedServiceId && dateInput.value) {
        console.log('Trang vừa load, đã có cả dịch vụ và ngày, gọi fetchAvailableTimeSlots');
        console.log('- selectedServiceId:', window.selectedServiceId);
        console.log('- date:', dateInput.value);
        fetchAvailableTimeSlots(window.selectedServiceId, dateInput.value);
    } else {
        console.log('Trang vừa load, chưa đủ thông tin để gọi fetchAvailableTimeSlots');
        console.log('- selectedServiceId:', window.selectedServiceId);
        console.log('- date:', dateInput.value);
    }
});
</script>
@endpush