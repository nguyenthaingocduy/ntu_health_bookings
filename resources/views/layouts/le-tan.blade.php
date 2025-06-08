<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lễ tân - Hệ thống đặt lịch làm đẹp')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ secure_asset('css/time-display-fix.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }

        @media (max-width: 768px) {
            .mobile-sidebar-open {
                transform: translateX(0);
            }
            .mobile-sidebar-closed {
                transform: translateX(-100%);
            }
        }

        /* Sidebar styles */
        .sidebar-nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #d1d5db;
            transition: all 0.2s;
        }

        .sidebar-nav-item:hover {
            background-color: rgba(75, 85, 99, 0.5);
            color: white;
        }

        .sidebar-nav-item.active {
            background-color: #4b5563;
            color: white;
        }

        .sidebar-dropdown-button {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0.75rem 1rem;
            color: #d1d5db;
            transition: all 0.2s;
            cursor: pointer;
        }

        .sidebar-dropdown-button:hover {
            background-color: rgba(75, 85, 99, 0.5);
            color: white;
        }

        .nav-group-items {
            padding-left: 1rem;
        }

        .nav-group-items a {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            color: #d1d5db;
            font-size: 0.9rem;
            transition: all 0.2s;
            border-left: 2px solid rgba(255, 255, 255, 0.1);
        }

        .nav-group-items a:hover {
            background-color: rgba(75, 85, 99, 0.5);
            color: white;
            border-left: 2px solid rgba(255, 255, 255, 0.5);
        }

        .nav-group-items a.active {
            background-color: #4b5563;
            color: white;
            border-left: 2px solid white;
        }

        /* New sidebar dropdown styles */
        .sidebar-dropdown-container {
            margin-bottom: 0.5rem;
        }

        .sidebar-menu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #d1d5db;
            cursor: pointer;
            transition: all 0.2s;
        }

        .sidebar-menu-item:hover {
            background-color: rgba(75, 85, 99, 0.5);
            color: white;
        }

        .sidebar-dropdown {
            display: none;
            padding-left: 1rem;
        }

        .sidebar-dropdown.show {
            display: block;
        }

        .sidebar-submenu-item {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            color: #d1d5db;
            font-size: 0.9rem;
            transition: all 0.2s;
            border-left: 2px solid rgba(255, 255, 255, 0.1);
            text-decoration: none;
        }

        .sidebar-submenu-item:hover {
            background-color: rgba(75, 85, 99, 0.5);
            color: white;
            border-left: 2px solid rgba(255, 255, 255, 0.5);
        }

        .sidebar-submenu-item.active {
            background-color: #4b5563;
            color: white;
            border-left: 2px solid white;
        }

        .sidebar-dropdown-icon {
            transition: transform 0.2s;
        }

        .sidebar-dropdown-icon.rotate {
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
                <div class="flex items-center cursor-pointer" id="logoContainer">
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
                <!-- Tổng quan -->
                <x-sidebar.nav-item
                    route="le-tan.dashboard"
                    text="Tổng quan"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>'
                    permission="appointments.view"
                />

                <!-- Quản lý khách hàng -->
                <div class="sidebar-dropdown-container">
                    <div class="sidebar-menu-item" onclick="toggleDropdown('customers-dropdown')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span class="flex-1">Quản lý khách hàng</span>
                        <svg id="customers-dropdown-icon" class="w-4 h-4 sidebar-dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div id="customers-dropdown" class="sidebar-dropdown">
                        <a href="{{ route('le-tan.customers.index') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.customers.index') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Xem thông tin khách hàng</span>
                        </a>
                        {{-- <a href="{{ route('le-tan.customers.create') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.customers.create') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Tạo tài khoản khách hàng</span>
                        </a> --}}
                        <a href="{{ route('le-tan.customers.index') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.customers.edit') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Cập nhật thông tin khách hàng</span>
                        </a>
                        <a href="{{ route('le-tan.customers.index') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.customers.show') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Tìm kiếm khách hàng</span>
                        </a>
                    </div>
                </div>

                <!-- Quản lý lịch hẹn -->
                <div class="sidebar-dropdown-container">
                    <div class="sidebar-menu-item" onclick="toggleDropdown('appointments-dropdown')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="flex-1">Quản lý lịch hẹn</span>
                        <svg id="appointments-dropdown-icon" class="w-4 h-4 sidebar-dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div id="appointments-dropdown" class="sidebar-dropdown">
                        <a href="{{ route('le-tan.appointments.create') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.appointments.create') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Đặt lịch hẹn</span>
                        </a>
                        <a href="{{ route('le-tan.appointments.index') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.appointments.index') && !request()->has('status') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Xác nhận lịch hẹn</span>
                        </a>
                        <a href="{{ route('le-tan.appointments.index', ['status' => 'cancelled']) }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.appointments.index') && request()->get('status') == 'cancelled' ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Hủy lịch hẹn</span>
                        </a>
                        <a href="{{ route('le-tan.reminders.index') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.reminders.index') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Nhắc lịch hẹn</span>
                        </a>
                        <a href="{{ route('le-tan.reminders.create') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.reminders.create') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Tạo nhắc lịch hẹn</span>
                        </a>
                    </div>
                </div>

                <!-- Quản lý thanh toán -->
                <div class="sidebar-dropdown-container">
                    <div class="sidebar-menu-item" onclick="toggleDropdown('payments-dropdown')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="flex-1">Quản lý thanh toán</span>
                        <svg id="payments-dropdown-icon" class="w-4 h-4 sidebar-dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div id="payments-dropdown" class="sidebar-dropdown">
                        <a href="{{ route('le-tan.payments.create') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.payments.create') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Tạo thanh toán</span>
                        </a>
                        <a href="{{ route('le-tan.invoices.create') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.invoices.create') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Tạo hóa đơn</span>
                        </a>
                        <a href="{{ route('le-tan.payments.index') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.payments.index') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Xác nhận thanh toán</span>
                        </a>
                        <a href="{{ route('le-tan.invoices.index') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.invoices.index') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>In hóa đơn</span>
                        </a>
                        <a href="{{ route('le-tan.payments.index') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.payments.history') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Xem lịch sử thanh toán</span>
                        </a>
                    </div>
                </div>

                <!-- Quản lý nhân viên -->
                {{-- <div class="sidebar-dropdown-container">
                    <div class="sidebar-menu-item" onclick="toggleDropdown('employees-dropdown')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="flex-1">Quản lý nhân viên</span>
                        <svg id="employees-dropdown-icon" class="w-4 h-4 sidebar-dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div id="employees-dropdown" class="sidebar-dropdown">
                        <a href="{{ route('le-tan.dashboard') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.employees.index') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Xem danh sách nhân viên</span>
                        </a>
                        <a href="{{ route('le-tan.dashboard') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.employees.schedule') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Phân công lịch làm việc</span>
                        </a>
                    </div>
                </div> --}}

                <!-- Quản lý dịch vụ -->
                <x-sidebar.dynamic-menu
                    title="Quản lý dịch vụ"
                    permissionGroup="services"
                    :routes="['le-tan.services.index', 'le-tan.services.create', 'le-tan.services.edit', 'le-tan.consultations.index']"
                    icon='<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>'
                >
                    <x-sidebar.dynamic-item
                        route="le-tan.services.index"
                        text="Xem dịch vụ"
                        permission="services.view"
                        icon='<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>'
                    />
                    <x-sidebar.dynamic-item
                        route="le-tan.services.create"
                        text="Thêm dịch vụ"
                        permission="services.create"
                        icon='<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>'
                    />
                    <x-sidebar.dynamic-item
                        route="le-tan.services.index"
                        text="Sửa dịch vụ"
                        permission="services.edit"
                        icon='<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>'
                    />
                    <x-sidebar.dynamic-item
                        route="le-tan.services.index"
                        text="Xóa dịch vụ"
                        permission="services.delete"
                        icon='<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>'
                    />
                    <x-sidebar.dynamic-item
                        route="le-tan.consultations.index"
                        text="Tư vấn dịch vụ"
                        permission="services.view"
                        icon='<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>'
                    />
                </x-sidebar.dynamic-menu>

                <!-- Quản lý khuyến mãi -->
                <x-sidebar.dynamic-menu
                    title="Quản lý khuyến mãi"
                    permissionGroup="promotions"
                    :routes="['le-tan.promotions.index', 'le-tan.promotions.create', 'le-tan.promotions.edit']"
                    icon='<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                    </svg>'
                >
                    <x-sidebar.dynamic-item
                        route="le-tan.promotions.index"
                        text="Xem khuyến mãi"
                        permission="promotions.view"
                        icon='<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>'
                    />
                    <x-sidebar.dynamic-item
                        route="le-tan.promotions.create"
                        text="Thêm khuyến mãi"
                        permission="promotions.create"
                        icon='<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>'
                    />
                    <x-sidebar.dynamic-item
                        route="le-tan.promotions.index"
                        text="Sửa khuyến mãi"
                        permission="promotions.edit"
                        icon='<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>'
                    />
                    <x-sidebar.dynamic-item
                        route="le-tan.promotions.index"
                        text="Xóa khuyến mãi"
                        permission="promotions.delete"
                        icon='<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>'
                    />
                </x-sidebar.dynamic-menu>

                <!-- Quản lý lịch làm việc -->
                {{-- <div class="sidebar-dropdown-container">
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
                        <a href="{{ route('le-tan.dashboard') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.schedule') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Xem lịch làm việc</span>
                        </a>
                        <a href="{{ route('le-tan.dashboard') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.appointments.assigned') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Phân công lịch hẹn</span>
                        </a>
                        <a href="{{ route('le-tan.dashboard') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.work-status.index') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Cập nhật trạng thái công việc</span>
                        </a>
                        <a href="{{ route('le-tan.dashboard') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.sessions.completed') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Báo cáo công việc</span>
                        </a>
                    </div>
                </div> --}}

                <!-- Báo cáo và thống kê - Chỉ hiển thị nếu có quyền -->
                <x-permission-check permission="reports.view">
                <div class="sidebar-dropdown-container">
                    <div class="sidebar-menu-item" onclick="toggleDropdown('reports-dropdown')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="flex-1">Báo cáo và thống kê</span>
                        <svg id="reports-dropdown-icon" class="w-4 h-4 sidebar-dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div id="reports-dropdown" class="sidebar-dropdown">
                        <a href="{{ route('le-tan.reports.revenue') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.reports.revenue') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Thống kê doanh thu</span>
                        </a>
                        <a href="{{ route('le-tan.reports.appointments') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.reports.appointments') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Thống kê lịch hẹn</span>
                        </a>
                        <a href="{{ route('le-tan.reports.services') }}" class="sidebar-submenu-item {{ request()->routeIs('le-tan.reports.services') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Thống kê dịch vụ</span>
                        </a>
                    </div>
                </div>
                </x-permission-check>

                <!-- Tài khoản -->
                {{-- <x-sidebar.section-title title="Tài khoản" />

                <x-sidebar.nav-item
                    route="admin.permissions.my-permissions"
                    text="Quyền của tôi"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>'
                />

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200">
                        <span class="w-6 h-6 flex items-center justify-center mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </span>
                        <span>Đăng xuất</span>
                    </button>
                </form> --}}
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
                                <h1 class="text-xl font-bold text-gray-800">@yield('header', 'Lễ tân')</h1>
                            </div>
                        </div>
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

        // Function to toggle dropdown
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const icon = document.getElementById(dropdownId + '-icon');

            if (dropdown.style.display === 'block') {
                dropdown.style.display = 'none';
                if (icon) icon.classList.remove('rotate', 'active');
            } else {
                dropdown.style.display = 'block';
                if (icon) icon.classList.add('rotate', 'active');
            }
        }

        // Initialize dropdowns when page loads
        document.addEventListener('DOMContentLoaded', function() {
            console.log("DOM Content Loaded - Initializing dropdowns");

            // Get all dropdown toggles
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            console.log("Found " + dropdownToggles.length + " dropdown buttons");

            // Add click event listeners to all dropdown toggles
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    // Find the dropdown menu (next sibling after the button)
                    const dropdownMenu = this.nextElementSibling;
                    const arrow = this.querySelector('.dropdown-arrow');

                    // Toggle the dropdown
                    if (dropdownMenu.style.display === 'none') {
                        dropdownMenu.style.display = 'block';
                        if (arrow) arrow.classList.add('transform', 'rotate-180');
                    } else {
                        dropdownMenu.style.display = 'none';
                        if (arrow) arrow.classList.remove('transform', 'rotate-180');
                    }
                });
            });

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

            // Xử lý sự kiện click vào logo để chuyển về trang dashboard
            const logoContainer = document.getElementById('logoContainer');
            if (logoContainer) {
                logoContainer.addEventListener('click', function() {
                    window.location.href = "{{ route('le-tan.dashboard') }}";
                });
            }

        });
    </script>
    @yield('scripts')
</body>
</html>
