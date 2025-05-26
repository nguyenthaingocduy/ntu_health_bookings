# 📖 HƯỚNG DẪN TÌM KIẾM CODE - BEAUTY SALON BOOKING

## 🎯 MỤC ĐÍCH TÀI LIỆU

Tài liệu này giúp bạn nhanh chóng tìm thấy vị trí code cụ thể trong dự án để:
- **Bảo vệ luận án**: Trình bày code cho giảng viên
- **Phát triển tính năng**: Tìm hiểu cách thức hoạt động
- **Debug lỗi**: Xác định vị trí code gây ra vấn đề
- **Mở rộng hệ thống**: Hiểu cấu trúc để thêm tính năng mới

---

## 🔍 TÌM KIẾM THEO CHỨC NĂNG

### 1. 📅 **CHỨC NĂNG ĐẶT LỊCH HẸN**

#### 🎯 **Tôi muốn tìm code xử lý đặt lịch:**
```
📁 File: app/Http/Controllers/Customer/AppointmentController.php
📍 Dòng 108-385: Hàm store() - Xử lý đặt lịch mới
📍 Dòng 110-116: Validation input từ form
📍 Dòng 119-123: Kiểm tra khung giờ còn trống
📍 Dòng 130-180: Tìm nhân viên phù hợp
📍 Dòng 207-224: Tính giá với mã khuyến mãi
📍 Dòng 244-258: Tạo appointment record
📍 Dòng 288-370: Gửi email xác nhận
```

#### 🎯 **Tôi muốn tìm giao diện đặt lịch:**
```
📁 File: resources/views/customer/appointments/create.blade.php
📍 Dòng 61-175: Form chọn dịch vụ (Bước 1)
📍 Dòng 177-213: Chọn thời gian (Bước 2)  
📍 Dòng 215-270: Thông tin khách hàng (Bước 3)
📍 Dòng 363-414: JavaScript chọn time slot
📍 Dòng 700-862: JavaScript load khung giờ trống
📍 Dòng 871-952: JavaScript validate mã khuyến mãi
```

#### 🎯 **Tôi muốn tìm API kiểm tra khung giờ:**
```
📁 File: app/Http/Controllers/Api/TimeSlotController.php
📍 Dòng 93-296: checkAvailableSlots() - API chính
📍 Dòng 106-110: Validate request
📍 Dòng 130-142: Lấy tất cả time slots
📍 Dòng 145-177: Lấy appointments đã đặt
📍 Dòng 180-253: Filter khung giờ khả dụng
```

### 2. 💰 **CHỨC NĂNG KHUYẾN MÃI**

#### 🎯 **Tôi muốn tìm code tính giá khuyến mãi:**
```
📁 File: app/Services/PricingService.php
📍 Dòng 22-155: calculateFinalPrice() - Hàm chính
📍 Dòng 78-136: Logic áp dụng mã khuyến mãi
📍 Dòng 98-136: Kiểm tra điều kiện và tính discount
```

#### 🎯 **Tôi muốn tìm API validate mã khuyến mãi:**
```
📁 File: app/Http/Controllers/Api/PromotionController.php
📍 Dòng 118-162: validateCode() - API validate
📍 Dòng 120-123: Validate input
📍 Dòng 128-135: Tìm promotion trong DB
📍 Dòng 137-142: Kiểm tra tính hợp lệ
📍 Dòng 144-149: Kiểm tra minimum purchase
📍 Dòng 151-161: Tính và trả về discount
```

#### 🎯 **Tôi muốn tìm model Promotion:**
```
📁 File: app/Models/Promotion.php
📍 Dòng 80-120: getIsValidAttribute() - Kiểm tra hợp lệ
📍 Dòng 123-146: calculateDiscount() - Tính giảm giá
📍 Dòng 148-157: scopeActive() - Lấy promotion đang hoạt động
```

### 3. ⏰ **CHỨC NĂNG QUẢN LÝ THỜI GIAN**

#### 🎯 **Tôi muốn tìm model Time:**
```
📁 File: app/Models/Time.php
📍 Dòng 28-31: isFull() - Kiểm tra khung giờ đầy
📍 Dòng 38-41: getAvailableSlotsAttribute() - Số chỗ trống
📍 Dòng 48-52: getFormattedTimeAttribute() - Format thời gian
```

#### 🎯 **Tôi muốn tìm logic cập nhật booked_count:**
```
📁 File: app/Http/Controllers/Customer/AppointmentController.php
📍 Dòng 244-258: Tạo appointment và cập nhật booked_count
📍 Dòng 469-517: Hủy lịch và giảm booked_count
```

### 4. 👥 **CHỨC NĂNG NGƯỜI DÙNG**

#### 🎯 **Tôi muốn tìm authentication:**
```
📁 File: app/Http/Controllers/Auth/LoginController.php
📍 Dòng 20-50: Xử lý đăng nhập
📁 File: app/Http/Middleware/CheckUserRole.php
📍 Dòng 15-40: Kiểm tra vai trò người dùng
```

#### 🎯 **Tôi muốn tìm model User:**
```
📁 File: app/Models/User.php
📍 Dòng 50-100: Relationships với các model khác
📍 Dòng 120-180: Phương thức kiểm tra permissions
```

### 5. 🔔 **CHỨC NĂNG NHẮC NHỞ**

#### 🎯 **Tôi muốn tìm controller nhắc nhở:**
```
📁 File: app/Http/Controllers/LeTan/ReminderController.php
📍 Dòng 22-45: index() - Danh sách nhắc nhở
📍 Dòng 84-108: store() - Tạo nhắc nhở mới
📍 Dòng 175-214: sendReminder() - Gửi nhắc nhở
```

#### 🎯 **Tôi muốn tìm giao diện nhắc nhở:**
```
📁 File: resources/views/le-tan/reminders/index.blade.php
📍 Dòng 1-200: Danh sách nhắc nhở
📁 File: resources/views/le-tan/reminders/create.blade.php
📍 Dòng 1-150: Form tạo nhắc nhở mới
```

---

## 🗂️ TÌM KIẾM THEO LOẠI FILE

### 📋 **CONTROLLERS**
```
📁 Admin Controllers: app/Http/Controllers/Admin/
  ├── DashboardController.php (dòng 15-50: Dashboard admin)
  ├── AppointmentController.php (dòng 25-200: Quản lý lịch hẹn)
  ├── UserController.php (dòng 30-150: Quản lý người dùng)
  ├── ServiceController.php (dòng 20-180: Quản lý dịch vụ)
  └── PromotionController.php (dòng 25-350: Quản lý khuyến mãi)

📁 Customer Controllers: app/Http/Controllers/Customer/
  ├── AppointmentController.php (dòng 108-385: Đặt lịch)
  └── DashboardController.php (dòng 15-80: Dashboard khách hàng)

📁 Staff Controllers: app/Http/Controllers/LeTan/
  ├── AppointmentController.php (dòng 25-300: Quản lý lịch hẹn)
  ├── CustomerController.php (dòng 20-200: Quản lý khách hàng)
  └── ReminderController.php (dòng 22-214: Nhắc lịch hẹn)

📁 API Controllers: app/Http/Controllers/Api/
  ├── PromotionController.php (dòng 118-282: API khuyến mãi)
  ├── TimeSlotController.php (dòng 93-296: API khung giờ)
  └── CustomerController.php (dòng 15-100: API khách hàng)
```

### 🗃️ **MODELS**
```
📁 app/Models/
  ├── User.php (dòng 15-200: Model người dùng)
  ├── Appointment.php (dòng 20-300: Model lịch hẹn)
  ├── Service.php (dòng 15-350: Model dịch vụ)
  ├── Promotion.php (dòng 20-160: Model khuyến mãi)
  ├── Time.php (dòng 15-59: Model khung giờ)
  └── Reminder.php (dòng 15-54: Model nhắc nhở)
```

### 🎨 **VIEWS**
```
📁 Customer Views: resources/views/customer/
  ├── dashboard.blade.php (dòng 1-150: Dashboard)
  └── appointments/create.blade.php (dòng 1-1206: Form đặt lịch)

📁 Admin Views: resources/views/admin/
  ├── dashboard.blade.php (dòng 1-200: Dashboard admin)
  ├── appointments/ (Quản lý lịch hẹn)
  ├── users/ (Quản lý người dùng)
  └── promotions/ (Quản lý khuyến mãi)

📁 Staff Views: resources/views/le-tan/
  ├── dashboard.blade.php (dòng 1-250: Dashboard lễ tân)
  ├── appointments/ (Quản lý lịch hẹn)
  └── reminders/ (Nhắc lịch hẹn)
```

### 🛣️ **ROUTES**
```
📁 routes/
  ├── web.php (dòng 1-800: Routes chính + debug routes)
  ├── admin.php (dòng 1-150: Routes admin)
  ├── le-tan.php (dòng 1-120: Routes lễ tân)
  └── api.php (dòng 1-108: API routes)
```

---

## 🔧 TÌM KIẾM THEO VẤN ĐỀ CẦN GIẢI QUYẾT

### ❌ **LỖI KHUNG GIỜ HIỂN THỊ SAI**
```
🔍 Kiểm tra:
1. Model Time: app/Models/Time.php (dòng 38-41)
2. API TimeSlot: app/Http/Controllers/Api/TimeSlotController.php (dòng 180-253)
3. JavaScript: resources/views/customer/appointments/create.blade.php (dòng 700-862)
```

### ❌ **LỖI MÃ KHUYẾN MÃI KHÔNG HOẠT ĐỘNG**
```
🔍 Kiểm tra:
1. API validate: app/Http/Controllers/Api/PromotionController.php (dòng 118-162)
2. PricingService: app/Services/PricingService.php (dòng 78-136)
3. JavaScript: resources/views/customer/appointments/create.blade.php (dòng 871-952)
4. Model Promotion: app/Models/Promotion.php (dòng 80-146)
```

### ❌ **LỖI EMAIL KHÔNG GỬI ĐƯỢC**
```
🔍 Kiểm tra:
1. Email logic: app/Http/Controllers/Customer/AppointmentController.php (dòng 288-370)
2. Config email: config/mail.php
3. Environment: .env (MAIL_* settings)
```

### ❌ **LỖI PHÂN QUYỀN**
```
🔍 Kiểm tra:
1. Middleware: app/Http/Middleware/CheckUserRole.php (dòng 15-40)
2. Routes: routes/admin.php, routes/le-tan.php, routes/nvkt.php
3. Model User: app/Models/User.php (dòng 120-180)
```

---

## 🚀 DEBUG ROUTES HỮU ÍCH

### 🔍 **Routes để test và debug:**
```
GET /debug-promotion/{code}         # Debug mã khuyến mãi cụ thể
GET /test-promotion-validation      # Test form validation khuyến mãi
GET /test-booking-promotion         # Test toàn bộ flow đặt lịch
GET /debug-reminders               # Debug hệ thống nhắc nhở
GET /test-reminders-page           # Test trang nhắc nhở
```

### 📊 **Kiểm tra database:**
```bash
# Vào tinker để kiểm tra data
php artisan tinker

# Kiểm tra appointments
App\Models\Appointment::count()

# Kiểm tra promotions
App\Models\Promotion::where('is_active', true)->get()

# Kiểm tra time slots
App\Models\Time::all()
```

---

## 📝 GHI CHÚ CHO BẢO VỆ LUẬN ÁN

### 🎯 **Các điểm nổi bật để trình bày:**

1. **Kiến trúc MVC rõ ràng**:
   - Models: app/Models/ (Business logic)
   - Views: resources/views/ (Presentation layer)
   - Controllers: app/Http/Controllers/ (Application logic)

2. **API RESTful**:
   - routes/api.php: Định nghĩa endpoints
   - app/Http/Controllers/Api/: Xử lý API requests

3. **Phân quyền người dùng**:
   - Middleware: app/Http/Middleware/CheckUserRole.php
   - Routes riêng biệt cho từng role

4. **Tính năng nổi bật**:
   - Hệ thống khuyến mãi: PricingService.php
   - Quản lý thời gian: TimeSlotController.php
   - Email notifications: AppointmentController.php

5. **Frontend hiện đại**:
   - Tailwind CSS styling
   - JavaScript ES6+
   - Responsive design
