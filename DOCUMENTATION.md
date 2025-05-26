# TÀI LIỆU HƯỚNG DẪN DỰ ÁN BEAUTY SALON BOOKING

## 📁 CẤU TRÚC THƯ MỤC CHÍNH

### 🎯 Controllers (app/Http/Controllers/)
```
app/Http/Controllers/
├── Admin/                          # Controllers cho Admin
│   ├── DashboardController.php     # Dashboard admin - dòng 15-50
│   ├── AppointmentController.php   # Quản lý lịch hẹn - dòng 25-200
│   ├── UserController.php          # Quản lý người dùng - dòng 30-150
│   ├── ServiceController.php       # Quản lý dịch vụ - dòng 20-180
│   ├── PromotionController.php     # Quản lý khuyến mãi - dòng 25-350
│   └── InvoiceController.php       # Quản lý hóa đơn - dòng 20-250
├── Customer/                       # Controllers cho Khách hàng
│   ├── AppointmentController.php   # Đặt lịch khách hàng - dòng 108-385
│   ├── DashboardController.php     # Dashboard khách hàng - dòng 15-80
│   └── ProfileController.php       # Hồ sơ cá nhân - dòng 20-120
├── LeTan/                         # Controllers cho Lễ tân
│   ├── DashboardController.php     # Dashboard lễ tân - dòng 20-127
│   ├── AppointmentController.php   # Quản lý lịch hẹn - dòng 25-300
│   ├── CustomerController.php      # Quản lý khách hàng - dòng 20-200
│   ├── ReminderController.php      # Nhắc lịch hẹn - dòng 22-214
│   └── PaymentController.php       # Thanh toán - dòng 15-180
├── NVKT/                          # Controllers cho Nhân viên kỹ thuật
│   ├── DashboardController.php     # Dashboard NVKT - dòng 15-100
│   └── AppointmentController.php   # Xử lý lịch hẹn - dòng 20-150
└── Api/                           # API Controllers
    ├── PromotionController.php     # API khuyến mãi - dòng 118-282
    ├── TimeSlotController.php      # API khung giờ - dòng 93-296
    └── CustomerController.php      # API khách hàng - dòng 15-100
```

### 🗃️ Models (app/Models/)
```
app/Models/
├── User.php                       # Model người dùng - dòng 15-200
├── Appointment.php                # Model lịch hẹn - dòng 20-300
├── Service.php                    # Model dịch vụ - dòng 15-350
├── Promotion.php                  # Model khuyến mãi - dòng 20-160
├── Time.php                       # Model khung giờ - dòng 15-59
├── Reminder.php                   # Model nhắc nhở - dòng 15-54
├── Invoice.php                    # Model hóa đơn - dòng 15-120
├── Payment.php                    # Model thanh toán - dòng 15-80
├── Role.php                       # Model vai trò - dòng 15-60
└── Permission.php                 # Model quyền - dòng 15-50
```

### 🎨 Views (resources/views/)
```
resources/views/
├── layouts/                       # Layout chính
│   ├── app.blade.php             # Layout khách hàng - dòng 1-200
│   ├── admin.blade.php           # Layout admin - dòng 1-300
│   ├── le-tan.blade.php          # Layout lễ tân - dòng 1-400
│   └── nvkt.blade.php            # Layout NVKT - dòng 1-250
├── customer/                      # Views khách hàng
│   ├── dashboard.blade.php       # Dashboard - dòng 1-150
│   └── appointments/
│       ├── create.blade.php      # Đặt lịch - dòng 1-1206
│       ├── index.blade.php       # Danh sách lịch - dòng 1-200
│       └── show.blade.php        # Chi tiết lịch - dòng 1-300
├── admin/                        # Views admin
│   ├── dashboard.blade.php       # Dashboard - dòng 1-200
│   ├── appointments/             # Quản lý lịch hẹn
│   ├── users/                    # Quản lý người dùng
│   ├── services/                 # Quản lý dịch vụ
│   └── promotions/               # Quản lý khuyến mãi
├── le-tan/                       # Views lễ tân
│   ├── dashboard.blade.php       # Dashboard - dòng 1-250
│   ├── appointments/             # Quản lý lịch hẹn
│   ├── customers/                # Quản lý khách hàng
│   └── reminders/                # Nhắc lịch hẹn
│       ├── index.blade.php       # Danh sách - dòng 1-200
│       ├── create.blade.php      # Tạo mới - dòng 1-150
│       └── show.blade.php        # Chi tiết - dòng 1-180
└── nvkt/                         # Views NVKT
    ├── dashboard.blade.php       # Dashboard - dòng 1-150
    └── appointments/             # Xử lý lịch hẹn
```

### 🛣️ Routes (routes/)
```
routes/
├── web.php                       # Routes chính - dòng 1-800
├── admin.php                     # Routes admin - dòng 1-150
├── le-tan.php                    # Routes lễ tân - dòng 1-120
├── nvkt.php                      # Routes NVKT - dòng 1-80
└── api.php                       # API routes - dòng 1-108
```

## 🔧 CÁC CHỨC NĂNG CHÍNH VÀ VỊ TRÍ CODE

### 1. 📅 HỆ THỐNG ĐẶT LỊCH

#### Đặt lịch khách hàng:
- **Controller**: `app/Http/Controllers/Customer/AppointmentController.php`
  - `create()` - dòng 40-106: Hiển thị form đặt lịch
  - `store()` - dòng 108-385: Xử lý đặt lịch mới
  - `show()` - dòng 387-467: Xem chi tiết lịch hẹn
  - `cancel()` - dòng 469-517: Hủy lịch hẹn

- **View**: `resources/views/customer/appointments/create.blade.php`
  - Form đặt lịch - dòng 61-278
  - JavaScript xử lý - dòng 362-1206

#### API kiểm tra khung giờ:
- **Controller**: `app/Http/Controllers/Api/TimeSlotController.php`
  - `checkAvailableSlots()` - dòng 93-296: Kiểm tra khung giờ trống

### 2. 💰 HỆ THỐNG KHUYẾN MÃI

#### Quản lý khuyến mãi (Admin):
- **Controller**: `app/Http/Controllers/Admin/PromotionController.php`
  - `index()` - dòng 25-50: Danh sách khuyến mãi
  - `create()` - dòng 60-80: Tạo khuyến mãi mới
  - `store()` - dòng 90-150: Lưu khuyến mãi
  - `validateCode()` - dòng 320-372: Validate mã khuyến mãi

#### API khuyến mãi:
- **Controller**: `app/Http/Controllers/Api/PromotionController.php`
  - `validateCode()` - dòng 118-162: Validate mã khuyến mãi
  - `getActivePromotions()` - dòng 25-110: Lấy khuyến mãi đang hoạt động

#### Tính giá với khuyến mãi:
- **Service**: `app/Services/PricingService.php`
  - `calculateFinalPrice()` - dòng 22-155: Tính giá cuối cùng sau khuyến mãi

### 3. 👥 HỆ THỐNG NGƯỜI DÙNG VÀ PHÂN QUYỀN

#### Model User:
- **File**: `app/Models/User.php`
  - Relationships - dòng 50-100
  - Permissions - dòng 120-180

#### Middleware phân quyền:
- **File**: `app/Http/Middleware/CheckUserRole.php`
  - `handle()` - dòng 15-40: Kiểm tra vai trò người dùng

### 4. 🔔 HỆ THỐNG NHẮC NHỞ

#### Controller nhắc nhở:
- **File**: `app/Http/Controllers/LeTan/ReminderController.php`
  - `index()` - dòng 22-45: Danh sách nhắc nhở
  - `create()` - dòng 68-76: Tạo nhắc nhở mới
  - `sendReminder()` - dòng 175-214: Gửi nhắc nhở

## 📊 DATABASE SCHEMA

### Bảng chính:
```sql
-- users: Người dùng (dòng migration: 2014_10_12_000000_create_users_table.php)
-- appointments: Lịch hẹn (dòng migration: 2024_xx_xx_create_appointments_table.php)
-- services: Dịch vụ (dòng migration: 2024_xx_xx_create_services_table.php)
-- promotions: Khuyến mãi (dòng migration: 2024_xx_xx_create_promotions_table.php)
-- times: Khung giờ (dòng migration: 2024_xx_xx_create_times_table.php)
-- reminders: Nhắc nhở (dòng migration: 2024_xx_xx_create_reminders_table.php)
```

## 🎯 CÁC API ENDPOINTS QUAN TRỌNG

### API Routes (routes/api.php):
```
GET  /api/check-available-slots     # Kiểm tra khung giờ - dòng 27
POST /api/validate-promotion        # Validate mã khuyến mãi - dòng 43
GET  /api/active-promotions         # Lấy khuyến mãi đang hoạt động - dòng 42
GET  /api/customers/search          # Tìm kiếm khách hàng - dòng 39
```

## 🔍 DEBUGGING VÀ TESTING

### Debug Routes (routes/web.php):
```
GET /debug-promotion/{code}         # Debug mã khuyến mãi - dòng 409-496
GET /test-promotion-validation      # Test validation - dòng 498-590
GET /test-booking-promotion         # Test đặt lịch với khuyến mãi - dòng 592-719
GET /debug-reminders               # Debug nhắc nhở - dòng 721-779
```

## 📝 CHI TIẾT CÁC CHỨC NĂNG QUAN TRỌNG

### 1. 🎯 CHỨC NĂNG ĐẶT LỊCH HẸN

#### A. Form đặt lịch (resources/views/customer/appointments/create.blade.php):
```php
// Bước 1: Chọn dịch vụ (dòng 81-175)
<div class="mb-8" id="step-1-content">
    // Grid hiển thị dịch vụ với giá và khuyến mãi

// Bước 2: Chọn thời gian (dòng 177-213)
<div class="mb-8" id="step-2-content">
    // Date picker và time slots

// Bước 3: Thông tin khách hàng và mã khuyến mãi (dòng 215-270)
<div class="mb-8" id="step-3-content">
    // Form thông tin cá nhân và input mã khuyến mãi
```

#### B. JavaScript xử lý đặt lịch:
```javascript
// Hàm chọn thời gian (dòng 363-414)
function selectTimeSlot(slotId)

// Hàm kiểm tra khung giờ trống (dòng 700-862)
function fetchAvailableTimeSlots(serviceId, date)

// Hàm validate mã khuyến mãi (dòng 871-952)
function validatePromotionCode()

// Event listeners (dòng 1172-1198)
// - Click nút áp dụng mã khuyến mãi
// - Enter trên input mã khuyến mãi
// - Submit form
```

#### C. Controller xử lý đặt lịch:
```php
// app/Http/Controllers/Customer/AppointmentController.php

// Hiển thị form đặt lịch (dòng 40-106)
public function create()

// Xử lý đặt lịch mới (dòng 108-385)
public function store(Request $request)
{
    // Validate input (dòng 110-116)
    // Kiểm tra khung giờ (dòng 119-123)
    // Tìm nhân viên phù hợp (dòng 130-180)
    // Tính giá với khuyến mãi (dòng 207-224)
    // Tạo appointment (dòng 244-258)
    // Cập nhật usage count mã khuyến mãi (dòng 261-280)
    // Gửi email xác nhận (dòng 288-370)
}
```

### 2. 💸 HỆ THỐNG TÍNH GIÁ VÀ KHUYẾN MÃI

#### A. PricingService (app/Services/PricingService.php):
```php
// Hàm tính giá cuối cùng (dòng 22-155)
public function calculateFinalPrice(Service $service, ?string $promotionCode = null, ?User $user = null)
{
    // 1. Giá gốc dịch vụ (dòng 35-45)
    // 2. Áp dụng mã khuyến mãi (dòng 78-136)
    // 3. Áp dụng giảm giá theo loại khách hàng (dòng 127-139)
    // 4. Tính tổng giảm giá (dòng 144-154)
}
```

#### B. Model Promotion (app/Models/Promotion.php):
```php
// Tính giảm giá (dòng 123-146)
public function calculateDiscount($amount)

// Scope active promotions (dòng 148-157)
public function scopeActive($query)

// Kiểm tra tính hợp lệ (dòng 80-120)
public function getIsValidAttribute()
```

#### C. API validate mã khuyến mãi:
```php
// app/Http/Controllers/Api/PromotionController.php

// Validate mã khuyến mãi (dòng 118-162)
public function validateCode(Request $request)
{
    // Validate input (dòng 120-123)
    // Tìm promotion (dòng 128-135)
    // Kiểm tra tính hợp lệ (dòng 137-142)
    // Kiểm tra minimum purchase (dòng 144-149)
    // Tính discount (dòng 151-161)
}
```

### 3. ⏰ HỆ THỐNG QUẢN LÝ THỜI GIAN

#### A. Model Time (app/Models/Time.php):
```php
// Kiểm tra khung giờ đầy (dòng 28-31)
public function isFull()

// Lấy số chỗ trống (dòng 38-41)
public function getAvailableSlotsAttribute()

// Format thời gian (dòng 48-52)
public function getFormattedTimeAttribute()
```

#### B. API kiểm tra khung giờ:
```php
// app/Http/Controllers/Api/TimeSlotController.php

// Kiểm tra khung giờ khả dụng (dòng 93-296)
public function checkAvailableSlots(Request $request)
{
    // Validate request (dòng 106-110)
    // Lấy tất cả time slots (dòng 130-142)
    // Lấy appointments đã đặt (dòng 145-177)
    // Filter khung giờ khả dụng (dòng 180-253)
    // Return JSON response (dòng 257-278)
}
```

### 4. 🔐 HỆ THỐNG PHÂN QUYỀN

#### A. Middleware CheckUserRole:
```php
// app/Http/Middleware/CheckUserRole.php

// Kiểm tra vai trò (dòng 15-40)
public function handle($request, Closure $next, $role)
```

#### B. Routes với middleware:
```php
// routes/admin.php - Routes cho Admin
Route::middleware(['auth', CheckUserRole::class.':Admin'])

// routes/le-tan.php - Routes cho Lễ tân
Route::middleware(['auth', CheckUserRole::class.':Receptionist'])

// routes/nvkt.php - Routes cho NVKT
Route::middleware(['auth', CheckUserRole::class.':Employee'])
```

### 5. 📧 HỆ THỐNG EMAIL VÀ NHẮC NHỞ

#### A. Gửi email xác nhận đặt lịch:
```php
// app/Http/Controllers/Customer/AppointmentController.php (dòng 288-370)

// Tạo email data (dòng 316-327)
// Gửi email (dòng 329-333)
// Log email (dòng 337-349)
```

#### B. Hệ thống nhắc nhở:
```php
// app/Http/Controllers/LeTan/ReminderController.php

// Tạo nhắc nhở (dòng 84-108)
public function store(Request $request)

// Gửi nhắc nhở (dòng 175-214)
public function sendReminder($id)
```

## 🛠️ CÁC HELPER VÀ UTILITY

### A. TimeHelper:
```php
// app/Helpers/TimeHelper.php
// Format thời gian hiển thị
```

### B. UrlHelper:
```php
// app/Helpers/UrlHelper.php
// Tạo URL cho email
```

## 🎨 FRONTEND ASSETS

### A. CSS Framework:
- **Tailwind CSS**: Styling chính cho toàn bộ ứng dụng
- **Custom CSS**: Trong các file blade template

### B. JavaScript:
- **Vanilla JS**: Xử lý tương tác người dùng
- **Fetch API**: Gọi API endpoints
- **Event Listeners**: Xử lý form và user actions

## 📱 RESPONSIVE DESIGN

### A. Layout breakpoints:
```css
/* Mobile first approach */
sm: 640px   /* Small devices */
md: 768px   /* Medium devices */
lg: 1024px  /* Large devices */
xl: 1280px  /* Extra large devices */
```

### B. Grid system:
```html
<!-- Responsive grid classes -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
```
