@extends('layouts.admin')

@section('title', 'Thêm nhân viên mới')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Thêm nhân viên mới</h1>
        <a href="{{ route('admin.employees.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition duration-300 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">
                    Vui lòng kiểm tra lại thông tin bên dưới
                </p>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-pink-500 from-pink-500 to-indigo-600 px-6 py-4">
            <h2 class="text-white text-lg font-semibold">Thông tin nhân viên</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="employeeForm">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Họ <span class="text-red-500">*</span></label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('first_name') border-red-500 @enderror">
                        @error('first_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Tên <span class="text-red-500">*</span></label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('last_name') border-red-500 @enderror">
                        @error('last_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại <span class="text-red-500">*</span></label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="birthday" class="block text-sm font-medium text-gray-700 mb-1">Ngày sinh <span class="text-red-500">*</span></label>
                        <input type="date" id="birthday" name="birthday" value="{{ old('birthday') }}" required max="{{ now()->subYears(18)->format('Y-m-d') }}"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('birthday') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Nhân viên phải từ 18 tuổi trở lên</p>
                        @error('birthday')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Giới tính <span class="text-red-500">*</span></label>
                        <select id="gender" name="gender" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gender') border-red-500 @enderror">
                            <option value="">Chọn giới tính</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                        </select>
                        @error('gender')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Vai trò <span class="text-red-500">*</span></label>
                        <select id="role_id" name="role_id" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role_id') border-red-500 @enderror">
                            <option value="">Chọn vai trò</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="clinic_id" class="block text-sm font-medium text-gray-700 mb-1">Cơ sở làm việc <span class="text-red-500">*</span></label>
                        <select id="clinic_id" name="clinic_id" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('clinic_id') border-red-500 @enderror">
                            <option value="">Chọn cơ sở</option>
                            @foreach($clinics as $clinic)
                                <option value="{{ $clinic->id }}" {{ old('clinic_id') == $clinic->id ? 'selected' : '' }}>
                                    {{ $clinic->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('clinic_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái <span class="text-red-500">*</span></label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="active" class="h-4 w-4 text-pink-600 focus:ring-pink-500" {{ old('status', 'active') == 'active' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Đang làm việc</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="inactive" class="h-4 w-4 text-pink-600 focus:ring-pink-500" {{ old('status') == 'inactive' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Đã nghỉ việc</span>
                            </label>
                        </div>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="services" class="block text-sm font-medium text-gray-700 mb-1">Dịch vụ đảm nhận</label>
                        <select id="services" name="services[]" multiple
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('services') border-red-500 @enderror">
                            @foreach($services ?? [] as $service)
                                <option value="{{ $service->id }}" {{ (old('services') && in_array($service->id, old('services'))) ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Giữ Ctrl để chọn nhiều dịch vụ</p>
                        @error('services')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ <span class="text-red-500">*</span></label>
                    <textarea id="address" name="address" rows="3" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address', 'N/A') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Vui lòng nhập địa chỉ của nhân viên</p>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">Ảnh đại diện</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-blue-100">
                    <div id="avatarPreview" class="mt-2 flex justify-center"></div>
                    <p class="text-xs text-gray-500 mt-1 text-center">Nếu không tải ảnh lên, hệ thống sẽ sử dụng ảnh mặc định</p>
                    @error('avatar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.employees.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-300">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="px-6 py-2 bg-pink-500 from-blue-500 to-indigo-600 text-white rounded-lg hover:opacity-90 transition duration-300">
                        <i class="fas fa-save mr-2"></i>Lưu nhân viên
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize select2 for multiple select
        $('#services').select2({
            placeholder: "Chọn dịch vụ",
            allowClear: true,
            theme: "classic"
        });

        // Form submit handler
        $("#employeeForm").submit(function(e) {
            // Log form data for debugging
            console.log('Form submitted');
            const formData = new FormData(this);
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + (pair[1] instanceof File ? pair[1].name : pair[1]));
            }
            // Continue with form submission
            return true;
        });

        // Preview avatar before upload
        $("#avatar").change(function() {
            const file = this.files[0];
            if (file) {
                console.log('File selected:', file.name, 'Size:', file.size, 'Type:', file.type);

                // Check file size
                if (file.size > 2 * 1024 * 1024) {
                    alert('Kích thước file quá lớn. Vui lòng chọn file nhỏ hơn 2MB.');
                    this.value = '';
                    $("#avatarPreview").html('');
                    return;
                }

                // Check file type
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    alert('Loại file không hợp lệ. Vui lòng chọn file ảnh (JPG, PNG, GIF).');
                    this.value = '';
                    $("#avatarPreview").html('');
                    return;
                }

                let reader = new FileReader();
                reader.onload = function(event) {
                    $("#avatarPreview").html('<div><img src="' + event.target.result + '" class="mt-2 rounded-full w-24 h-24 object-cover border-2 border-blue-200 shadow-md" /></div>');
                }
                reader.onerror = function(event) {
                    console.error('FileReader error:', event);
                    alert('Lỗi khi đọc file. Vui lòng thử lại.');
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush
