@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-5xl mx-auto">
        <!-- Tiêu đề -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Liên hệ với chúng tôi</h1>
            <div class="h-1 w-24 bg-gradient-to-r from-pink-500 to-purple-600 mx-auto"></div>
            <p class="text-gray-600 mt-4 max-w-2xl mx-auto">Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn. Hãy liên hệ với chúng tôi theo những cách sau.</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8 mb-12">
            <!-- Thông tin liên hệ -->
            <div class="col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Thông tin liên hệ</h2>
                    
                    <div class="mb-6">
                        <div class="flex items-start mb-3">
                            <div class="bg-pink-100 rounded-full p-2 mr-3">
                                <i class="fas fa-map-marker-alt text-pink-600"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Địa chỉ</h3>
                                <p class="text-gray-600">02 Nguyen Dinh Chieu, Quận 1, TP. Hồ Chí Minh</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start mb-3">
                            <div class="bg-pink-100 rounded-full p-2 mr-3">
                                <i class="fas fa-phone-alt text-pink-600"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Điện thoại</h3>
                                <p class="text-gray-600">0987.654.321</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start mb-3">
                            <div class="bg-pink-100 rounded-full p-2 mr-3">
                                <i class="fas fa-envelope text-pink-600"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Email</h3>
                                <p class="text-gray-600">contact@ntuhealth.com</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="bg-pink-100 rounded-full p-2 mr-3">
                                <i class="fas fa-clock text-pink-600"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Giờ làm việc</h3>
                                <p class="text-gray-600">Hàng ngày (kể cả Chủ nhật): 9:00 - 20:00</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Social Media -->
                    <div>
                        <h3 class="font-medium text-gray-800 mb-3">Theo dõi chúng tôi</h3>
                        <div class="flex space-x-3">
                            <a href="#" class="bg-gradient-to-r from-pink-500 to-purple-600 text-white w-9 h-9 rounded-full flex items-center justify-center hover:shadow-lg transition">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="bg-gradient-to-r from-pink-500 to-purple-600 text-white w-9 h-9 rounded-full flex items-center justify-center hover:shadow-lg transition">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="bg-gradient-to-r from-pink-500 to-purple-600 text-white w-9 h-9 rounded-full flex items-center justify-center hover:shadow-lg transition">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="bg-gradient-to-r from-pink-500 to-purple-600 text-white w-9 h-9 rounded-full flex items-center justify-center hover:shadow-lg transition">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form liên hệ -->
            <div class="col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Gửi tin nhắn cho chúng tôi</h2>
                    
                    <form action="#" method="POST">
                        @csrf
                        <div class="grid md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="block text-gray-700 mb-2">Họ và tên <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500" required>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500" required>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="phone" class="block text-gray-700 mb-2">Số điện thoại</label>
                            <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                        </div>
                        
                        <div class="mb-6">
                            <label for="subject" class="block text-gray-700 mb-2">Tiêu đề <span class="text-red-500">*</span></label>
                            <input type="text" id="subject" name="subject" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500" required>
                        </div>
                        
                        <div class="mb-6">
                            <label for="message" class="block text-gray-700 mb-2">Nội dung <span class="text-red-500">*</span></label>
                            <textarea id="message" name="message" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500" required></textarea>
                        </div>
                        
                        <button type="submit" class="px-6 py-3 bg-white text-pink-600 font-semibold rounded-full hover:bg-gray-50 transition shadow-md border border-pink-100">Gửi tin nhắn</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Bản đồ -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Vị trí của chúng tôi</h2>
            <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3918.6587708349!2d106.78518997597367!3d10.839187157955575!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3175271ad01c9423%3A0x2be8c60124676a57!2zOTcgTWFuIFRoaeG7h24sIEhp4buHcCBQaMO6LCBUaMOgbmggcGjhu5EgVGjhu6cgxJDhu6ljLCBUaMOgbmggcGjhu5EgSOG7kyBDaMOtIE1pbmgsIFZp4buHdCBOYW0!5e0!3m2!1svi!2s!4v1700123456789!5m2!1svi!2s" 
                    width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection 