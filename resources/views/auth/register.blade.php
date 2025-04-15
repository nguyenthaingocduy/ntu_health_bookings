@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="mb-4 text-center">
            <h2 class="text-2xl font-bold text-gray-900">
                {{ __('Đăng ký tài khoản') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ __('Hoặc') }}
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                    {{ __('đăng nhập nếu đã có tài khoản') }}
                </a>
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label for="first_name" class="block text-sm font-medium text-gray-700">
                    {{ __('Họ') }}
                </label>
                <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autocomplete="given-name" autofocus
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('first_name') border-red-500 @enderror">
                @error('first_name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="last_name" class="block text-sm font-medium text-gray-700">
                    {{ __('Tên') }}
                </label>
                <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('last_name') border-red-500 @enderror">
                @error('last_name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">
                    {{ __('Email') }}
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">
                    {{ __('Số điện thoại') }}
                </label>
                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required autocomplete="tel"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('phone') border-red-500 @enderror">
                @error('phone')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="gender" class="block text-sm font-medium text-gray-700">
                    {{ __('Giới tính') }}
                </label>
                <select id="gender" name="gender" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('gender') border-red-500 @enderror">
                    <option value="">{{ __('Chọn giới tính') }}</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('Nam') }}</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('Nữ') }}</option>
                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>{{ __('Khác') }}</option>
                </select>
                @error('gender')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">
                    {{ __('Mật khẩu') }}
                </label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password-confirm" class="block text-sm font-medium text-gray-700">
                    {{ __('Xác nhận mật khẩu') }}
                </label>
                <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700">
                    {{ __('Địa chỉ') }}
                </label>
                <textarea id="address" name="address" rows="3" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                @error('address')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Đăng ký') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection