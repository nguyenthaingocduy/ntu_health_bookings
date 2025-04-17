@extends('layouts.admin')

@section('title', 'Test Image Upload')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-pink-500 px-6 py-4">
            <h2 class="text-white text-lg font-semibold">Test Image Upload</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.test-upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                    <input type="file" name="image" id="image" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600">
                        Upload Image
                    </button>
                </div>
            </form>
            
            @if(session('image_path'))
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Uploaded Image</h3>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <img src="{{ asset(session('image_path')) }}" alt="Uploaded Image" class="max-h-64 mx-auto">
                        <p class="mt-2 text-sm text-gray-600 text-center">Path: {{ session('image_path') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
