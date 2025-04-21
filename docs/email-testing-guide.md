# Hướng dẫn kiểm tra và sử dụng chức năng gửi email

Hướng dẫn này sẽ giúp bạn kiểm tra và sử dụng chức năng gửi email trong hệ thống Beauty Spa Booking.

## Chuẩn bị

Trước khi bắt đầu, hãy đảm bảo bạn đã thiết lập tài khoản Gmail `ntuhealthbooking@gmail.com` theo hướng dẫn trong file `docs/gmail-setup-guide-for-ntuhealthbooking.md`.

## Kiểm tra chức năng gửi email

### 1. Kiểm tra qua giao diện web

1. Truy cập trang kiểm tra email:
   ```
   http://your-website.com/email-test
   ```

2. Tại đây, bạn có thể:
   - Gửi email kiểm tra đến bất kỳ địa chỉ email nào
   - Kiểm tra các mẫu email khác nhau (đăng ký, đặt lịch, nhắc nhở)
   - Xem nhật ký email đã gửi

### 2. Kiểm tra qua dòng lệnh

Bạn có thể sử dụng các lệnh sau để kiểm tra chức năng gửi email:

1. Kiểm tra cơ bản:
   ```bash
   php artisan email:test your@email.com
   ```

2. Kiểm tra với tài khoản ntuhealthbooking:
   ```bash
   php artisan email:test-ntuhealthbooking your@email.com
   ```

3. Sử dụng script kiểm tra:
   ```bash
   php test-ntuhealthbooking-email.php your@email.com
   ```

## Các tính năng gửi email trong hệ thống

Hệ thống Beauty Spa Booking sử dụng email trong các trường hợp sau:

1. **Xác nhận đăng ký tài khoản**:
   - Gửi email chào mừng khi người dùng đăng ký tài khoản mới
   - Chứa thông tin tài khoản và liên kết đăng nhập

2. **Xác nhận đặt lịch**:
   - Gửi email xác nhận khi khách hàng đặt lịch hẹn mới
   - Chứa thông tin chi tiết về lịch hẹn và hướng dẫn

3. **Nhắc nhở lịch hẹn**:
   - Gửi email nhắc nhở trước ngày hẹn
   - Chạy tự động thông qua lệnh đã được lên lịch

## Xử lý sự cố

Nếu bạn gặp vấn đề với chức năng gửi email:

1. **Kiểm tra nhật ký**:
   - Xem file `storage/logs/laravel.log` để tìm lỗi liên quan đến email
   - Xem bảng `email_logs` trong cơ sở dữ liệu

2. **Kiểm tra cấu hình**:
   - Đảm bảo thông tin trong file `.env` là chính xác
   - Đảm bảo Mật khẩu ứng dụng đã được thiết lập đúng cách

3. **Kiểm tra kết nối**:
   - Đảm bảo máy chủ có thể kết nối đến Gmail SMTP (cổng 587)
   - Kiểm tra tường lửa và cài đặt mạng

## Tùy chỉnh mẫu email

Các mẫu email được lưu trữ trong thư mục `resources/views/emails/`. Bạn có thể chỉnh sửa các file sau để tùy chỉnh nội dung email:

- `registration.blade.php`: Mẫu email xác nhận đăng ký
- `appointment-confirmation.blade.php`: Mẫu email xác nhận đặt lịch
- `appointment-reminder.blade.php`: Mẫu email nhắc nhở lịch hẹn
- `test.blade.php`: Mẫu email kiểm tra

## Theo dõi và thống kê

Hệ thống lưu trữ thông tin về tất cả các email đã gửi trong bảng `email_logs`. Bạn có thể truy vấn bảng này để xem thống kê và theo dõi tình trạng gửi email.
