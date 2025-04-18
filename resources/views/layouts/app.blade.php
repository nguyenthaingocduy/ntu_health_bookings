<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts and Styles -->
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
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
    </style>
    
    @stack('styles')
</head>
<body>
    <div id="app">
        @include('partials.navigation')

        @yield('content')
        
        <!-- Footer -->
        <footer class="footer">
            <div class="container mx-auto px-6">
                <div class="footer-content">
                    <div class="footer-brand">
                        <span class="text-xl font-bold bg-gradient-to-r from-pink-500 to-purple-600 bg-clip-text text-transparent">Beauty Salon</span>
                        <p class="text-sm text-gray-600 mt-1">Chăm sóc sức khỏe và sắc đẹp của bạn</p>
                    </div>
                    
                    <div class="footer-info">
                        <div class="mb-2">
                            <i class="fas fa-map-marker-alt text-pink-500 mr-2"></i>
                            <span class="text-gray-700">02 Nguyen Dinh Chieu, Quận 1, TP. Hồ Chí Minh</span>
                        </div>
                        <div class="mb-2">
                            <i class="fas fa-phone-alt text-pink-500 mr-2"></i>
                            <span class="text-gray-700">0987.654.321</span>
                        </div>
                        <div>
                            <i class="fas fa-envelope text-pink-500 mr-2"></i>
                            <span class="text-gray-700">contact@ntuhealth.com</span>
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
                    &copy; {{ date('Y') }} NTU Health. Tất cả quyền được bảo lưu.
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
    <!-- Toastr JS -->
    <script>
        // Script chạy ngay lập tức
        (function() {
            console.log('Script menu dropdown đang chạy');
            
            // Lấy button và menu dropdown
            const userMenuButton = document.getElementById('user-menu-button');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            
            if (userMenuButton && dropdownMenu) {
                console.log('Đã tìm thấy phần tử menu');
                
                // Thêm sự kiện click cho button
                userMenuButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Đã nhấp vào avatar');
                    
                    // Toggle hiển thị dropdown menu
                    if (dropdownMenu.style.display === 'none' || dropdownMenu.style.display === '') {
                        dropdownMenu.style.display = 'block';
                    } else {
                        dropdownMenu.style.display = 'none';
                    }
                });
                
                // Đóng dropdown khi click ra ngoài
                document.addEventListener('click', function(e) {
                    if (!userMenuButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.style.display = 'none';
                    }
                });
                
                // Thêm các sự kiện hover nếu cần
                userMenuButton.addEventListener('mouseenter', function() {
                    userMenuButton.classList.add('ring-2', 'ring-indigo-500');
                });
                
                userMenuButton.addEventListener('mouseleave', function() {
                    if (dropdownMenu.style.display !== 'block') {
                        userMenuButton.classList.remove('ring-2', 'ring-indigo-500');
                    }
                });
            } else {
                console.error('Không tìm thấy phần tử menu dropdown');
            }
        })();
    </script>
</body>
</html> 