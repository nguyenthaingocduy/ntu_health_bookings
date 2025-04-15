<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hệ thống đăng ký khám sức khỏe - Trường Đại học Nha Trang')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('staff.dashboard') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="40" class="me-2">
                Đăng ký khám sức khỏe - CBVC
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}" href="{{ route('staff.dashboard') }}">
                            <i class="fas fa-home me-1"></i> Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('staff.appointments.*') ? 'active' : '' }}" href="{{ route('staff.appointments.index') }}">
                            <i class="fas fa-calendar-check me-1"></i> Lịch khám
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->first_name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('staff.profile.index') }}">
                                    <i class="fas fa-user me-1"></i> Thông tin cá nhân
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('staff.profile.change-password') }}">
                                    <i class="fas fa-key me-1"></i> Đổi mật khẩu
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-1"></i> Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Trường Đại học Nha Trang</h5>
                    <p>
                        <i class="fas fa-map-marker-alt me-2"></i> Địa chỉ: 02 Nguyễn Đình Chiểu, Nha Trang, Khánh Hòa<br>
                        <i class="fas fa-phone me-2"></i> Điện thoại: (0258) 2471303<br>
                        <i class="fas fa-envelope me-2"></i> Email: dhnt@ntu.edu.vn
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>Liên kết</h5>
                    <ul class="list-unstyled">
                        <li><a href="https://ntu.edu.vn" class="text-white" target="_blank">Website trường</a></li>
                        <li><a href="https://ntu.edu.vn/phong-to-chuc-hanh-chinh" class="text-white" target="_blank">Phòng Tổ chức - Hành chính</a></li>
                        <li><a href="https://ntu.edu.vn/tram-y-te" class="text-white" target="_blank">Trạm Y tế</a></li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Trường Đại học Nha Trang. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/script.js') }}"></script>
    @yield('scripts')
</body>
</html>
