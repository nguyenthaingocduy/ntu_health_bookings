@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Trạng thái đăng nhập</div>

                <div class="card-body">
                    @if(Auth::check())
                        <div class="alert alert-success">
                            <p><strong>Đã đăng nhập</strong></p>
                            <p><strong>User ID:</strong> {{ Auth::user()->id }}</p>
                            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                            <p><strong>Họ tên:</strong> {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                            <p><strong>Role:</strong> {{ Auth::user()->role ? Auth::user()->role->name : 'Không có' }}</p>
                            <p><strong>Check Auth::check():</strong> {{ Auth::check() ? 'true' : 'false' }}</p>
                        </div>
                        <a href="{{ route('customer.dashboard') }}" class="btn btn-primary">Đi đến Dashboard</a>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger">Đăng xuất</button>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <p><strong>Chưa đăng nhập</strong></p>
                            <p>Bạn chưa đăng nhập vào hệ thống.</p>
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-primary">Đăng nhập</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 