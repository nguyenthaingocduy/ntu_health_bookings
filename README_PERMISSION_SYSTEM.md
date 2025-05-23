# 📋 HỆ THỐNG PHÂN QUYỀN - BEAUTY SALON BOOKING

## 🎯 TỔNG QUAN

Hệ thống phân quyền của Beauty Salon Booking được thiết kế để quản lý quyền truy cập và thao tác của các người dùng khác nhau trong hệ thống. Hệ thống sử dụng mô hình **Role-Based Access Control (RBAC)** kết hợp với **User-Based Permissions** để đảm bảo tính bảo mật và linh hoạt.

## 🏗️ KIẾN TRÚC HỆ THỐNG

### 1. **Các Thành Phần Chính**

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│     USERS       │    │     ROLES       │    │  PERMISSIONS    │
│                 │    │                 │    │                 │
│ - ID            │    │ - ID            │    │ - ID            │
│ - Name          │◄───┤ - Name          │    │ - Name          │
│ - Email         │    │ - Description   │    │ - Display Name  │
│ - Role ID       │    │                 │    │ - Description   │
│ - Status        │    │                 │    │ - Group         │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         │                       └───────────────────────┘
         │                              │
         │              ┌─────────────────────────────┐
         │              │   ROLE_PERMISSIONS          │
         │              │                             │
         │              │ - Role ID                   │
         │              │ - Permission ID             │
         │              └─────────────────────────────┘
         │
         │              ┌─────────────────────────────┐
         └──────────────┤   USER_PERMISSIONS          │
                        │                             │
                        │ - User ID                   │
                        │ - Permission ID             │
                        │ - Can View                  │
                        │ - Can Create                │
                        │ - Can Edit                  │
                        │ - Can Delete                │
                        │ - Granted By                │
                        └─────────────────────────────┘
```

### 2. **Mô Hình Dữ Liệu**

#### **Bảng Users**
- Lưu trữ thông tin người dùng
- Liên kết với Role thông qua `role_id`
- Có thể có quyền cá nhân thông qua `user_permissions`

#### **Bảng Roles**
- Định nghĩa các vai trò trong hệ thống
- Mỗi role có thể có nhiều permissions

#### **Bảng Permissions**
- Định nghĩa các quyền cụ thể
- Được nhóm theo `group` để dễ quản lý

#### **Bảng Role_Permissions**
- Liên kết giữa Role và Permission
- Định nghĩa quyền mặc định cho từng vai trò

#### **Bảng User_Permissions**
- Quyền cá nhân của từng user
- Có 4 loại hành động: View, Create, Edit, Delete
- Ghi nhận người cấp quyền (`granted_by`)

## 👥 CÁC VAI TRÒ TRONG HỆ THỐNG

### 1. **Admin (Quản trị viên)**
- **Mô tả**: Quản trị viên hệ thống với toàn quyền quản lý
- **Quyền hạn**: Có tất cả quyền trong hệ thống
- **Truy cập**: `/admin/dashboard`
- **Chức năng chính**:
  - Quản lý toàn bộ hệ thống
  - Phân quyền cho các vai trò khác
  - Quản lý người dùng, dịch vụ, khuyến mãi
  - Xem báo cáo và thống kê

### 2. **Receptionist (Lễ tân)**
- **Mô tả**: Lễ tân - Quản lý lịch hẹn, hỗ trợ khách hàng tại quầy
- **Truy cập**: `/le-tan/dashboard`
- **Chức năng chính**:
  - Quản lý lịch hẹn khách hàng
  - Tạo và cập nhật thông tin khách hàng
  - Xử lý thanh toán
  - Tạo hóa đơn

### 3. **Technician (Nhân viên kỹ thuật)**
- **Mô tả**: Nhân viên kỹ thuật - Thực hiện dịch vụ chăm sóc
- **Truy cập**: `/nvkt/dashboard`
- **Chức năng chính**:
  - Xem lịch làm việc cá nhân
  - Cập nhật trạng thái công việc
  - Tạo ghi chú chuyên môn
  - Quản lý tiến trình chăm sóc khách hàng

### 4. **Customer (Khách hàng)**
- **Mô tả**: Khách hàng sử dụng dịch vụ của spa
- **Truy cập**: `/customer/dashboard`
- **Chức năng chính**:
  - Đặt lịch hẹn
  - Xem lịch sử dịch vụ
  - Quản lý thông tin cá nhân
  - Sử dụng mã khuyến mãi

### 5. **Employee (Nhân viên chung)**
- **Mô tả**: Nhân viên làm việc tại spa (vai trò chung)
- **Chức năng**: Tùy thuộc vào quyền được phân

### 6. **Staff (Nhân viên hệ thống)**
- **Mô tả**: Nhân viên chung của hệ thống
- **Chức năng**: Tùy thuộc vào quyền được phân

## 🔐 NHÓM QUYỀN VÀ CHỨC NĂNG

### 1. **APPOINTMENTS (Quản lý lịch hẹn)**
- `appointments.view`: Xem lịch hẹn
- `appointments.create`: Thêm lịch hẹn mới
- `appointments.edit`: Sửa thông tin lịch hẹn
- `appointments.delete`: Xóa lịch hẹn
- `appointments.cancel`: Hủy lịch hẹn

### 2. **USERS (Quản lý người dùng)**
- `users.view`: Xem danh sách và thông tin người dùng
- `users.create`: Tạo tài khoản người dùng mới
- `users.edit`: Chỉnh sửa thông tin người dùng
- `users.delete`: Xóa tài khoản người dùng

### 3. **SERVICES (Quản lý dịch vụ)**
- `services.view`: Xem danh sách dịch vụ
- `services.create`: Thêm dịch vụ mới
- `services.edit`: Chỉnh sửa thông tin dịch vụ
- `services.delete`: Xóa dịch vụ

### 4. **PROMOTIONS (Quản lý khuyến mãi)**
- `promotions.view`: Xem danh sách khuyến mãi
- `promotions.create`: Tạo chương trình khuyến mãi
- `promotions.edit`: Chỉnh sửa khuyến mãi
- `promotions.delete`: Xóa khuyến mãi

### 5. **INVOICES (Quản lý hóa đơn)**
- `invoices.view`: Xem hóa đơn
- `invoices.create`: Tạo hóa đơn mới
- `invoices.edit`: Chỉnh sửa hóa đơn
- `invoices.delete`: Xóa hóa đơn
- `invoices.print`: In hóa đơn

### 6. **CUSTOMERS (Quản lý khách hàng)**
- `customers.view`: Xem thông tin khách hàng đầy đủ
- `customers.view_limited`: Xem thông tin khách hàng giới hạn
- `customers.create`: Thêm khách hàng mới
- `customers.edit`: Chỉnh sửa thông tin khách hàng

### 7. **REPORTS (Báo cáo và thống kê)**
- `reports.view`: Xem các báo cáo
- `reports.export`: Xuất báo cáo ra file

### 8. **SETTINGS (Cài đặt hệ thống)**
- `settings.view`: Xem cài đặt hệ thống
- `settings.edit`: Chỉnh sửa cài đặt

### 9. **PERMISSIONS (Quản lý phân quyền)**
- `permissions.view`: Xem danh sách quyền
- `permissions.assign`: Gán quyền cho người dùng/vai trò

### 10. **ROLES (Quản lý vai trò)**
- `roles.view`: Xem danh sách vai trò
- `roles.create`: Tạo vai trò mới
- `roles.edit`: Chỉnh sửa vai trò
- `roles.delete`: Xóa vai trò

### 11. **POSTS (Quản lý tin tức)**
- `posts.view`: Xem tin tức
- `posts.create`: Thêm tin tức mới
- `posts.edit`: Chỉnh sửa tin tức
- `posts.delete`: Xóa tin tức

### 12. **GENERAL (Chức năng chung)**
- `notifications.send`: Gửi thông báo
- `payments.view`: Xem chi tiết thanh toán
- `payments.create`: Tạo thanh toán
- `professional_notes.view`: Xem ghi chú chuyên môn
- `professional_notes.create`: Tạo ghi chú chuyên môn
- `professional_notes.edit`: Chỉnh sửa ghi chú
- `work_schedule.view`: Xem lịch làm việc
- `session_status.update`: Cập nhật trạng thái phiên làm việc
- `treatment_progress.view`: Xem tiến trình điều trị
- `treatment_progress.update`: Cập nhật tiến trình điều trị
- `service_packages.register`: Đăng ký gói dịch vụ

## ⚙️ CÁCH THỨC HOẠT ĐỘNG

### 1. **Phân Quyền Theo Vai Trò (Role-Based)**
```php
// Kiểm tra quyền thông qua vai trò
if ($user->hasPermission('appointments.view')) {
    // Cho phép xem lịch hẹn
}

// Kiểm tra vai trò cụ thể
if ($user->isAdmin()) {
    // Admin có tất cả quyền
}
```

### 2. **Phân Quyền Cá Nhân (User-Based)**
```php
// Kiểm tra quyền cá nhân với hành động cụ thể
if ($user->hasDirectPermission('appointments', 'create')) {
    // Cho phép tạo lịch hẹn
}

// Kiểm tra các hành động khác nhau
$user->canView('appointments');    // Xem
$user->canCreate('appointments');  // Tạo
$user->canEdit('appointments');    // Sửa
$user->canDelete('appointments');  // Xóa
```

### 3. **Cache Quyền**
- Hệ thống sử dụng cache để tối ưu hiệu suất
- Cache được xóa tự động khi quyền thay đổi
- Thời gian cache: 5 phút

## 🛠️ GIAO DIỆN QUẢN LÝ PHÂN QUYỀN

### 1. **Trang Chính** (`/admin/permissions`)
- Hiển thị tổng quan về hệ thống phân quyền
- Liên kết đến các chức năng quản lý

### 2. **Phân Quyền Theo Vai Trò** (`/admin/permissions/role-permissions-matrix`)
- Ma trận phân quyền theo vai trò
- Giao diện dạng bảng với checkbox
- Cập nhật hàng loạt quyền cho vai trò

### 3. **Phân Quyền Cá Nhân** (`/admin/permissions/user-permissions`)
- Quản lý quyền cá nhân cho từng người dùng
- 4 loại hành động: View, Create, Edit, Delete
- Ghi nhận người cấp quyền

### 4. **Quản Lý Quyền** (`/admin/permissions/create`)
- Tạo quyền mới
- Phân nhóm quyền
- Tự động gán cho Admin

## 🔧 CÁC CHỨC NĂNG ADMIN CÓ THỂ THỰC HIỆN

### 1. **Quản Lý Vai Trò**
- ✅ Tạo vai trò mới
- ✅ Chỉnh sửa mô tả vai trò
- ✅ Xóa vai trò (nếu không có người dùng)
- ✅ Gán quyền cho vai trò

### 2. **Quản Lý Quyền**
- ✅ Tạo quyền mới với nhóm
- ✅ Chỉnh sửa thông tin quyền
- ✅ Xóa quyền không sử dụng
- ✅ Phân nhóm quyền theo chức năng

### 3. **Phân Quyền Người Dùng**
- ✅ Gán quyền cá nhân cho người dùng
- ✅ Thu hồi quyền cá nhân
- ✅ Xem lịch sử phân quyền
- ✅ Phân quyền chi tiết (View/Create/Edit/Delete)

### 4. **Ma Trận Phân Quyền**
- ✅ Xem toàn bộ quyền của tất cả vai trò
- ✅ Cập nhật hàng loạt quyền
- ✅ So sánh quyền giữa các vai trò
- ✅ Xuất ma trận phân quyền

### 5. **Kiểm Soát Truy Cập**
- ✅ Middleware kiểm tra quyền tự động
- ✅ Ẩn/hiện menu theo quyền
- ✅ Bảo vệ route theo quyền
- ✅ Thông báo lỗi khi không có quyền

### 6. **Tối Ưu Hiệu Suất**
- ✅ Cache quyền người dùng
- ✅ Lazy loading permissions
- ✅ Batch update quyền
- ✅ Tự động clear cache khi thay đổi

## 📊 THỐNG KÊ HỆ THỐNG

### Hiện Tại Có:
- **6 Vai trò**: Admin, Receptionist, Technician, Customer, Employee, Staff
- **45+ Quyền**: Được phân thành 12 nhóm chức năng
- **4 Loại hành động**: View, Create, Edit, Delete (cho quyền cá nhân)
- **2 Cấp độ phân quyền**: Role-based và User-based

## 🚀 HƯỚNG DẪN SỬ DỤNG

### Cho Admin:
1. Truy cập `/admin/permissions`
2. Chọn loại phân quyền muốn quản lý
3. Sử dụng giao diện để cập nhật quyền
4. Hệ thống tự động áp dụng và clear cache

### Cho Developer:
1. Sử dụng các method có sẵn trong User model
2. Kiểm tra quyền trước khi thực hiện hành động
3. Sử dụng middleware để bảo vệ route
4. Thêm quyền mới khi phát triển tính năng

## 🔒 BẢO MẬT

- ✅ Mã hóa session và cache
- ✅ Validation đầu vào nghiêm ngặt
- ✅ Logging tất cả thay đổi quyền
- ✅ Timeout session tự động
- ✅ Kiểm tra quyền ở nhiều lớp

## 💡 VÍ DỤ THỰC TẾ

### 1. **Kịch Bản: Thêm Nhân Viên Mới**
```
Bước 1: Admin tạo tài khoản cho nhân viên mới
Bước 2: Gán vai trò "Receptionist" cho nhân viên
Bước 3: Nhân viên tự động có quyền:
  - Xem và tạo lịch hẹn
  - Quản lý thông tin khách hàng
  - Tạo hóa đơn
  - Xử lý thanh toán
```

### 2. **Kịch Bản: Phân Quyền Đặc Biệt**
```
Tình huống: Nhân viên kỹ thuật cần quyền xem báo cáo
Giải pháp: Admin cấp quyền cá nhân "reports.view"
Kết quả: Nhân viên có thêm quyền xem báo cáo ngoài quyền cơ bản
```

### 3. **Kịch Bản: Thu Hồi Quyền**
```
Tình huống: Nhân viên chuyển bộ phận, không cần quyền tạo khuyến mãi
Giải pháp: Admin thu hồi quyền "promotions.create"
Kết quả: Nhân viên không thể tạo khuyến mãi nhưng vẫn giữ quyền khác
```

## 🛡️ MIDDLEWARE VÀ BẢO VỆ ROUTE

### 1. **Middleware Kiểm Tra Quyền**
```php
// Trong routes/web.php
Route::middleware(['auth', 'permission:appointments.view'])
    ->get('/appointments', [AppointmentController::class, 'index']);

// Kiểm tra nhiều quyền
Route::middleware(['auth', 'permission:appointments.create,appointments.edit'])
    ->post('/appointments', [AppointmentController::class, 'store']);
```

### 2. **Kiểm Tra Trong Controller**
```php
public function index()
{
    // Kiểm tra quyền cơ bản
    if (!auth()->user()->hasPermission('appointments.view')) {
        abort(403, 'Bạn không có quyền xem lịch hẹn');
    }

    // Kiểm tra quyền chi tiết
    if (auth()->user()->canView('appointments')) {
        return view('appointments.index');
    }
}
```

### 3. **Ẩn/Hiện Menu Theo Quyền**
```blade
{{-- Trong Blade template --}}
@if(auth()->user()->hasPermission('appointments.view'))
    <a href="{{ route('appointments.index') }}">Quản lý lịch hẹn</a>
@endif

@if(auth()->user()->canCreate('promotions'))
    <button>Tạo khuyến mãi</button>
@endif
```

## 🔄 QUY TRÌNH PHÂN QUYỀN

### 1. **Phân Quyền Mới Cho Vai Trò**
```
1. Admin truy cập: /admin/permissions/role-permissions-matrix
2. Chọn vai trò cần phân quyền
3. Tick/untick các quyền cần thiết
4. Nhấn "Cập nhật quyền"
5. Hệ thống tự động:
   - Xóa quyền cũ
   - Thêm quyền mới
   - Clear cache cho tất cả user có vai trò này
   - Ghi log thay đổi
```

### 2. **Phân Quyền Cá Nhân**
```
1. Admin truy cập: /admin/permissions/user-permissions
2. Chọn người dùng cần phân quyền
3. Chọn quyền và loại hành động (View/Create/Edit/Delete)
4. Nhấn "Cập nhật quyền"
5. Hệ thống tự động:
   - Cập nhật quyền cá nhân
   - Clear cache cho user đó
   - Ghi nhận người cấp quyền
   - Ghi log thay đổi
```

## 📈 MONITORING VÀ LOGGING

### 1. **Log Hệ Thống**
- Tất cả thay đổi quyền được ghi log
- Ghi nhận người thực hiện và thời gian
- Log được lưu trong `storage/logs/laravel.log`

### 2. **Cache Monitoring**
- Cache quyền có thời gian sống 5 phút
- Tự động clear khi có thay đổi
- Có thể manual clear cache qua Artisan command

### 3. **Performance Tracking**
- Đo thời gian kiểm tra quyền
- Monitor số lượng query database
- Tối ưu hóa dựa trên usage pattern

## 🚨 XỬ LÝ LỖI VÀ NGOẠI LỆ

### 1. **Lỗi Phổ Biến**
- **403 Forbidden**: Không có quyền truy cập
- **404 Not Found**: Route không tồn tại hoặc không có quyền
- **500 Server Error**: Lỗi hệ thống phân quyền

### 2. **Xử Lý Ngoại Lệ**
```php
try {
    $user->assignPermission('new.permission');
} catch (PermissionException $e) {
    // Xử lý lỗi phân quyền
    return back()->with('error', 'Không thể cấp quyền: ' . $e->getMessage());
} catch (Exception $e) {
    // Xử lý lỗi chung
    Log::error('Permission error: ' . $e->getMessage());
    return back()->with('error', 'Đã xảy ra lỗi hệ thống');
}
```

## 🔧 MAINTENANCE VÀ TỐI ỮU

### 1. **Dọn Dẹp Định Kỳ**
```bash
# Clear cache quyền
php artisan cache:clear

# Rebuild permission cache
php artisan permission:cache-reset

# Cleanup unused permissions
php artisan permission:cleanup
```

### 2. **Backup Phân Quyền**
```bash
# Export permission matrix
php artisan permission:export --format=json

# Import permission matrix
php artisan permission:import backup.json
```

## 📚 TÀI LIỆU THAM KHẢO

### 1. **API Documentation**
- Tất cả method phân quyền được document trong code
- Sử dụng PHPDoc cho auto-completion
- Unit test coverage > 90%

### 2. **Database Schema**
```sql
-- Xem cấu trúc bảng quyền
DESCRIBE permissions;
DESCRIBE roles;
DESCRIBE role_permissions;
DESCRIBE user_permissions;
```

### 3. **Artisan Commands**
```bash
# Xem danh sách quyền
php artisan permission:list

# Tạo quyền mới
php artisan permission:create "new.permission" --group="custom"

# Gán quyền cho vai trò
php artisan permission:assign-role "Admin" "new.permission"
```

---

## 🎯 KẾT LUẬN

Hệ thống phân quyền của Beauty Salon Booking cung cấp:

✅ **Tính Bảo Mật Cao**: Kiểm tra quyền ở nhiều lớp
✅ **Linh Hoạt**: Hỗ trợ cả phân quyền vai trò và cá nhân
✅ **Dễ Quản Lý**: Giao diện trực quan cho Admin
✅ **Hiệu Suất Tốt**: Sử dụng cache để tối ưu
✅ **Mở Rộng**: Dễ dàng thêm quyền và vai trò mới
✅ **Audit Trail**: Ghi log đầy đủ các thay đổi

**Lưu ý**: Hệ thống này được thiết kế để đảm bảo tính bảo mật cao và linh hoạt trong quản lý. Admin có thể dễ dàng điều chỉnh quyền hạn mà không cần can thiệp vào code.
