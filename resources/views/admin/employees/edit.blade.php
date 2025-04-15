@extends('layouts.admin')

@section('title', 'Chỉnh sửa nhân viên')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa nhân viên</h1>
        <a href="{{ route('admin.employees.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin nhân viên</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="first_name">Họ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="last_name">Tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $employee->last_name) }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $employee->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="birthday">Ngày sinh</label>
                            <input type="date" class="form-control @error('birthday') is-invalid @enderror" id="birthday" name="birthday" value="{{ old('birthday', $employee->birthday ? $employee->birthday->format('Y-m-d') : '') }}">
                            @error('birthday')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gender">Giới tính</label>
                            <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
                                <option value="">Chọn giới tính</option>
                                <option value="male" {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                                <option value="female" {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                                <option value="other" {{ old('gender', $employee->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role_id">Vai trò <span class="text-danger">*</span></label>
                            <select class="form-control @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                                <option value="">Chọn vai trò</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $employee->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="clinic_id">Cơ sở làm việc <span class="text-danger">*</span></label>
                            <select class="form-control @error('clinic_id') is-invalid @enderror" id="clinic_id" name="clinic_id" required>
                                <option value="">Chọn cơ sở</option>
                                @foreach($clinics as $clinic)
                                    <option value="{{ $clinic->id }}" {{ old('clinic_id', $employee->clinic_id) == $clinic->id ? 'selected' : '' }}>
                                        {{ $clinic->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('clinic_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Đang làm việc</option>
                                <option value="inactive" {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>Đã nghỉ việc</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="services">Dịch vụ đảm nhận</label>
                            <select class="form-control @error('services') is-invalid @enderror" id="services" name="services[]" multiple>
                                @foreach($services ?? [] as $service)
                                    <option value="{{ $service->id }}" 
                                        {{ (old('services') && in_array($service->id, old('services'))) || 
                                           (isset($employee) && $employee->services->contains($service->id)) ? 'selected' : '' }}>
                                        {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Giữ Ctrl để chọn nhiều dịch vụ</small>
                            @error('services')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Địa chỉ</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $employee->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    @if($employee->avatar_url)
                        <div class="mb-3">
                            <label>Ảnh đại diện hiện tại</label>
                            <div>
                                <img src="{{ asset($employee->avatar_url) }}" alt="{{ $employee->name }}" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        </div>
                    @endif
                    <label for="avatar">Thay đổi ảnh đại diện</label>
                    <input type="file" class="form-control-file @error('avatar') is-invalid @enderror" id="avatar" name="avatar">
                    <small class="form-text text-muted">Chọn hình ảnh có kích thước tối đa 2MB, định dạng jpg, jpeg, png, gif. Để trống nếu không muốn thay đổi.</small>
                    @error('avatar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật nhân viên</button>
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
            allowClear: true
        });
    });
</script>
@endpush
