@extends('layouts.staff')

@section('title', 'Đổi mật khẩu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Đổi mật khẩu</h1>
    <a href="{{ route('staff.profile.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Thay đổi mật khẩu</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('staff.profile.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Mật khẩu phải có ít nhất 8 ký tự.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key me-1"></i> Cập nhật mật khẩu
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
                    <h6><i class="fas fa-shield-alt text-primary me-2"></i>Mật khẩu an toàn</h6>
                    <p>Một mật khẩu an toàn nên có:</p>
                    <ul class="ps-3">
                        <li>Ít nhất 8 ký tự</li>
                        <li>Kết hợp chữ hoa và chữ thường</li>
                        <li>Bao gồm số và ký tự đặc biệt</li>
                        <li>Không sử dụng thông tin cá nhân dễ đoán</li>
                        <li>Không trùng với mật khẩu đã sử dụng trước đây</li>
                    </ul>
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Lưu ý:</strong> Sau khi đổi mật khẩu, bạn sẽ cần đăng nhập lại bằng mật khẩu mới.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
