<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Hệ thống đăng ký khám sức khỏe - Trường Đại học Nha Trang')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts and Styles -->
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Additional Staff UI Styles -->
    <style>
        /* Form element consistency */
        .form-input, .form-select, .form-textarea,
        input[type="text"], input[type="email"], input[type="password"],
        input[type="date"], input[type="number"], select, textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        /* Consistent card styling */
        .staff-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .staff-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            background-color: #f9fafb;
        }

        .staff-card-body {
            padding: 1.5rem;
        }

        /* Consistent button styling */
        .staff-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 0.375rem;
            transition: all 0.2s;
        }

        /* Consistent spacing */
        .staff-section {
            margin-bottom: 2rem;
        }

        .staff-mb-4 {
            margin-bottom: 1rem;
        }

        .staff-mb-6 {
            margin-bottom: 1.5rem;
        }

        .staff-p-4 {
            padding: 1rem;
        }

        .staff-p-6 {
            padding: 1.5rem;
        }
    </style>

    <style>
        :root {
            --primary: #FF69B4;
            --primary-dark: #FF1493;
            --secondary: #9370DB;
            --accent: #FFB6C1;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(45deg, #FFE6F2 0%, #F0E6FF 100%);
            min-height: 100vh;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link {
            color: #333 !important;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .card {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(255, 105, 180, 0.1);
            padding: 20px 25px;
            font-weight: 600;
            color: #333;
            font-size: 1.2rem;
        }

        .card-body {
            padding: 25px;
        }

        .form-control {
            border: 2px solid #eee;
            border-radius: 15px;
            padding: 12px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(255, 105, 180, 0.25);
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 15px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 105, 180, 0.3);
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }

        .btn-primary:hover::after {
            left: 100%;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            padding: 1rem;
            position: absolute;
            top: 100%;
            right: 0;
            z-index: 1000;
        }

        .dropdown-item {
            border-radius: 10px;
            padding: 10px 20px;
            color: #333;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: linear-gradient(45deg, rgba(255,105,180,0.1), rgba(147,112,219,0.1));
            color: var(--primary);
            transform: translateX(5px);
        }

        .dropdown-divider {
            border-color: rgba(255,105,180,0.1);
            margin: 0.5rem 0;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(45deg, var(--primary-dark), var(--secondary));
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.2rem;
            }

            .card {
                margin: 1rem;
            }

            .btn-primary {
                width: 100%;
                margin-bottom: 1rem;
            }
        }

        /* Thêm CSS để làm nổi bật user menu và avatar */
        #user-menu-button {
            cursor: pointer;
            border: 2px solid #e5e7eb;
            padding: 2px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 10;
        }

        #user-menu-button:hover {
            border-color: var(--primary);
            transform: scale(1.05);
        }

        .user-menu {
            z-index: 100;
            min-width: 200px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
        }

        .user-menu a {
            display: block;
            padding: 10px 15px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .user-menu a:hover {
            background-color: #f3f4f6;
            transform: translateX(5px);
        }

        /* Kiểu hiển thị rõ ràng cho status active */
        .user-menu.active {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* Tăng không gian xung quanh menu user */
        .user-menu {
            padding: 0.5rem 0;
            margin-top: 0.5rem;
        }

        /* Footer styles */
        .footer {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 105, 180, 0.1);
            padding: 1.5rem 0;
            margin-top: 2rem;
        }

        .footer-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-start;
        }

        .footer-brand {
            margin-bottom: 1rem;
            flex: 1 1 250px;
        }

        .footer-info {
            margin-bottom: 1rem;
            flex: 2 1 400px;
            font-size: 0.9rem;
        }

        .footer-socials {
            flex: 1 1 150px;
            display: flex;
            justify-content: flex-end;
        }

        .footer-socials a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            color: white;
            margin-left: 0.5rem;
            transition: all 0.3s ease;
        }

        .footer-socials a:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 105, 180, 0.3);
        }

        @media (max-width: 768px) {
            .footer-content {
                flex-direction: column;
            }

            .footer-socials {
                justify-content: flex-start;
                margin-top: 1rem;
            }

            .footer-socials a:first-child {
                margin-left: 0;
            }
        }

        /* Sidebar styles */
        .sidebar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 105, 180, 0.1);
            transition: all 0.3s ease;
        }

        .sidebar-link {
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .sidebar-link:hover {
            background: linear-gradient(45deg, rgba(255,105,180,0.1), rgba(147,112,219,0.1));
            transform: translateX(5px);
        }

        .sidebar-link.active {
            background: linear-gradient(45deg, rgba(255,105,180,0.2), rgba(147,112,219,0.2));
            color: var(--primary);
            font-weight: 600;
        }

        .sidebar-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            margin-right: 12px;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            color: white;
        }

        /* Mobile sidebar */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -280px;
                width: 280px;
                height: 100vh;
                z-index: 1000;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            }

            .sidebar.open {
                left: 0;
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }

            .sidebar-overlay.active {
                display: block;
            }
        }

        /* Scrollbar hide for mobile categories */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="flex min-h-screen bg-gradient-to-br from-pink-50 via-purple-50 to-blue-50">
        <!-- Sidebar -->
        <div class="sidebar w-64 h-screen sticky top-0 hidden md:block" id="sidebar">
            <div class="p-6">
                <a href="{{ route('staff.dashboard') }}" class="flex items-center mb-8">
                    <span class="text-3xl font-extrabold bg-gradient-to-r from-pink-500 to-purple-600 bg-clip-text text-transparent">NTU</span>
                    <span class="text-3xl font-bold text-gray-800 ml-1">Health</span>
                </a>

                <div class="space-y-2">
                    <a href="{{ route('staff.dashboard') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:text-pink-600 {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                        <div class="sidebar-icon">
                            <i class="fas fa-home text-sm"></i>
                        </div>
                        <span>Trang chủ</span>
                    </a>

                    <a href="{{ route('staff.work-schedule') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:text-pink-600 {{ request()->routeIs('staff.work-schedule') ? 'active' : '' }}">
                        <div class="sidebar-icon">
                            <i class="fas fa-calendar-week text-sm"></i>
                        </div>
                        <span>Lịch làm việc</span>
                    </a>

                    <a href="{{ route('staff.appointments.index') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:text-pink-600 {{ request()->routeIs('staff.appointments.*') ? 'active' : '' }}">
                        <div class="sidebar-icon">
                            <i class="fas fa-calendar-check text-sm"></i>
                        </div>
                        <span>Lịch hẹn</span>
                    </a>

                    <a href="{{ route('staff.statistics') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:text-pink-600 {{ request()->routeIs('staff.statistics') ? 'active' : '' }}">
                        <div class="sidebar-icon">
                            <i class="fas fa-chart-bar text-sm"></i>
                        </div>
                        <span>Thống kê</span>
                    </a>

                    <div class="border-t border-gray-200 my-4"></div>

                    <a href="{{ route('staff.profile.index') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 hover:text-pink-600 {{ request()->routeIs('staff.profile.*') ? 'active' : '' }}">
                        <div class="sidebar-icon">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <span>Hồ sơ cá nhân</span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="sidebar-link flex items-center w-full px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg">
                            <div class="sidebar-icon bg-red-500">
                                <i class="fas fa-sign-out-alt text-sm"></i>
                            </div>
                            <span>Đăng xuất</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Mobile sidebar overlay -->
        <div class="sidebar-overlay" id="sidebar-overlay"></div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Top Navigation -->
            <nav class="bg-gradient-to-r from-white to-pink-50 shadow-lg sticky top-0 z-40 font-sans transition-all duration-300 backdrop-blur-md">
                <div class="container mx-auto px-6">
                    <div class="flex items-center justify-between h-16">
                        <!-- Mobile menu button -->
                        <div class="flex items-center md:hidden">
                            <button id="mobile-menu-button" class="text-gray-700 hover:text-pink-600 focus:outline-none">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                        </div>

                        <!-- Logo (visible only on mobile) -->
                        <div class="md:hidden flex items-center">
                            <a href="{{ route('staff.dashboard') }}" class="flex items-center">
                                <span class="text-2xl font-extrabold bg-gradient-to-r from-pink-500 to-purple-600 bg-clip-text text-transparent">NTU</span>
                                <span class="text-2xl font-bold text-gray-800 ml-1">Health</span>
                            </a>
                        </div>

                        <!-- Page Title (visible on desktop) -->
                        <div class="hidden md:flex items-center">
                            <h1 class="text-xl font-bold text-gray-800">@yield('page-title', 'Trang chủ')</h1>
                        </div>

                        <!-- User Profile -->
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center px-3 py-2 text-gray-700 hover:text-pink-600 transition">
                                <span class="mr-2 font-medium hidden sm:inline-block">{{ Auth::user()->first_name }}</span>
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
                                <a href="{{ route('staff.profile.index') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition">
                                    <i class="fas fa-user w-6 text-pink-500"></i>
                                    <span>Hồ sơ cá nhân</span>
                                </a>
                                <a href="{{ route('staff.profile.change-password') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition">
                                    <i class="fas fa-key w-6 text-pink-500"></i>
                                    <span>Đổi mật khẩu</span>
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
                    </div>

                    <!-- Mobile Page Title -->
                    <div class="md:hidden py-2 -mt-1">
                        <h1 class="text-xl font-bold text-gray-800">@yield('page-title', 'Trang chủ')</h1>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <main class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
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
            </main>

            <!-- Footer -->
            <footer class="footer">
                <div class="container mx-auto px-6">
                    <div class="footer-content">
                        <div class="footer-brand">
                            <span class="text-xl font-bold bg-gradient-to-r from-pink-500 to-purple-600 bg-clip-text text-transparent">NTU Health</span>
                            <p class="text-sm text-gray-600 mt-1">Chăm sóc sức khỏe và sắc đẹp của bạn</p>
                        </div>

                        <div class="footer-info">
                            <div class="mb-2">
                                <i class="fas fa-map-marker-alt text-pink-500 mr-2"></i>
                                <span class="text-gray-700">02 Nguyen Dinh Chieu, Nha Trang, Khánh Hòa</span>
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-phone-alt text-pink-500 mr-2"></i>
                                <span class="text-gray-700">(0258) 2471303</span>
                            </div>
                            <div>
                                <i class="fas fa-envelope text-pink-500 mr-2"></i>
                                <span class="text-gray-700">dhnt@ntu.edu.vn</span>
                            </div>
                        </div>

                        <div class="footer-socials">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>

                    <div class="text-center text-gray-600 text-sm mt-4">
                        &copy; {{ date('Y') }} Trường Đại học Nha Trang. Tất cả quyền được bảo lưu.
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Mobile Sidebar Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            if (mobileMenuButton && sidebar && sidebarOverlay) {
                mobileMenuButton.addEventListener('click', function() {
                    sidebar.classList.toggle('open');
                    sidebarOverlay.classList.toggle('active');
                    document.body.classList.toggle('overflow-hidden');
                });

                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('open');
                    sidebarOverlay.classList.remove('active');
                    document.body.classList.remove('overflow-hidden');
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
