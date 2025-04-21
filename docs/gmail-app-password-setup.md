# Hướng dẫn thiết lập App Password cho Gmail

Để hệ thống có thể gửi email thông qua tài khoản Gmail `ntuhealthbooking@gmail.com`, bạn cần thiết lập App Password. Đây là một mật khẩu đặc biệt cho phép ứng dụng truy cập vào tài khoản Gmail của bạn mà không cần sử dụng mật khẩu chính.

## Bước 1: Bật xác minh 2 bước cho tài khoản Gmail

1. Đăng nhập vào tài khoản Gmail `ntuhealthbooking@gmail.com`
2. Truy cập [Tài khoản Google](https://myaccount.google.com/)
3. Chọn **Bảo mật** từ menu bên trái
4. Trong phần "Đăng nhập vào Google", chọn **Xác minh 2 bước**
5. Nhấp vào **Bắt đầu** và làm theo các bước trên màn hình
6. Hoàn thành quá trình xác minh để bật Xác minh 2 bước

## Bước 2: Tạo App Password

1. Sau khi đã bật Xác minh 2 bước, truy cập lại [Tài khoản Google](https://myaccount.google.com/)
2. Chọn **Bảo mật** từ menu bên trái
3. Trong phần "Đăng nhập vào Google", chọn **Mật khẩu ứng dụng**
4. Ở cuối trang, nhấp vào **Chọn ứng dụng** và chọn **Thư**
5. Nhấp vào **Chọn thiết bị** và chọn **Khác (Tên tùy chỉnh)**
6. Nhập "Beauty Spa Booking" làm tên
7. Nhấp vào **Tạo**
8. Google sẽ hiển thị một mật khẩu gồm 16 ký tự (thường được hiển thị dưới dạng 4 nhóm, mỗi nhóm 4 ký tự)
9. **Sao chép mật khẩu này** - đây là App Password của bạn

## Bước 3: Cập nhật file .env

1. Mở file `.env` trong thư mục gốc của ứng dụng
2. Tìm phần cấu hình email và cập nhật như sau:
   ```
   # Gmail Configuration for production
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=ntuhealthbooking@gmail.com
   MAIL_PASSWORD=your-16-character-app-password
   MAIL_ENCRYPTION=tls
   
   # Log driver for testing (uncomment to log emails instead of sending)
   #MAIL_MAILER=log
   ```
   
   Thay `your-16-character-app-password` bằng App Password bạn đã sao chép ở Bước 2.
   
   **Lưu ý**: Nhập mật khẩu không có dấu cách, mặc dù Google hiển thị nó có dấu cách.

3. Lưu file

## Bước 4: Xóa bộ nhớ đệm cấu hình

Chạy lệnh sau để xóa bộ nhớ đệm cấu hình:

```bash
php artisan config:clear
```

## Bước 5: Kiểm tra gửi email

Chạy lệnh sau để kiểm tra gửi email:

```bash
php artisan email:send-test duynguyen.11032003@gmail.com
```

Nếu email được gửi thành công, bạn sẽ nhận được thông báo "Test email sent successfully!".

## Xử lý sự cố

Nếu bạn gặp vấn đề khi thiết lập App Password hoặc gửi email:

1. **Kiểm tra Xác minh 2 bước**: Đảm bảo rằng Xác minh 2 bước đã được bật cho tài khoản Gmail.
2. **Kiểm tra App Password**: Đảm bảo rằng bạn đã nhập đúng App Password vào file `.env`.
3. **Kiểm tra cài đặt Gmail**: Đảm bảo rằng tài khoản Gmail không có cài đặt bảo mật nào khác ngăn chặn việc truy cập từ ứng dụng.
4. **Kiểm tra nhật ký**: Xem file `storage/logs/laravel.log` để tìm lỗi liên quan đến email.

## Chế độ thử nghiệm

Nếu bạn muốn thử nghiệm hệ thống email mà không thực sự gửi email, bạn có thể sử dụng chế độ log:

1. Mở file `.env`
2. Thay đổi `MAIL_MAILER=smtp` thành `MAIL_MAILER=log`
3. Xóa bộ nhớ đệm cấu hình: `php artisan config:clear`

Khi ở chế độ log, hệ thống sẽ ghi nội dung email vào file `storage/logs/laravel.log` thay vì gửi email thực sự.
