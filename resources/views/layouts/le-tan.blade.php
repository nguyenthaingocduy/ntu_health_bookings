<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lễ tân - Hệ thống đặt lịch làm đẹp')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        /* Sidebar nav group styles */
        .nav-group {
            margin-bottom: 0.5rem;
        }

        .nav-group-items {
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
            display: block !important; /* Đảm bảo hiển thị */
        }

        .nav-group-items a {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            font-size: 0.9rem;
            display: block;
            padding-left: 1rem;
            border-left: 2px solid rgba(255, 255, 255, 0.1);
            margin-left: 0.5rem;
        }

        .nav-group-items a:hover {
            border-left: 2px solid rgba(255, 255, 255, 0.5);
        }

        .nav-group-items svg {
            width: 1rem;
            height: 1rem;
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
                <x-sidebar.nav-item
                    route="le-tan.dashboard"
                    text="Tổng quan"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>'
                    permission="appointments.view"
                />

                <!-- Quản lý khách hàng -->
                <x-sidebar.nav-dropdown
                    title="Quản lý khách hàng"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>'
                    :routes="['le-tan.customers.index', 'le-tan.customers.create', 'le-tan.customers.edit', 'le-tan.customers.show']"
                >
                    <x-sidebar.nav-item
                        route="le-tan.customers.index"
                        text="Xem thông tin khách hàng"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>'
                        permission="customers.view"
                    />

                    <x-sidebar.nav-item
                        route="le-tan.customers.create"
                        text="Tạo tài khoản khách hàng"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>'
                        permission="customers.create"
                    />

                    <x-sidebar.nav-item
                        route="le-tan.customers.index"
                        text="Cập nhật thông tin khách hàng"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>'
                        permission="customers.edit"
                    />

                    <x-sidebar.nav-item
                        route="le-tan.customers.index"
                        text="Tìm kiếm khách hàng"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>'
                        permission="customers.view"
                    />
                </x-sidebar.nav-dropdown>

                <!-- Quản lý lịch hẹn -->
                <x-sidebar.nav-dropdown
                    title="Quản lý lịch hẹn"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>'
                    :routes="['le-tan.appointments.create', 'le-tan.appointments.index', 'le-tan.reminders.index', 'le-tan.reminders.create']"
                >
                    <x-sidebar.nav-item
                        route="le-tan.appointments.create"
                        text="Đặt lịch hẹn"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>'
                        permission="appointments.create"
                    />

                    <x-sidebar.nav-item
                        route="le-tan.appointments.index"
                        text="Xác nhận lịch hẹn"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>'
                        permission="appointments.view"
                    />

                    <x-sidebar.nav-item
                        route="le-tan.appointments.index"
                        text="Hủy lịch hẹn"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>'
                        permission="appointments.cancel"
                    />

                    <x-sidebar.nav-item
                        route="le-tan.reminders.index"
                        text="Nhắc lịch hẹn"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>'
                        permission="reminders.view"
                    />

                    <x-sidebar.nav-item
                        route="le-tan.reminders.create"
                        text="Tạo nhắc lịch hẹn"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>'
                        permission="reminders.create"
                    />
                </x-sidebar.nav-dropdown>

                <!-- Quản lý thanh toán -->
                <x-sidebar.nav-dropdown
                    title="Quản lý thanh toán"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>'
                    :routes="['le-tan.payments.index', 'le-tan.invoices.index', 'le-tan.invoices.create']"
                >
                    <x-sidebar.nav-item
                        route="le-tan.invoices.create"
                        text="Tạo hóa đơn"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>'
                        permission="invoices.create"
                    />

                    <x-sidebar.nav-item
                        route="le-tan.payments.index"
                        text="Xác nhận thanh toán"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>'
                        permission="payments.view"
                    />

                    <x-sidebar.nav-item
                        route="le-tan.invoices.index"
                        text="In hóa đơn"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>'
                        permission="invoices.print"
                    />

                    <x-sidebar.nav-item
                        route="le-tan.payments.index"
                        text="Xem lịch sử thanh toán"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>'
                        permission="payments.view"
                    />
                </x-sidebar.nav-dropdown>

                <!-- Quản lý nhân viên -->
                <x-sidebar.nav-dropdown
                    title="Quản lý nhân viên"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>'
                    :routes="['le-tan.employees.index', 'le-tan.employees.create', 'le-tan.employees.edit']"
                >
                    <x-sidebar.nav-item
                        route="le-tan.dashboard"
                        text="Xem danh sách nhân viên"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>'
                        permission="employees.view"
                    />

                    <x-sidebar.nav-item
                        route="le-tan.dashboard"
                        text="Phân công lịch làm việc"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>'
                        permission="employees.schedule"
                    />
                </x-sidebar.nav-dropdown>

                <!-- Quản lý dịch vụ -->
                <x-sidebar.nav-dropdown
                    title="Quản lý dịch vụ"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>'
                    :routes="['le-tan.services.index', 'le-tan.consultations.index']"
                >
                    <x-sidebar.nav-item
                        route="le-tan.services.index"
                        text="Xem thông tin dịch vụ"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>'
                        permission="services.view"
                    />

                    <x-sidebar.nav-item
                        route="le-tan.consultations.index"
                        text="Tư vấn dịch vụ cho khách hàng"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>'
                        permission="consultations.view"
                    />

                    <x-sidebar.nav-item
                        route="le-tan.services.index"
                        text="Xem thông tin khuyến mãi"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                        </svg>'
                        permission="services.view"
                    />
                </x-sidebar.nav-dropdown>

                <!-- Báo cáo và thống kê -->
                <x-sidebar.nav-dropdown
                    title="Báo cáo và thống kê"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>'
                    :routes="['le-tan.reports.index', 'le-tan.statistics.index']"
                >
                    <x-sidebar.nav-item
                        route="le-tan.dashboard"
                        text="Thống kê doanh thu"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>'
                        permission="reports.view"
                    />

                    <x-sidebar.nav-item
                        route="le-tan.dashboard"
                        text="Thống kê lịch hẹn"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>'
                        permission="reports.view"
                    />

                    <x-sidebar.nav-item
                        route="le-tan.dashboard"
                        text="Thống kê dịch vụ"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>'
                        permission="reports.view"
                    />
                </x-sidebar.nav-dropdown>

                <!-- Tài khoản -->
                <x-sidebar.section-title title="Tài khoản" />

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
                </form>
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
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Thông tin cá nhân</a>
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cài đặt</a>
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

        // Toggle dropdown function for sidebar
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

        // Đảm bảo tất cả các dropdown đều được hiển thị khi trang tải
        document.addEventListener('DOMContentLoaded', function() {
            // Lấy tất cả các phần tử nav-group-items
            const navGroups = document.querySelectorAll('.nav-group-items');

            // Đảm bảo chúng được hiển thị
            navGroups.forEach(function(group) {
                group.classList.remove('hidden');
                group.classList.add('block');

                // Lấy ID của group
                const id = group.id;
                if (id) {
                    const arrow = document.getElementById('arrow-' + id);
                    if (arrow) {
                        arrow.classList.add('transform', 'rotate-180');
                    }
                }
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
