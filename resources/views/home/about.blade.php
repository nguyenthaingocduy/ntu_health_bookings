@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <!-- Tiêu đề -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Giới thiệu về Beauty Salon</h1>
            <div class="h-1 w-24 bg-gradient-to-r from-pink-500 to-purple-600 mx-auto"></div>
        </div>
        
        <!-- Nội dung giới thiệu -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-10">
            <div class="prose prose-lg max-w-none">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Câu chuyện của chúng tôi</h2>
                <p class="mb-4">BeautySalon được thành lập vào năm 2020 với sứ mệnh mang đến những dịch vụ chăm sóc sức khỏe và sắc đẹp chất lượng cao, kết hợp giữa y học hiện đại và phương pháp truyền thống.</p>
                
                <p class="mb-4">Với đội ngũ bác sĩ, chuyên gia thẩm mỹ và kỹ thuật viên có trình độ chuyên môn cao, cùng với trang thiết bị hiện đại, chúng tôi cam kết mang đến cho khách hàng những trải nghiệm dịch vụ tốt nhất.</p>
                
                <h2 class="text-2xl font-semibold text-gray-800 my-6">Tầm nhìn & Sứ mệnh</h2>
                
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-pink-50 p-6 rounded-lg">
                        <h3 class="text-xl font-semibold text-pink-600 mb-3">Tầm nhìn</h3>
                        <p>Trở thành thương hiệu hàng đầu trong lĩnh vực chăm sóc sức khỏe và làm đẹp tại Việt Nam, được khách hàng tin tưởng và lựa chọn.</p>
                    </div>
                    
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <h3 class="text-xl font-semibold text-purple-600 mb-3">Sứ mệnh</h3>
                        <p>Mang đến những dịch vụ chăm sóc sức khỏe và sắc đẹp chất lượng cao, an toàn, hiệu quả, giúp khách hàng tự tin và tỏa sáng.</p>
                    </div>
                </div>
                
                <h2 class="text-2xl font-semibold text-gray-800 my-6">Giá trị cốt lõi</h2>
                
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="border border-gray-200 p-4 rounded-lg text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-heart text-black text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Tận tâm</h3>
                        <p class="text-gray-600">Đặt khách hàng làm trung tâm, luôn lắng nghe và thấu hiểu nhu cầu.</p>
                    </div>
                    
                    <div class="border border-gray-200 p-4 rounded-lg text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-medal text-black text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Chất lượng</h3>
                        <p class="text-gray-600">Cam kết cung cấp dịch vụ chất lượng cao với quy trình chuẩn quốc tế.</p>
                    </div>
                    
                    <div class="border border-gray-200 p-4 rounded-lg text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-shield-alt text-black text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">An toàn</h3>
                        <p class="text-gray-600">Đặt sự an toàn của khách hàng lên hàng đầu trong mọi dịch vụ.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Đội ngũ của chúng tôi -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-10">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Đội ngũ chuyên gia</h2>
            
            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center">
                    <img src="https://randomuser.me/api/portraits/women/1.jpg" alt="Dr. Nguyễn Thị An" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover border-4 border-pink-200">
                    <h3 class="text-lg font-semibold">Bs. Nguyễn Thị An</h3>
                    <p class="text-pink-600">Giám đốc Y khoa</p>
                    <p class="text-gray-600 mt-2">Hơn 15 năm kinh nghiệm trong lĩnh vực thẩm mỹ và da liễu.</p>
                </div>
                
                <div class="text-center">
                    <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Dr. Trần Văn Bình" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover border-4 border-pink-200">
                    <h3 class="text-lg font-semibold">Bs. Trần Văn Bình</h3>
                    <p class="text-pink-600">Chuyên gia Phun xăm</p>
                    <p class="text-gray-600 mt-2">Chuyên gia hàng đầu về kỹ thuật phun xăm thẩm mỹ.</p>
                </div>
                
                <div class="text-center">
                    <img src="https://randomuser.me/api/portraits/women/2.jpg" alt="Dr. Lê Thị Cẩm" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover border-4 border-pink-200">
                    <h3 class="text-lg font-semibold">Bs. Lê Thị Cẩm</h3>
                    <p class="text-pink-600">Chuyên gia Điều trị da</p>
                    <p class="text-gray-600 mt-2">Tốt nghiệp chuyên ngành Da liễu tại Đại học Y Dược TP.HCM.</p>
                </div>
            </div>
        </div>
        
        <!-- CTA -->
        <div class="bg-gradient-to-r from-pink-500 to-purple-600 rounded-xl shadow-lg p-8 text-center text-white">
            <h2 class="text-3xl font-bold mb-4 text-black">Hãy đến với Beauty Salon</h2>
            <p class="text-lg mb-6 text-black font-medium">Trải nghiệm dịch vụ chăm sóc sức khỏe và sắc đẹp chất lượng cao</p>
            <a href="{{ route('customer.appointments.create') }}" class="inline-block px-6 py-3 bg-pink text-pink-500 font-semibold rounded-full hover:bg-gray-50 transition shadow-md border border-pink-100">Đặt lịch ngay</a>
        </div>
    </div>
</div>
@endsection 