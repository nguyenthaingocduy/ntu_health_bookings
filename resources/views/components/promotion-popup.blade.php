<!-- Modern Promotion Pop-up -->
<div id="promotionPopup" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <!-- Backdrop with blur effect -->
    <div id="promotionOverlay" onclick="hidePromotionPopup()" class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>

    <!-- Pop-up Content -->
    <div id="promotionContent" class="relative bg-white rounded-2xl w-11/12 md:w-2/3 lg:w-1/2 xl:w-2/5 max-w-lg mx-auto overflow-hidden shadow-2xl transform transition-all duration-300 scale-95 hover:scale-100 max-h-[90vh] overflow-y-auto">

        <!-- Close Button -->
        <button type="button" id="closePromotionBtn" onclick="hidePromotionPopup()"
                class="absolute top-4 right-4 z-20 w-8 h-8 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full flex items-center justify-center shadow-lg transition-all duration-200 hover:scale-110 group">
            <svg class="w-4 h-4 text-gray-600 group-hover:text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Header Section - Optimized Colors -->
        <div class="relative bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-6 text-gray-800 overflow-hidden border-b border-gray-200">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-pink-200 to-purple-200 opacity-30 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-br from-blue-200 to-indigo-200 opacity-30 rounded-full translate-y-12 -translate-x-12"></div>

            <!-- Content -->
            <div class="relative z-10">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-rose-500 rounded-full flex items-center justify-center mr-4 shadow-lg">
                        <svg class="w-6 h-6 text-yellow" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">🌸 <span class="text-pink-600">Beauty</span> <span class="text-purple-600">Salon</span></h2>
                        <p class="text-gray-600 text-sm font-medium">Ưu đãi đặc biệt dành cho bạn</p>
                    </div>
                </div>

                <!-- Special offer badge -->
                {{-- <div class="inline-flex items-center bg-gradient-to-r from-amber-400 to-orange-400 text-black px-4 py-2 rounded-full text-sm font-semibold shadow-md">
                    <svg class="w-2 h-2 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    ✨ Ưu đãi đặc biệt ✨
                </div> --}}
            </div>
        </div>

        <!-- Content Section -->
        <div class="p-6">
            <!-- Promotion Details -->
            <div id="promotionDetails" class="space-y-4">
                <!-- Dynamic content will be inserted here -->
            </div>

            <!-- Additional Information - Compact -->
            {{-- <div class="mt-4 space-y-3">
                <!-- Contact Information - Compact -->
                <div class="bg-gradient-to-r from-pink-50 to-rose-50 p-3 rounded-lg border border-pink-200">
                    <div class="text-sm text-gray-700 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="font-medium">📞 Hotline:</span>
                            <span class="text-pink-600 font-semibold">0123-456-789</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-medium">📧 Email:</span>
                            <span class="text-pink-600 font-semibold text-xs">ntuhealthbooking@gmail.com</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-medium">🕐 Giờ mở:</span>
                            <span class="font-semibold">8:00 - 20:00 (Hàng ngày)</span>
                        </div>
                    </div>
                </div>

                <!-- Terms & Conditions - Compact -->
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-3 rounded-lg border border-gray-200">
                    <div class="text-xs text-gray-600 space-y-1" id="promotionTerms">
                        <div>• Áp dụng cho tất cả khách hàng đã đăng ký</div>
                        <div>• Không áp dụng đồng thời với ưu đãi khác</div>
                        <div>• Chỉ áp dụng khi đặt lịch trực tuyến</div>
                    </div>
                </div>
            </div> --}}
        </div>

        <!-- Footer Section - Improved Contrast -->
        <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-7 py-3 border-t border-gray-200">
            <!-- Expiry info -->
            {{-- <div class="flex items-center justify-center text-sm text-gray-700 bg-white px-3 py-2 rounded-lg shadow-sm border mb-4">
                <svg class="w-2 h-2 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span id="promotionExpiry" class="font-medium">Ưu đãi có hạn</span>
            </div> --}}

            <!-- Action buttons -->
            <div class="flex flex-col space-y-3">
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="/admin/promotions"
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                        <svg class="w-2 h-2 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Quản lý khuyến mãi
                    </a>
                @else
                    <a href="/customer/appointments/create"
                       class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-pink-500 to-rose-500 text-black text-sm font-bold rounded-lg hover:from-pink-600 hover:to-rose-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                    
                        🎯 Đặt lịch ngay
                    </a>
                @endif

                <button type="button" onclick="hidePromotionPopup()"
                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-white text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200 border border-gray-300 hover:border-gray-400 shadow-sm ">
                    <svg class="w-2 h-2 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Để sau
                </button>
            </div>
        </div>
    </div>
</div>
