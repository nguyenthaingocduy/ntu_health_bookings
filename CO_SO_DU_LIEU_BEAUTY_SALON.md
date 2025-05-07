# CƠ SỞ DỮ LIỆU HỆ THỐNG BEAUTY SALON

## Sơ đồ Cơ sở dữ liệu (ERD)

```
+-------------------+       +-------------------+       +-------------------+
|       ROLES       |       |       USERS       |       |  CUSTOMER_TYPES   |
+-------------------+       +-------------------+       +-------------------+
| PK: id            |<----->| PK: id            |<----->| PK: id            |
| name              |       | first_name        |       | type_name         |
| description       |       | last_name         |       | description       |
| created_at        |       | email             |       | discount_percent  |
| updated_at        |       | password          |       | min_points        |
|                   |       | phone             |       | created_at        |
|                   |       | address           |       | updated_at        |
|                   |       | gender            |       |                   |
|                   |       | FK: role_id       |       |                   |
|                   |       | FK: type_id       |       |                   |
|                   |       | province_id       |       |                   |
|                   |       | district_id       |       |                   |
|                   |       | ward_id           |       |                   |
|                   |       | remember_token    |       |                   |
|                   |       | created_at        |       |                   |
|                   |       | updated_at        |       |                   |
+-------------------+       +-------------------+       +-------------------+
                                     | 1
                                     |
                                     | n
+-------------------+       +-------------------+       +-------------------+
|    CATEGORIES     |       |      SERVICES     |       |    PROMOTIONS     |
+-------------------+       +-------------------+       +-------------------+
| PK: id            |<----->| PK: id            |<----->| PK: id            |
| name              |       | name              |       | code              |
| description       |       | description       |       | name              |
| image             |       | price             |       | description       |
| FK: parent_id     |       | discount_price    |       | discount_percent  |
| status            |       | duration          |       | start_date        |
| created_at        |       | image             |       | end_date          |
| updated_at        |       | FK: category_id   |       | status            |
|                   |       | status            |       | created_at        |
|                   |       | created_at        |       | updated_at        |
|                   |       | updated_at        |       |                   |
+-------------------+       +-------------------+       +-------------------+
                                     | 1                        | n
                                     |                          |
                                     | n                        | n
+-------------------+       +-------------------+       +-------------------+
|   TIME_SLOTS      |       |    APPOINTMENTS   |       | SERVICE_PROMOTION |
+-------------------+       +-------------------+       +-------------------+
| PK: id            |<----->| PK: id            |       | PK: id            |
| start_time        |       | FK: customer_id   |<----->| FK: service_id    |
| end_time          |       | FK: service_id    |       | FK: promotion_id  |
| max_bookings      |       | FK: employee_id   |       | created_at        |
| created_at        |       | FK: time_slot_id  |       | updated_at        |
| updated_at        |       | date_appointments |       |                   |
|                   |       | status            |       |                   |
|                   |       | notes             |       |                   |
|                   |       | created_at        |       |                   |
|                   |       | updated_at        |       |                   |
+-------------------+       +-------------------+       +-------------------+
                                     | 1
                                     |
                                     | n
+-------------------+       +-------------------+       +-------------------+
|     PAYMENTS      |       |      INVOICES     |       | PROFESSIONAL_NOTES|
+-------------------+       +-------------------+       +-------------------+
| PK: id            |<----->| PK: id            |       | PK: id            |
| FK: appointment_id|       | invoice_number    |<----->| FK: customer_id   |
| amount            |       | FK: customer_id   |       | FK: employee_id   |
| payment_method    |       | FK: payment_id    |       | FK: appointment_id|
| payment_status    |       | total_amount      |       | title             |
| transaction_id    |       | tax_amount        |       | content           |
| created_at        |       | discount_amount   |       | created_at        |
| updated_at        |       | final_amount      |       | updated_at        |
|                   |       | created_at        |       |                   |
|                   |       | updated_at        |       |                   |
+-------------------+       +-------------------+       +-------------------+
                                                                | 1
                                                                |
                                                                | n
+-------------------+       +-------------------+       +-------------------+
|     REMINDERS     |       |   CONSULTATIONS   |       |      RATINGS      |
+-------------------+       +-------------------+       +-------------------+
| PK: id            |       | PK: id            |       | PK: id            |
| FK: appointment_id|       | FK: customer_id   |       | FK: customer_id   |
| reminder_date     |       | FK: service_id    |       | FK: service_id    |
| reminder_type     |       | FK: created_by    |       | FK: appointment_id|
| status            |       | consultation_date |       | rating            |
| message           |       | notes             |       | comment           |
| created_at        |       | status            |       | created_at        |
| updated_at        |       | created_at        |       | updated_at        |
|                   |       | updated_at        |       |                   |
+-------------------+       +-------------------+       +-------------------+
```

## Mô tả chi tiết các bảng

### 1. ROLES (Vai trò)
- **id**: BIGINT UNSIGNED, Khóa chính, định danh duy nhất cho vai trò, tự động tăng
- **name**: VARCHAR(50), Tên vai trò (Admin, Lễ tân, Nhân viên kỹ thuật, Khách hàng), NOT NULL, UNIQUE
- **description**: TEXT, Mô tả vai trò, NULL
- **created_at**: TIMESTAMP, Thời gian tạo, NOT NULL, DEFAULT CURRENT_TIMESTAMP
- **updated_at**: TIMESTAMP, Thời gian cập nhật, NULL, DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP

### 2. USERS (Người dùng)
- **id**: CHAR(36), Khóa chính, định danh duy nhất cho người dùng (UUID)
- **first_name**: VARCHAR(100), Tên, NOT NULL
- **last_name**: VARCHAR(100), Họ, NOT NULL
- **email**: VARCHAR(255), Địa chỉ email (duy nhất), NOT NULL, UNIQUE
- **password**: VARCHAR(255), Mật khẩu (đã mã hóa), NOT NULL
- **phone**: VARCHAR(20), Số điện thoại, NULL
- **address**: VARCHAR(255), Địa chỉ, NULL
- **gender**: ENUM('male', 'female', 'other'), Giới tính, NULL
- **role_id**: BIGINT UNSIGNED, Khóa ngoại liên kết với bảng ROLES, NOT NULL
- **type_id**: BIGINT UNSIGNED, Khóa ngoại liên kết với bảng CUSTOMER_TYPES (chỉ áp dụng cho khách hàng), NULL
- **province_id**: BIGINT UNSIGNED, Mã tỉnh/thành phố, NULL
- **district_id**: BIGINT UNSIGNED, Mã quận/huyện, NULL
- **ward_id**: BIGINT UNSIGNED, Mã phường/xã, NULL
- **remember_token**: VARCHAR(100), Token ghi nhớ đăng nhập, NULL
- **created_at**: TIMESTAMP, Thời gian tạo, NOT NULL, DEFAULT CURRENT_TIMESTAMP
- **updated_at**: TIMESTAMP, Thời gian cập nhật, NULL, DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP

### 3. CUSTOMER_TYPES (Loại khách hàng)
- **id**: BIGINT UNSIGNED, Khóa chính, định danh duy nhất cho loại khách hàng, tự động tăng
- **type_name**: VARCHAR(50), Tên loại khách hàng (Regular, Silver, Gold, Platinum), NOT NULL, UNIQUE
- **description**: TEXT, Mô tả loại khách hàng, NULL
- **discount_percent**: DECIMAL(5,2), Phần trăm giảm giá mặc định, DEFAULT 0
- **min_points**: INT UNSIGNED, Số điểm tối thiểu để đạt loại khách hàng này, DEFAULT 0
- **created_at**: TIMESTAMP, Thời gian tạo, NOT NULL, DEFAULT CURRENT_TIMESTAMP
- **updated_at**: TIMESTAMP, Thời gian cập nhật, NULL, DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP

### 4. CATEGORIES (Danh mục dịch vụ)
- **id**: BIGINT UNSIGNED, Khóa chính, định danh duy nhất cho danh mục, tự động tăng
- **name**: VARCHAR(100), Tên danh mục, NOT NULL
- **description**: TEXT, Mô tả danh mục, NULL
- **image**: VARCHAR(255), Đường dẫn hình ảnh, NULL
- **parent_id**: BIGINT UNSIGNED, Khóa ngoại tự tham chiếu (cho danh mục cha), NULL
- **status**: ENUM('active', 'inactive'), Trạng thái, DEFAULT 'active'
- **created_at**: TIMESTAMP, Thời gian tạo, NOT NULL, DEFAULT CURRENT_TIMESTAMP
- **updated_at**: TIMESTAMP, Thời gian cập nhật, NULL, DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP

### 5. SERVICES (Dịch vụ)
- **id**: BIGINT UNSIGNED, Khóa chính, định danh duy nhất cho dịch vụ, tự động tăng
- **name**: VARCHAR(100), Tên dịch vụ, NOT NULL
- **description**: TEXT, Mô tả dịch vụ, NULL
- **price**: DECIMAL(10,2), Giá gốc, NOT NULL
- **discount_price**: DECIMAL(10,2), Giá đã giảm (nếu có), NULL
- **duration**: INT UNSIGNED, Thời gian thực hiện dịch vụ (phút), DEFAULT 60
- **image**: VARCHAR(255), Đường dẫn hình ảnh, NULL
- **category_id**: BIGINT UNSIGNED, Khóa ngoại liên kết với bảng CATEGORIES, NOT NULL
- **status**: ENUM('active', 'inactive'), Trạng thái, DEFAULT 'active'
- **created_at**: TIMESTAMP, Thời gian tạo, NOT NULL, DEFAULT CURRENT_TIMESTAMP
- **updated_at**: TIMESTAMP, Thời gian cập nhật, NULL, DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP

### 6. PROMOTIONS (Khuyến mãi)
- **id**: BIGINT UNSIGNED, Khóa chính, định danh duy nhất cho khuyến mãi, tự động tăng
- **code**: VARCHAR(50), Mã khuyến mãi, NOT NULL, UNIQUE
- **name**: VARCHAR(100), Tên khuyến mãi, NOT NULL
- **description**: TEXT, Mô tả khuyến mãi, NULL
- **discount_percent**: DECIMAL(5,2), Phần trăm giảm giá, NOT NULL
- **start_date**: DATE, Ngày bắt đầu, NOT NULL
- **end_date**: DATE, Ngày kết thúc, NOT NULL
- **status**: ENUM('active', 'inactive'), Trạng thái, DEFAULT 'active'
- **created_at**: TIMESTAMP, Thời gian tạo, NOT NULL, DEFAULT CURRENT_TIMESTAMP
- **updated_at**: TIMESTAMP, Thời gian cập nhật, NULL, DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP

### 7. SERVICE_PROMOTION (Dịch vụ - Khuyến mãi)
- **id**: BIGINT UNSIGNED, Khóa chính, định danh duy nhất, tự động tăng
- **service_id**: BIGINT UNSIGNED, Khóa ngoại liên kết với bảng SERVICES, NOT NULL
- **promotion_id**: BIGINT UNSIGNED, Khóa ngoại liên kết với bảng PROMOTIONS, NOT NULL
- **created_at**: TIMESTAMP, Thời gian tạo, NOT NULL, DEFAULT CURRENT_TIMESTAMP
- **updated_at**: TIMESTAMP, Thời gian cập nhật, NULL, DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP

### 8. TIME_SLOTS (Khung giờ)
- **id**: BIGINT UNSIGNED, Khóa chính, định danh duy nhất cho khung giờ, tự động tăng
- **start_time**: TIME, Thời gian bắt đầu, NOT NULL
- **end_time**: TIME, Thời gian kết thúc, NOT NULL
- **max_bookings**: INT UNSIGNED, Số lượng đặt lịch tối đa cho khung giờ này, DEFAULT 10
- **created_at**: TIMESTAMP, Thời gian tạo, NOT NULL, DEFAULT CURRENT_TIMESTAMP
- **updated_at**: TIMESTAMP, Thời gian cập nhật, NULL, DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP

### 9. APPOINTMENTS (Lịch hẹn)
- **id**: BIGINT UNSIGNED, Khóa chính, định danh duy nhất cho lịch hẹn, tự động tăng
- **customer_id**: CHAR(36), Khóa ngoại liên kết với bảng USERS (khách hàng), NOT NULL
- **service_id**: BIGINT UNSIGNED, Khóa ngoại liên kết với bảng SERVICES, NOT NULL
- **employee_id**: CHAR(36), Khóa ngoại liên kết với bảng USERS (nhân viên kỹ thuật), NULL
- **time_slot_id**: BIGINT UNSIGNED, Khóa ngoại liên kết với bảng TIME_SLOTS, NOT NULL
- **date_appointments**: DATE, Ngày hẹn, NOT NULL
- **status**: ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled'), Trạng thái, DEFAULT 'pending'
- **notes**: TEXT, Ghi chú, NULL
- **created_at**: TIMESTAMP, Thời gian tạo, NOT NULL, DEFAULT CURRENT_TIMESTAMP
- **updated_at**: TIMESTAMP, Thời gian cập nhật, NULL, DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP

### 10. PAYMENTS (Thanh toán)
- **id**: BIGINT UNSIGNED, Khóa chính, định danh duy nhất cho thanh toán, tự động tăng
- **appointment_id**: BIGINT UNSIGNED, Khóa ngoại liên kết với bảng APPOINTMENTS, NOT NULL
- **amount**: DECIMAL(10,2), Số tiền thanh toán, NOT NULL
- **payment_method**: ENUM('cash', 'credit_card', 'bank_transfer'), Phương thức thanh toán, DEFAULT 'cash'
- **payment_status**: ENUM('pending', 'completed', 'failed'), Trạng thái thanh toán, DEFAULT 'pending'
- **transaction_id**: VARCHAR(100), Mã giao dịch (cho thanh toán điện tử), NULL
- **created_at**: TIMESTAMP, Thời gian tạo, NOT NULL, DEFAULT CURRENT_TIMESTAMP
- **updated_at**: TIMESTAMP, Thời gian cập nhật, NULL, DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP

### 11. INVOICES (Hóa đơn)
- **id**: BIGINT UNSIGNED, Khóa chính, định danh duy nhất cho hóa đơn, tự động tăng
- **invoice_number**: VARCHAR(50), Số hóa đơn, NOT NULL, UNIQUE
- **customer_id**: CHAR(36), Khóa ngoại liên kết với bảng USERS (khách hàng), NOT NULL
- **payment_id**: BIGINT UNSIGNED, Khóa ngoại liên kết với bảng PAYMENTS, NOT NULL
- **total_amount**: DECIMAL(10,2), Tổng tiền, NOT NULL
- **tax_amount**: DECIMAL(10,2), Tiền thuế, DEFAULT 0
- **discount_amount**: DECIMAL(10,2), Tiền giảm giá, DEFAULT 0
- **final_amount**: DECIMAL(10,2), Số tiền cuối cùng, NOT NULL
- **created_at**: TIMESTAMP, Thời gian tạo, NOT NULL, DEFAULT CURRENT_TIMESTAMP
- **updated_at**: TIMESTAMP, Thời gian cập nhật, NULL, DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP

### 12. PROFESSIONAL_NOTES (Ghi chú chuyên môn)
- **id**: BIGINT UNSIGNED, Khóa chính, định danh duy nhất cho ghi chú, tự động tăng
- **customer_id**: CHAR(36), Khóa ngoại liên kết với bảng USERS (khách hàng), NOT NULL
- **employee_id**: CHAR(36), Khóa ngoại liên kết với bảng USERS (nhân viên kỹ thuật), NOT NULL
- **appointment_id**: BIGINT UNSIGNED, Khóa ngoại liên kết với bảng APPOINTMENTS, NULL
- **title**: VARCHAR(255), Tiêu đề ghi chú, NOT NULL
- **content**: TEXT, Nội dung ghi chú, NOT NULL
- **created_at**: TIMESTAMP, Thời gian tạo, NOT NULL, DEFAULT CURRENT_TIMESTAMP
- **updated_at**: TIMESTAMP, Thời gian cập nhật, NULL, DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP

### 13. REMINDERS (Nhắc nhở)
- **id**: BIGINT UNSIGNED, Khóa chính, định danh duy nhất cho nhắc nhở, tự động tăng
- **appointment_id**: BIGINT UNSIGNED, Khóa ngoại liên kết với bảng APPOINTMENTS, NOT NULL
- **reminder_date**: DATETIME, Ngày và giờ nhắc nhở, NOT NULL
- **reminder_type**: ENUM('email', 'sms'), Loại nhắc nhở, DEFAULT 'email'
- **status**: ENUM('pending', 'sent', 'failed'), Trạng thái, DEFAULT 'pending'
- **message**: TEXT, Nội dung tin nhắn, NULL
- **created_at**: TIMESTAMP, Thời gian tạo, NOT NULL, DEFAULT CURRENT_TIMESTAMP
- **updated_at**: TIMESTAMP, Thời gian cập nhật, NULL, DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP

### 14. CONSULTATIONS (Tư vấn dịch vụ)
- **id**: BIGINT UNSIGNED, Khóa chính, định danh duy nhất cho tư vấn, tự động tăng
- **customer_id**: CHAR(36), Khóa ngoại liên kết với bảng USERS (khách hàng), NOT NULL
- **service_id**: BIGINT UNSIGNED, Khóa ngoại liên kết với bảng SERVICES, NOT NULL
- **created_by**: CHAR(36), Khóa ngoại liên kết với bảng USERS (lễ tân), NOT NULL
- **consultation_date**: DATE, Ngày tư vấn, NOT NULL
- **notes**: TEXT, Ghi chú tư vấn, NULL
- **status**: ENUM('pending', 'completed', 'cancelled'), Trạng thái, DEFAULT 'pending'
- **created_at**: TIMESTAMP, Thời gian tạo, NOT NULL, DEFAULT CURRENT_TIMESTAMP
- **updated_at**: TIMESTAMP, Thời gian cập nhật, NULL, DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP

### 15. RATINGS (Đánh giá)
- **id**: BIGINT UNSIGNED, Khóa chính, định danh duy nhất cho đánh giá, tự động tăng
- **customer_id**: CHAR(36), Khóa ngoại liên kết với bảng USERS (khách hàng), NOT NULL
- **service_id**: BIGINT UNSIGNED, Khóa ngoại liên kết với bảng SERVICES, NOT NULL
- **appointment_id**: BIGINT UNSIGNED, Khóa ngoại liên kết với bảng APPOINTMENTS, NOT NULL
- **rating**: TINYINT UNSIGNED, Số sao đánh giá (1-5), NOT NULL
- **comment**: TEXT, Nhận xét, NULL
- **created_at**: TIMESTAMP, Thời gian tạo, NOT NULL, DEFAULT CURRENT_TIMESTAMP
- **updated_at**: TIMESTAMP, Thời gian cập nhật, NULL, DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP

## Các ràng buộc khóa ngoại

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
12. **INVOICES.customer_id** -> **USERS.id**
13. **INVOICES.payment_id** -> **PAYMENTS.id**
14. **PROFESSIONAL_NOTES.customer_id** -> **USERS.id**
15. **PROFESSIONAL_NOTES.employee_id** -> **USERS.id**
16. **PROFESSIONAL_NOTES.appointment_id** -> **APPOINTMENTS.id**
17. **REMINDERS.appointment_id** -> **APPOINTMENTS.id**
18. **CONSULTATIONS.customer_id** -> **USERS.id**
19. **CONSULTATIONS.service_id** -> **SERVICES.id**
20. **CONSULTATIONS.created_by** -> **USERS.id**
21. **RATINGS.customer_id** -> **USERS.id**
22. **RATINGS.service_id** -> **SERVICES.id**
23. **RATINGS.appointment_id** -> **APPOINTMENTS.id**

## Chỉ mục (Indexes)

Ngoài các khóa chính và khóa ngoại, các chỉ mục sau đây nên được tạo để tối ưu hiệu suất truy vấn:

1. **USERS**: email (UNIQUE), phone
2. **SERVICES**: name, category_id, status
3. **PROMOTIONS**: code (UNIQUE), status, start_date, end_date
4. **APPOINTMENTS**: customer_id, employee_id, date_appointments, status
5. **PAYMENTS**: appointment_id, payment_status
6. **INVOICES**: invoice_number (UNIQUE), customer_id
7. **CONSULTATIONS**: customer_id, service_id, consultation_date, status
8. **RATINGS**: customer_id, service_id, appointment_id

## Kết luận

Cơ sở dữ liệu này được thiết kế để hỗ trợ đầy đủ các chức năng của hệ thống Beauty Salon, bao gồm quản lý người dùng, dịch vụ, lịch hẹn, thanh toán và các tính năng khác. Cấu trúc cơ sở dữ liệu tuân theo các nguyên tắc thiết kế chuẩn, với các mối quan hệ rõ ràng giữa các bảng và các ràng buộc toàn vẹn dữ liệu.

Các kiểu dữ liệu được chọn phù hợp với nhu cầu lưu trữ và xử lý dữ liệu của hệ thống, đảm bảo hiệu suất và tính nhất quán. Các chỉ mục được đề xuất sẽ giúp tối ưu hóa các truy vấn thường xuyên, đặc biệt là các truy vấn liên quan đến tìm kiếm và lọc dữ liệu.

Cơ sở dữ liệu này có thể được triển khai trên các hệ quản trị cơ sở dữ liệu quan hệ phổ biến như MySQL, PostgreSQL hoặc SQL Server với các điều chỉnh nhỏ phù hợp với từng hệ quản trị.
