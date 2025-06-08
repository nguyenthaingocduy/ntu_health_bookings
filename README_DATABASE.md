# Mã SQL Tạo Cơ Sở Dữ Liệu Hệ Thống Đặt Lịch Làm Đẹp

Tài liệu này cung cấp mã SQL để tạo cơ sở dữ liệu cho hệ thống đặt lịch làm đẹp dành cho nhân viên Trường Đại học Nha Trang.

## Tạo Cơ Sở Dữ Liệu

```sql
CREATE DATABASE ntu_health_bookings CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ntu_health_bookings;
```

## Tạo Các Bảng

### 1. Bảng `roles` (Vai trò)

```sql
CREATE TABLE roles (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);
```

### 2. Bảng `customer_types` (Loại khách hàng)

```sql
CREATE TABLE customer_types (
    id CHAR(36) PRIMARY KEY,
    type_name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);
```

### 3. Bảng `users` (Người dùng)

```sql
CREATE TABLE users (
    id CHAR(36) PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    date_of_birth DATE,
    role_id CHAR(36) NOT NULL,
    type_id CHAR(36) NOT NULL,
    province_id VARCHAR(10),
    district_id VARCHAR(10),
    ward_id VARCHAR(10),
    staff_id VARCHAR(50),
    department VARCHAR(100),
    position VARCHAR(100),
    employee_code VARCHAR(50),
    status ENUM('active', 'inactive') DEFAULT 'active',
    email_notifications_enabled BOOLEAN DEFAULT TRUE,
    notify_appointment_confirmation BOOLEAN DEFAULT TRUE,
    notify_appointment_reminder BOOLEAN DEFAULT TRUE,
    notify_appointment_cancellation BOOLEAN DEFAULT TRUE,
    notify_promotions BOOLEAN DEFAULT TRUE,
    updated_by CHAR(36),
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (type_id) REFERENCES customer_types(id)
);
```

### 4. Bảng `categories` (Danh mục)

```sql
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id BIGINT UNSIGNED,
    name VARCHAR(100) NOT NULL,
    icon VARCHAR(100),
    description TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);
```

### 5. Bảng `clinics` (Cơ sở)

```sql
CREATE TABLE clinics (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image_url VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);
```

### 6. Bảng `services` (Dịch vụ)

```sql
CREATE TABLE services (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    description TEXT,
    price DECIMAL(12, 2) NOT NULL,
    duration INT NOT NULL COMMENT 'Thời gian thực hiện tính bằng phút',
    category_id BIGINT UNSIGNED,
    clinic_id BIGINT UNSIGNED,
    image_url VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    is_health_checkup BOOLEAN DEFAULT FALSE,
    required_tests JSON,
    preparation_instructions TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (clinic_id) REFERENCES clinics(id) ON DELETE CASCADE
);
```

### 7. Bảng `employees` (Nhân viên)

```sql
CREATE TABLE employees (
    id CHAR(36) PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    birthday DATE NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    role_id CHAR(36) NOT NULL,
    clinic_id BIGINT UNSIGNED NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    email VARCHAR(100),
    avatar_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (clinic_id) REFERENCES clinics(id) ON DELETE CASCADE
);
```

### 8. Bảng `times` (Khung giờ)

```sql
CREATE TABLE times (
    id CHAR(36) PRIMARY KEY,
    started_time TIME NOT NULL,
    capacity INT DEFAULT 10,
    booked_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);
```

### 9. Bảng `time_slots` (Khung giờ làm việc)

```sql
CREATE TABLE time_slots (
    id CHAR(36) PRIMARY KEY,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    day_of_week TINYINT NOT NULL COMMENT '1-7 tương ứng với Thứ 2 - Chủ nhật',
    is_available BOOLEAN DEFAULT TRUE,
    max_appointments INT DEFAULT 10,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);
```

### 10. Bảng `promotions` (Khuyến mãi)

```sql
CREATE TABLE promotions (
    id CHAR(36) PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    discount_type ENUM('percentage', 'fixed') DEFAULT 'percentage',
    discount_value DECIMAL(12, 2) NOT NULL,
    minimum_purchase DECIMAL(12, 2) DEFAULT 0,
    maximum_discount DECIMAL(12, 2),
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    usage_limit INT,
    usage_count INT DEFAULT 0,
    created_by CHAR(36) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);
```

### 11. Bảng `service_promotion` (Liên kết dịch vụ - khuyến mãi)

```sql
CREATE TABLE service_promotion (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_id BIGINT UNSIGNED NOT NULL,
    promotion_id CHAR(36) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    FOREIGN KEY (promotion_id) REFERENCES promotions(id) ON DELETE CASCADE,
    UNIQUE (service_id, promotion_id)
);
```

### 12. Bảng `appointments` (Lịch hẹn)

```sql
CREATE TABLE appointments (
    id CHAR(36) PRIMARY KEY,
    customer_id CHAR(36) NOT NULL,
    service_id BIGINT UNSIGNED NOT NULL,
    date_register DATETIME NOT NULL,
    date_appointments DATETIME NOT NULL,
    time_appointments_id CHAR(36) NOT NULL,
    employee_id CHAR(36) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed', 'no-show', 'in_progress') DEFAULT 'pending',
    notes TEXT,
    doctor_id CHAR(36),
    time_slot_id CHAR(36),
    check_in_time DATETIME,
    check_out_time DATETIME,
    is_completed BOOLEAN DEFAULT FALSE,
    cancellation_reason TEXT,
    promotion_code VARCHAR(50),
    final_price DECIMAL(15, 2),
    created_by CHAR(36),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id),
    FOREIGN KEY (service_id) REFERENCES services(id),
    FOREIGN KEY (employee_id) REFERENCES users(id),
    FOREIGN KEY (time_appointments_id) REFERENCES times(id),
    FOREIGN KEY (time_slot_id) REFERENCES time_slots(id)
);
```

### 13. Bảng `payments` (Thanh toán)

```sql
CREATE TABLE payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    appointment_id CHAR(36) NOT NULL,
    customer_id CHAR(36) NOT NULL,
    amount DECIMAL(12, 2) NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'cash',
    payment_status VARCHAR(50) DEFAULT 'pending',
    transaction_id VARCHAR(100),
    notes TEXT,
    created_by CHAR(36) NOT NULL,
    updated_by CHAR(36),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id),
    FOREIGN KEY (customer_id) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id)
);
```

### 14. Bảng `invoices` (Hóa đơn)

```sql
CREATE TABLE invoices (
    id CHAR(36) PRIMARY KEY,
    invoice_number VARCHAR(50) NOT NULL UNIQUE,
    invoice_date DATE,
    user_id CHAR(36) NOT NULL,
    appointment_id CHAR(36),
    subtotal DECIMAL(12, 2) NOT NULL,
    tax DECIMAL(12, 2) DEFAULT 0,
    discount DECIMAL(12, 2) DEFAULT 0,
    total DECIMAL(12, 2) NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'cash',
    payment_status VARCHAR(50) DEFAULT 'pending',
    notes TEXT,
    created_by CHAR(36) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id)
);
```

### 15. Bảng `invoice_items` (Chi tiết hóa đơn)

```sql
CREATE TABLE invoice_items (
    id CHAR(36) PRIMARY KEY,
    invoice_id CHAR(36) NOT NULL,
    service_id BIGINT UNSIGNED,
    item_name VARCHAR(100) NOT NULL,
    item_description TEXT,
    quantity INT NOT NULL,
    unit_price DECIMAL(12, 2) NOT NULL,
    discount DECIMAL(12, 2) DEFAULT 0,
    total DECIMAL(12, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL
);
```

### 16. Bảng `reminders` (Nhắc nhở)

```sql
CREATE TABLE reminders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    appointment_id CHAR(36) NOT NULL,
    reminder_date DATETIME NOT NULL,
    message TEXT NOT NULL,
    reminder_type ENUM('email', 'sms', 'both') DEFAULT 'email',
    status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
    created_by CHAR(36) NOT NULL,
    sent_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
```

### 17. Bảng `health_records` (Hồ sơ sức khỏe)

```sql
CREATE TABLE health_records (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    appointment_id CHAR(36),
    check_date DATETIME NOT NULL,
    height DECIMAL(5, 2) COMMENT 'Chiều cao (cm)',
    weight DECIMAL(5, 2) COMMENT 'Cân nặng (kg)',
    blood_pressure VARCHAR(20) COMMENT 'Huyết áp (ví dụ: 120/80)',
    heart_rate INT COMMENT 'Nhịp tim (nhịp/phút)',
    blood_type VARCHAR(10) COMMENT 'Nhóm máu (A, B, AB, O với +/-)',
    allergies TEXT COMMENT 'Dị ứng',
    medical_history TEXT COMMENT 'Tiền sử bệnh',
    diagnosis TEXT COMMENT 'Chẩn đoán',
    recommendations TEXT COMMENT 'Khuyến nghị',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL
);
```

## Dữ Liệu Mẫu

### 1. Thêm vai trò

```sql
INSERT INTO roles (id, name, description) VALUES
(UUID(), 'Admin', 'Quản trị viên hệ thống'),
(UUID(), 'Customer', 'Khách hàng'),
(UUID(), 'Receptionist', 'Quản lý lịch hẹn, hỗ trợ khách hàng tại quầy'),
(UUID(), 'Technician', 'Thực hiện dịch vụ chăm sóc, theo dõi lịch trình');
```

### 2. Thêm loại khách hàng

```sql
INSERT INTO customer_types (id, type_name) VALUES
(UUID(), 'Regular'),
(UUID(), 'VIP'),
(UUID(), 'Premium');
```

## Chỉ Mục Bổ Sung

Để tối ưu hiệu suất truy vấn, thêm các chỉ mục sau:

```sql
-- Chỉ mục cho bảng users
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_phone ON users(phone);
CREATE INDEX idx_users_role ON users(role_id);
CREATE INDEX idx_users_type ON users(type_id);

-- Chỉ mục cho bảng services
CREATE INDEX idx_services_category ON services(category_id);
CREATE INDEX idx_services_clinic ON services(clinic_id);
CREATE INDEX idx_services_status ON services(status);

-- Chỉ mục cho bảng appointments
CREATE INDEX idx_appointments_customer ON appointments(customer_id);
CREATE INDEX idx_appointments_service ON appointments(service_id);
CREATE INDEX idx_appointments_employee ON appointments(employee_id);
CREATE INDEX idx_appointments_date ON appointments(date_appointments);
CREATE INDEX idx_appointments_status ON appointments(status);

-- Chỉ mục cho bảng promotions
CREATE INDEX idx_promotions_code ON promotions(code);
CREATE INDEX idx_promotions_dates ON promotions(start_date, end_date);
CREATE INDEX idx_promotions_active ON promotions(is_active);

-- Chỉ mục cho bảng invoices
CREATE INDEX idx_invoices_user ON invoices(user_id);
CREATE INDEX idx_invoices_appointment ON invoices(appointment_id);
CREATE INDEX idx_invoices_date ON invoices(invoice_date);
CREATE INDEX idx_invoices_status ON invoices(payment_status);
```

