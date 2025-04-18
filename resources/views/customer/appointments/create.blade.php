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
                    <h3 class="text-xl font-semibold mb-4">Chọn dịch vụ</h3>

                    @if($services->isEmpty())
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
                        <p>Hiện tại không có dịch vụ nào khả dụng. Vui lòng quay lại sau.</p>
                    </div>
                    @else
                    {{-- Container for Event Delegation --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="service-list">
                        @foreach($services as $service)
                        <div class="service-card relative border rounded-lg p-4 cursor-pointer hover:border-pink-500 transition {{ (old('service_id') == $service->id || (isset($serviceId) && $serviceId == $service->id)) ? 'selected-service border-pink-500' : '' }}">
                            <label for="service-{{ $service->id }}" class="absolute inset-0 cursor-pointer z-10"></label>

                            <input type="radio" name="service_id" id="service-{{ $service->id }}" value="{{ $service->id }}"
                                {{ (old('service_id') == $service->id || (isset($serviceId) && $serviceId == $service->id)) ? 'checked' : '' }}
                                class="absolute opacity-0" required>

                            <div class="flex items-start relative z-0">
                                <div class="w-20 h-20 bg-pink-100 rounded flex items-center justify-center text-pink-500">
                                    <i class="fas fa-spa text-2xl"></i>
                                </div>

                                <div class="ml-4 flex-1">
                                    <h4 class="font-semibold">{{ $service->name }}</h4>
                                    <p class="text-gray-600 text-sm mb-2">{{ Str::limit($service->descriptive, 80) }}</p>
                                    <div class="flex items-center justify-between">
                                        @if(isset($service->price))
                                        <span class="text-pink-500 font-semibold">{{ number_format($service->price) }}đ</span>
                                        @endif
                                        @if(isset($service->duration))
                                        <span class="text-gray-500">{{ $service->duration }} phút</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="absolute top-4 right-4 w-5 h-5 border-2 rounded-full flex items-center justify-center service-radio pointer-events-none">
                                <div class="w-3 h-3 bg-pink-500 rounded-full service-radio-dot" style="opacity: {{ (old('service_id') == $service->id || (isset($serviceId) && $serviceId == $service->id)) ? '1' : '0' }};"></div>
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
                             <label for="notes" class="block text-gray-700 mb-2">Ghi chú</label>
                             <textarea name="notes" id="notes" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                                 placeholder="Nhập yêu cầu đặc biệt nếu có...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-10">
                    <button type="submit" class="bg-pink-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-pink-600 transition disabled:opacity-50" id="submit-button" disabled>
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
document.addEventListener('DOMContentLoaded', function() {
    // Lấy các phần tử DOM
    const dateInput = document.getElementById('date_appointments');
    const timeSlotContainer = document.getElementById('time-slots-container');
    const submitButton = document.getElementById('submit-button');
    const submitHint = document.getElementById('submit-hint');

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
    let selectedServiceId = null;
    const checkedRadio = document.querySelector('input[name="service_id"]:checked');
    if (checkedRadio) {
        selectedServiceId = checkedRadio.value;
        updateServiceDisplay(selectedServiceId);
    }

    // Lấy mốc thời gian được chọn (nếu có)
    let selectedTimeSlotId = null;
    const checkedTimeRadio = document.querySelector('input[name="time_appointments_id"]:checked');
    if (checkedTimeRadio) {
        selectedTimeSlotId = checkedTimeRadio.value;
    }

    // Thêm sự kiện change cho các radio button dịch vụ
    document.querySelectorAll('input[name="service_id"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                selectedServiceId = this.value;
                updateServiceDisplay(selectedServiceId);

                // Nếu đã chọn ngày, cập nhật các khung giờ
                if (dateInput.value) {
                    fetchAvailableTimeSlots(selectedServiceId, dateInput.value);
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

        if (!selectedServiceId) {
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
            timeSlotContainer.innerHTML = `
                <div class="col-span-3 text-red-500 p-4 rounded-lg border border-red-200 bg-red-50">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Không thể chọn ngày trong quá khứ. Vui lòng chọn từ ngày hôm nay trở đi.
                </div>
            `;
            return;
        }

        fetchAvailableTimeSlots(selectedServiceId, selectedDate);
    });

    // Hàm lấy các khung giờ có sẵn
    function fetchAvailableTimeSlots(serviceId, date) {
        // Hiển thị loading
        timeSlotContainer.innerHTML = `
            <div class="col-span-3 text-gray-500 flex items-center justify-center py-4">
                <div class="loading-spinner mr-3"></div> Đang kiểm tra khung giờ khả dụng...
            </div>
        `;

        // Gọi API để lấy khung giờ - sử dụng API cũ
        const apiUrl = `/api/check-available-slots?service_id=${serviceId}&date=${date}`;
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

                if (data.success && data.available_slots && data.available_slots.length > 0) {
                    // Hiển thị các khung giờ khả dụng
                    timeSlotContainer.innerHTML = '';

                    data.available_slots.forEach(slot => {
                        const availableText = slot.available_slots > 0
                            ? `<span class="text-xs text-green-600 block mt-1">${slot.available_slots}/${slot.capacity} chỗ trống</span>`
                            : '<span class="text-xs text-red-600 block mt-1">Đã đầy</span>';

                        const timeSlotHTML = `
                            <div class="time-slot-wrapper">
                                <label for="time-input-${slot.id}" class="text-center px-4 py-2 border rounded-lg cursor-pointer hover:border-pink-500 transition time-slot-div block">
                                    ${slot.time}
                                    ${availableText}
                                </label>
                                <input type="radio" name="time_appointments_id" id="time-input-${slot.id}" value="${slot.id}" class="hidden time-radio" required>
                            </div>
                        `;

                        timeSlotContainer.insertAdjacentHTML('beforeend', timeSlotHTML);
                    });

                    // Thêm sự kiện change cho các radio button thời gian
                    document.querySelectorAll('input[name="time_appointments_id"]').forEach(radio => {
                        radio.addEventListener('change', function() {
                            if (this.checked) {
                                selectedTimeSlotId = this.value;

                                // Cập nhật hiển thị
                                document.querySelectorAll('.time-slot-div').forEach(slot => {
                                    slot.classList.remove('bg-pink-500', 'text-white', 'border-pink-500');
                                });

                                // Highlight slot được chọn
                                const label = document.querySelector(`label[for="time-input-${selectedTimeSlotId}"]`);
                                if (label) {
                                    label.classList.add('bg-pink-500', 'text-white', 'border-pink-500');
                                }

                                // Kiểm tra nút Submit
                                checkSubmitButton();
                            }
                        });
                    });
                } else {
                    // Không có khung giờ khả dụng
                    timeSlotContainer.innerHTML = `
                        <div class="col-span-3 text-red-500 p-4 rounded-lg border border-red-200 bg-red-50">
                            <i class="fas fa-times-circle mr-2"></i>
                            Không có khung giờ nào khả dụng cho ngày này. Vui lòng chọn ngày khác.
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Lỗi:', error);
                timeSlotContainer.innerHTML = `
                    <div class="col-span-3 text-red-500 p-4 rounded-lg border border-red-200 bg-red-50">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Đã xảy ra lỗi khi kiểm tra khung giờ: ${error.message}. Vui lòng thử lại sau.
                    </div>
                `;
            });
    }

    // Kiểm tra và cập nhật trạng thái nút Submit
    function checkSubmitButton() {
        const serviceSelected = !!selectedServiceId && document.querySelector('input[name="service_id"]:checked');
        const dateSelected = !!dateInput.value;
        const timeSelected = !!selectedTimeSlotId && document.querySelector('input[name="time_appointments_id"]:checked');

        const allSelected = serviceSelected && dateSelected && timeSelected;

        if (submitButton) {
            submitButton.disabled = !allSelected;
        }

        if (submitHint) {
            submitHint.style.display = allSelected ? 'none' : 'block';
        }
    }

    // Kích hoạt kiểm tra lần đầu
    checkSubmitButton();

    // Nếu đã có cả dịch vụ và ngày được chọn khi trang load, kiểm tra khung giờ
    if (selectedServiceId && dateInput.value) {
        fetchAvailableTimeSlots(selectedServiceId, dateInput.value);
    }
});
</script>
@endpush