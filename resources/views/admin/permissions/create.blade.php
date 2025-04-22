@extends('layouts.admin')

@section('title', 'Thêm quyền mới')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Thêm quyền mới</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tổng quan</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Quản lý quyền</a></li>
        <li class="breadcrumb-item active">Thêm quyền mới</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Thêm quyền mới
        </div>
        <div class="card-body">
            <form action="{{ route('admin.permissions.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Tên quyền <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                           value="{{ old('name') }}" required placeholder="Ví dụ: users.view">
                    <div class="form-text">Tên quyền nên theo định dạng: resource.action (ví dụ: users.view, posts.create)</div>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="display_name" class="form-label">Tên hiển thị <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('display_name') is-invalid @enderror" id="display_name" 
                           name="display_name" value="{{ old('display_name') }}" required placeholder="Ví dụ: Xem người dùng">
                    @error('display_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" 
                              name="description" rows="3" placeholder="Mô tả chi tiết về quyền này">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="group" class="form-label">Nhóm <span class="text-danger">*</span></label>
                    <select class="form-select @error('group') is-invalid @enderror" id="group" name="group" required>
                        <option value="">-- Chọn nhóm --</option>
                        @foreach($groups as $group)
                        <option value="{{ $group }}" {{ old('group') == $group ? 'selected' : '' }}>
                            {{ ucfirst($group) }}
                        </option>
                        @endforeach
                        <option value="new">+ Thêm nhóm mới</option>
                    </select>
                    @error('group')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3" id="new-group-container" style="display: none;">
                    <label for="new_group" class="form-label">Nhóm mới <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="new_group" name="new_group" 
                           value="{{ old('new_group') }}" placeholder="Nhập tên nhóm mới">
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const groupSelect = document.getElementById('group');
        const newGroupContainer = document.getElementById('new-group-container');
        const newGroupInput = document.getElementById('new_group');
        
        // Hiển thị trường nhập nhóm mới nếu chọn "Thêm nhóm mới"
        groupSelect.addEventListener('change', function() {
            if (this.value === 'new') {
                newGroupContainer.style.display = 'block';
                newGroupInput.setAttribute('required', 'required');
            } else {
                newGroupContainer.style.display = 'none';
                newGroupInput.removeAttribute('required');
            }
        });
        
        // Kiểm tra giá trị ban đầu
        if (groupSelect.value === 'new') {
            newGroupContainer.style.display = 'block';
            newGroupInput.setAttribute('required', 'required');
        }
    });
</script>
@endsection
