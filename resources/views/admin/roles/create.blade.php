@extends('layouts.admin')

@section('title', 'Tạo vai trò mới')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tạo vai trò mới</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tổng quan</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Quản lý vai trò</a></li>
        <li class="breadcrumb-item active">Tạo vai trò mới</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Tạo vai trò mới
        </div>
        <div class="card-body">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Tên vai trò <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                           value="{{ old('name') }}" required placeholder="Ví dụ: Admin, Nhân viên, Khách hàng">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" 
                              name="description" rows="3" placeholder="Mô tả chi tiết về vai trò này">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
