# Hướng dẫn thiết lập Gmail cho hệ thống Beauty Spa Booking

Hướng dẫn này sẽ giúp bạn thiết lập tài khoản Gmail để gửi email từ hệ thống Beauty Spa Booking.

## Bước 1: Bật xác minh 2 bước cho tài khoản Gmail

1. Truy cập vào [Tài khoản Google của bạn](https://myaccount.google.com/).
2. Chọn **Bảo mật** từ menu bên trái.
3. Trong phần "Đăng nhập vào Google", chọn **Xác minh 2 bước**.
4. Nhấp vào **Bắt đầu** và làm theo các bước trên màn hình.
5. Hoàn thành quá trình xác minh để bật Xác minh 2 bước.

## Bước 2: Tạo Mật khẩu ứng dụng

1. Truy cập vào [Tài khoản Google của bạn](https://myaccount.google.com/).
2. Chọn **Bảo mật** từ menu bên trái.
3. Trong phần "Đăng nhập vào Google", chọn **Mật khẩu ứng dụng**.
   - Lưu ý: Nếu bạn không thấy tùy chọn này, có thể là vì:
     - Xác minh 2 bước chưa được thiết lập cho tài khoản của bạn.
     - Xác minh 2 bước chỉ được thiết lập cho khóa bảo mật.
     - Tài khoản của bạn là tài khoản công ty, trường học hoặc tổ chức khác.
     - Bảo vệ nâng cao đã được bật.
4. Ở cuối trang, nhấp vào **Chọn ứng dụng** và chọn **Thư**.
5. Nhấp vào **Chọn thiết bị** và chọn **Khác (Tên tùy chỉnh)**.
6. Nhập "Beauty Spa Booking" làm tên.
7. Nhấp vào **Tạo**.
8. Mật khẩu ứng dụng sẽ được hiển thị. **Sao chép mật khẩu này** (mã 16 ký tự).
9. Nhấp vào **Xong**.

## Bước 3: Cập nhật file .env

1. Mở file `.env` trong thư mục gốc của ứng dụng.
2. Tìm phần cấu hình email và cập nhật như sau:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=duynguyen.11032003@gmail.com
   MAIL_PASSWORD=mật_khẩu_ứng_dụng_16_ký_tự
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="duynguyen.11032003@gmail.com"
   MAIL_FROM_NAME="Beauty Spa Booking"
   ```
   - Lưu ý: Nhập mật khẩu không có dấu cách, mặc dù Google hiển thị nó có dấu cách.
3. Lưu file.

## Bước 4: Xóa bộ nhớ đệm cấu hình

Chạy lệnh sau để xóa bộ nhớ đệm cấu hình:

```bash
php artisan config:clear
```

## Bước 5: Kiểm tra gửi email

Chạy lệnh sau để kiểm tra gửi email:

```bash
php artisan email:test-production duynguyen.11032003@gmail.com
```

## Xử lý sự cố

Nếu bạn gặp vấn đề:

1. **Kiểm tra Mật khẩu ứng dụng**: Đảm bảo bạn đã sao chép Mật khẩu ứng dụng chính xác.
2. **Kiểm tra cài đặt Gmail**: Đảm bảo rằng "Quyền truy cập của ứng dụng kém an toàn" đã TẮT (không cần thiết với Mật khẩu ứng dụng).
3. **Kiểm tra nhật ký**: Xem trong `storage/logs/laravel.log` để tìm lỗi liên quan đến email.
4. **Thử tài khoản Gmail khác**: Nếu vấn đề vẫn tiếp diễn, hãy thử thiết lập Mật khẩu ứng dụng với tài khoản Gmail khác.

## Lưu ý bảo mật

- Giữ Mật khẩu ứng dụng an toàn. Bất kỳ ai có Mật khẩu ứng dụng của bạn đều có thể truy cập vào Tài khoản Google của bạn.
- Nếu bạn nghi ngờ Mật khẩu ứng dụng của mình đã bị xâm phạm, bạn có thể thu hồi nó bất cứ lúc nào từ cài đặt bảo mật Tài khoản Google của bạn.
- Mật khẩu ứng dụng dành cho các ứng dụng không hỗ trợ OAuth, đây là phương thức xác thực an toàn hơn.
