# Hướng dẫn thiết lập APP_URL cho hệ thống Beauty Spa Booking

Để đảm bảo các liên kết trong email hoạt động chính xác, bạn cần thiết lập `APP_URL` trong file `.env` của ứng dụng. Đây là một bước quan trọng để đảm bảo khách hàng có thể nhấp vào các liên kết trong email và truy cập vào hệ thống.

## Tại sao cần thiết lập APP_URL?

Khi hệ thống gửi email có chứa liên kết (ví dụ: liên kết xem lịch hẹn), Laravel sẽ sử dụng giá trị `APP_URL` để tạo URL đầy đủ. Nếu `APP_URL` không được thiết lập đúng, các liên kết trong email sẽ trỏ đến địa chỉ không chính xác (ví dụ: `http://localhost` hoặc `127.0.0.1`), khiến khách hàng không thể truy cập.

## Cách thiết lập APP_URL

1. Mở file `.env` trong thư mục gốc của ứng dụng
2. Tìm dòng `APP_URL=http://localhost` (hoặc giá trị tương tự)
3. Thay đổi giá trị này thành URL thực tế của trang web của bạn, ví dụ:
   ```
   APP_URL=https://your-production-domain.com
   ```
   
   Thay `your-production-domain.com` bằng tên miền thực tế của trang web của bạn.

4. Lưu file

## Xóa bộ nhớ đệm cấu hình

Sau khi thay đổi `APP_URL`, bạn cần xóa bộ nhớ đệm cấu hình để thay đổi có hiệu lực:

```bash
php artisan config:clear
```

## Kiểm tra thiết lập

Để kiểm tra xem `APP_URL` đã được thiết lập đúng chưa, bạn có thể:

1. Chạy lệnh sau để xem giá trị hiện tại:
   ```bash
   php artisan tinker --execute="echo config('app.url');"
   ```

2. Gửi email kiểm tra và kiểm tra các liên kết trong email:
   ```bash
   php artisan email:send-test your@email.com
   ```

## Các trường hợp đặc biệt

### Phát triển cục bộ

Nếu bạn đang phát triển cục bộ, bạn có thể thiết lập `APP_URL` thành:

```
APP_URL=http://localhost:8000
```

Hoặc cổng mà bạn đang sử dụng cho máy chủ phát triển.

### Nhiều môi trường

Nếu bạn có nhiều môi trường (phát triển, kiểm thử, sản xuất), bạn nên có các file `.env` riêng cho từng môi trường với giá trị `APP_URL` phù hợp.

## Xử lý sự cố

Nếu các liên kết trong email vẫn không hoạt động sau khi thiết lập `APP_URL`:

1. Kiểm tra xem bạn đã xóa bộ nhớ đệm cấu hình chưa
2. Kiểm tra xem URL trong email có đúng định dạng không
3. Kiểm tra xem tên miền của bạn có trỏ đến máy chủ đúng không
4. Kiểm tra xem máy chủ web của bạn có được cấu hình để xử lý các yêu cầu đến tên miền của bạn không
