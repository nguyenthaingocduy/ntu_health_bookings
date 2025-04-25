@extends('layouts.admin')

@section('title', 'Quyền của tôi')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Quyền của tôi</h1>
            <p class="text-sm text-gray-500 mt-1">Xem các quyền bạn được cấp trong hệ thống</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-150 shadow-sm border border-gray-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Quay lại
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 mb-8">
        <div class="bg-gradient-to-r from-pink-50 to-pink-100 px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-pink-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
                Thông tin người dùng
            </h3>
        </div>
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center">
                <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-6">
                    <div class="relative">
                        <div class="w-24 h-24 rounded-full overflow-hidden bg-gradient-to-r from-pink-300 to-purple-300 flex items-center justify-center">
                            <img class="w-full h-full object-cover" src="https://ui-avatars.com/api/?name={{ Auth::user()->first_name }}&background=f472b6&color=ffffff" alt="{{ Auth::user()->first_name }}">
                        </div>
                    </div>
                </div>
                <div class="flex-grow">
                    <h4 class="text-xl font-semibold text-gray-800">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h4>
                    <p class="text-gray-600">{{ Auth::user()->email }}</p>
                    <div class="mt-2 flex items-center">
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">{{ Auth::user()->role->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                Quyền của tôi
            </h3>
        </div>
        <div class="p-6">
            <div class="mb-6">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" id="permission-search" placeholder="Tìm kiếm quyền...">
                </div>
            </div>

            <div class="space-y-4">
                @php
                    $permissions = Auth::user()->role->permissions()->orderBy('group')->orderBy('name')->get()->groupBy('group');
                    $userPermissions = Auth::user()->userPermissions()->pluck('permission_id')->toArray();
                @endphp

                @if($permissions->count() > 0)
                    @foreach($permissions as $group => $groupPermissions)
                    <div class="permission-group border border-gray-200 rounded-lg overflow-hidden" data-group="{{ $group }}">
                        <div class="permission-group-header bg-gray-50 px-4 py-3 flex justify-between items-center cursor-pointer hover:bg-gray-100 transition-colors duration-150">
                            <div class="flex items-center">
                                <span class="font-medium text-gray-700">{{ ucfirst($group) }}</span>
                                <span class="bg-blue-500 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full ml-2">{{ $groupPermissions->count() }}</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        <div class="permission-group-body p-4 bg-white">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($groupPermissions as $permission)
                                <div class="permission-item bg-gray-50 p-3 rounded-lg" data-permission-name="{{ $permission->name }}">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 mt-1">
                                            @if(in_array($permission->id, $userPermissions) || Auth::user()->isAdmin())
                                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-700">{{ $permission->display_name }}</span>
                                            @if($permission->description)
                                                <span class="text-xs text-gray-500">{{ $permission->description }}</span>
                                            @endif
                                            <span class="text-xs text-blue-500 mt-1">{{ $permission->name }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-500">Bạn chưa được cấp quyền nào trong hệ thống.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-8 bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                Thông tin về quyền
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Quyền là gì?</h4>
                    <p class="text-gray-600">Quyền là khả năng thực hiện một hành động cụ thể trong hệ thống. Mỗi quyền cho phép bạn truy cập hoặc thực hiện một chức năng nhất định.</p>
                </div>
                
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Làm thế nào để có thêm quyền?</h4>
                    <p class="text-gray-600">Nếu bạn cần thêm quyền để thực hiện công việc của mình, vui lòng liên hệ với quản trị viên hệ thống. Chỉ quản trị viên mới có thể cấp thêm quyền cho người dùng.</p>
                </div>
                
                <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p><span class="font-bold">Lưu ý:</span> Việc phân quyền được thực hiện để đảm bảo an toàn và bảo mật thông tin trong hệ thống. Mỗi người dùng chỉ nên có những quyền cần thiết để thực hiện công việc của mình.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const permissionSearch = document.getElementById('permission-search');
        const permissionGroups = document.querySelectorAll('.permission-group-header');
        
        // Tìm kiếm quyền
        permissionSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            document.querySelectorAll('.permission-item').forEach(item => {
                const permissionName = item.getAttribute('data-permission-name').toLowerCase();
                const permissionLabel = item.querySelector('span').textContent.toLowerCase();

                if (permissionName.includes(searchTerm) || permissionLabel.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });

            // Hiển thị/ẩn nhóm dựa trên kết quả tìm kiếm
            document.querySelectorAll('.permission-group').forEach(group => {
                const visibleItems = Array.from(group.querySelectorAll('.permission-item')).filter(item => 
                    getComputedStyle(item).display !== 'none'
                ).length;
                
                if (visibleItems === 0) {
                    group.style.display = 'none';
                } else {
                    group.style.display = '';
                }
            });
        });

        // Mở/đóng nhóm quyền
        permissionGroups.forEach(header => {
            header.addEventListener('click', function() {
                const body = this.nextElementSibling;
                const arrow = this.querySelector('svg');
                
                if (body.classList.contains('hidden')) {
                    body.classList.remove('hidden');
                    arrow.classList.remove('rotate-180');
                } else {
                    body.classList.add('hidden');
                    arrow.classList.add('rotate-180');
                }
            });
        });
    });
</script>
@endsection
