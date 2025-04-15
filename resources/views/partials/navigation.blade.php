<!-- Main Navigation -->
<nav class="bg-gradient-to-r from-white to-pink-50 shadow-lg sticky top-0 z-40 font-sans transition-all duration-300 backdrop-blur-md">
    <div class="container mx-auto px-6">
        <!-- Desktop Navigation -->
        <div class="flex items-center justify-between h-20">
            <!-- Logo and Brand -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    <span class="text-3xl font-extrabold bg-gradient-to-r from-pink-500 to-purple-600 bg-clip-text text-transparent">NTU</span>
                    <span class="text-3xl font-bold text-gray-800 ml-1">Health</span>
                </a>
            </div>

            <!-- Main Menu - Desktop -->
            <div class="hidden md:flex items-center space-x-1">
                <a href="{{ route('home') }}" class="relative px-5 py-2 text-gray-700 hover:text-pink-600 transition group mx-1">
                    <span>Trang chủ</span>
                    <span class="absolute inset-x-0 bottom-0 h-0.5 bg-gradient-to-r from-pink-500 to-purple-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                </a>
                <a href="{{ route('services.index') }}" class="relative px-5 py-2 text-gray-700 hover:text-pink-600 transition group mx-1">
                    <span>Dịch vụ</span>
                    <span class="absolute inset-x-0 bottom-0 h-0.5 bg-gradient-to-r from-pink-500 to-purple-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                </a>
                <a href="{{ route('about') }}" class="relative px-5 py-2 text-gray-700 hover:text-pink-600 transition group mx-1">
                    <span>Giới thiệu</span>
                    <span class="absolute inset-x-0 bottom-0 h-0.5 bg-gradient-to-r from-pink-500 to-purple-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                </a>
                <a href="{{ route('contact') }}" class="relative px-5 py-2 text-gray-700 hover:text-pink-600 transition group mx-1">
                    <span>Liên hệ</span>
                    <span class="absolute inset-x-0 bottom-0 h-0.5 bg-gradient-to-r from-pink-500 to-purple-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                </a>
                {{-- <a href="{{ route('services.index') }}?category=phun-xam" class="relative px-5 py-2 text-gray-700 hover:text-pink-600 transition group mx-1">
                    <span>Phun xăm</span>
                    <span class="absolute inset-x-0 bottom-0 h-0.5 bg-gradient-to-r from-pink-500 to-purple-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                </a>
                <a href="{{ route('services.index') }}?category=dieu-tri-da" class="relative px-5 py-2 text-gray-700 hover:text-pink-600 transition group mx-1">
                    <span>Điều trị da</span>
                    <span class="absolute inset-x-0 bottom-0 h-0.5 bg-gradient-to-r from-pink-500 to-purple-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                </a>
                <a href="{{ route('services.index') }}?category=giam-mo" class="relative px-5 py-2 text-gray-700 hover:text-pink-600 transition group mx-1">
                    <span>Giảm mỡ</span>
                    <span class="absolute inset-x-0 bottom-0 h-0.5 bg-gradient-to-r from-pink-500 to-purple-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                </a>
                <a href="{{ route('services.index') }}?category=triet-long" class="relative px-5 py-2 text-gray-700 hover:text-pink-600 transition group mx-1">
                    <span>Triệt lông</span>
                    <span class="absolute inset-x-0 bottom-0 h-0.5 bg-gradient-to-r from-pink-500 to-purple-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                </a> --}}
            </div>
            
            <!-- Auth Menu -->
            <div class="flex items-center">
                @guest
                    <a href="{{ route('login') }}" class="px-5 py-2 text-pink-600 hover:text-pink-700 transition font-medium">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="ml-4 px-6 py-2.5 bg-white text-pink-600 border border-pink-100 rounded-full font-medium hover:shadow-md shadow-sm hover:bg-gray-50 transition">Đăng ký</a>
                @else
                    <!-- Profile Dropdown (Sử dụng JS để hoạt động tốt hơn) -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" class="flex items-center px-5 py-2 text-gray-700 hover:text-pink-600 transition">
                            <span class="mr-2 font-medium">{{ Auth::user()->first_name }}</span>
                            <img class="h-9 w-9 rounded-full object-cover border-2 border-pink-200 hover:border-pink-400 transition-colors" 
                                 src="https://ui-avatars.com/api/?name={{ Auth::user()->first_name }}&background=f9a8d4&color=ffffff" 
                                 alt="{{ Auth::user()->first_name }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <!-- Dropdown menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-51 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg py-2 z-50 border border-gray-100"
                             style="display: none;">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm text-gray-500">Xin chào</p>
                                <p class="font-bold text-gray-800">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                            </div>
                            <a href="{{ route('customer.dashboard') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition">
                                <i class="fas fa-tachometer-alt w-6 text-pink-500"></i>
                                <span>Trang cá nhân</span>
                            </a>
                            <a href="{{ route('customer.appointments.index') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition">
                                <i class="fas fa-calendar-alt w-6 text-pink-500"></i>
                                <span>Lịch hẹn</span>
                            </a>
                            <a href="{{ route('customer.profile.show') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition">
                                <i class="fas fa-user w-6 text-pink-500"></i>
                                <span>Hồ sơ</span>
                            </a>
                            <hr class="my-1 border-gray-100">
                            <form action="{{ route('logout') }}" method="POST" class="block">
                                @csrf
                                <button type="submit" class="w-full flex items-center text-left px-4 py-2.5 text-red-600 hover:bg-red-50 transition">
                                    <i class="fas fa-sign-out-alt w-6"></i>
                                    <span>Đăng xuất</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
        
        <!-- Mobile Categories -->
        <div class="md:hidden py-3 -mt-1 overflow-x-auto flex space-x-2 scrollbar-hide">
            <a href="{{ route('home') }}" class="flex-shrink-0 px-4 py-1.5 bg-gradient-to-r from-pink-100 to-purple-100 text-pink-600 rounded-full border border-pink-200 hover:shadow-md hover:border-pink-300 transition whitespace-nowrap text-sm font-medium">
                Trang chủ
            </a>
            <a href="{{ route('services.index') }}" class="flex-shrink-0 px-4 py-1.5 bg-gradient-to-r from-pink-100 to-purple-100 text-pink-600 rounded-full border border-pink-200 hover:shadow-md hover:border-pink-300 transition whitespace-nowrap text-sm font-medium">
                Dịch vụ
            </a>
            <a href="{{ route('about') }}" class="flex-shrink-0 px-4 py-1.5 bg-gradient-to-r from-pink-100 to-purple-100 text-pink-600 rounded-full border border-pink-200 hover:shadow-md hover:border-pink-300 transition whitespace-nowrap text-sm font-medium">
                Giới thiệu
            </a>
            <a href="{{ route('contact') }}" class="flex-shrink-0 px-4 py-1.5 bg-gradient-to-r from-pink-100 to-purple-100 text-pink-600 rounded-full border border-pink-200 hover:shadow-md hover:border-pink-300 transition whitespace-nowrap text-sm font-medium">
                Liên hệ
            </a>
            {{-- <a href="{{ route('services.index') }}?category=phun-xam" class="flex-shrink-0 px-4 py-1.5 bg-gradient-to-r from-pink-100 to-purple-100 text-pink-600 rounded-full border border-pink-200 hover:shadow-md hover:border-pink-300 transition whitespace-nowrap text-sm font-medium">
                Phun xăm
            </a>
            <a href="{{ route('services.index') }}?category=dieu-tri-da" class="flex-shrink-0 px-4 py-1.5 bg-gradient-to-r from-pink-100 to-purple-100 text-pink-600 rounded-full border border-pink-200 hover:shadow-md hover:border-pink-300 transition whitespace-nowrap text-sm font-medium">
                Điều trị da
            </a>
            <a href="{{ route('services.index') }}?category=giam-mo" class="flex-shrink-0 px-4 py-1.5 bg-gradient-to-r from-pink-100 to-purple-100 text-pink-600 rounded-full border border-pink-200 hover:shadow-md hover:border-pink-300 transition whitespace-nowrap text-sm font-medium">
                Giảm mỡ
            </a>
            <a href="{{ route('services.index') }}?category=triet-long" class="flex-shrink-0 px-4 py-1.5 bg-gradient-to-r from-pink-100 to-purple-100 text-pink-600 rounded-full border border-pink-200 hover:shadow-md hover:border-pink-300 transition whitespace-nowrap text-sm font-medium">
                Triệt lông
            </a> --}}
        </div>
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
    background: rgba(255, 255, 255, 0.8);
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