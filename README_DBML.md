# Cấu Trúc Cơ Sở Dữ Liệu Hệ Thống Beauty Salon

Tài liệu này mô tả cấu trúc cơ sở dữ liệu của hệ thống đặt lịch làm đẹp cho nhân viên Trường Đại học Nha Trang, được trình bày theo định dạng DBML (Database Markup Language).

## Sơ Đồ Cơ Sở Dữ Liệu

```dbml
// Định nghĩa cấu trúc cơ sở dữ liệu hệ thống Beauty Salon
// Sử dụng DBML (Database Markup Language)

Table roles {
  id char(36) [pk]
  name varchar(50) [not null]
  description varchar(255)
  created_at timestamp
  updated_at timestamp
}

Table customer_types {
  id char(36) [pk]
  type_name varchar(50) [not null]
  created_at timestamp
  updated_at timestamp
}

Table users {
  id char(36) [pk]
  first_name varchar(50) [not null]
  last_name varchar(50) [not null]
  email varchar(100) [not null, unique]
  password varchar(255) [not null]
  phone varchar(20) [not null]
  address varchar(255) [not null]
  gender varchar(10) [not null]
  date_of_birth date
  role_id char(36) [not null, ref: > roles.id]
  type_id char(36) [not null, ref: > customer_types.id]
  province_id varchar(10)
  district_id varchar(10)
  ward_id varchar(10)
  staff_id varchar(50)
  department varchar(100)
  position varchar(100)
  employee_code varchar(50)
  status enum('active', 'inactive') [default: 'active']
  email_notifications_enabled boolean [default: true]
  notify_appointment_confirmation boolean [default: true]
  notify_appointment_reminder boolean [default: true]
  notify_appointment_cancellation boolean [default: true]
  notify_promotions boolean [default: true]
  updated_by char(36)
  remember_token varchar(100)
  created_at timestamp
  updated_at timestamp
}

Table categories {
  id bigint [pk, increment]
  parent_id bigint [ref: > categories.id]
  name varchar(100) [not null]
  icon varchar(100)
  description text
  status enum('active', 'inactive') [default: 'active']
  created_at timestamp
  updated_at timestamp
}

Table clinics {
  id bigint [pk, increment]
  name varchar(100) [not null]
  address varchar(255) [not null]
  phone varchar(20) [not null]
  email varchar(100) [not null, unique]
  description text
  image_url varchar(255)
  status enum('active', 'inactive') [default: 'active']
  created_at timestamp
  updated_at timestamp
}

Table services {
  id bigint [pk, increment]
  name varchar(100) [not null]
  slug varchar(150) [not null, unique]
  description text
  price decimal(12,2) [not null]
  duration int [not null, note: 'Thời gian thực hiện tính bằng phút']
  category_id bigint [ref: > categories.id]
  clinic_id bigint [ref: > clinics.id]
  image_url varchar(255)
  status enum('active', 'inactive') [default: 'active']
  is_health_checkup boolean [default: false]
  required_tests json
  preparation_instructions text
  created_at timestamp
  updated_at timestamp
}

Table employees {
  id char(36) [pk]
  first_name varchar(50) [not null]
  last_name varchar(50) [not null]
  birthday date [not null]
  address varchar(255) [not null]
  phone varchar(20) [not null]
  gender varchar(10) [not null]
  role_id char(36) [not null, ref: > roles.id]
  clinic_id bigint [not null, ref: > clinics.id]
  status enum('active', 'inactive') [default: 'active']
  email varchar(100)
  avatar_url varchar(255)
  created_at timestamp
  updated_at timestamp
}

Table times {
  id char(36) [pk]
  started_time time [not null]
  capacity int [default: 10]
  booked_count int [default: 0]
  created_at timestamp
  updated_at timestamp
}

Table time_slots {
  id char(36) [pk]
  start_time time [not null]
  end_time time [not null]
  day_of_week tinyint [not null, note: '1-7 tương ứng với Thứ 2 - Chủ nhật']
  is_available boolean [default: true]
  max_appointments int [default: 10]
  created_at timestamp
  updated_at timestamp
}

Table promotions {
  id char(36) [pk]
  title varchar(100) [not null]
  code varchar(50) [not null, unique]
  description text
  discount_type enum('percentage', 'fixed') [default: 'percentage']
  discount_value decimal(12,2) [not null]
  minimum_purchase decimal(12,2) [default: 0]
  maximum_discount decimal(12,2)
  start_date date [not null]
  end_date date [not null]
  is_active boolean [default: true]
  usage_limit int
  usage_count int [default: 0]
  created_by char(36) [not null, ref: > users.id]
  created_at timestamp
  updated_at timestamp
}

Table service_promotion {
  id bigint [pk, increment]
  service_id bigint [not null, ref: > services.id]
  promotion_id char(36) [not null, ref: > promotions.id]
  created_at timestamp
  updated_at timestamp

  indexes {
    (service_id, promotion_id) [unique]
  }
}

Table appointments {
  id char(36) [pk]
  customer_id char(36) [not null, ref: > users.id]
  service_id bigint [not null, ref: > services.id]
  date_register datetime [not null]
  date_appointments datetime [not null]
  time_appointments_id char(36) [not null, ref: > times.id]
  employee_id char(36) [not null, ref: > users.id]
  status enum('pending', 'confirmed', 'cancelled', 'completed', 'no-show', 'in_progress') [default: 'pending']
  notes text
  doctor_id char(36)
  time_slot_id char(36) [ref: > time_slots.id]
  check_in_time datetime
  check_out_time datetime
  is_completed boolean [default: false]
  cancellation_reason text
  promotion_code varchar(50)
  final_price decimal(15,2)
  created_by char(36) [ref: > users.id]
  created_at timestamp
  updated_at timestamp
}

Table payments {
  id bigint [pk, increment]
  appointment_id char(36) [not null, ref: > appointments.id]
  customer_id char(36) [not null, ref: > users.id]
  amount decimal(12,2) [not null]
  payment_method varchar(50) [default: 'cash']
  payment_status varchar(50) [default: 'pending']
  transaction_id varchar(100)
  notes text
  created_by char(36) [not null, ref: > users.id]
  updated_by char(36) [ref: > users.id]
  created_at timestamp
  updated_at timestamp
}

Table invoices {
  id char(36) [pk]
  invoice_number varchar(50) [not null, unique]
  invoice_date date
  user_id char(36) [not null, ref: > users.id]
  appointment_id char(36) [ref: > appointments.id]
  subtotal decimal(12,2) [not null]
  tax decimal(12,2) [default: 0]
  discount decimal(12,2) [default: 0]
  total decimal(12,2) [not null]
  payment_method varchar(50) [default: 'cash']
  payment_status varchar(50) [default: 'pending']
  notes text
  created_by char(36) [not null, ref: > users.id]
  created_at timestamp
  updated_at timestamp
  deleted_at timestamp
}

Table invoice_items {
  id char(36) [pk]
  invoice_id char(36) [not null, ref: > invoices.id]
  service_id bigint [ref: > services.id]
  item_name varchar(100) [not null]
  item_description text
  quantity int [not null]
  unit_price decimal(12,2) [not null]
  discount decimal(12,2) [default: 0]
  total decimal(12,2) [not null]
  created_at timestamp
  updated_at timestamp
}

Table reminders {
  id bigint [pk, increment]
  appointment_id char(36) [not null, ref: > appointments.id]
  reminder_date datetime [not null]
  message text [not null]
  reminder_type enum('email', 'sms', 'both') [default: 'email']
  status enum('pending', 'sent', 'failed') [default: 'pending']
  created_by char(36) [not null, ref: > users.id]
  sent_at datetime
  created_at timestamp
  updated_at timestamp
  deleted_at timestamp
}

Table health_records {
  id char(36) [pk]
  user_id char(36) [not null, ref: > users.id]
  appointment_id char(36) [ref: > appointments.id]
  check_date datetime [not null]
  height decimal(5,2) [note: 'Chiều cao (cm)']
  weight decimal(5,2) [note: 'Cân nặng (kg)']
  blood_pressure varchar(20) [note: 'Huyết áp (ví dụ: 120/80)']
  heart_rate int [note: 'Nhịp tim (nhịp/phút)']
  blood_type varchar(10) [note: 'Nhóm máu (A, B, AB, O với +/-)']
  allergies text [note: 'Dị ứng']
  medical_history text [note: 'Tiền sử bệnh']
  diagnosis text [note: 'Chẩn đoán']
  recommendations text [note: 'Khuyến nghị']
  created_at timestamp
  updated_at timestamp
}

// Định nghĩa các mối quan hệ bổ sung
Ref: users.updated_by > users.id
Ref: appointments.doctor_id > users.id
```

## Mô Tả Các Bảng Chính

1. **roles**: Lưu trữ các vai trò trong hệ thống (Admin, Customer, Receptionist, Technician)
2. **customer_types**: Lưu trữ các loại khách hàng (Regular, VIP, Premium)
3. **users**: Lưu trữ thông tin người dùng, bao gồm khách hàng và nhân viên
4. **categories**: Lưu trữ các danh mục dịch vụ, có thể phân cấp (danh mục cha-con)
5. **clinics**: Lưu trữ thông tin về các cơ sở làm đẹp
6. **services**: Lưu trữ thông tin về các dịch vụ làm đẹp
7. **employees**: Lưu trữ thông tin chi tiết về nhân viên
8. **times**: Lưu trữ các khung giờ làm việc
9. **time_slots**: Lưu trữ các khung giờ làm việc chi tiết theo ngày trong tuần
10. **promotions**: Lưu trữ thông tin về các chương trình khuyến mãi
11. **service_promotion**: Bảng trung gian liên kết dịch vụ và khuyến mãi
12. **appointments**: Lưu trữ thông tin về các cuộc hẹn
13. **payments**: Lưu trữ thông tin về các giao dịch thanh toán
14. **invoices**: Lưu trữ thông tin về các hóa đơn
15. **invoice_items**: Lưu trữ chi tiết các mục trong hóa đơn
16. **reminders**: Lưu trữ thông tin về các nhắc nhở
17. **health_records**: Lưu trữ thông tin về hồ sơ sức khỏe của khách hàng

## Các Mối Quan Hệ Chính

- Mỗi người dùng (users) có một vai trò (roles) và một loại khách hàng (customer_types)
- Mỗi dịch vụ (services) thuộc về một danh mục (categories) và một cơ sở (clinics)
- Mỗi danh mục (categories) có thể có danh mục cha (parent_id)
- Mỗi cuộc hẹn (appointments) liên kết với một khách hàng (users), một dịch vụ (services), một nhân viên (users) và một khung giờ (times)
- Mỗi hóa đơn (invoices) liên kết với một người dùng (users) và có thể liên kết với một cuộc hẹn (appointments)
- Mỗi mục hóa đơn (invoice_items) thuộc về một hóa đơn (invoices) và có thể liên kết với một dịch vụ (services)
- Mỗi khuyến mãi (promotions) có thể áp dụng cho nhiều dịch vụ (services) thông qua bảng trung gian (service_promotion)

## Lưu Ý

- Hệ thống sử dụng UUID (char(36)) cho các khóa chính của nhiều bảng, giúp tăng tính bảo mật và dễ dàng mở rộng
- Các bảng đều có các trường created_at và updated_at để theo dõi thời gian tạo và cập nhật
- Nhiều bảng sử dụng soft delete (deleted_at) để không xóa dữ liệu hoàn toàn
- Các mối quan hệ được định nghĩa rõ ràng giữa các bảng để đảm bảo tính toàn vẹn dữ liệu

## Cách Sử Dụng DBML

Bạn có thể sử dụng mã DBML này để:
1. Trực quan hóa cơ sở dữ liệu trên các công cụ như [dbdiagram.io](https://dbdiagram.io)
2. Tạo mã SQL từ DBML bằng các công cụ chuyển đổi
3. Tạo tài liệu cơ sở dữ liệu tự động

## Kết Luận

Cơ sở dữ liệu này được thiết kế để hỗ trợ đầy đủ các chức năng của hệ thống đặt lịch làm đẹp, bao gồm quản lý người dùng, dịch vụ, lịch hẹn, thanh toán và các tính năng khác. Cấu trúc cơ sở dữ liệu tuân theo các nguyên tắc thiết kế chuẩn, với các mối quan hệ rõ ràng giữa các bảng và các ràng buộc toàn vẹn dữ liệu.
