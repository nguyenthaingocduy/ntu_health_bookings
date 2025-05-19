<!-- Promotion Pop-up -->
<div id="promotionPopup" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <!-- Overlay - Blocks interaction with main interface -->
    <div id="promotionOverlay" onclick="hidePromotionPopup()" class="absolute inset-0 bg-white bg-opacity-70"></div>

    <!-- Pop-up Content -->
    <div id="promotionContent" class="relative bg-gradient-to-br from-pink-600 to-purple-800 rounded-xl w-full md:w-1/2 lg:w-1/2 h-auto max-h-1/2 mx-auto overflow-hidden shadow-2xl border-2 border-pink-300">
        <!-- Close Button -->
        <div class="absolute top-3 right-3 z-10">
            <button type="button" id="closePromotionBtn" onclick="hidePromotionPopup()" class="bg-pink text-pink-600 hover:bg-pink-100 w-10 h-10 flex items-center justify-center rounded-full shadow-lg transition-all duration-200 transform hover:scale-110 border-2 border-pink-300 hover:border-pink-400">
                <span class="text-2xl font-bold">×</span>
            </button>
        </div>

        <!-- Decorative Elements -->
        <div class="absolute top-0 left-0 w-20 h-20 bg-yellow-400 rounded-br-full opacity-20"></div>
        <div class="absolute bottom-0 right-0 w-32 h-32 bg-pink-300 rounded-tl-full opacity-20"></div>

        <!-- Header with Logo and Image -->
        <div class="flex items-center p-4 bg-gradient-to-r from-pink-700 to-purple-900">
            <div class="mr-3 bg-white rounded-full p-2 shadow-md">
                <img src="/storage/logo/beauty-icon.png" alt="Beauty Icon" class="w-10 h-10" onerror="this.src='https://img.icons8.com/color/96/spa-flower.png'">
            </div>
            <h2 id="salonName" class="text-3xl font-bold">
                <span class="text-pink">Beauty</span><span class="text-yellow-300">Salon</span>
            </h2>
        </div>

        <!-- Content Area with Image -->
        <div class="flex flex-col md:flex-row">
            <!-- Left Content -->
            <div class="md:w-3/5 p-6">
                <!-- Promotion Content -->
                <div id="promotionDetails" class="space-y-4 text-black">
                    <!-- Promotion items will be inserted here dynamically -->
                </div>
            </div>

            <!-- Right Image -->
            <div class="md:w-2/5 relative hidden md:block">
                <img id="promotionImage" src="" alt="Beauty Treatment" class="h-full w-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-pink-600 to-transparent opacity-60"></div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="p-5 bg-gradient-to-r from-pink-700 to-purple-900">
            <div class="flex justify-between items-center">
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="/admin/promotions" class="inline-block bg-blue-500 text-white font-bold py-3 px-8 rounded-full hover:bg-blue-400 transition duration-300 shadow-lg transform hover:scale-105">
                        Quản lý khuyến mãi
                    </a>
                @else
                    <a href="/services" class="inline-block bg-white text-pink-900 font-bold py-3 px-8 rounded-full hover:bg-gray-100 transition duration-300 shadow-lg transform hover:scale-105">
                        Xem dịch vụ ngay
                    </a>
                @endif
                <div class="flex items-center space-x-3">
                    <p id="promotionExpiry" class="text-white text-sm bg-pink-800 py-2 px-2 rounded-full"></p>
                    <button type="button" onclick="hidePromotionPopup()" class="bg-white text-pink-600 hover:bg-pink-100 py-1 px-3 rounded-full text-sm font-medium border border-pink-300 hover:border-pink-400 transition-all duration-200">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
