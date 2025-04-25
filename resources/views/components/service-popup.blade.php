<!-- Service Promotion Pop-up -->
<div id="servicePromotionPopup" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    
    <!-- Pop-up Content -->
    <div class="relative bg-indigo-900 text-white rounded-lg w-full max-w-md mx-4 overflow-hidden">
        <!-- Close Button -->
        <button id="closePopupBtn" class="absolute top-2 right-2 text-white text-2xl z-10 w-8 h-8 flex items-center justify-center rounded-full hover:bg-white hover:bg-opacity-20">
            <i class="fas fa-times"></i>
        </button>
        
        <!-- Service Image and Content -->
        <div class="flex flex-col">
            <!-- Header with Logo -->
            <div class="bg-indigo-800 p-4 flex items-center">
                <img src="{{ asset('images\employees\1745472719_6809cccfb74d9.jpg') }}" alt="Beauty Center Logo" class="w-10 h-10 object-cover opacity-50">
                <div id="popupServiceTitle" class="ml-4 text-xl font-bold uppercase"></div>
            </div>
            
            <!-- Service Image -->
            <div class="relative">
                <img id="popupServiceImage" src="" alt="Service Promotion" class="w-full object-cover h-64">
            </div>
            
            <!-- Promotion Price -->
            <div class="p-4 text-center">
                <div class="text-lg">NHẬN NGAY <span class="font-bold">suất ưu đãi</span></div>
                <div class="text-3xl font-bold my-2">
                    CHỈ VỚI <span id="popupServicePrice" class="text-yellow-300"></span> Đ/SUẤT
                </div>
                
                <!-- Registration Form -->
                <form id="promotionForm" class="mt-4 space-y-3">
                    <input type="hidden" id="serviceId" name="service_id">
                    <div>
                        <input type="text" name="name" placeholder="*Họ và tên:" required
                            class="w-full p-3 rounded-md bg-indigo-700 text-white placeholder-indigo-300 border border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <input type="tel" name="phone" placeholder="*Số điện thoại:" required
                            class="w-full p-3 rounded-md bg-indigo-700 text-white placeholder-indigo-300 border border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <select name="clinic_id" required
                            class="w-full p-3 rounded-md bg-indigo-700 text-white border border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 appearance-none">
                            <option value="" disabled selected>Chọn chi nhánh gần nhất</option>
                            @foreach($clinics as $clinic)
                                <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" 
                            class="w-full py-3 px-4 bg-yellow-500 hover:bg-yellow-600 text-indigo-900 font-bold rounded-md transition duration-200 uppercase">
                            ĐĂNG KÝ NGAY
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
