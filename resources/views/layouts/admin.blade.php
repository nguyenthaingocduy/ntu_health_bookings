<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản trị') - Trang Nhã Spa</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Thêm CSS cơ bản để đảm bảo các phần tử hiển thị đúng -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Time Display Fix -->
    <link rel="stylesheet" href="{{ secure_asset('css/time-display-fix.css') }}">

    <style>
        /* Fix z-index issues */
        .dropdown-menu {
            z-index: 100 !important;
        }

        /* Admin Footer Styles */
        .admin-footer {
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
            padding: 1rem 1.5rem;
            color: #4b5563;
            font-size: 0.875rem;
        }

        .admin-footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        @media (max-width: 640px) {
            .admin-footer-content {
                flex-direction: column;
                gap: 0.5rem;
            }
        }

        /* Custom Pagination Styles */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.25rem;
        }

        .pagination .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0.75rem;
            margin: 0 0.125rem;
            text-decoration: none;
            color: #6b7280;
            background-color: white;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            transition: all 0.2s ease-in-out;
            min-width: 2.5rem;
            height: 2.5rem;
        }

        .pagination .page-link:hover {
            background-color: #f3f4f6;
            color: #374151;
            border-color: #9ca3af;
        }

        .pagination .page-item.active .page-link {
            background-color: #ec4899;
            border-color: #ec4899;
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            color: #d1d5db;
            background-color: #f9fafb;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }

        .pagination .page-item.disabled .page-link:hover {
            background-color: #f9fafb;
            color: #d1d5db;
            border-color: #e5e7eb;
        }

        /* Responsive pagination */
        @media (max-width: 640px) {
            .pagination .page-link {
                padding: 0.375rem 0.5rem;
                min-width: 2rem;
                height: 2rem;
                font-size: 0.875rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white overflow-y-auto">
            <div class="p-4">
                <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-pink-500">
                    Trang Nhã Spa
                </a>
            </div>

            <nav class="mt-4">
                <a href="{{ route('admin.dashboard') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    Tổng quan
                </a>

                <a href="{{ route('admin.appointments.index') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs('admin.appointments.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-calendar-alt w-6"></i>
                    Lịch hẹn
                </a>

                <a href="{{ route('admin.services.index') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs('admin.services.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-spa w-6"></i>
                    Dịch vụ
                </a>

                <a href="{{ route('admin.customers.index') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs('admin.customers.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-users w-6"></i>
                    Khách hàng
                </a>

                <a href="{{ route('admin.customer-types.index') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs('admin.customer-types.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-user-tag w-6"></i>
                    Loại khách hàng
                </a>

                <a href="{{ route('admin.employees.index') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs('admin.employees.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-user-tie w-6"></i>
                    Nhân viên
                </a>

                <a href="{{ route('admin.clinics.index') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs('admin.clinics.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-clinic-medical w-6"></i>
                    Phòng khám
                </a>

                <a href="{{ route('admin.promotions.index') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->is('admin/promotions*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-tags w-6"></i>
                    Khuyến mãi
                </a>

                <a href="{{ route('admin.invoices.index') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->is('admin/invoices*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-file-invoice-dollar w-6"></i>
                    Quản lý hóa đơn
                </a>
{{--
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->is('admin/work-schedules*') ? 'bg-gray-700' : '' }}">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-week w-6"></i>
                            <span>Phân công lịch làm việc</span>
                        </div>
                        <svg :class="{'transform rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="pl-8 mt-1 space-y-1">
                        <a href="{{ route('admin.work-schedules.weekly-assignment') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 {{ request()->routeIs('admin.work-schedules.weekly-assignment') ? 'bg-gray-700' : '' }}">
                            <i class="fas fa-calendar-alt w-5"></i>
                            Phân công lịch làm việc
                        </a>
                        <a href="{{ route('admin.work-schedules.view-week') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 {{ request()->routeIs('admin.work-schedules.view-week') ? 'bg-gray-700' : '' }}">
                            <i class="fas fa-eye w-5"></i>
                            Xem lịch làm việc
                        </a>
                    </div>
                </div> --}}

                <div class="py-2 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Báo cáo & Thống kê
                </div>

                <a href="{{ route('admin.revenue.index') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->is('admin/revenue*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-chart-line w-6"></i>
                    Thống kê doanh thu
                </a>

                {{-- <a href="{{ route('admin.reports.customer-types') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->is('admin/reports/customer-types*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-chart-pie w-6"></i>
                    Phân bố khách hàng
                </a> --}}

                <div class="py-2 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Quản trị hệ thống
                </div>

                <a href="{{ route('admin.permissions.index') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->is('admin/permissions*') || request()->is('admin/role-permissions*') || request()->is('admin/user-permissions*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-shield-alt w-6"></i>
                    Phân quyền
                </a>

                <a href="{{ route('admin.roles.index') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->is('admin/roles*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-user-shield w-6"></i>
                    Vai trò
                </a>

                {{-- <a href="{{ route('admin.permissions.my-permissions') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->is('admin/my-permissions*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-key w-6"></i>
                    Quyền của tôi
                </a> --}}

                <a href="{{ route('admin.contacts.index') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs('admin.contacts.*') ? 'bg-gray-700' : '' }}">
                    <div class="flex items-center justify-between w-full">
                        <div>
                            <i class="fas fa-envelope w-6"></i>
                            Tin nhắn liên hệ
                        </div>
                        @if(isset($unreadContactCount) && $unreadContactCount > 0)
                            <span class="bg-pink-500 text-white text-xs rounded-full px-2 py-1 ml-2">{{ $unreadContactCount }}</span>
                        @endif
                    </div>
                </a>

                <!-- Thêm khoảng trống ở cuối sidebar -->
                <div class="py-10"></div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="flex justify-between items-center px-6 py-4">
                    <h1 class="text-2xl font-semibold text-gray-800">@yield('header')</h1>

                     <div class="flex items-center">
                            <div class="ml-3 relative">
                                <div class="flex items-center">
                                    <span class="mr-3 text-sm text-gray-600 hidden sm:inline-block">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                                    <div class="relative" id="userMenuContainer">
                                        <img id="userMenuButton" class="h-8 w-8 rounded-full object-cover cursor-pointer" src="https://ui-avatars.com/api/?name={{ Auth::user()->first_name }}&background=0D8ABC&color=fff" alt="{{ Auth::user()->first_name }}">

                                        <!-- Dropdown menu -->
                                        <div id="userMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                                            {{-- <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Thông tin cá nhân</a>
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cài đặt</a> --}}
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Đăng xuất</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>

            <!-- Admin Footer -->
            <footer class="admin-footer">
                <div class="admin-footer-content">
                    <div>
                        &copy; {{ date('Y') }} Beauty Spa - Hệ thống quản lý
                    </div>
                    <div>
                        <span>Phiên bản 1.0</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>


    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        // Xử lý sự kiện click vào avatar để hiển thị dropdown menu
            const userMenuButton = document.getElementById('userMenuButton');
            const userMenu = document.getElementById('userMenu');

            if (userMenuButton && userMenu) {
                userMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userMenu.classList.toggle('hidden');
                });

                // Đóng dropdown khi click ra ngoài
                document.addEventListener('click', function(e) {
                    if (!userMenuButton.contains(e.target) && !userMenu.contains(e.target)) {
                        userMenu.classList.add('hidden');
                    }
                });
            }
    </script>
    @stack('scripts')
</body>
</html>