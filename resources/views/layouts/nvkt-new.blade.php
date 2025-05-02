<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kỹ thuật viên - Hệ thống đặt lịch làm đẹp')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media (max-width: 768px) {
            .mobile-sidebar-open {
                transform: translateX(0);
            }
            .mobile-sidebar-closed {
                transform: translateX(-100%);
            }
        }

        .sidebar-dropdown {
            height: 0;
            overflow: hidden;
            transition: height 0.3s ease;
        }

        .sidebar-dropdown.active {
            height: auto;
        }

        .sidebar-menu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #d1d5db;
            transition: all 0.3s ease;
        }

        .sidebar-menu-item:hover {
            background-color: #374151;
            color: white;
        }

        .sidebar-menu-item.active {
            background-color: #4B5563;
            color: white;
        }

        .sidebar-submenu-item {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem 0.5rem 3rem;
            color: #d1d5db;
            transition: all 0.3s ease;
        }

        .sidebar-submenu-item:hover {
            background-color: #374151;
            color: white;
        }

        .sidebar-submenu-item.active {
            background-color: #4B5563;
            color: white;
        }

        .sidebar-dropdown-icon {
            transition: transform 0.3s ease;
        }

        .sidebar-dropdown-icon.active {
            transform: rotate(180deg);
        }
    </style>
    @yield('styles')
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Sidebar -->
    <div class="flex flex-col md:flex-row min-h-screen">
        <aside id="sidebar" class="bg-gray-800 text-white w-full md:w-64 flex-shrink-0 md:sticky md:top-0 md:h-screen overflow-y-auto transition-transform duration-300 ease-in-out md:transform-none mobile-sidebar-closed fixed inset-y-0 left-0 z-30">
            <div class="p-4 flex items-center justify-between md:justify-start">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto mr-2">
                    <span class="text-xl font-semibold">Beauty Spa</span>
                </div>
                <button id="closeSidebar" class="md:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <nav class="mt-8">
                <!-- Tổng quan -->
                <a href="{{ route('nvkt.dashboard') }}" class="sidebar-menu-item {{ request()->routeIs('nvkt.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Tổng quan</span>
                </a>

                <!-- Quản lý lịch làm việc -->
                <div class="sidebar-dropdown-container">
                    <div class="sidebar-menu-item" onclick="toggleDropdown('schedule-dropdown')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="flex-1">Quản lý lịch làm việc</span>
                        <svg id="schedule-dropdown-icon" class="w-4 h-4 sidebar-dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div id="schedule-dropdown" class="sidebar-dropdown">
                        <a href="{{ route('nvkt.schedule') }}" class="sidebar-submenu-item {{ request()->routeIs('nvkt.schedule') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Xem lịch làm việc</span>
                        </a>
                        <a href="{{ route('nvkt.appointments.assigned') }}" class="sidebar-submenu-item {{ request()->routeIs('nvkt.appointments.assigned') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Xem lịch hẹn được phân công</span>
                        </a>
                        <a href="{{ route('nvkt.work-status.index') }}" class="sidebar-submenu-item {{ request()->routeIs('nvkt.work-status.index') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Cập nhật trạng thái công việc</span>
                        </a>
                        <a href="{{ route('nvkt.sessions.completed') }}" class="sidebar-submenu-item {{ request()->routeIs('nvkt.sessions.completed') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Báo cáo công việc</span>
                        </a>
                    </div>
                </div>

                <!-- Quản lý dịch vụ -->
                <div class="sidebar-dropdown-container">
                    <div class="sidebar-menu-item" onclick="toggleDropdown('service-dropdown')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="flex-1">Quản lý dịch vụ</span>
                        <svg id="service-dropdown-icon" class="w-4 h-4 sidebar-dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div id="service-dropdown" class="sidebar-dropdown">
                        <a href="{{ route('nvkt.services.index') }}" class="sidebar-submenu-item {{ request()->routeIs('nvkt.services.index') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Xem thông tin dịch vụ</span>
                        </a>
                        <a href="{{ route('nvkt.notes.index') }}" class="sidebar-submenu-item {{ request()->routeIs('nvkt.notes.index') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Ghi chú chuyên môn</span>
                        </a>
                    </div>
                </div>

                <!-- Quản lý khách hàng -->
                <div class="sidebar-dropdown-container">
                    <div class="sidebar-menu-item" onclick="toggleDropdown('customer-dropdown')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="flex-1">Quản lý khách hàng</span>
                        <svg id="customer-dropdown-icon" class="w-4 h-4 sidebar-dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div id="customer-dropdown" class="sidebar-dropdown">
                        <a href="{{ route('nvkt.customers.index') }}" class="sidebar-submenu-item {{ request()->routeIs('nvkt.customers.index') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Xem thông tin khách hàng (giới hạn)</span>
                        </a>
                        <a href="{{ route('nvkt.customers.index') }}" class="sidebar-submenu-item {{ request()->routeIs('nvkt.customers.service-history') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Xem lịch sử sử dụng dịch vụ</span>
                        </a>
                    </div>
                </div>

                <!-- Báo cáo thống kê -->
                <div class="sidebar-dropdown-container">
                    <div class="sidebar-menu-item" onclick="toggleDropdown('report-dropdown')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="flex-1">Báo cáo thống kê</span>
                        <svg id="report-dropdown-icon" class="w-4 h-4 sidebar-dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div id="report-dropdown" class="sidebar-dropdown">
                        <a href="{{ route('nvkt.reports.customers') }}" class="sidebar-submenu-item {{ request()->routeIs('nvkt.reports.customers') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Báo cáo số lượng khách hàng</span>
                        </a>
                        <a href="{{ route('nvkt.reports.services') }}" class="sidebar-submenu-item {{ request()->routeIs('nvkt.reports.services') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Thống kê dịch vụ đã thực hiện</span>
                        </a>
                        <a href="{{ route('nvkt.reports.ratings') }}" class="sidebar-submenu-item {{ request()->routeIs('nvkt.reports.ratings') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Đánh giá kết quả dịch vụ</span>
                        </a>
                    </div>
                </div>

                <!-- Tài khoản -->
                <div class="mt-6 px-4 py-2">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tài khoản</h3>
                </div>

                <a href="{{ route('admin.permissions.my-permissions') }}" class="sidebar-menu-item {{ request()->routeIs('admin.permissions.my-permissions') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    <span>Quyền của tôi</span>
                </a>
            </nav>
        </aside>

        <!-- Main content -->
        <main class="flex-1">
            <!-- Top navigation -->
            <nav class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <button id="openSidebar" class="md:hidden mr-4 text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                            <div class="flex-shrink-0 flex items-center">
                                <h1 class="text-xl font-bold text-gray-800">@yield('header', 'Kỹ thuật viên')</h1>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="ml-3 relative">
                                <div>
                                    <button id="userMenuButton" class="flex items-center focus:outline-none" aria-expanded="false" aria-haspopup="true">
                                        <span class="mr-3 text-sm text-gray-600 hidden sm:inline-block">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                                        <img class="h-8 w-8 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ Auth::user()->first_name }}&background=0D8ABC&color=fff" alt="{{ Auth::user()->first_name }}">
                                    </button>
                                </div>
                                <div id="userMenu" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 hidden" role="menu" aria-orientation="vertical" aria-labelledby="userMenuButton" tabindex="-1">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Thông tin cá nhân</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Cài đặt</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Đăng xuất</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page content -->
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <!-- Overlay for mobile sidebar -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden md:hidden"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/nvkt-api.js') }}"></script>
    <script>
        // Mobile sidebar toggle
        document.getElementById('openSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('mobile-sidebar-closed');
            document.getElementById('sidebar').classList.add('mobile-sidebar-open');
            document.getElementById('sidebarOverlay').classList.remove('hidden');
        });

        document.getElementById('closeSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('mobile-sidebar-open');
            document.getElementById('sidebar').classList.add('mobile-sidebar-closed');
            document.getElementById('sidebarOverlay').classList.add('hidden');
        });

        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('mobile-sidebar-open');
            document.getElementById('sidebar').classList.add('mobile-sidebar-closed');
            document.getElementById('sidebarOverlay').classList.add('hidden');
        });

        // User dropdown menu toggle
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenu = document.getElementById('userMenu');

        userMenuButton.addEventListener('click', function() {
            userMenu.classList.toggle('hidden');
        });

        // Close the dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });

        // Sidebar dropdown toggle
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            const icon = document.getElementById(id + '-icon');

            if (dropdown.classList.contains('active')) {
                dropdown.classList.remove('active');
                dropdown.style.height = '0';
                icon.classList.remove('active');
            } else {
                // Đóng tất cả các dropdown khác
                const allDropdowns = document.querySelectorAll('.sidebar-dropdown');
                const allIcons = document.querySelectorAll('.sidebar-dropdown-icon');

                allDropdowns.forEach(function(item) {
                    item.classList.remove('active');
                    item.style.height = '0';
                });

                allIcons.forEach(function(item) {
                    item.classList.remove('active');
                });

                // Mở dropdown hiện tại
                dropdown.classList.add('active');
                dropdown.style.height = dropdown.scrollHeight + 'px';
                icon.classList.add('active');
            }
        }

        // Mở dropdown nếu có mục con đang active
        document.addEventListener('DOMContentLoaded', function() {
            const activeSubmenuItem = document.querySelector('.sidebar-submenu-item.active');

            if (activeSubmenuItem) {
                const parentDropdown = activeSubmenuItem.closest('.sidebar-dropdown');
                const parentDropdownId = parentDropdown.id;
                const icon = document.getElementById(parentDropdownId + '-icon');

                parentDropdown.classList.add('active');
                parentDropdown.style.height = parentDropdown.scrollHeight + 'px';
                icon.classList.add('active');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
