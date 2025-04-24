<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Beauty Spa</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* Fix z-index issues */
        .dropdown-menu {
            z-index: 100 !important;
        }

        /* Dashboard Footer Styles */
        .dashboard-footer {
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
            padding: 1rem 1.5rem;
            color: #4b5563;
            font-size: 0.875rem;
        }

        .dashboard-footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        @media (max-width: 640px) {
            .dashboard-footer-content {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white">
            <div class="p-4">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-pink-500">
                    Beauty Spa
                </a>
            </div>

            <nav class="mt-4">
                @php
                    $user = Auth::user();
                    $role = $user->role ? $user->role->name : '';
                    $roleLower = strtolower($role);
                    $dashboardRoute = $roleLower . '.dashboard';
                @endphp

                <a href="{{ route($dashboardRoute) }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs($dashboardRoute) ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    Tổng quan
                </a>

                @if($user->can('view appointments') || $user->can('manage appointments'))
                <a href="{{ route($roleLower . '.appointments.index') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs($roleLower . '.appointments.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-calendar-alt w-6"></i>
                    Lịch hẹn
                </a>
                @endif

                @if($user->can('view services') || $user->can('manage services'))
                <a href="{{ route($roleLower . '.services.index') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs($roleLower . '.services.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-spa w-6"></i>
                    Dịch vụ
                </a>
                @endif

                @if($user->can('view customers') || $user->can('manage customers'))
                <a href="{{ route($roleLower . '.customers.index') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs($roleLower . '.customers.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-users w-6"></i>
                    Khách hàng
                </a>
                @endif

                @if($roleLower === 'admin' || $user->can('manage employees'))
                <a href="{{ route($roleLower . '.employees.index') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs($roleLower . '.employees.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-user-tie w-6"></i>
                    Nhân viên
                </a>
                @endif

                @if($roleLower === 'admin' || $user->can('manage clinics'))
                <a href="{{ route($roleLower . '.clinics.index') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs($roleLower . '.clinics.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-clinic-medical w-6"></i>
                    Phòng khám
                </a>
                @endif

                @if($roleLower === 'admin' || $user->can('manage promotions'))
                <a href="{{ route($roleLower . '.promotions.index') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->is($roleLower . '/promotions*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-tags w-6"></i>
                    Khuyến mãi
                </a>
                @endif

                @if($roleLower === 'admin')
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
                @endif

                @if($roleLower === 'admin' || $user->can('manage contacts'))
                <a href="{{ route($roleLower . '.contacts.index') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs($roleLower . '.contacts.*') ? 'bg-gray-700' : '' }}">
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
                @endif

                @if($roleLower === 'technician' || $roleLower === 'receptionist')
                <a href="{{ route($roleLower . '.schedule') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs($roleLower . '.schedule') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-calendar-week w-6"></i>
                    Lịch làm việc
                </a>
                @endif

                @if($roleLower === 'technician')
                <a href="{{ route('technician.sessions') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs('technician.sessions') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-clipboard-list w-6"></i>
                    Phiên làm việc
                </a>
                @endif

                @if($roleLower === 'receptionist')
                <a href="{{ route('receptionist.statistics') }}"
                   class="block px-4 py-2 text-gray-300 hover:bg-gray-700 {{ request()->routeIs('receptionist.statistics') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-chart-bar w-6"></i>
                    Thống kê
                </a>
                @endif
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="flex justify-between items-center px-6 py-4">
                    <h1 class="text-2xl font-semibold text-gray-800">@yield('header')</h1>

                    <div class="flex items-center">
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                                <span>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded shadow-lg py-2 z-50">
                                @php
                                    $user = Auth::user();
                                    $role = $user->role ? strtolower($user->role->name) : '';
                                    $profileRoute = $role . '.profile.edit';
                                    if ($role === 'admin') {
                                        $profileRoute = 'admin.profile.edit';
                                    } elseif ($role === 'staff') {
                                        $profileRoute = 'staff.profile.edit';
                                    } elseif ($role === 'receptionist') {
                                        $profileRoute = 'receptionist.profile.edit';
                                    } elseif ($role === 'technician') {
                                        $profileRoute = 'technician.profile.edit';
                                    } elseif ($role === 'customer') {
                                        $profileRoute = 'customer.profile.edit';
                                    }
                                @endphp
                                <a href="{{ route($profileRoute) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Hồ sơ cá nhân
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        Đăng xuất
                                    </button>
                                </form>
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

            <!-- Dashboard Footer -->
            <footer class="dashboard-footer">
                <div class="dashboard-footer-content">
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

    @stack('scripts')
</body>
</html>
