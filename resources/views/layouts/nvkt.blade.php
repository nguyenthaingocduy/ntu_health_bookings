<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kỹ thuật viên - Hệ thống đặt lịch làm đẹp')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        @media (max-width: 768px) {
            .mobile-sidebar-open {
                transform: translateX(0);
            }
            .mobile-sidebar-closed {
                transform: translateX(-100%);
            }
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
                   <img src="/storage/logo/beauty-icon.png" alt="Beauty Icon" class="w-10 h-10" onerror="this.src='https://img.icons8.com/color/96/spa-flower.png'">

                    <span class="text-xl font-semibold">Beauty Spa</span>
                </div>
                <button id="closeSidebar" class="md:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <nav class="mt-8">
                <x-sidebar.nav-item
                    route="nvkt.dashboard"
                    text="Tổng quan"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>'
                    permission="work_schedule.view"
                />

                <x-sidebar.nav-dropdown
                    title="Quản lý lịch làm việc"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>'
                    :routes="['nvkt.schedule', 'nvkt.appointments.assigned', 'nvkt.work-status.index', 'nvkt.sessions.completed']"
                >
                    <x-sidebar.nav-item
                        route="nvkt.schedule"
                        text="Xem lịch làm việc"
                        icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>'
                        permission="work_schedule.view"
                    />

                    <x-sidebar.nav-item
                        route="nvkt.appointments.assigned"
                        text="Xem lịch hẹn được phân công"
                        icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>'
                        permission="work_schedule.view"
                    />

                    <x-sidebar.nav-item
                        route="nvkt.work-status.index"
                        text="Cập nhật trạng thái công việc"
                        icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>'
                        permission="work_schedule.update"
                    />

                    <x-sidebar.nav-item
                        route="nvkt.sessions.completed"
                        text="Báo cáo công việc"
                        icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>'
                        permission="treatment_progress.view"
                    />
                </x-sidebar.nav-dropdown>

                <x-sidebar.nav-dropdown
                    title="Quản lý dịch vụ"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>'
                    :routes="['nvkt.services.index', 'nvkt.notes.index']"
                >
                    <x-sidebar.nav-item
                        route="nvkt.services.index"
                        text="Xem thông tin dịch vụ"
                        icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>'
                        permission="services.view"
                    />

                    <x-sidebar.nav-item
                        route="nvkt.notes.index"
                        text="Ghi chú chuyên môn"
                        icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>'
                        permission="professional_notes.view"
                    />
                </x-sidebar.nav-dropdown>

                <x-sidebar.nav-dropdown
                    title="Quản lý khách hàng"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>'
                    :routes="['nvkt.customers.index', 'nvkt.customers.show', 'nvkt.customers.service-history']"
                >
                    <x-sidebar.nav-item
                        route="nvkt.customers.index"
                        text="Xem thông tin khách hàng (giới hạn)"
                        icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>'
                        permission="customers.view"
                    />

                    <x-sidebar.nav-item
                        route="nvkt.customers.service-history"
                        text="Xem lịch sử sử dụng dịch vụ của khách hàng"
                        icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>'
                        permission="customers.view"
                    />
                </x-sidebar.nav-dropdown>

                <x-sidebar.nav-dropdown
                    title="Báo cáo thống kê"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>'
                    :routes="['nvkt.reports.customers', 'nvkt.reports.services', 'nvkt.reports.ratings']"
                >
                    <x-sidebar.nav-item
                        route="nvkt.reports.customers"
                        text="Báo cáo số lượng khách hàng đã phục vụ"
                        icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>'
                        permission="reports.view"
                    />

                    <x-sidebar.nav-item
                        route="nvkt.reports.services"
                        text="Thống kê dịch vụ đã thực hiện"
                        icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>'
                        permission="reports.view"
                    />

                    <x-sidebar.nav-item
                        route="nvkt.reports.ratings"
                        text="Đánh giá kết quả dịch vụ"
                        icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>'
                        permission="reports.view"
                    />
                </x-sidebar.nav-dropdown>

                <x-sidebar.section-title title="Tài khoản" />

                <x-sidebar.nav-item
                    route="admin.permissions.my-permissions"
                    text="Quyền của tôi"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>'
                />
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
            const arrow = document.getElementById('arrow-' + id);

            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                dropdown.classList.add('block');
                arrow.classList.add('transform', 'rotate-180');
            } else {
                dropdown.classList.remove('block');
                dropdown.classList.add('hidden');
                arrow.classList.remove('transform', 'rotate-180');
            }
        }
    </script>
    @yield('scripts')
</body>
</html>
