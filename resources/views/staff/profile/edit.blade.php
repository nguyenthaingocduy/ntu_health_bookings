@extends('layouts.staff')

@section('title', 'Chỉnh sửa thông tin cá nhân')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Chỉnh sửa thông tin cá nhân</h1>
    <a href="{{ route('staff.profile.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Thông tin cá nhân</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('staff.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">Họ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $staff->first_name) }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $staff->last_name) }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $staff->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $staff->phone) }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $staff->address) }}" required>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="gender" class="form-label">Giới tính <span class="text-danger">*</span></label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                            <option value="male" {{ old('gender', $staff->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                            <option value="female" {{ old('gender', $staff->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                            <option value="other" {{ old('gender', $staff->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="birthday" class="form-label">Ngày sinh</label>
                        <input type="text" class="form-control datepicker @error('birthday') is-invalid @enderror" id="birthday" name="birthday" value="{{ old('birthday', $staff->birthday ? $staff->birthday->format('Y-m-d') : '') }}">
                        @error('birthday')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Hướng dẫn</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6><i class="fas fa-info-circle text-primary me-2"></i>Thông tin cần thiết</h6>
                    <ul class="ps-3">
                        <li>Họ và tên: Nhập đầy đủ họ và tên của bạn</li>
                        <li>Email: Địa chỉ email dùng để đăng nhập và nhận thông báo</li>
                        <li>Số điện thoại: Số điện thoại liên hệ</li>
                        <li>Địa chỉ: Địa chỉ hiện tại của bạn</li>
                        <li>Giới tính: Chọn giới tính của bạn</li>
                        <li>Ngày sinh: Chọn ngày sinh của bạn (không bắt buộc)</li>
                    </ul>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Lưu ý:</strong> Thông tin cá nhân của bạn sẽ được bảo mật và chỉ sử dụng cho mục đích đặt lịch khám sức khỏe.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo date picker cho ngày sinh
        flatpickr("#birthday", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            locale: "vn",
            disableMobile: "true"
        });
    });
</script>
@endsection
