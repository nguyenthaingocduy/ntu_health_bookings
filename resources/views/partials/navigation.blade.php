<!-- Main Navigation -->
<nav class="bg-white sticky top-0 z-40 font-sans transition-all duration-300 shadow-md">
    <!-- Top Header with Gradient -->
    <div class="bg-gradient-to-r from-pink-500 to-purple-600 py-2 px-4 sm:px-6 lg:px-8">
        <div class="container mx-auto flex justify-end items-center space-x-4 text-black text-sm">
            <div class="flex items-center">
                <i class="fas fa-phone-alt mr-1.5"></i>
                <span>+84 123 456 789</span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-envelope mr-1.5"></i>
                <span>ntuhealthbooking@gmail.com</span>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 border-b border-gray-100">
        <!-- Desktop Navigation -->
        <div class="flex items-center justify-between h-16 md:h-20">
            <!-- Logo and Brand -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center group">
                    <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg shadow-lg flex items-center justify-center mr-2">
                        <i class="fas fa-spa text-black text-xl"></i>
                    </div>
                    <div>
                        <span class="text-2xl md:text-3xl font-extrabold bg-gradient-to-r from-pink-500 to-purple-600 bg-clip-text text-transparent leading-none">Beauty</span>
                        <span class="text-2xl md:text-3xl font-bold text-gray-800 ml-1 leading-none">Salon</span>
                        <div class="text-xs text-gray-500 mt-0.5">Nha Trang University</div>
                    </div>
                </a>
            </div>

            <!-- Main Menu - Desktop -->
            <div class="hidden md:flex items-center space-x-3">
                <a href="{{ route('home') }}" style="color: #374151 !important;" class="group relative overflow-hidden px-5 py-2.5 rounded-md bg-black shadow-sm hover:text-black transition-all duration-300 ease-out">
                    <span class="absolute inset-0 bg-black from-pink-500 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                    <span class="relative flex items-center justify-center font-medium">
                        <i class="fas fa-home mr-2 text-pink-500 group-hover:text-black transition-colors"></i> <span class="group-hover:text-black">Trang chủ</span>
                    </a>
                </span>
                <a href="{{ route('services.index') }}" style="color: #374151 !important;" class="group relative overflow-hidden px-5 py-2.5 rounded-md bg-white shadow-sm hover:text-white transition-all duration-300 ease-out">
                    <span class="absolute inset-0 bg-gradient-to-r from-pink-500 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                    <span class="relative flex items-center justify-center font-medium">
                        <i class="fas fa-spa mr-2 text-pink-500 group-hover:text-white transition-colors"></i> <span class="group-hover:text-white">Dịch vụ</span>
                    </span>
                </a>
                <a href="{{ route('about') }}" style="color: #374151 !important;" class="group relative overflow-hidden px-5 py-2.5 rounded-md bg-white shadow-sm hover:text-white transition-all duration-300 ease-out">
                    <span class="absolute inset-0 bg-gradient-to-r from-pink-500 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                    <span class="relative flex items-center justify-center font-medium">
                        <i class="fas fa-info-circle mr-2 text-pink-500 group-hover:text-white transition-colors"></i> <span class="group-hover:text-white">Giới thiệu</span>
                    </span>
                </a>
                <a href="{{ route('contact') }}" style="color: #374151 !important;" class="group relative overflow-hidden px-5 py-2.5 rounded-md bg-white shadow-sm hover:text-white transition-all duration-300 ease-out">
                    <span class="absolute inset-0 bg-gradient-to-r from-pink-500 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                    <span class="relative flex items-center justify-center font-medium">
                        <i class="fas fa-envelope mr-2 text-pink-500 group-hover:text-white transition-colors"></i> <span class="group-hover:text-white">Liên hệ</span>
                    </span>
                </a>
            </div>

            <!-- Auth Menu -->
            <div class="flex items-center">
                @guest
                    <div class="hidden md:flex items-center">
                        <a href="{{ route('login') }}" class="px-4 py-2 text-gray-700 hover:text-pink-600 transition font-medium">
                            <i class="fas fa-sign-in-alt mr-1.5"></i> Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="ml-2 sm:ml-4 px-4 sm:px-6 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg font-medium hover:shadow-lg hover:shadow-pink-500/20 transition duration-300 transform hover:-translate-y-0.5">
                            <i class="fas fa-user-plus mr-1.5"></i> Đăng ký
                        </a>
                    </div>
                    <div class="md:hidden flex justify-center space-x-4 py-3 border-t border-gray-100">
                        <a href="{{ route('login') }}" class="px-5 py-2 text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-pink-600 transition whitespace-nowrap text-sm font-medium">
                            <i class="fas fa-sign-in-alt mr-1.5"></i> Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2 text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-pink-600 transition whitespace-nowrap text-sm font-medium">
                            <i class="fas fa-user-plus mr-1.5"></i> Đăng ký
                        </a>
                    </div>
                @else
                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-all duration-200 group">
                            <span class="mr-2 font-medium text-gray-700 group-hover:text-pink-600 hidden sm:inline-block">{{ Auth::user()->first_name }}</span>
                            <div class="relative">
                                <div class="absolute -inset-0.5 rounded-full bg-gradient-to-r from-pink-300 to-purple-300 opacity-70 group-hover:opacity-100 blur-sm group-hover:blur-md transition-all duration-300"></div>
                                <img class="relative h-9 w-9 rounded-full object-cover border-2 border-white shadow-sm"
                                     src="https://ui-avatars.com/api/?name={{ Auth::user()->first_name }}&background=f472b6&color=ffffff"
                                     alt="{{ Auth::user()->first_name }}">
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-500 group-hover:text-pink-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute right-0 mt-3 w-64 bg-white rounded-xl shadow-lg py-2 z-50 border border-gray-100 overflow-hidden"
                             style="display: none;">
                            <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-pink-50 to-purple-50">
                                <p class="text-sm text-gray-500">Xin chào</p>
                                <p class="font-bold text-gray-800">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                            </div>
                            <div class="py-2">
                                @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-all duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-user-shield text-purple-500"></i>
                                    </div>
                                    <span class="font-medium">Quản trị hệ thống</span>
                                </a>
                                @endif

                                @if(Auth::user()->isReceptionist())
                                <a href="{{ route('le-tan.dashboard') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-all duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-user-tie text-blue-500"></i>
                                    </div>
                                    <span class="font-medium">Lễ tân</span>
                                </a>
                                @endif

                                @if(Auth::user()->isTechnician())
                                <a href="{{ route('nvkt.dashboard') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-all duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-user-md text-green-500"></i>
                                    </div>
                                    <span class="font-medium">Kỹ thuật viên</span>
                                </a>
                                @endif

                                @if(Auth::user()->isStaff())
                                <a href="{{ route('staff.dashboard') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-all duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-user-tie text-yellow-500"></i>
                                    </div>
                                    <span class="font-medium">Nhân viên</span>
                                </a>
                                @endif

                                <a href="{{ route('customer.dashboard') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-all duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-pink-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-tachometer-alt text-pink-500"></i>
                                    </div>
                                    <span class="font-medium">Trang cá nhân</span>
                                </a>
                                <a href="{{ route('customer.appointments.index') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-all duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-pink-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-calendar-alt text-pink-500"></i>
                                    </div>
                                    <span class="font-medium">Lịch hẹn</span>
                                </a>
                                <a href="{{ route('customer.profile.show') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-all duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-pink-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-pink-500"></i>
                                    </div>
                                    <span class="font-medium">Hồ sơ</span>
                                </a>
                            </div>
                            <div class="mt-2 pt-2 border-t border-gray-100 bg-gray-50">
                                <form action="{{ route('logout') }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center text-left px-4 py-2.5 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                                        <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center mr-3">
                                            <i class="fas fa-sign-out-alt text-red-500"></i>
                                        </div>
                                        <span class="font-medium">Đăng xuất</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden py-3 overflow-x-auto flex space-x-3 scrollbar-hide">
            <a href="{{ route('home') }}" class="flex-shrink-0 px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-black rounded-md shadow-sm hover:shadow-md transition whitespace-nowrap text-sm font-medium flex items-center justify-center">
                <i class="fas fa-home mr-1.5 text-pink-500 m-2"></i> Trang chủ
            </a>
            <a href="{{ route('services.index') }}" class="flex-shrink-0 px-4 py-2 bg-white text-gray-700 rounded-md shadow-sm hover:bg-pink-50 hover:text-pink-600 transition whitespace-nowrap text-sm font-medium flex items-center justify-center">
                <i class="fas fa-spa mr-1.5 text-pink-500 m-2"></i> Dịch vụ
            </a>
            <a href="{{ route('about') }}" class="flex-shrink-0 px-4 py-2 bg-white text-gray-700 rounded-md shadow-sm hover:bg-pink-50 hover:text-pink-600 transition whitespace-nowrap text-sm font-medium flex items-center justify-center">
                <i class="fas fa-info-circle mr-1.5 text-pink-500 m-2"></i> Giới thiệu
            </a>
            <a href="{{ route('contact') }}" class="flex-shrink-0 px-4 py-2 bg-white text-gray-700 rounded-md shadow-sm hover:bg-pink-50 hover:text-pink-600 transition whitespace-nowrap text-sm font-medium flex items-center justify-center">
                <i class="fas fa-envelope mr-1.5 text-pink-500 m-2"></i> Liên hệ
            </a>
        </div>

        <!-- Mobile Auth Buttons (Only show if not logged in) -->

    </div>
</nav>

<!-- Add this CSS and JS for dropdown -->
<style>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* CSS cho hiệu ứng glass effect */
.backdrop-blur-md {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

nav.scrolled {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

nav.scrolled .bg-gradient-to-r {
    padding-top: 0;
    padding-bottom: 0;
    height: 0;
    overflow: hidden;
    transition: all 0.3s ease;
}

nav.scrolled .container {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

/* Fix z-index issues */
.relative {
    position: relative;
}

/* Tăng z-index cho dropdown menu */
[x-show="open"] {
    z-index: 999 !important;
    position: absolute;
    top: 100%;
    right: 0;
}

/* Đảm bảo dropdown menu luôn hiển thị khi mở */
.dropdown-visible {
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Kiểm tra Alpine.js đã được load hay chưa
    if (typeof window.Alpine === 'undefined') {
        // Nếu chưa, load Alpine.js
        var script = document.createElement('script');
        script.src = 'https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js';
        script.defer = true;
        document.head.appendChild(script);
    }

    // Hiệu ứng thay đổi header khi cuộn
    window.addEventListener('scroll', function() {
        const nav = document.querySelector('nav');
        if (window.scrollY > 10) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });

    // Fix cho trường hợp Alpine.js không hoạt động
    setTimeout(function() {
        if (typeof window.Alpine === 'undefined' || document.querySelectorAll('[x-data]').length > 0) {
            // Xử lý dropdown bằng vanilla JS
            document.querySelectorAll('[x-data]').forEach(function(el) {
                const dropdownButton = el.querySelector('button');
                const dropdownMenu = el.querySelector('[x-show]');

                if (dropdownButton && dropdownMenu) {
                    dropdownButton.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const isOpen = dropdownMenu.classList.contains('dropdown-visible');

                        if (isOpen) {
                            dropdownMenu.classList.remove('dropdown-visible');
                            dropdownMenu.style.display = 'none';
                        } else {
                            dropdownMenu.classList.add('dropdown-visible');
                            dropdownMenu.style.display = 'block';
                            dropdownMenu.style.zIndex = '999';
                        }
                    });

                    document.addEventListener('click', function(e) {
                        if (!el.contains(e.target)) {
                            dropdownMenu.classList.remove('dropdown-visible');
                            dropdownMenu.style.display = 'none';
                        }
                    });
                }
            });
        }
    }, 500); // Đợi 500ms để đảm bảo trang đã load hoàn toàn
});
</script>