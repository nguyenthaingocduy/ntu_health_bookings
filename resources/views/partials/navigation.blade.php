<!-- Main Navigation -->
<nav class="bg-pink-900 sticky top-0 z-40 font-sans transition-all duration-300 shadow-md">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Desktop Navigation -->
        <div class="flex items-center justify-between h-16 md:h-20">
            <!-- Logo and Brand -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center group">
                    <div class="relative">
                        <span class="absolute -inset-1 rounded-full bg-gradient-to-r from-pink-500/30 to-purple-500/30 blur-md group-hover:blur-xl transition-all duration-300 opacity-70 group-hover:opacity-100"></span>
                        <span class="relative text-3xl font-extrabold bg-gradient-to-r from-pink-400 to-purple-400 bg-clip-text text-transparent">Beauty</span>
                    </div>
                    <span class="text-3xl font-bold text-white ml-1 relative">Salon</span>
                </a>
            </div>

            <!-- Main Menu - Desktop -->
            <div class="hidden md:flex items-center space-x-1">
                <a href="{{ route('home') }}" class="relative px-3 py-2 text-gray-300 hover:text-white transition group mx-1 overflow-hidden rounded-lg">
                    <span class="relative z-10 font-medium">Trang chủ</span>
                    <span class="absolute inset-0 bg-pink-700 scale-0 group-hover:scale-100 transition-transform duration-300 origin-bottom-right rounded-lg"></span>
                </a>
                <a href="{{ route('services.index') }}" class="relative px-3 py-2 text-gray-300 hover:text-white transition group mx-1 overflow-hidden rounded-lg">
                    <span class="relative z-10 font-medium">Dịch vụ</span>
                    <span class="absolute inset-0 bg-pink-700 scale-0 group-hover:scale-100 transition-transform duration-300 origin-bottom-right rounded-lg"></span>
                </a>
                <a href="{{ route('about') }}" class="relative px-3 py-2 text-gray-300 hover:text-white transition group mx-1 overflow-hidden rounded-lg">
                    <span class="relative z-10 font-medium">Giới thiệu</span>
                    <span class="absolute inset-0 bg-pink-700 scale-0 group-hover:scale-100 transition-transform duration-300 origin-bottom-right rounded-lg"></span>
                </a>
                <a href="{{ route('contact') }}" class="relative px-3 py-2 text-gray-300 hover:text-white transition group mx-1 overflow-hidden rounded-lg">
                    <span class="relative z-10 font-medium">Liên hệ</span>
                    <span class="absolute inset-0 bg-pink-700 scale-0 group-hover:scale-100 transition-transform duration-300 origin-bottom-right rounded-lg"></span>
                </a>
            </div>

            <!-- Auth Menu -->
            <div class="flex items-center">
                @guest
                    {{-- <a href="{{ route('login') }}" class="px-4 py-2 text-gray-300 hover:text-white transition font-medium">
                        <span>Đăng nhập</span>
                    </a>
                    <a href="{{ route('register') }}" class="ml-2 sm:ml-4 px-4 sm:px-6 py-2 bg-gradient-to-r from-blue-500 to-teal-500 text-white rounded-lg font-medium hover:shadow-lg hover:shadow-blue-500/20 transition duration-300 transform hover:-translate-y-0.5">
                        <span>Đăng ký</span>
                    </a> --}}
                    <div class="md:hidden flex justify-center space-x-4 py-3 border-t border-pink-700">
                        <a href="{{ route('login') }}" class="px-5 py-2 text-gray-300 border border-pink-700 rounded-lg hover:bg-pink-800 hover:text-white transition whitespace-nowrap text-sm font-medium">
                            <i class="fas fa-sign-in-alt mr-1.5"></i> Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2 bg-gradient-to-r from-pink-500 to-purple-500 text-white rounded-lg shadow-md hover:shadow-lg transition whitespace-nowrap text-sm font-medium">
                            <i class="fas fa-user-plus mr-1.5"></i> Đăng ký
                        </a>
                    </div>
                @else
                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" class="flex items-center px-3 py-2 rounded-lg hover:bg-pink-800 transition-all duration-200 group">
                            <span class="mr-2 font-medium text-gray-300 group-hover:text-white hidden sm:inline-block">{{ Auth::user()->first_name }}</span>
                            <div class="relative">
                                <div class="absolute -inset-0.5 rounded-full bg-gradient-to-r from-pink-500 to-purple-500 opacity-70 group-hover:opacity-100 blur-sm group-hover:blur-md transition-all duration-300"></div>
                                <img class="relative h-9 w-9 rounded-full object-cover border-2 border-pink-800"
                                     src="https://ui-avatars.com/api/?name={{ Auth::user()->first_name }}&background=3b82f6&color=ffffff"
                                     alt="{{ Auth::user()->first_name }}">
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400 group-hover:text-white transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                             class="absolute right-0 mt-3 w-64 bg-pink-800 rounded-xl shadow-xl py-2 z-50 border border-pink-700 overflow-hidden"
                             style="display: none;">
                            <div class="px-4 py-3 border-b border-pink-700 bg-gradient-to-r from-pink-900/50 to-purple-900/50">
                                <p class="text-sm text-gray-400">Xin chào</p>
                                <p class="font-bold text-white">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                            </div>
                            <div class="py-2">
                                <a href="{{ route('customer.dashboard') }}" class="flex items-center px-4 py-2.5 text-gray-300 hover:bg-pink-700 hover:text-white transition-all duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-pink-900/50 flex items-center justify-center mr-3">
                                        <i class="fas fa-tachometer-alt text-pink-400"></i>
                                    </div>
                                    <span class="font-medium">Trang cá nhân</span>
                                </a>
                                <a href="{{ route('customer.appointments.index') }}" class="flex items-center px-4 py-2.5 text-gray-300 hover:bg-pink-700 hover:text-white transition-all duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-pink-900/50 flex items-center justify-center mr-3">
                                        <i class="fas fa-calendar-alt text-pink-400"></i>
                                    </div>
                                    <span class="font-medium">Lịch hẹn</span>
                                </a>
                                <a href="{{ route('customer.profile.show') }}" class="flex items-center px-4 py-2.5 text-gray-300 hover:bg-pink-700 hover:text-white transition-all duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-pink-900/50 flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-pink-400"></i>
                                    </div>
                                    <span class="font-medium">Hồ sơ</span>
                                </a>
                            </div>
                            <div class="mt-2 pt-2 border-t border-pink-700 bg-pink-900">
                                <form action="{{ route('logout') }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center text-left px-4 py-2.5 text-red-400 hover:bg-pink-700 transition-all duration-200">
                                        <div class="w-8 h-8 rounded-lg bg-red-900/30 flex items-center justify-center mr-3">
                                            <i class="fas fa-sign-out-alt text-red-400"></i>
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
            <a href="{{ route('home') }}" class="flex-shrink-0 px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-500 text-white rounded-lg shadow-md shadow-pink-500/20 hover:shadow-lg hover:shadow-pink-500/30 transition whitespace-nowrap text-sm font-medium">
                <i class="fas fa-home mr-1.5"></i> Trang chủ
            </a>
            <a href="{{ route('services.index') }}" class="flex-shrink-0 px-4 py-2 bg-pink-800 text-gray-300 rounded-lg shadow-sm border border-pink-700 hover:bg-pink-700 hover:text-white transition whitespace-nowrap text-sm font-medium">
                <i class="fas fa-spa mr-1.5"></i> Dịch vụ
            </a>
            <a href="{{ route('about') }}" class="flex-shrink-0 px-4 py-2 bg-pink-800 text-gray-300 rounded-lg shadow-sm border border-pink-700 hover:bg-pink-700 hover:text-white transition whitespace-nowrap text-sm font-medium">
                <i class="fas fa-info-circle mr-1.5"></i> Giới thiệu
            </a>
            <a href="{{ route('contact') }}" class="flex-shrink-0 px-4 py-2 bg-pink-800 text-gray-300 rounded-lg shadow-sm border border-pink-700 hover:bg-pink-700 hover:text-white transition whitespace-nowrap text-sm font-medium">
                <i class="fas fa-envelope mr-1.5"></i> Liên hệ
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
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    background: rgba(157, 23, 77, 0.8);
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