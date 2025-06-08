@extends('layouts.admin')

@section('title', 'Chỉnh sửa nhân viên')

@section('header', 'Chỉnh sửa nhân viên')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="employeeEditForm">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Thông tin cơ bản</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Họ <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $employee->first_name) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('first_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Tên <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $employee->last_name) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('last_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $employee->email) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $employee->phone) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Thông tin cá nhân</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="birthday" class="block text-sm font-medium text-gray-700 mb-2">Ngày sinh</label>
                    <input type="date" name="birthday" id="birthday" value="{{ old('birthday', $employee->birthday ? $employee->birthday->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('birthday')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Giới tính</label>
                    <select name="gender" id="gender"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Chọn giới tính</option>
                        <option value="male" {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                        <option value="female" {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                        <option value="other" {{ old('gender', $employee->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                    </select>
                    @error('gender')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2">Vai trò <span class="text-red-500">*</span></label>
                    <select name="role_id" id="role_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Chọn vai trò</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $employee->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="clinic_id" class="block text-sm font-medium text-gray-700 mb-2">Cơ sở làm việc <span class="text-red-500">*</span></label>
                    <select name="clinic_id" id="clinic_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Chọn cơ sở</option>
                        @foreach($clinics as $clinic)
                            <option value="{{ $clinic->id }}" {{ old('clinic_id', $employee->clinic_id) == $clinic->id ? 'selected' : '' }}>
                                {{ $clinic->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('clinic_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Thông tin công việc</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Đang làm việc</option>
                        <option value="inactive" {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>Đã nghỉ việc</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="services" class="block text-sm font-medium text-gray-700 mb-2">Dịch vụ đảm nhận</label>
                    <select name="services[]" id="services" multiple
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($services ?? [] as $service)
                            <option value="{{ $service->id }}"
                                {{ (old('services') && in_array($service->id, old('services'))) ||
                                   (isset($employee) && $employee->services->contains($service->id)) ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-sm text-gray-500 mt-1">Giữ Ctrl để chọn nhiều dịch vụ</p>
                    @error('services')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Thay đổi mật khẩu</h3>
            <p class="text-sm text-gray-500 mb-4">Bỏ trống nếu không muốn thay đổi mật khẩu</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu mới</label>
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Tối thiểu 8 ký tự</p>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Xác nhận mật khẩu mới</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('password_confirmation')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-6">
            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ</label>
            <textarea name="address" id="address" rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('address', $employee->address) }}</textarea>
            @error('address')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Ảnh đại diện</h3>

            <div>
                @if($employee->avatar_url)
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Ảnh đại diện hiện tại</p>
                        <img src="{{ secure_asset($employee->avatar_url ?? 'images/employees/default-avatar.svg') }}" alt="{{ $employee->first_name }}" class="w-32 h-32 object-cover rounded-full">
                    </div>
                @endif

                <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">Thay đổi ảnh đại diện</label>
                <input type="file" id="avatar" name="avatar" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <div id="avatarPreview" class="mt-2 flex justify-center"></div>
                <p class="text-sm text-gray-500 mt-1">Chọn hình ảnh có kích thước tối đa 2MB, định dạng JPG, PNG, GIF. Để trống nếu không muốn thay đổi ảnh đại diện.</p>
                @error('avatar')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.employees.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Hủy bỏ
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                Cập nhật nhân viên
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize select2 for multiple select if available
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
            jQuery('#services').select2({
                placeholder: "Chọn dịch vụ",
                allowClear: true
            });
        }

        // Form submit handler for debugging
        $("#employeeEditForm").submit(function(e) {
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
