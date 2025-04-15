@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <!-- Tiêu đề -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Giới thiệu về NTU Health</h1>
            <div class="h-1 w-24 bg-gradient-to-r from-pink-500 to-purple-600 mx-auto"></div>
        </div>
        
        <!-- Nội dung giới thiệu -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-10">
            <div class="prose prose-lg max-w-none">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Câu chuyện của chúng tôi</h2>
                <p class="mb-4">NTU Health được thành lập vào năm 2020 với sứ mệnh mang đến những dịch vụ chăm sóc sức khỏe và sắc đẹp chất lượng cao, kết hợp giữa y học hiện đại và phương pháp truyền thống.</p>
                
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
                            <i class="fas fa-heart text-white text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Tận tâm</h3>
                        <p class="text-gray-600">Đặt khách hàng làm trung tâm, luôn lắng nghe và thấu hiểu nhu cầu.</p>
                    </div>
                    
                    <div class="border border-gray-200 p-4 rounded-lg text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-medal text-white text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Chất lượng</h3>
                        <p class="text-gray-600">Cam kết cung cấp dịch vụ chất lượng cao với quy trình chuẩn quốc tế.</p>
                    </div>
                    
                    <div class="border border-gray-200 p-4 rounded-lg text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-shield-alt text-white text-2xl"></i>
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
            <h2 class="text-3xl font-bold mb-4 text-white">Hãy đến với NTU Health</h2>
            <p class="text-lg mb-6 text-white font-medium">Trải nghiệm dịch vụ chăm sóc sức khỏe và sắc đẹp chất lượng cao</p>
            <a href="{{ route('customer.appointments.create') }}" class="inline-block px-6 py-3 bg-white text-pink-600 font-semibold rounded-full hover:bg-gray-50 transition shadow-md border border-pink-100">Đặt lịch ngay</a>
        </div>
    </div>
</div>
@endsection 

{{-- CSS Styles remain the same as your last working visual version --}}
@push('styles')
<style>
    .service-card.selected-service {
        border-color: #ec4899;
        background-color: rgba(236, 72, 153, 0.05);
        box-shadow: 0 4px 6px -1px rgba(236, 72, 153, 0.1), 0 2px 4px -1px rgba(236, 72, 153, 0.06);
    }
    .service-card.selected-service .service-radio-dot {
        opacity: 1 !important;
    }
    .time-slot-div.selected-time { /* Target the div for visual selection */
        background-color: #ec4899;
        color: white;
        border-color: #ec4899;
        box-shadow: 0 4px 6px -1px rgba(236, 72, 153, 0.1), 0 2px 4px -1px rgba(236, 72, 153, 0.06);
    }
    .time-slot-div {
        transition: all 0.2s ease;
    }
    .time-slot-div:hover {
        border-color: #ec4899;
        transform: translateY(-1px);
    }
    .loading-spinner { display: inline-block; width: 1rem; height: 1rem; border: 2px solid rgba(236, 72, 153, 0.3); border-radius: 50%; border-top-color: #ec4899; animation: spin 1s ease-in-out infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    input[type="date"] { position: relative; }
    input[type="date"]::-webkit-calendar-picker-indicator { cursor: pointer; border-radius: 4px; margin-right: 2px; opacity: 0.8; filter: invert(0.8) sepia(100%) saturate(100%) hue-rotate(220deg); }
    input[type="date"]::-webkit-inner-spin-button, input[type="date"]::-webkit-clear-button { display: none; }
    .step-circle.active { background-color: #ec4899; color: white; }
    .step-circle.inactive { background-color: #e5e7eb; color: #4b5563; } /* gray-200, gray-600 */
    .step-text.active { color: #111827; } /* gray-900 */
    .step-text.inactive { color: #4b5563; } /* gray-600 */
    .step-line.active { border-color: #ec4899; }
    .step-line.inactive { border-color: #e5e7eb; } /* gray-200 */

</style>
@endpush