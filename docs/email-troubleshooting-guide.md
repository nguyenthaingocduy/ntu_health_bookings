# Hướng dẫn khắc phục sự cố email

Nếu khách hàng không nhận được email xác nhận đặt lịch, hãy làm theo các bước sau để khắc phục sự cố:

## 1. Kiểm tra cấu hình email

### Kiểm tra file .env

1. Mở file `.env` trong thư mục gốc của ứng dụng
2. Kiểm tra cấu hình email:
   ```
   MAIL_MAILER=log  # Đang ở chế độ log, email sẽ được ghi vào file log thay vì gửi đi
   
   # Gmail Configuration for production (uncomment and configure when ready)
   #MAIL_MAILER=smtp
   #MAIL_HOST=smtp.gmail.com
   #MAIL_PORT=587
   #MAIL_USERNAME=ntuhealthbooking@gmail.com
   # You need to generate an App Password in your Google account
   #MAIL_PASSWORD=your-16-character-app-password
   #MAIL_ENCRYPTION=tls
   ```

3. Nếu bạn muốn gửi email thực sự, hãy cập nhật cấu hình như sau:
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
   
   Thay `your-16-character-app-password` bằng App Password bạn đã tạo theo hướng dẫn trong file `docs/gmail-app-password-setup.md`.

4. Sau khi cập nhật, chạy lệnh sau để xóa bộ nhớ đệm cấu hình:
   ```bash
   php artisan config:clear
   ```

## 2. Kiểm tra nhật ký email

### Kiểm tra file log

1. Mở file `storage/logs/laravel.log`
2. Tìm kiếm các dòng liên quan đến email, ví dụ:
   ```
   [2025-04-21 07:34:58] local.DEBUG: From: Beauty Spa Booking <ntuhealthbooking@gmail.com>
   To: duynguyen.11032003@gmail.com
   Subject: Test Email from Beauty Spa Booking
   ```

3. Nếu bạn thấy các dòng như trên, điều đó có nghĩa là email đã được ghi vào log (khi ở chế độ `MAIL_MAILER=log`).

### Kiểm tra bảng email_logs

1. Truy cập cơ sở dữ liệu của bạn
2. Kiểm tra bảng `email_logs`
3. Tìm kiếm các bản ghi có `to` là địa chỉ email của khách hàng
4. Kiểm tra trạng thái (`status`) của các bản ghi này

## 3. Kiểm tra thư mục spam

Yêu cầu khách hàng kiểm tra thư mục spam/junk trong hộp thư của họ. Đôi khi, email từ hệ thống có thể bị phân loại là spam.

## 4. Thử nghiệm gửi email

### Sử dụng công cụ kiểm tra email

1. Truy cập trang kiểm tra email:
   ```
   http://your-website.com/email-test
   ```

2. Nhập địa chỉ email của khách hàng và gửi email kiểm tra
3. Kiểm tra xem khách hàng có nhận được email không

### Sử dụng script kiểm tra

1. Chạy script kiểm tra email:
   ```bash
   php test-email-logging.php customer@example.com
   ```

2. Kiểm tra xem email có được ghi vào log không

## 5. Cấu hình Gmail

Nếu bạn đang sử dụng Gmail để gửi email, hãy đảm bảo rằng:

1. Tài khoản Gmail `ntuhealthbooking@gmail.com` đã được thiết lập đúng cách
2. Xác minh 2 bước đã được bật
3. App Password đã được tạo và cấu hình đúng trong file `.env`
4. Tài khoản Gmail không bị giới hạn bởi các chính sách bảo mật của Google

## 6. Giải pháp tạm thời

Nếu vẫn không thể gửi email, bạn có thể sử dụng một trong các giải pháp tạm thời sau:

### Sử dụng dịch vụ email khác

1. Đăng ký một dịch vụ email như Mailgun, SendGrid, hoặc Amazon SES
2. Cập nhật cấu hình email trong file `.env` để sử dụng dịch vụ này

### Sử dụng chế độ log

1. Cập nhật file `.env` để sử dụng chế độ log:
   ```
   MAIL_MAILER=log
   ```

2. Tạo một trang web để khách hàng có thể xem nội dung email đã được ghi vào log

## 7. Liên hệ hỗ trợ

Nếu bạn đã thử tất cả các giải pháp trên mà vẫn không thể gửi email, hãy liên hệ với đội ngũ hỗ trợ kỹ thuật để được trợ giúp thêm.
