# NTU Health Booking - Hệ Thống Đặt Lịch Chăm Sóc Sức Khỏe

<p align="center">
  <img src="public/images/logo.png" alt="NTU Health Booking Logo" width="200">
</p>

<p align="center">
  <a href="#giới-thiệu">Giới Thiệu</a> •
  <a href="#tính-năng">Tính Năng</a> •
  <a href="#công-nghệ">Công Nghệ</a> •
  <a href="#cài-đặt">Cài Đặt</a> •
  <a href="#sử-dụng">Sử Dụng</a> •
  <a href="#đóng-góp">Đóng Góp</a> •
  <a href="#giấy-phép">Giấy Phép</a>
</p>

## Giới Thiệu

**NTU Health Booking** là hệ thống đặt lịch hẹn trực tuyến dành cho dịch vụ chăm sóc sức khỏe và làm đẹp tại Trường Đại học Nha Trang. Dự án được phát triển nhằm mục đích tạo ra một nền tảng hiện đại, tiện lợi giúp nhân viên và sinh viên của trường dễ dàng đặt lịch sử dụng các dịch vụ chăm sóc sức khỏe và làm đẹp.

Hệ thống được thiết kế với giao diện thân thiện, dễ sử dụng, đồng thời tích hợp nhiều tính năng quản lý hiệu quả cho cả khách hàng, nhân viên và quản trị viên. Từ việc đặt lịch, quản lý thông tin cá nhân đến theo dõi lịch sử sử dụng dịch vụ, NTU Health Booking mang đến trải nghiệm toàn diện cho người dùng.

## Tính Năng

### 👤 Dành Cho Khách Hàng
- **Đặt lịch hẹn trực tuyến** - Chọn dịch vụ, ngày giờ và thanh toán
- **Xem lịch sử lịch hẹn** - Theo dõi các lịch hẹn đã đặt, đang chờ hoặc đã hoàn thành
- **Quản lý thông tin cá nhân** - Cập nhật hồ sơ, đổi mật khẩu
- **Đánh giá dịch vụ** - Đánh giá sau khi sử dụng dịch vụ

### 👨‍💼 Dành Cho Admin
- **Tổng quan** - Thống kê doanh thu, lịch hẹn, khách hàng mới
- **Quản lý lịch hẹn** - Xem, chỉnh sửa, xác nhận, hủy lịch hẹn
- **Lịch hẹn theo lịch** - Hiển thị lịch hẹn dạng lịch tháng, tuần, ngày
- **Quản lý nhân viên khám sức khỏe** - Thêm, sửa, xóa thông tin nhân viên
- **Quản lý dịch vụ & sản phẩm** - Thêm, sửa, xóa dịch vụ và sản phẩm
- **Quản lý khách hàng** - Xem thông tin và lịch sử sử dụng dịch vụ của khách hàng
- **Quản lý hóa đơn** - Tạo, in hóa đơn và thống kê doanh thu
- **Quản lý tin tức** - Đăng tin, bài viết, khuyến mãi
- **Cài đặt hệ thống** - Tùy chỉnh thông tin cửa hàng, giờ làm việc
- **Quản lý phân quyền** - Phân quyền cho các tài khoản trong hệ thống

### 💇‍♂️ Dành Cho Nhân Viên
#### Lễ Tân
- **Quản lý thông tin khách hàng** - Xem và cập nhật thông tin khách hàng
- **Tạo lịch hẹn** - Đặt lịch cho khách hàng
- **Xác nhận thanh toán** - Xác nhận thanh toán cho các dịch vụ

#### Nhân Viên Kỹ Thuật
- **Lịch làm việc** - Xem lịch hẹn được phân công
- **Cập nhật trạng thái dịch vụ** - Cập nhật tiến độ thực hiện dịch vụ
- **Thống kê** - Theo dõi số lượng khách hàng, doanh thu

## Công Nghệ

### Backend
- **PHP Laravel** - Framework PHP mạnh mẽ và linh hoạt
- **MySQL** - Hệ quản trị cơ sở dữ liệu

### Frontend
- **HTML, CSS, JavaScript** - Nền tảng cơ bản
- **Bootstrap 5** - Framework CSS phổ biến
- **Tailwind CSS** - Framework CSS tiện lợi với các tiện ích
- **FullCalendar.js** - Thư viện hiển thị lịch
- **Chart.js** - Thư viện tạo biểu đồ

## Cài Đặt

### Yêu Cầu Hệ Thống
- PHP >= 8.1
- MySQL >= 5.7
- Composer
- Node.js & NPM

### Các Bước Cài Đặt

1. **Clone dự án**
   ```bash
   git clone https://github.com/your-username/ntu-health-booking.git
   cd ntu-health-booking
   ```

2. **Cài đặt các gói phụ thuộc**
   ```bash
   composer install
   npm install
   ```

3. **Cấu hình môi trường**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Cấu hình cơ sở dữ liệu**
   - Chỉnh sửa file `.env` với thông tin cơ sở dữ liệu của bạn

5. **Chạy migration và seeder**
   ```bash
   php artisan migrate --seed
   ```

6. **Tạo liên kết storage**
   ```bash
   php artisan storage:link
   ```

7. **Biên dịch tài nguyên frontend**
   ```bash
   npm run dev
   ```

8. **Khởi chạy ứng dụng**
   ```bash
   php artisan serve
   ```

## Sử Dụng

### Tài Khoản Mặc Định

- **Admin**
  - Email: admin@example.com
  - Mật khẩu: password

- **Lễ Tân**
  - Email: receptionist@example.com
  - Mật khẩu: password

- **Nhân Viên Kỹ Thuật**
  - Email: technician@example.com
  - Mật khẩu: password

- **Khách Hàng**
  - Email: customer@example.com
  - Mật khẩu: password

### Hướng Dẫn Cơ Bản

1. **Đăng nhập** vào hệ thống với tài khoản tương ứng
2. **Khám phá các tính năng** dựa trên vai trò của bạn
3. **Đặt lịch hẹn** (đối với khách hàng) hoặc **quản lý lịch hẹn** (đối với nhân viên/admin)

## Đóng Góp

Chúng tôi rất hoan nghênh mọi đóng góp để cải thiện dự án. Nếu bạn muốn đóng góp, vui lòng:

1. Fork dự án
2. Tạo nhánh tính năng (`git checkout -b feature/amazing-feature`)
3. Commit thay đổi của bạn (`git commit -m 'Add some amazing feature'`)
4. Push lên nhánh (`git push origin feature/amazing-feature`)
5. Mở Pull Request

## Giấy Phép

Dự án này được phân phối dưới giấy phép MIT. Xem file `LICENSE` để biết thêm thông tin.

---

Phát triển bởi Sinh Viên Trường Đại học Nha Trang © 2025
