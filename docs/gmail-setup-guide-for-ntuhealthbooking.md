# Hướng dẫn thiết lập Gmail cho hệ thống Beauty Spa Booking

Hướng dẫn này sẽ giúp bạn thiết lập tài khoản Gmail `ntuhealthbooking@gmail.com` để gửi email từ hệ thống Beauty Spa Booking.

## Bước 1: Đăng nhập vào tài khoản Gmail

1. Truy cập [Gmail](https://mail.google.com)
2. Đăng nhập với tài khoản `ntuhealthbooking@gmail.com`

## Bước 2: Bật xác minh 2 bước cho tài khoản Gmail

1. Truy cập vào [Tài khoản Google](https://myaccount.google.com/) sau khi đã đăng nhập
2. Chọn **Bảo mật** từ menu bên trái
3. Trong phần "Đăng nhập vào Google", chọn **Xác minh 2 bước**
4. Nhấp vào **Bắt đầu** và làm theo các bước trên màn hình
5. Hoàn thành quá trình xác minh để bật Xác minh 2 bước (thường sẽ yêu cầu số điện thoại để nhận mã xác nhận)

## Bước 3: Tạo Mật khẩu ứng dụng

1. Sau khi đã bật Xác minh 2 bước, truy cập vào [Tài khoản Google](https://myaccount.google.com/)
2. Chọn **Bảo mật** từ menu bên trái
3. Trong phần "Đăng nhập vào Google", chọn **Mật khẩu ứng dụng**
   - Lưu ý: Nếu bạn không thấy tùy chọn này, có thể là vì Xác minh 2 bước chưa được thiết lập đúng cách
4. Ở cuối trang, nhấp vào **Chọn ứng dụng** và chọn **Thư**
5. Nhấp vào **Chọn thiết bị** và chọn **Khác (Tên tùy chỉnh)**
6. Nhập "Beauty Spa Booking" làm tên
7. Nhấp vào **Tạo**
8. Google sẽ hiển thị một mật khẩu gồm 16 ký tự (thường được hiển thị dưới dạng 4 nhóm, mỗi nhóm 4 ký tự)
9. **Sao chép mật khẩu này** - đây là Mật khẩu ứng dụng của bạn
10. Nhấp vào **Xong**

## Bước 4: Cập nhật file .env

1. Mở file `.env` trong thư mục gốc của ứng dụng
2. Tìm phần cấu hình email và cập nhật như sau:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=ntuhealthbooking@gmail.com
   MAIL_PASSWORD=mật_khẩu_ứng_dụng_16_ký_tự
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="ntuhealthbooking@gmail.com"
   MAIL_FROM_NAME="Beauty Spa Booking"
   ```
   - Lưu ý: Thay `mật_khẩu_ứng_dụng_16_ký_tự` bằng Mật khẩu ứng dụng bạn đã sao chép ở Bước 3
   - Nhập mật khẩu không có dấu cách, mặc dù Google hiển thị nó có dấu cách

3. Lưu file

## Bước 5: Xóa bộ nhớ đệm cấu hình

Chạy lệnh sau để xóa bộ nhớ đệm cấu hình:

```bash
php artisan config:clear
```

## Bước 6: Kiểm tra gửi email

Truy cập trang kiểm tra email để xác nhận rằng hệ thống email đang hoạt động:

```
http://your-website.com/email-test
```

Hoặc chạy lệnh sau để kiểm tra từ dòng lệnh:

```bash
php artisan email:send-test ntuhealthbooking@gmail.com
```

## Xử lý sự cố

Nếu bạn gặp vấn đề:

1. **Kiểm tra Mật khẩu ứng dụng**: Đảm bảo bạn đã sao chép Mật khẩu ứng dụng chính xác và không có dấu cách
2. **Kiểm tra cài đặt Gmail**: Đảm bảo rằng "Quyền truy cập của ứng dụng kém an toàn" đã TẮT (không cần thiết với Mật khẩu ứng dụng)
3. **Kiểm tra nhật ký**: Xem trong `storage/logs/laravel.log` để tìm lỗi liên quan đến email
4. **Kiểm tra cài đặt SMTP**: Đảm bảo rằng cài đặt SMTP (host, port, encryption) là chính xác
5. **Kiểm tra tường lửa**: Đảm bảo rằng máy chủ của bạn cho phép kết nối đến cổng SMTP (587)

## Lưu ý bảo mật

- Giữ Mật khẩu ứng dụng an toàn. Bất kỳ ai có Mật khẩu ứng dụng của bạn đều có thể gửi email từ tài khoản của bạn
- Nếu bạn nghi ngờ Mật khẩu ứng dụng của mình đã bị xâm phạm, bạn có thể thu hồi nó bất cứ lúc nào từ cài đặt bảo mật Tài khoản Google của bạn
- Thường xuyên kiểm tra nhật ký email để đảm bảo hệ thống đang hoạt động bình thường
