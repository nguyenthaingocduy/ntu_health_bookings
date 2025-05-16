@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Đăng nhập') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <div class="form-group">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-group">
                                <label for="password" class="form-label">{{ __('Mật khẩu') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Ghi nhớ đăng nhập') }}
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary w-100">
                                {{ __('Đăng nhập') }}
                            </button>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <a href="{{ route('register') }}" class="text-decoration-none px-3 py-2 rounded-pill bg-light hover-bg-primary">
                                <i class="fas fa-user-plus me-1"></i> {{ __('Tạo tài khoản mới') }}
                            </a>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-decoration-none px-3 py-2 rounded-pill bg-light hover-bg-primary">
                                    {{ __('Quên mật khẩu?') }} <i class="fas fa-question-circle ms-1"></i>
                                </a>
                            @endif
                        </div>

                        <style>
                            .hover-bg-primary {
                                transition: all 0.3s ease;
                            }
                            .hover-bg-primary:hover {
                                background-color: var(--primary) !important;
                                color: white !important;
                            }
                        </style>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection