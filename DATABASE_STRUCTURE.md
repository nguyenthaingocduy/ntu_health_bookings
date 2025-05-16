# Cơ Sở Dữ Liệu Hệ Thống Đặt Lịch Làm Đẹp

Tài liệu này mô tả cấu trúc cơ sở dữ liệu của hệ thống đặt lịch làm đẹp cho nhân viên Trường Đại học Nha Trang.

## Tổng Quan

Hệ thống sử dụng cơ sở dữ liệu quan hệ với các bảng được thiết kế để hỗ trợ đầy đủ các chức năng của hệ thống đặt lịch làm đẹp, bao gồm quản lý người dùng, dịch vụ, lịch hẹn, thanh toán và các tính năng khác.

## Các Bảng Dữ Liệu

### 1. Bảng `users` (Người dùng)

Lưu trữ thông tin về tất cả người dùng trong hệ thống, bao gồm khách hàng, nhân viên và quản trị viên.

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | uuid | Khóa chính, định danh duy nhất |
| first_name | string | Tên |
| last_name | string | Họ |
| email | string | Email (duy nhất) |
| password | string | Mật khẩu (đã mã hóa) |
| phone | string | Số điện thoại |
| address | string | Địa chỉ |
| gender | string | Giới tính |
| date_of_birth | date | Ngày sinh |
| role_id | uuid | Khóa ngoại tới bảng roles |
| type_id | uuid | Khóa ngoại tới bảng customer_types |
| province_id | string | Mã tỉnh/thành phố |
| district_id | string | Mã quận/huyện |
| ward_id | string | Mã phường/xã |
| staff_id | string | Mã nhân viên (nếu là nhân viên) |
| department | string | Phòng ban (nếu là nhân viên) |
| position | string | Chức vụ (nếu là nhân viên) |
| employee_code | string | Mã nhân viên (nếu là nhân viên) |
| status | enum | Trạng thái (active, inactive) |
| email_notifications_enabled | boolean | Cho phép thông báo qua email |
| notify_appointment_confirmation | boolean | Nhận thông báo xác nhận lịch hẹn |
| notify_appointment_reminder | boolean | Nhận thông báo nhắc nhở lịch hẹn |
| notify_appointment_cancellation | boolean | Nhận thông báo hủy lịch hẹn |
| notify_promotions | boolean | Nhận thông báo khuyến mãi |
| updated_by | uuid | Người cập nhật gần nhất |
| remember_token | string | Token ghi nhớ đăng nhập |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

### 2. Bảng `roles` (Vai trò)

Định nghĩa các vai trò trong hệ thống.

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | uuid | Khóa chính, định danh duy nhất |
| name | string | Tên vai trò |
| description | string | Mô tả vai trò |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

### 3. Bảng `customer_types` (Loại khách hàng)

Định nghĩa các loại khách hàng.

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | uuid | Khóa chính, định danh duy nhất |
| type_name | string | Tên loại khách hàng |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

### 4. Bảng `categories` (Danh mục)

Lưu trữ các danh mục dịch vụ.

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | bigint | Khóa chính, tự động tăng |
| parent_id | bigint | Khóa ngoại tới danh mục cha |
| name | string | Tên danh mục |
| icon | string | Biểu tượng danh mục |
| description | text | Mô tả danh mục |
| status | enum | Trạng thái (active, inactive) |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

### 5. Bảng `services` (Dịch vụ)

Lưu trữ thông tin về các dịch vụ làm đẹp.

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | bigint | Khóa chính, tự động tăng |
| name | string | Tên dịch vụ |
| slug | string | Đường dẫn thân thiện |
| description | text | Mô tả dịch vụ |
| price | decimal | Giá dịch vụ |
| duration | integer | Thời gian thực hiện (phút) |
| category_id | bigint | Khóa ngoại tới bảng categories |
| clinic_id | bigint | Khóa ngoại tới bảng clinics |
| image_url | string | Đường dẫn hình ảnh |
| status | enum | Trạng thái (active, inactive) |
| is_health_checkup | boolean | Có phải dịch vụ khám sức khỏe |
| required_tests | json | Các xét nghiệm cần thiết |
| preparation_instructions | text | Hướng dẫn chuẩn bị |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

### 6. Bảng `clinics` (Phòng khám/Cơ sở)

Lưu trữ thông tin về các cơ sở làm đẹp.

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | bigint | Khóa chính, tự động tăng |
| name | string | Tên cơ sở |
| address | string | Địa chỉ |
| phone | string | Số điện thoại |
| email | string | Email |
| description | text | Mô tả |
| image_url | string | Đường dẫn hình ảnh |
| status | enum | Trạng thái (active, inactive) |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

### 7. Bảng `employees` (Nhân viên)

Lưu trữ thông tin về nhân viên làm đẹp.

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | uuid | Khóa chính, định danh duy nhất |
| first_name | string | Tên |
| last_name | string | Họ |
| birthday | date | Ngày sinh |
| address | string | Địa chỉ |
| phone | string | Số điện thoại |
| gender | string | Giới tính |
| role_id | uuid | Khóa ngoại tới bảng roles |
| clinic_id | bigint | Khóa ngoại tới bảng clinics |
| status | enum | Trạng thái (active, inactive) |
| email | string | Email |
| avatar_url | string | Đường dẫn ảnh đại diện |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

### 8. Bảng `times` (Khung giờ)

Lưu trữ các khung giờ làm việc.

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | uuid | Khóa chính, định danh duy nhất |
| started_time | string | Thời gian bắt đầu |
| capacity | integer | Số lượng khách tối đa |
| booked_count | integer | Số lượng đã đặt |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

### 9. Bảng `time_slots` (Khung giờ làm việc)

Lưu trữ các khung giờ làm việc chi tiết.

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | uuid | Khóa chính, định danh duy nhất |
| start_time | time | Thời gian bắt đầu |
| end_time | time | Thời gian kết thúc |
| day_of_week | integer | Ngày trong tuần (1-7) |
| is_available | boolean | Có sẵn sàng không |
| max_appointments | integer | Số lượng cuộc hẹn tối đa |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

### 10. Bảng `appointments` (Lịch hẹn)

Lưu trữ thông tin về các cuộc hẹn.

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | string | Khóa chính, định danh duy nhất |
| customer_id | string | Khóa ngoại tới bảng users |
| service_id | string | Khóa ngoại tới bảng services |
| date_register | datetime | Ngày đăng ký |
| date_appointments | datetime | Ngày hẹn |
| time_appointments_id | string | Khóa ngoại tới bảng times |
| employee_id | string | Khóa ngoại tới bảng users (nhân viên) |
| status | enum | Trạng thái (pending, confirmed, cancelled, completed, no-show, in_progress) |
| notes | text | Ghi chú |
| doctor_id | string | ID bác sĩ (nếu có) |
| time_slot_id | string | Khóa ngoại tới bảng time_slots |
| check_in_time | datetime | Thời gian check-in |
| check_out_time | datetime | Thời gian check-out |
| is_completed | boolean | Đã hoàn thành chưa |
| cancellation_reason | text | Lý do hủy |
| promotion_code | string | Mã khuyến mãi |
| final_price | decimal | Giá cuối cùng |
| created_by | uuid | Người tạo |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

### 11. Bảng `promotions` (Khuyến mãi)

Lưu trữ thông tin về các chương trình khuyến mãi.

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | uuid | Khóa chính, định danh duy nhất |
| title | string | Tiêu đề khuyến mãi |
| code | string | Mã khuyến mãi (duy nhất) |
| description | text | Mô tả khuyến mãi |
| discount_type | enum | Loại giảm giá (percentage, fixed) |
| discount_value | decimal | Giá trị giảm giá |
| minimum_purchase | decimal | Giá trị mua tối thiểu |
| maximum_discount | decimal | Giảm giá tối đa |
| start_date | date | Ngày bắt đầu |
| end_date | date | Ngày kết thúc |
| is_active | boolean | Có đang hoạt động |
| usage_limit | integer | Giới hạn sử dụng |
| usage_count | integer | Số lần đã sử dụng |
| created_by | uuid | Người tạo |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

### 12. Bảng `service_promotion` (Liên kết dịch vụ - khuyến mãi)

Bảng trung gian liên kết dịch vụ và khuyến mãi.

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | bigint | Khóa chính, tự động tăng |
| service_id | bigint | Khóa ngoại tới bảng services |
| promotion_id | uuid | Khóa ngoại tới bảng promotions |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

### 13. Bảng `payments` (Thanh toán)

Lưu trữ thông tin về các giao dịch thanh toán.

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | bigint | Khóa chính, tự động tăng |
| appointment_id | uuid | Khóa ngoại tới bảng appointments |
| customer_id | uuid | Khóa ngoại tới bảng users |
| amount | decimal | Số tiền thanh toán |
| payment_method | string | Phương thức thanh toán |
| payment_status | string | Trạng thái thanh toán |
| transaction_id | string | Mã giao dịch |
| notes | text | Ghi chú |
| created_by | uuid | Người tạo |
| updated_by | uuid | Người cập nhật |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

### 14. Bảng `invoices` (Hóa đơn)

Lưu trữ thông tin về các hóa đơn.

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | uuid | Khóa chính, định danh duy nhất |
| invoice_number | string | Số hóa đơn (duy nhất) |
| invoice_date | date | Ngày hóa đơn |
| user_id | uuid | Khóa ngoại tới bảng users |
| appointment_id | uuid | Khóa ngoại tới bảng appointments |
| subtotal | decimal | Tổng tiền trước thuế |
| tax | decimal | Thuế |
| discount | decimal | Giảm giá |
| total | decimal | Tổng tiền |
| payment_method | string | Phương thức thanh toán |
| payment_status | string | Trạng thái thanh toán |
| notes | text | Ghi chú |
| created_by | uuid | Người tạo |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |
| deleted_at | timestamp | Thời gian xóa (soft delete) |

### 15. Bảng `invoice_items` (Chi tiết hóa đơn)

Lưu trữ chi tiết các mục trong hóa đơn.

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | uuid | Khóa chính, định danh duy nhất |
| invoice_id | uuid | Khóa ngoại tới bảng invoices |
| service_id | bigint | Khóa ngoại tới bảng services |
| item_name | string | Tên mục |
| item_description | text | Mô tả mục |
| quantity | integer | Số lượng |
| unit_price | decimal | Đơn giá |
| discount | decimal | Giảm giá |
| total | decimal | Tổng tiền |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

### 16. Bảng `reminders` (Nhắc nhở)

Lưu trữ thông tin về các nhắc nhở.

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | bigint | Khóa chính, tự động tăng |
| appointment_id | string | Khóa ngoại tới bảng appointments |
| reminder_date | datetime | Ngày nhắc nhở |
| message | text | Nội dung nhắc nhở |
| reminder_type | enum | Loại nhắc nhở (email, sms, both) |
| status | enum | Trạng thái (pending, sent, failed) |
| created_by | string | Người tạo |
| sent_at | datetime | Thời gian gửi |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |
| deleted_at | timestamp | Thời gian xóa (soft delete) |

### 17. Bảng `health_records` (Hồ sơ sức khỏe)

Lưu trữ thông tin về hồ sơ sức khỏe của khách hàng.

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | uuid | Khóa chính, định danh duy nhất |
| user_id | uuid | Khóa ngoại tới bảng users |
| appointment_id | uuid | Khóa ngoại tới bảng appointments |
| check_date | datetime | Ngày kiểm tra |
| height | decimal | Chiều cao (cm) |
| weight | decimal | Cân nặng (kg) |
| blood_pressure | string | Huyết áp |
| heart_rate | integer | Nhịp tim |
| blood_type | string | Nhóm máu |
| allergies | text | Dị ứng |
| medical_history | text | Tiền sử bệnh |
| diagnosis | text | Chẩn đoán |
| recommendations | text | Khuyến nghị |

## Mối Quan Hệ Giữa Các Bảng

1. **USERS.role_id** -> **ROLES.id**
2. **USERS.type_id** -> **CUSTOMER_TYPES.id**
3. **CATEGORIES.parent_id** -> **CATEGORIES.id**
4. **SERVICES.category_id** -> **CATEGORIES.id**
5. **SERVICE_PROMOTION.service_id** -> **SERVICES.id**
6. **SERVICE_PROMOTION.promotion_id** -> **PROMOTIONS.id**
7. **APPOINTMENTS.customer_id** -> **USERS.id**
8. **APPOINTMENTS.service_id** -> **SERVICES.id**
9. **APPOINTMENTS.employee_id** -> **USERS.id**
10. **APPOINTMENTS.time_slot_id** -> **TIME_SLOTS.id**
11. **PAYMENTS.appointment_id** -> **APPOINTMENTS.id**
12. **INVOICES.user_id** -> **USERS.id**
13. **INVOICES.appointment_id** -> **APPOINTMENTS.id**
14. **INVOICE_ITEMS.invoice_id** -> **INVOICES.id**
15. **INVOICE_ITEMS.service_id** -> **SERVICES.id**
16. **REMINDERS.appointment_id** -> **APPOINTMENTS.id**
17. **HEALTH_RECORDS.user_id** -> **USERS.id**
18. **HEALTH_RECORDS.appointment_id** -> **APPOINTMENTS.id**

## Kết Luận

Cơ sở dữ liệu này được thiết kế để hỗ trợ đầy đủ các chức năng của hệ thống đặt lịch làm đẹp, bao gồm quản lý người dùng, dịch vụ, lịch hẹn, thanh toán và các tính năng khác. Cấu trúc cơ sở dữ liệu tuân theo các nguyên tắc thiết kế chuẩn, với các mối quan hệ rõ ràng giữa các bảng và các ràng buộc toàn vẹn dữ liệu.

Các kiểu dữ liệu được chọn phù hợp với nhu cầu lưu trữ và xử lý dữ liệu của hệ thống, đảm bảo hiệu suất và tính nhất quán. Hệ thống sử dụng UUID cho các khóa chính của nhiều bảng, giúp tăng tính bảo mật và dễ dàng mở rộng.
