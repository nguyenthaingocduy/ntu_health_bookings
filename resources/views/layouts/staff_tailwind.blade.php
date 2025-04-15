<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hệ thống đăng ký khám sức khỏe - Trường Đại học Nha Trang')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @yield('styles')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <div x-data="{ mobileMenuOpen: false, userMenuOpen: false }">
        <!-- Top Navigation -->
        <nav class="bg-primary-600 text-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('staff.dashboard') }}" class="flex items-center">
                                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto mr-2">
                                <span class="font-semibold text-lg">Đăng ký khám sức khỏe - CBVC</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex md:items-center md:space-x-4">
                        <a href="{{ route('staff.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('staff.dashboard') ? 'bg-primary-700 text-white' : 'text-white hover:bg-primary-500' }}">
                            <i class="fas fa-home mr-1"></i> Trang chủ
                        </a>
                        <a href="{{ route('staff.appointments.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('staff.appointments.*') ? 'bg-primary-700 text-white' : 'text-white hover:bg-primary-500' }}">
                            <i class="fas fa-calendar-check mr-1"></i> Lịch khám
                        </a>
                        <a href="{{ route('staff.work-schedule') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('staff.work-schedule') ? 'bg-primary-700 text-white' : 'text-white hover:bg-primary-500' }}">
                            <i class="fas fa-calendar-week mr-1"></i> Lịch làm việc
                        </a>
                        <a href="{{ route('staff.statistics') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('staff.statistics') ? 'bg-primary-700 text-white' : 'text-white hover:bg-primary-500' }}">
                            <i class="fas fa-chart-bar mr-1"></i> Thống kê
                        </a>
                        
                        <!-- User Dropdown -->
                        <div class="ml-3 relative" x-data="{ open: false }">
                            <div>
                                <button @click="open = !open" class="flex items-center text-sm font-medium text-white hover:text-gray-200 focus:outline-none">
                                    <i class="fas fa-user-circle text-xl mr-1"></i>
                                    <span>{{ Auth::user()->first_name }}</span>
                                    <i class="fas fa-chevron-down ml-1"></i>
                                </button>
                            </div>
                            
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                 x-cloak>
                                <div class="py-1">
                                    <a href="{{ route('staff.profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2"></i> Thông tin cá nhân
                                    </a>
                                    <a href="{{ route('staff.profile.change-password') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-key mr-2"></i> Đổi mật khẩu
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile menu button -->
                    <div class="flex items-center md:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-primary-500 focus:outline-none">
                            <i class="fas" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div x-show="mobileMenuOpen" class="md:hidden bg-primary-700" x-cloak>
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="{{ route('staff.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('staff.dashboard') ? 'bg-primary-800 text-white' : 'text-white hover:bg-primary-500' }}">
                        <i class="fas fa-home mr-2"></i> Trang chủ
                    </a>
                    <a href="{{ route('staff.appointments.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('staff.appointments.*') ? 'bg-primary-800 text-white' : 'text-white hover:bg-primary-500' }}">
                        <i class="fas fa-calendar-check mr-2"></i> Lịch khám
                    </a>
                    <a href="{{ route('staff.work-schedule') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('staff.work-schedule') ? 'bg-primary-800 text-white' : 'text-white hover:bg-primary-500' }}">
                        <i class="fas fa-calendar-week mr-2"></i> Lịch làm việc
                    </a>
                    <a href="{{ route('staff.statistics') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('staff.statistics') ? 'bg-primary-800 text-white' : 'text-white hover:bg-primary-500' }}">
                        <i class="fas fa-chart-bar mr-2"></i> Thống kê
                    </a>
                </div>
                <div class="pt-4 pb-3 border-t border-primary-800">
                    <div class="flex items-center px-5">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-circle text-2xl text-white"></i>
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-white">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                            <div class="text-sm font-medium text-primary-200">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    <div class="mt-3 px-2 space-y-1">
                        <a href="{{ route('staff.profile.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-primary-500">
                            <i class="fas fa-user mr-2"></i> Thông tin cá nhân
                        </a>
                        <a href="{{ route('staff.profile.change-password') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-primary-500">
                            <i class="fas fa-key mr-2"></i> Đổi mật khẩu
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-primary-500">
                                <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    
    <!-- Main Content -->
    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Alerts -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button type="button" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-200 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.remove()">
                                    <span class="sr-only">Đóng</span>
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button type="button" class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-200 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.remove()">
                                    <span class="sr-only">Đóng</span>
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Trường Đại học Nha Trang</h3>
                    <div class="space-y-2">
                        <p class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-primary-400"></i>
                            <span>02 Nguyễn Đình Chiểu, Nha Trang, Khánh Hòa</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-phone mt-1 mr-3 text-primary-400"></i>
                            <span>(0258) 2471303</span>
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-envelope mt-1 mr-3 text-primary-400"></i>
                            <span>dhnt@ntu.edu.vn</span>
                        </p>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Liên kết</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="https://ntu.edu.vn" class="text-gray-300 hover:text-white flex items-center" target="_blank">
                                <i class="fas fa-external-link-alt mr-2 text-primary-400"></i>
                                Website trường
                            </a>
                        </li>
                        <li>
                            <a href="https://ntu.edu.vn/phong-to-chuc-hanh-chinh" class="text-gray-300 hover:text-white flex items-center" target="_blank">
                                <i class="fas fa-external-link-alt mr-2 text-primary-400"></i>
                                Phòng Tổ chức - Hành chính
                            </a>
                        </li>
                        <li>
                            <a href="https://ntu.edu.vn/tram-y-te" class="text-gray-300 hover:text-white flex items-center" target="_blank">
                                <i class="fas fa-external-link-alt mr-2 text-primary-400"></i>
                                Trạm Y tế
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Trường Đại học Nha Trang. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('scripts')
</body>
</html>
