@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>{{ isset($service) ? 'Chỉnh sửa dịch vụ' : 'Thêm dịch vụ mới' }}</h6>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary btn-sm mb-0">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}">
                        @csrf
                        @if(isset($service))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">Tên dịch vụ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', isset($service) ? $service->name : '') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-control-label">Trạng thái <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="active" {{ old('status', isset($service) ? $service->status : '') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="inactive" {{ old('status', isset($service) ? $service->status : '') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id" class="form-control-label">Danh mục <span class="text-danger">*</span></label>
                                    <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', isset($service) ? $service->category_id : '') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="clinic_id" class="form-control-label">Phòng khám <span class="text-danger">*</span></label>
                                    <select class="form-control @error('clinic_id') is-invalid @enderror" id="clinic_id" name="clinic_id" required>
                                        <option value="">-- Chọn phòng khám --</option>
                                        @foreach($clinics as $clinic)
                                            <option value="{{ $clinic->id }}" {{ old('clinic_id', isset($service) ? $service->clinic_id : '') == $clinic->id ? 'selected' : '' }}>
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

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price" class="form-control-label">Giá dịch vụ (VNĐ) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', isset($service) ? $service->price : '') }}" min="0" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="promotion" class="form-control-label">Khuyến mãi (%)</label>
                                    <input type="number" class="form-control @error('promotion') is-invalid @enderror" id="promotion" name="promotion" value="{{ old('promotion', isset($service) ? $service->promotion : '') }}" min="0" max="100">
                                    @error('promotion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label for="image" class="form-control-label">Hình ảnh</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(isset($service) && $service->image_url)
                                <div class="mt-2">
                                    <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="img-thumbnail" style="max-height: 150px">
                                    <p class="small text-muted mt-1">Hình ảnh hiện tại. Tải lên hình mới để thay đổi.</p>
                                </div>
                            @endif
                        </div>

                        <div class="form-group mt-3">
                            <label for="descriptive" class="form-control-label">Mô tả dịch vụ <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('descriptive') is-invalid @enderror" id="descriptive" name="descriptive" rows="5" required>{{ old('descriptive', isset($service) ? $service->descriptive : '') }}</textarea>
                            @error('descriptive')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn bg-gradient-primary">
                                {{ isset($service) ? 'Cập nhật' : 'Thêm mới' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Thêm mã JavaScript theo cần thiết ở đây
    document.addEventListener('DOMContentLoaded', function() {
        // Có thể thêm các tính năng như preview hình ảnh, validate form, v.v.
        const imageInput = document.getElementById('image');
        if (imageInput) {
            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    const imgPreview = document.createElement('img');
                    imgPreview.className = 'img-thumbnail mt-2';
                    imgPreview.style.maxHeight = '150px';
                    
                    reader.onload = function(e) {
                        imgPreview.src = e.target.result;
                    }
                    
                    reader.readAsDataURL(file);
                    
                    // Xóa preview cũ nếu có
                    const oldPreview = imageInput.nextElementSibling.nextElementSibling;
                    if (oldPreview && oldPreview.tagName === 'DIV') {
                        oldPreview.replaceWith(imgPreview);
                    } else {
                        imageInput.parentNode.appendChild(imgPreview);
                    }
                }
            });
        }
    });
</script>
@endpush 