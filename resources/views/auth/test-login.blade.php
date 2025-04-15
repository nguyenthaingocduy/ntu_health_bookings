@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Test Đăng nhập</div>

                <div class="card-body">
                    <form id="loginForm">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="password">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-check mt-3">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Đăng nhập</button>
                    </form>
                    <div id="result" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        
        fetch('{{ route("test.login") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            const resultDiv = document.getElementById('result');
            resultDiv.innerHTML = `
                <div class="alert ${data.success ? 'alert-success' : 'alert-danger'}">
                    <p><strong>Kết quả:</strong> ${data.message}</p>
                    ${data.success ? 
                        `<p><strong>User ID:</strong> ${data.user.id}</p>
                        <p><strong>Email:</strong> ${data.user.email}</p>
                        <p><strong>Authenticated:</strong> ${data.is_authenticated}</p>
                        <p><strong>Role:</strong> ${data.role}</p>` 
                        : ''
                    }
                </div>
            `;
        })
        .catch(error => {
            document.getElementById('result').innerHTML = `
                <div class="alert alert-danger">
                    <p>Đã xảy ra lỗi khi gửi yêu cầu: ${error.message}</p>
                </div>
            `;
        });
    });
});
</script>
@endsection 