<!-- Service Promotion Pop-up -->
<div id="servicePromotionPopup" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <!-- Overlay (only visible when popup is centered) -->
    <div id="popupOverlay" class="absolute inset-0 bg-black bg-opacity-50"></div>

    <!-- Pop-up Content -->
    <div id="popupContent" class="relative bg-pink-600 text-white rounded-lg w-full md:w-1/2 lg:w-1/2 mx-auto overflow-hidden shadow-2xl cursor-move">
        <!-- Minimized View (Hidden by default) -->
        <div id="minimizedView" class="hidden">
            <div class="flex items-center justify-between p-3">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-white bg-opacity-20 flex items-center justify-center mr-3">
                        <i class="fas fa-spa text-white text-sm"></i>
                    </div>
                    <div class="text-sm font-medium" id="minimizedTitle">Khuyến mãi đặc biệt</div>
                </div>
                <div class="flex space-x-1">
                    <button id="expandPopupBtn" class="text-white text-sm w-6 h-6 flex items-center justify-center rounded-full hover:bg-white hover:bg-opacity-20 transition-all">
                        <i class="fas fa-expand-alt"></i>
                    </button>
                    <button id="closeMinimizedBtn" class="text-white text-sm w-6 h-6 flex items-center justify-center rounded-full hover:bg-white hover:bg-opacity-20 transition-all">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Full View -->
        <div id="fullView">
        <!-- Control Buttons -->
        <div class="absolute top-2 right-2 flex space-x-1 z-10">
            <!-- Minimize Button -->
            <button id="minimizePopupBtn" class="text-white text-lg w-7 h-7 flex items-center justify-center rounded-full hover:bg-white hover:bg-opacity-20 transition-all">
                <i class="fas fa-minus"></i>
            </button>

            <!-- Close Button -->
            <button id="closePopupBtn" class="text-white text-lg w-7 h-7 flex items-center justify-center rounded-full hover:bg-white hover:bg-opacity-20 transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Promotion Badge -->
        <div id="promotionBadge" class="absolute top-4 left-0 bg-yellow-500 text-indigo-900 font-bold py-1 px-4 rounded-r-full shadow-md transform -translate-y-1/2 hidden">
            <i class="fas fa-tags mr-1"></i> <span id="promotionBadgeText">GIẢM GIÁ 20%</span>
        </div>

        <!-- Service Image and Content -->
        <div class="flex flex-col">
            <!-- Header with Logo -->
            <div class="bg-pink-700 p-4 flex items-center">
                <div class="w-10 h-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                    <i class="fas fa-spa text-white text-xl"></i>
                </div>
                <div id="popupServiceTitle" class="ml-4 text-xl font-bold uppercase"></div>
            </div>

            <!-- Service Image -->
            <div class="relative">
                <img id="popupServiceImage" src="" alt="Service Promotion" class="w-full object-cover h-48">
                <!-- Overlay for text -->
                <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70"></div>
                <!-- Promotion Info -->
                <div id="promotionInfo" class="absolute bottom-0 left-0 right-0 p-3 text-white">
                    <div id="promotionTitle" class="text-lg font-bold mb-1"></div>
                    <div id="promotionPeriod" class="text-xs opacity-80"></div>
                </div>
            </div>

            <!-- Promotion Price -->
            <div class="p-4 text-center bg-pink-600">
                <div class="text-lg">NHẬN NGAY <span class="font-bold">ƯU ĐÃI ĐẶC BIỆT</span></div>
                <div class="flex items-center justify-center my-3 space-x-3">
                    <span id="popupOriginalPrice" class="text-base line-through text-gray-300"></span>
                    <span id="popupServicePrice" class="text-3xl font-bold text-yellow-300"></span>
                </div>
                <div class="bg-yellow-500 text-pink-900 font-bold py-2 px-4 rounded-lg inline-block mb-3">
                    <i class="fas fa-tags mr-1"></i> <span id="promotionBadgeText2">KHUYẾN MÃI ĐẶC BIỆT</span>
                </div>

                <!-- Registration Form -->
                <form id="promotionForm" class="mt-3 space-y-3 bg-pink-500 p-4 rounded-lg">
                    <input type="hidden" id="serviceId" name="service_id">
                    <div>
                        <input type="text" name="name" placeholder="*Họ và tên:" required
                            class="w-full p-3 text-base rounded-md bg-white text-pink-900 placeholder-pink-400 border-2 border-pink-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition-all">
                    </div>
                    <div>
                        <input type="tel" name="phone" placeholder="*Số điện thoại:" required
                            class="w-full p-3 text-base rounded-md bg-white text-pink-900 placeholder-pink-400 border-2 border-pink-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition-all">
                    </div>
                    <div>
                        <select name="clinic_id" required
                            class="w-full p-3 text-base rounded-md bg-white text-pink-900 border-2 border-pink-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 appearance-none transition-all">
                            <option value="" disabled selected>Chọn chi nhánh gần nhất</option>
                            @foreach($clinics as $clinic)
                                <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex space-x-3 pt-2">
                        <a href="/services"
                            class="w-1/2 py-3 px-4 bg-white hover:bg-gray-100 text-pink-700 text-base font-medium rounded-md transition duration-200 flex items-center justify-center shadow-md">
                            <i class="fas fa-search mr-2"></i> XEM THÊM
                        </a>
                        <button type="submit"
                            class="w-1/2 py-3 px-4 bg-yellow-500 hover:bg-yellow-400 text-pink-900 text-base font-bold rounded-md transition duration-200 flex items-center justify-center shadow-md">
                            <i class="fas fa-calendar-check mr-2"></i> ĐĂNG KÝ
                        </button>
                    </div>
                    <div class="text-sm text-white mt-2 font-medium">
                        * Ưu đãi có hạn, đăng ký ngay hôm nay!
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</div>
