# 🎓 CÂU HỎI BẢO VỆ KHÓA LUẬN TỐT NGHIỆP
## Hệ thống đặt lịch Beauty Salon cho cán bộ Đại học Nha Trang

---

## 📋 THÔNG TIN DỰ ÁN

**Tên đề tài:** Xây dựng hệ thống đặt lịch dịch vụ làm đẹp cho cán bộ Đại học Nha Trang
**Công nghệ:** Laravel 10, PHP 8.2, MySQL, Tailwind CSS
**Thời gian thực hiện:** 6 tháng
**Phạm vi:** Hệ thống quản lý salon làm đẹp với 4 vai trò chính

---

## 🎯 CÂU HỎI VỀ TỔNG QUAN DỰ ÁN

### **Q1: Tại sao em chọn đề tài này? Ý nghĩa thực tiễn của dự án?**

**Đáp án:**
- **Nhu cầu thực tế:** Cán bộ ĐH Nha Trang cần dịch vụ làm đẹp nhưng việc đặt lịch thủ công gây khó khăn
- **Số hóa quy trình:** Chuyển từ đặt lịch qua điện thoại sang hệ thống online tự động
- **Tối ưu thời gian:** Giảm 70% thời gian xử lý đặt lịch cho cả khách hàng và nhân viên
- **Quản lý hiệu quả:** Theo dõi doanh thu, lịch làm việc, khuyến mãi một cách khoa học
- **Trải nghiệm người dùng:** Giao diện thân thiện, đặt lịch 24/7, nhận thông báo tự động

### **Q2: Em có nghiên cứu các hệ thống tương tự không? Điểm khác biệt của dự án?**

**Đáp án:**
- **Nghiên cứu:** Booksy, Fresha, Salon Iris, Square Appointments
- **Điểm khác biệt:**
  - **Tích hợp với hệ thống trường:** Xác thực qua email @ntu.edu.vn
  - **Phân loại khách hàng:** Cán bộ, sinh viên, khách vãng lai với ưu đãi khác nhau
  - **Giao diện Việt hóa:** 100% tiếng Việt, phù hợp văn hóa địa phương
  - **Tính năng đặc biệt:** Hệ thống khuyến mãi theo cấp bậc, báo cáo doanh thu chi tiết
  - **Bảo mật cao:** Chỉ cán bộ trường mới được tạo tài khoản

---

## 💻 CÂU HỎI VỀ CÔNG NGHỆ

### **Q3: Tại sao em chọn Laravel? So sánh với các framework khác?**

**Đáp án:**
- **Lý do chọn Laravel:**
  - **Eloquent ORM:** Thao tác database dễ dàng, bảo mật SQL injection
  - **Blade Template:** Tái sử dụng code, maintainability cao
  - **Middleware:** Xử lý authentication, authorization linh hoạt
  - **Artisan CLI:** Tạo migration, seeder, controller nhanh chóng
  - **Community:** Tài liệu phong phú, cộng đồng hỗ trợ lớn

- **So sánh:**
  - **vs CodeIgniter:** Laravel có ORM mạnh hơn, architecture tốt hơn
  - **vs Symfony:** Laravel dễ học hơn, development speed nhanh hơn
  - **vs Node.js:** PHP có hosting rẻ hơn, phù hợp budget dự án

### **Q4: Kiến trúc hệ thống được thiết kế như thế nào?**

**Đáp án:**
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   PRESENTATION  │    │    BUSINESS     │    │      DATA       │
│     LAYER       │    │     LAYER       │    │     LAYER       │
├─────────────────┤    ├─────────────────┤    ├─────────────────┤
│ • Blade Views   │◄──►│ • Controllers   │◄──►│ • Models        │
│ • Tailwind CSS  │    │ • Middleware    │    │ • Migrations    │
│ • JavaScript    │    │ • Validation    │    │ • Seeders       │
│ • AJAX Requests │    │ • Business Logic│    │ • Relationships │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

**Áp dụng MVC Pattern:**
- **Model:** Eloquent ORM, quan hệ database
- **View:** Blade templates, responsive design
- **Controller:** Xử lý logic, validation, authorization

---

## 🗄️ CÂU HỎI VỀ CÁC SỞ DỮ LIỆU

### **Q5: Thiết kế database như thế nào? Các mối quan hệ chính?**

**Đáp án:**
```sql
-- Các bảng chính và mối quan hệ:

users (1) ──── (n) appointments ──── (1) services
  │                    │                   │
  │                    │                   │
  └── (1) user_types   └── (1) time_slots  └── (1) categories

appointments (n) ──── (n) appointment_services
promotions (n) ────── (n) service_promotions
users (n) ─────────── (n) user_permissions
```

**Các quan hệ quan trọng:**
- **One-to-Many:** User → Appointments, Category → Services
- **Many-to-Many:** Users ↔ Permissions, Services ↔ Promotions
- **Polymorphic:** Notifications cho nhiều loại đối tượng

### **Q6: Làm thế nào để đảm bảo tính toàn vẹn dữ liệu?**

**Đáp án:**
- **Foreign Key Constraints:** Đảm bảo tham chiếu hợp lệ
- **Database Transactions:** Rollback khi có lỗi
- **Validation Rules:** Kiểm tra dữ liệu trước khi lưu
- **Soft Deletes:** Không xóa vĩnh viễn, chỉ đánh dấu deleted_at
- **UUID Primary Keys:** Tránh conflict, bảo mật ID

```php
// Ví dụ transaction
DB::transaction(function () {
    $appointment = Appointment::create($data);
    $appointment->services()->attach($serviceIds);
    $this->sendNotification($appointment);
});
```

---

## 🔐 CÂU HỎI VỀ BẢO MẬT

### **Q7: Hệ thống xử lý bảo mật như thế nào?**

**Đáp án:**
- **Authentication:**
  - Laravel Sanctum cho API tokens
  - Session-based cho web interface
  - Email verification bắt buộc

- **Authorization:**
  - Role-based permissions (Admin, Receptionist, Technician, Customer)
  - Dynamic permission checking
  - Middleware protection cho routes

- **Data Security:**
  - Password hashing với bcrypt
  - CSRF protection
  - SQL injection prevention qua Eloquent
  - XSS protection qua Blade escaping

```php
// Ví dụ middleware authorization
public function handle($request, Closure $next, $permission)
{
    if (!auth()->user()->hasPermission($permission)) {
        abort(403, 'Unauthorized');
    }
    return $next($request);
}
```

### **Q8: Làm thế nào để ngăn chặn double booking?**

**Đáp án:**
- **Database Constraints:** Unique constraint trên (time_slot_id, date, staff_id)
- **Pessimistic Locking:** Lock record khi đang xử lý
- **Real-time Validation:** AJAX check availability
- **Queue System:** Xử lý đặt lịch tuần tự

```php
// Ví dụ ngăn double booking
DB::transaction(function () use ($data) {
    $existingBooking = Appointment::where('time_slot_id', $data['time_slot_id'])
        ->where('date', $data['date'])
        ->lockForUpdate()
        ->first();

    if ($existingBooking) {
        throw new Exception('Time slot already booked');
    }

    Appointment::create($data);
});
```

---

## ⚡ CÂU HỎI VỀ HIỆU SUẤT

### **Q9: Làm thế nào để tối ưu hiệu suất hệ thống?**

**Đáp án:**
- **Database Optimization:**
  - Indexing trên các cột thường query (email, date, status)
  - Eager loading để tránh N+1 problem
  - Query optimization với explain

- **Caching Strategy:**
  - Redis cache cho session và frequently accessed data
  - View caching cho static content
  - Database query caching

- **Frontend Optimization:**
  - Lazy loading cho images
  - CSS/JS minification
  - CDN cho static assets

```php
// Ví dụ eager loading
$appointments = Appointment::with(['user', 'services', 'timeSlot'])
    ->where('date', today())
    ->get();
```

### **Q10: Hệ thống xử lý concurrent users như thế nào?**

**Đáp án:**
- **Connection Pooling:** Tối ưu database connections
- **Queue Jobs:** Xử lý email, notifications bất đồng bộ
- **Session Management:** Redis session store
- **Load Balancing:** Chuẩn bị cho horizontal scaling

---

## 🎨 CÂU HỎI VỀ GIAO DIỆN NGƯỜI DÙNG

### **Q11: Tại sao chọn Tailwind CSS? Ưu nhược điểm?**

**Đáp án:**
- **Ưu điểm:**
  - **Utility-first:** Development nhanh, không cần viết CSS custom
  - **Responsive:** Mobile-first design dễ dàng
  - **Customizable:** Dễ dàng tùy chỉnh theme, colors
  - **Performance:** Purge unused CSS, file size nhỏ
  - **Maintainability:** Consistent design system

- **Nhược điểm:**
  - **Learning curve:** Cần học utility classes
  - **HTML verbose:** Nhiều classes trong HTML
  - **Team adoption:** Cần training cho team

### **Q12: Responsive design được implement như thế nào?**

**Đáp án:**
- **Mobile-first approach:** Design cho mobile trước, scale up
- **Breakpoints:** sm (640px), md (768px), lg (1024px), xl (1280px)
- **Flexible layouts:** CSS Grid và Flexbox
- **Touch-friendly:** Button size tối thiểu 44px, spacing hợp lý

```html
<!-- Ví dụ responsive grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- Cards responsive -->
</div>
```

---

## 📊 CÂU HỎI VỀ TÍNH NĂNG NGHIỆP VỤ

### **Q13: Hệ thống khuyến mãi hoạt động như thế nào?**

**Đáp án:**
- **Loại khuyến mãi:**
  - Giảm giá theo phần trăm
  - Giảm giá cố định
  - Combo services
  - Khuyến mãi theo customer type

- **Logic áp dụng:**
  - Kiểm tra thời gian hiệu lực
  - Validate điều kiện (minimum spending, customer type)
  - Stack multiple promotions
  - Calculate final price

```php
public function calculateDiscountedPrice($service, $user)
{
    $basePrice = $service->price;
    $totalDiscount = 0;

    // Customer type discount
    $totalDiscount += $user->customerType->discount_percentage;

    // Active promotions
    foreach ($service->activePromotions as $promotion) {
        $totalDiscount += $promotion->discount_percentage;
    }

    return $basePrice * (1 - $totalDiscount / 100);
}
```

### **Q14: Quản lý lịch làm việc của nhân viên như thế nào?**

**Đáp án:**
- **Work Schedule Management:**
  - Admin assign weekly schedules
  - Staff can view their assigned time slots
  - Automatic conflict detection
  - Overtime tracking

- **Appointment Assignment:**
  - Auto-assign based on availability
  - Manual assignment by receptionist
  - Load balancing between staff
  - Skill-based assignment

---

## 🧪 CÂU HỎI VỀ TESTING

### **Q15: Em có viết test cho dự án không? Chiến lược testing?**

**Đáp án:**
- **Testing Strategy:**
  - **Unit Tests:** Test individual methods, business logic
  - **Feature Tests:** Test complete user workflows
  - **Browser Tests:** Test JavaScript interactions
  - **API Tests:** Test API endpoints

- **Test Coverage:**
  - Authentication & Authorization
  - Booking workflow
  - Payment processing
  - Notification system

```php
// Ví dụ feature test
public function test_user_can_book_appointment()
{
    $user = User::factory()->create();
    $service = Service::factory()->create();

    $response = $this->actingAs($user)
        ->post('/appointments', [
            'service_id' => $service->id,
            'date' => '2024-01-15',
            'time_slot_id' => 1
        ]);

    $response->assertRedirect('/appointments');
    $this->assertDatabaseHas('appointments', [
        'user_id' => $user->id,
        'service_id' => $service->id
    ]);
}
```

---

## 🚀 CÂU HỎI VỀ TRIỂN KHAI

### **Q16: Dự án được deploy như thế nào? CI/CD pipeline?**

**Đáp án:**
- **Deployment Strategy:**
  - **Development:** Local với Laravel Sail/Docker
  - **Staging:** Testing environment trên cloud
  - **Production:** VPS với Nginx, PHP-FPM, MySQL

- **CI/CD Pipeline:**
  - Git push → GitHub Actions
  - Run tests → Build assets
  - Deploy to staging → Manual approval
  - Deploy to production

```yaml
# GitHub Actions workflow
name: Deploy
on:
  push:
    branches: [main]
jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Run tests
        run: php artisan test
      - name: Deploy to production
        run: ./deploy.sh
```

### **Q17: Monitoring và logging được xử lý như thế nào?**

**Đáp án:**
- **Application Monitoring:**
  - Laravel Telescope cho development
  - Error tracking với Sentry
  - Performance monitoring
  - Database query monitoring

- **Logging Strategy:**
  - Structured logging với Monolog
  - Log levels: DEBUG, INFO, WARNING, ERROR
  - Log rotation và archiving
  - Centralized logging với ELK stack

---

## 📈 CÂU HỎI VỀ TƯƠNG LAI

### **Q18: Hướng phát triển tiếp theo của dự án?**

**Đáp án:**
- **Phase 2 Features:**
  - Mobile app với React Native
  - AI-powered recommendation system
  - Video consultation booking
  - Integration với payment gateways (VNPay, MoMo)

- **Scalability Improvements:**
  - Microservices architecture
  - Redis cluster cho caching
  - CDN cho static assets
  - Auto-scaling với Kubernetes

### **Q19: Challenges gặp phải và cách giải quyết?**

**Đáp án:**
- **Technical Challenges:**
  - **Complex permission system:** Giải quyết bằng dynamic permission checking
  - **Real-time updates:** Implement với WebSockets và Laravel Echo
  - **Performance với large dataset:** Optimize với indexing và caching

- **Business Challenges:**
  - **User adoption:** Training và user-friendly interface
  - **Data migration:** Careful planning và testing
  - **Integration với existing systems:** API-first approach

---

## 💼 CÂU HỎI TỪ NHÀ TUYỂN DỤNG

### **Q20: Kinh nghiệm làm việc nhóm trong dự án này?**

**Đáp án:**
- **Team Collaboration:**
  - Git workflow với feature branches
  - Code review process
  - Daily standups và sprint planning
  - Documentation và knowledge sharing

- **Conflict Resolution:**
  - Technical disagreements → Proof of concept
  - Timeline conflicts → Priority matrix
  - Code conflicts → Pair programming

### **Q21: Em học được gì từ dự án này? Áp dụng vào công việc thực tế?**

**Đáp án:**
- **Technical Skills:**
  - Full-stack development với Laravel
  - Database design và optimization
  - Security best practices
  - Testing và deployment

- **Soft Skills:**
  - Project management
  - Problem-solving approach
  - Communication với stakeholders
  - Time management

- **Business Understanding:**
  - Requirements analysis
  - User experience design
  - Performance optimization
  - Maintenance và support

---

## 🎯 KẾT LUẬN

Dự án đã thành công xây dựng một hệ thống đặt lịch hoàn chỉnh với:
- **4 vai trò người dùng** với phân quyền chi tiết
- **Giao diện responsive** thân thiện người dùng
- **Bảo mật cao** với multiple layers protection
- **Performance tối ưu** cho concurrent users
- **Scalable architecture** sẵn sàng cho tương lai

**Giá trị mang lại:**
- Tiết kiệm 70% thời gian đặt lịch
- Tăng 40% hiệu quả quản lý salon
- Cải thiện trải nghiệm khách hàng
- Số hóa hoàn toàn quy trình làm việc

---

## � GIẢI THÍCH CHI TIẾT CÁC THUẬT NGỮ VÀ KHÁI NIỆM

### **🔤 BẢNG DỊCH THUẬT NGỮ TIẾNG ANH**

| **Tiếng Anh** | **Tiếng Việt** | **Giải thích chi tiết** |
|---------------|----------------|-------------------------|
| **Eloquent ORM** | Hệ thống ánh xạ đối tượng quan hệ Eloquent | Công cụ cho phép tương tác với cơ sở dữ liệu bằng các đối tượng PHP thay vì viết SQL thuần |
| **Blade Template** | Mẫu giao diện Blade | Engine template của Laravel, cho phép viết HTML với cú pháp PHP đơn giản |
| **Middleware** | Phần mềm trung gian | Lớp xử lý HTTP request trước khi đến controller, dùng cho authentication, logging |
| **Artisan CLI** | Giao diện dòng lệnh Artisan | Công cụ command line của Laravel để tạo file, chạy migration, clear cache |
| **Migration** | Di chuyển cơ sở dữ liệu | Script PHP để tạo, sửa đổi cấu trúc bảng database một cách có kiểm soát |
| **Seeder** | Trình gieo dữ liệu | Script tạo dữ liệu mẫu cho database, hữu ích cho testing và development |
| **Eager Loading** | Tải trước dữ liệu | Kỹ thuật tải dữ liệu liên quan cùng lúc để tránh N+1 query problem |
| **Lazy Loading** | Tải chậm dữ liệu | Tải dữ liệu chỉ khi thực sự cần thiết, tiết kiệm memory |
| **Pessimistic Locking** | Khóa bi quan | Khóa dữ liệu ngay khi truy cập để tránh xung đột |
| **Optimistic Locking** | Khóa lạc quan | Kiểm tra xung đột chỉ khi cập nhật dữ liệu |
| **Race Condition** | Điều kiện đua | Xung đột khi nhiều process cùng truy cập/sửa đổi dữ liệu |
| **Double Booking** | Đặt lịch trùng lặp | Tình huống nhiều người đặt cùng một slot thời gian |
| **Queue Jobs** | Công việc hàng đợi | Xử lý các tác vụ nặng bất đồng bộ (gửi email, xử lý file) |
| **Event-Driven** | Hướng sự kiện | Kiến trúc dựa trên events và listeners để tách biệt logic |
| **Repository Pattern** | Mẫu kho lưu trữ | Design pattern tách biệt logic truy cập dữ liệu khỏi business logic |
| **Factory Pattern** | Mẫu nhà máy | Design pattern để tạo objects, đặc biệt hữu ích trong testing |
| **Observer Pattern** | Mẫu quan sát | Design pattern theo dõi và phản ứng với thay đổi của objects |
| **CSRF Protection** | Bảo vệ chống giả mạo yêu cầu | Bảo mật chống tấn công Cross-Site Request Forgery |
| **XSS Protection** | Bảo vệ chống tấn công XSS | Bảo mật chống Cross-Site Scripting attacks |
| **SQL Injection** | Tấn công chèn SQL | Loại tấn công chèn mã SQL độc hại vào query |

### **🏗️ GIẢI THÍCH CHI TIẾT KIẾN TRÚC HỆ THỐNG**

#### **1. Mô hình MVC (Model-View-Controller)**
```
📁 app/
├── 📁 Models/          # Quản lý dữ liệu và business logic
│   ├── User.php        # Model người dùng
│   ├── Appointment.php # Model lịch hẹn
│   └── Service.php     # Model dịch vụ
├── 📁 Http/
│   ├── 📁 Controllers/ # Xử lý logic ứng dụng
│   │   ├── AppointmentController.php
│   │   └── ServiceController.php
│   └── 📁 Middleware/  # Xử lý request trước khi đến controller
└── 📁 resources/views/ # Giao diện người dùng (Blade templates)
```

**Giải thích:**
- **Model:** Đại diện cho dữ liệu và quy tắc nghiệp vụ
- **View:** Hiển thị dữ liệu cho người dùng
- **Controller:** Điều phối giữa Model và View

#### **2. Cấu trúc Database và Relationships**
```sql
-- Mối quan hệ chính trong hệ thống:

👤 users (Người dùng)
├── id (UUID - Khóa chính)
├── email (Email đăng nhập)
├── password (Mật khẩu đã mã hóa)
└── customer_type_id (Loại khách hàng)

📅 appointments (Lịch hẹn)
├── id (UUID - Khóa chính)
├── user_id (Khóa ngoại → users.id)
├── time_slot_id (Khóa ngoại → time_slots.id)
├── date (Ngày hẹn)
├── status (Trạng thái: pending/confirmed/completed)
└── total_amount (Tổng tiền)

💼 services (Dịch vụ)
├── id (UUID - Khóa chính)
├── name (Tên dịch vụ)
├── price (Giá dịch vụ)
├── duration (Thời gian thực hiện)
└── category_id (Khóa ngoại → categories.id)
```

**Các loại mối quan hệ:**
- **One-to-Many (1-n):** Một người dùng có nhiều lịch hẹn
- **Many-to-Many (n-n):** Một lịch hẹn có nhiều dịch vụ
- **Polymorphic:** Notifications có thể thuộc về nhiều loại đối tượng

### **🔐 GIẢI THÍCH HỆ THỐNG BẢO MẬT**

#### **1. Authentication (Xác thực)**
```php
// Xác thực qua email và password
if (Auth::attempt(['email' => $email, 'password' => $password])) {
    // Đăng nhập thành công
    return redirect()->intended('/dashboard');
}

// Xác thực qua API token (cho mobile app)
$user = auth('sanctum')->user();
```

#### **2. Authorization (Phân quyền)**
```php
// Kiểm tra quyền trong Controller
public function store(Request $request)
{
    // Kiểm tra quyền tạo appointment
    $this->authorize('create', Appointment::class);

    // Logic tạo appointment
}

// Kiểm tra quyền trong Blade template
@can('edit', $appointment)
    <a href="{{ route('appointments.edit', $appointment) }}">Sửa</a>
@endcan
```

#### **3. Data Security (Bảo mật dữ liệu)**
```php
// Mã hóa password
$user->password = Hash::make($request->password);

// Bảo vệ CSRF
@csrf // Trong form HTML

// Escape XSS
{{ $user->name }} // Tự động escape
{!! $htmlContent !!} // Raw HTML (cẩn thận)
```

### **⚡ GIẢI THÍCH TỐI ƯU HIỆU SUẤT**

#### **1. Database Optimization**
```php
// ❌ BAD: N+1 Problem
$appointments = Appointment::all();
foreach ($appointments as $appointment) {
    echo $appointment->user->name; // Tạo N queries
}

// ✅ GOOD: Eager Loading
$appointments = Appointment::with('user')->get();
foreach ($appointments as $appointment) {
    echo $appointment->user->name; // Chỉ 2 queries
}

// ✅ BETTER: Selective Loading
$appointments = Appointment::with('user:id,name,email')->get();
```

#### **2. Caching Strategy**
```php
// Cache dữ liệu thường xuyên truy cập
$services = Cache::remember('active_services', 3600, function () {
    return Service::where('is_active', true)->get();
});

// Cache với tags để dễ quản lý
Cache::tags(['services', 'promotions'])->put('service_promotions', $data, 1800);

// Xóa cache khi cập nhật
Cache::tags(['services'])->flush();
```

#### **3. Query Optimization**
```php
// Sử dụng index cho các cột thường query
Schema::table('appointments', function (Blueprint $table) {
    $table->index(['user_id', 'date']); // Composite index
    $table->index(['date', 'status']);   // Filter index
});

// Chunk processing cho large datasets
Appointment::chunk(1000, function ($appointments) {
    foreach ($appointments as $appointment) {
        // Xử lý từng appointment
    }
});
```

### **🔄 GIẢI THÍCH XỬ LÝ ĐỒNG THỜI (CONCURRENCY)**

#### **1. Ngăn chặn Double Booking**
```php
public function bookAppointment($data)
{
    return DB::transaction(function () use ($data) {
        // Bước 1: Khóa time slot
        $timeSlot = TimeSlot::where('id', $data['time_slot_id'])
            ->lockForUpdate() // Khóa hàng này
            ->first();

        // Bước 2: Kiểm tra xem đã có booking chưa
        $existingBooking = Appointment::where([
            'time_slot_id' => $data['time_slot_id'],
            'date' => $data['date'],
            'status' => 'confirmed'
        ])->exists();

        // Bước 3: Nếu đã có booking, báo lỗi
        if ($existingBooking) {
            throw new BookingConflictException('Slot đã được đặt');
        }

        // Bước 4: Tạo appointment mới
        $appointment = Appointment::create($data);

        // Bước 5: Giảm số slot available
        $timeSlot->decrement('available_slots');

        return $appointment;
    });
}
```

**Giải thích:**
- **DB::transaction():** Đảm bảo tất cả operations thành công hoặc rollback
- **lockForUpdate():** Khóa hàng để tránh race condition
- **Exception handling:** Xử lý lỗi khi có xung đột

#### **2. Optimistic Locking**
```php
class Appointment extends Model
{
    protected $fillable = ['version', ...];

    public function updateWithVersion($data)
    {
        $currentVersion = $this->version;
        $data['version'] = $currentVersion + 1;

        // Chỉ update nếu version chưa thay đổi
        $updated = $this->where('id', $this->id)
            ->where('version', $currentVersion)
            ->update($data);

        if (!$updated) {
            throw new OptimisticLockException('Dữ liệu đã được cập nhật bởi người khác');
        }

        return $this->fresh();
    }
}
```

### **📊 GIẢI THÍCH EVENT-DRIVEN ARCHITECTURE**

#### **1. Events và Listeners**
```php
// Event: Sự kiện xảy ra
class AppointmentBooked
{
    public function __construct(
        public Appointment $appointment
    ) {}
}

// Listener: Xử lý sự kiện
class SendBookingConfirmation
{
    public function handle(AppointmentBooked $event)
    {
        // Gửi email xác nhận
        Mail::to($event->appointment->user)
            ->send(new BookingConfirmationMail($event->appointment));
    }
}

class UpdateStatistics
{
    public function handle(AppointmentBooked $event)
    {
        // Cập nhật thống kê
        DailyStatistic::increment('total_bookings');
    }
}
```

#### **2. Đăng ký Events**
```php
// EventServiceProvider.php
protected $listen = [
    AppointmentBooked::class => [
        SendBookingConfirmation::class,
        UpdateStatistics::class,
        LogActivity::class,
    ],
    AppointmentCancelled::class => [
        SendCancellationNotification::class,
        RestoreSlotAvailability::class,
    ],
];
```

#### **3. Sử dụng Events**
```php
// Trong Controller
public function store(Request $request)
{
    $appointment = Appointment::create($request->validated());

    // Fire event - tự động trigger tất cả listeners
    event(new AppointmentBooked($appointment));

    return redirect()->route('appointments.index');
}
```

**Ưu điểm Event-Driven:**
- **Loose coupling:** Các thành phần không phụ thuộc trực tiếp
- **Extensibility:** Dễ dàng thêm listeners mới
- **Maintainability:** Code dễ bảo trì và test

### **🧪 GIẢI THÍCH TESTING STRATEGY**

#### **1. Unit Tests**
```php
// Test business logic
class AppointmentServiceTest extends TestCase
{
    public function test_can_calculate_total_amount()
    {
        $service1 = Service::factory()->create(['price' => 100000]);
        $service2 = Service::factory()->create(['price' => 150000]);

        $appointmentService = new AppointmentService();
        $total = $appointmentService->calculateTotal([$service1, $service2]);

        $this->assertEquals(250000, $total);
    }
}
```

#### **2. Feature Tests**
```php
// Test complete workflows
class BookingWorkflowTest extends TestCase
{
    public function test_user_can_book_appointment()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create();
        $timeSlot = TimeSlot::factory()->create();

        $response = $this->actingAs($user)
            ->post('/appointments', [
                'service_ids' => [$service->id],
                'time_slot_id' => $timeSlot->id,
                'date' => '2024-01-15'
            ]);

        $response->assertRedirect('/appointments');
        $this->assertDatabaseHas('appointments', [
            'user_id' => $user->id,
        ]);
    }
}
```

#### **3. Browser Tests**
```php
// Test JavaScript interactions
class BookingBrowserTest extends DuskTestCase
{
    public function test_user_can_select_services_and_book()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/booking')
                    ->select('service_ids[]', 1)
                    ->select('time_slot_id', 1)
                    ->type('date', '2024-01-15')
                    ->press('Đặt lịch')
                    ->assertSee('Đặt lịch thành công');
        });
    }
}
```

---

## �🔥 CÂU HỎI CHUYÊN SÂU CHO GIẢNG VIÊN

### **Q22: Giải thích chi tiết về Dynamic Permission System?**

**Đáp án:**
```php
// Hệ thống phân quyền động
class User extends Model
{
    public function hasAnyPermission($group, $action)
    {
        return $this->permissions()
            ->where('name', "{$group}.{$action}")
            ->where("can_{$action}", true)
            ->exists();
    }

    public function clearPermissionCache()
    {
        Cache::forget("user_permissions_{$this->id}");
    }
}

// Middleware tự động kiểm tra quyền
class CheckPermission
{
    public function handle($request, Closure $next, $permission)
    {
        [$group, $action] = explode('.', $permission);

        if (!auth()->user()->hasAnyPermission($group, $action)) {
            abort(403, "Không có quyền {$permission}");
        }

        return $next($request);
    }
}
```

**Ưu điểm:**
- **Flexible:** Thêm quyền mới không cần code
- **Scalable:** Support unlimited roles và permissions
- **Cacheable:** Performance cao với Redis cache
- **Auditable:** Track permission changes

### **Q23: Xử lý Concurrency và Race Conditions như thế nào?**

**Đáp án:**
```php
// Pessimistic Locking cho booking
public function bookAppointment($data)
{
    return DB::transaction(function () use ($data) {
        // Lock time slot để tránh double booking
        $timeSlot = TimeSlot::where('id', $data['time_slot_id'])
            ->lockForUpdate()
            ->first();

        // Kiểm tra availability
        $existingBooking = Appointment::where([
            'time_slot_id' => $data['time_slot_id'],
            'date' => $data['date'],
            'status' => 'confirmed'
        ])->exists();

        if ($existingBooking) {
            throw new BookingConflictException();
        }

        // Tạo appointment
        $appointment = Appointment::create($data);

        // Update slot availability
        $timeSlot->decrement('available_slots');

        return $appointment;
    });
}

// Optimistic Locking với version control
class Appointment extends Model
{
    protected $fillable = ['version', ...];

    public function updateWithVersion($data)
    {
        $currentVersion = $this->version;
        $data['version'] = $currentVersion + 1;

        $updated = $this->where('id', $this->id)
            ->where('version', $currentVersion)
            ->update($data);

        if (!$updated) {
            throw new OptimisticLockException();
        }

        return $this->fresh();
    }
}
```

### **Q24: Event-Driven Architecture được implement như thế nào?**

**Đáp án:**
```php
// Events
class AppointmentBooked
{
    public function __construct(
        public Appointment $appointment
    ) {}
}

class AppointmentCancelled
{
    public function __construct(
        public Appointment $appointment,
        public string $reason
    ) {}
}

// Listeners
class SendBookingConfirmation
{
    public function handle(AppointmentBooked $event)
    {
        Mail::to($event->appointment->user)
            ->send(new BookingConfirmationMail($event->appointment));
    }
}

class UpdateSlotAvailability
{
    public function handle(AppointmentCancelled $event)
    {
        $event->appointment->timeSlot->increment('available_slots');
    }
}

// Service Provider
class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        AppointmentBooked::class => [
            SendBookingConfirmation::class,
            UpdateStatistics::class,
            LogActivity::class,
        ],
        AppointmentCancelled::class => [
            UpdateSlotAvailability::class,
            SendCancellationNotification::class,
        ],
    ];
}

// Usage trong Controller
public function store(Request $request)
{
    $appointment = Appointment::create($request->validated());

    // Fire event
    event(new AppointmentBooked($appointment));

    return redirect()->route('appointments.index')
        ->with('success', 'Đặt lịch thành công!');
}
```

### **Q25: Caching Strategy chi tiết?**

**Đáp án:**
```php
// Multi-layer caching
class ServiceRepository
{
    public function getActiveServices()
    {
        return Cache::tags(['services'])
            ->remember('active_services', 3600, function () {
                return Service::with('category')
                    ->where('is_active', true)
                    ->get();
            });
    }

    public function getServicesByCategory($categoryId)
    {
        return Cache::tags(['services', 'categories'])
            ->remember("services_category_{$categoryId}", 1800, function () use ($categoryId) {
                return Service::where('category_id', $categoryId)
                    ->where('is_active', true)
                    ->get();
            });
    }

    public function clearServiceCache()
    {
        Cache::tags(['services'])->flush();
    }
}

// Cache warming
class CacheWarmupCommand extends Command
{
    public function handle()
    {
        $this->info('Warming up cache...');

        // Warm up frequently accessed data
        app(ServiceRepository::class)->getActiveServices();
        app(CategoryRepository::class)->getActiveCategories();
        app(PromotionRepository::class)->getActivePromotions();

        $this->info('Cache warmed up successfully!');
    }
}

// Cache invalidation
class ServiceObserver
{
    public function updated(Service $service)
    {
        Cache::tags(['services'])->flush();
        Cache::forget("service_{$service->id}");
    }
}
```

---

## 💡 CÂU HỎI VỀ DESIGN PATTERNS

### **Q26: Repository Pattern được áp dụng như thế nào?**

**Đáp án:**
```php
// Interface
interface AppointmentRepositoryInterface
{
    public function findByUser(User $user): Collection;
    public function findByDateRange(Carbon $start, Carbon $end): Collection;
    public function createWithServices(array $data, array $serviceIds): Appointment;
}

// Implementation
class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function findByUser(User $user): Collection
    {
        return Appointment::with(['services', 'timeSlot'])
            ->where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->get();
    }

    public function findByDateRange(Carbon $start, Carbon $end): Collection
    {
        return Appointment::with(['user', 'services'])
            ->whereBetween('date', [$start, $end])
            ->get();
    }

    public function createWithServices(array $data, array $serviceIds): Appointment
    {
        return DB::transaction(function () use ($data, $serviceIds) {
            $appointment = Appointment::create($data);
            $appointment->services()->attach($serviceIds);
            return $appointment->load('services');
        });
    }
}

// Service Provider binding
class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            AppointmentRepositoryInterface::class,
            AppointmentRepository::class
        );
    }
}
```

### **Q27: Observer Pattern cho Business Logic?**

**Đáp án:**
```php
// Model Observer
class AppointmentObserver
{
    public function creating(Appointment $appointment)
    {
        // Auto-generate appointment code
        $appointment->code = $this->generateAppointmentCode();

        // Set default status
        $appointment->status = 'pending';
    }

    public function created(Appointment $appointment)
    {
        // Send confirmation email
        Mail::to($appointment->user)->send(
            new AppointmentConfirmationMail($appointment)
        );

        // Create notification
        $appointment->user->notifications()->create([
            'type' => 'appointment_created',
            'data' => [
                'appointment_id' => $appointment->id,
                'message' => 'Lịch hẹn đã được tạo thành công'
            ]
        ]);
    }

    public function updating(Appointment $appointment)
    {
        // Log status changes
        if ($appointment->isDirty('status')) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'appointment_status_changed',
                'subject_type' => Appointment::class,
                'subject_id' => $appointment->id,
                'properties' => [
                    'old_status' => $appointment->getOriginal('status'),
                    'new_status' => $appointment->status
                ]
            ]);
        }
    }

    private function generateAppointmentCode(): string
    {
        return 'APT' . date('Ymd') . str_pad(
            Appointment::whereDate('created_at', today())->count() + 1,
            4, '0', STR_PAD_LEFT
        );
    }
}
```

### **Q28: Factory Pattern cho Testing?**

**Đáp án:**
```php
// Model Factory
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'user_id' => User::factory(),
            'time_slot_id' => TimeSlot::factory(),
            'date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'completed']),
            'notes' => $this->faker->optional()->sentence,
            'total_amount' => $this->faker->numberBetween(100000, 500000),
        ];
    }

    public function pending()
    {
        return $this->state(['status' => 'pending']);
    }

    public function confirmed()
    {
        return $this->state(['status' => 'confirmed']);
    }

    public function withServices(int $count = 2)
    {
        return $this->afterCreating(function (Appointment $appointment) use ($count) {
            $services = Service::factory()->count($count)->create();
            $appointment->services()->attach($services);
        });
    }
}

// Usage trong tests
class AppointmentTest extends TestCase
{
    public function test_user_can_view_their_appointments()
    {
        $user = User::factory()->create();
        $appointments = Appointment::factory()
            ->count(3)
            ->confirmed()
            ->withServices(2)
            ->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->get('/appointments');

        $response->assertStatus(200)
            ->assertViewHas('appointments')
            ->assertSee($appointments->first()->code);
    }
}
```

---

## 🎯 CÂU HỎI VỀ PERFORMANCE OPTIMIZATION

### **Q29: Database Query Optimization strategies?**

**Đáp án:**
```php
// N+1 Problem Solution
// BAD: N+1 queries
$appointments = Appointment::all();
foreach ($appointments as $appointment) {
    echo $appointment->user->name; // N queries
    echo $appointment->services->count(); // N queries
}

// GOOD: Eager loading
$appointments = Appointment::with(['user', 'services'])->get();
foreach ($appointments as $appointment) {
    echo $appointment->user->name; // No additional queries
    echo $appointment->services->count(); // No additional queries
}

// Advanced eager loading với constraints
$appointments = Appointment::with([
    'user:id,first_name,last_name,email',
    'services' => function ($query) {
        $query->select('id', 'name', 'price')
              ->where('is_active', true);
    },
    'timeSlot:id,start_time,end_time'
])->get();

// Database indexing strategy
Schema::table('appointments', function (Blueprint $table) {
    $table->index(['user_id', 'date']); // Composite index
    $table->index(['date', 'status']); // For filtering
    $table->index('time_slot_id'); // Foreign key index
});

// Query optimization với raw SQL khi cần
$monthlyStats = DB::select("
    SELECT
        DATE_FORMAT(date, '%Y-%m') as month,
        COUNT(*) as total_appointments,
        SUM(total_amount) as total_revenue,
        AVG(total_amount) as avg_revenue
    FROM appointments
    WHERE status = 'completed'
        AND date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
    GROUP BY DATE_FORMAT(date, '%Y-%m')
    ORDER BY month DESC
");
```

### **Q30: Memory Management và Resource Optimization?**

**Đáp án:**
```php
// Chunk processing cho large datasets
public function generateMonthlyReport()
{
    $totalRevenue = 0;
    $appointmentCount = 0;

    // Process 1000 records at a time
    Appointment::where('status', 'completed')
        ->whereMonth('date', now()->month)
        ->chunk(1000, function ($appointments) use (&$totalRevenue, &$appointmentCount) {
            foreach ($appointments as $appointment) {
                $totalRevenue += $appointment->total_amount;
                $appointmentCount++;
            }
        });

    return [
        'total_revenue' => $totalRevenue,
        'appointment_count' => $appointmentCount,
        'average_revenue' => $appointmentCount > 0 ? $totalRevenue / $appointmentCount : 0
    ];
}

// Lazy collections cho memory efficiency
public function exportAppointments()
{
    return Appointment::with(['user', 'services'])
        ->cursor() // Returns LazyCollection
        ->map(function ($appointment) {
            return [
                'code' => $appointment->code,
                'customer' => $appointment->user->full_name,
                'services' => $appointment->services->pluck('name')->join(', '),
                'date' => $appointment->date->format('d/m/Y'),
                'amount' => number_format($appointment->total_amount)
            ];
        });
}

// Resource cleanup
class AppointmentExportJob implements ShouldQueue
{
    public function handle()
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'appointments_export');

        try {
            $this->generateExcelFile($tempFile);
            $this->sendEmailWithAttachment($tempFile);
        } finally {
            // Always cleanup temp files
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }
    }
}
```

---

## 🔧 CÂU HỎI VỀ MAINTENANCE & MONITORING

### **Q31: Error Handling và Logging Strategy?**

**Đáp án:**
```php
// Custom Exception Classes
class BookingException extends Exception
{
    public static function slotNotAvailable(): self
    {
        return new self('Time slot is not available for booking');
    }

    public static function invalidTimeSlot(): self
    {
        return new self('Selected time slot is invalid');
    }
}

// Global Exception Handler
class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof BookingException) {
            return response()->json([
                'error' => $exception->getMessage(),
                'code' => 'BOOKING_ERROR'
            ], 400);
        }

        if ($exception instanceof ValidationException) {
            Log::warning('Validation failed', [
                'errors' => $exception->errors(),
                'input' => $request->all(),
                'user_id' => auth()->id()
            ]);
        }

        return parent::render($request, $exception);
    }
}

// Structured Logging
class AppointmentService
{
    public function createAppointment(array $data)
    {
        Log::info('Creating appointment', [
            'user_id' => auth()->id(),
            'service_ids' => $data['service_ids'],
            'date' => $data['date']
        ]);

        try {
            $appointment = $this->repository->create($data);

            Log::info('Appointment created successfully', [
                'appointment_id' => $appointment->id,
                'appointment_code' => $appointment->code
            ]);

            return $appointment;
        } catch (Exception $e) {
            Log::error('Failed to create appointment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);

            throw $e;
        }
    }
}
```

### **Q32: Health Checks và Monitoring?**

**Đáp án:**
```php
// Health Check Endpoint
class HealthController extends Controller
{
    public function check()
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'queue' => $this->checkQueue(),
        ];

        $allHealthy = collect($checks)->every(fn($check) => $check['status'] === 'ok');

        return response()->json([
            'status' => $allHealthy ? 'healthy' : 'unhealthy',
            'checks' => $checks,
            'timestamp' => now()->toISOString()
        ], $allHealthy ? 200 : 503);
    }

    private function checkDatabase(): array
    {
        try {
            DB::select('SELECT 1');
            return ['status' => 'ok', 'message' => 'Database connection successful'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    private function checkCache(): array
    {
        try {
            Cache::put('health_check', 'ok', 60);
            $value = Cache::get('health_check');
            return ['status' => $value === 'ok' ? 'ok' : 'error'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}

// Performance Monitoring
class PerformanceMiddleware
{
    public function handle($request, Closure $next)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        $response = $next($request);

        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        $executionTime = ($endTime - $startTime) * 1000; // ms
        $memoryUsage = $endMemory - $startMemory;

        Log::info('Request performance', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'execution_time_ms' => round($executionTime, 2),
            'memory_usage_mb' => round($memoryUsage / 1024 / 1024, 2),
            'user_id' => auth()->id()
        ]);

        // Alert if slow request
        if ($executionTime > 2000) { // 2 seconds
            Log::warning('Slow request detected', [
                'url' => $request->fullUrl(),
                'execution_time_ms' => $executionTime
            ]);
        }

        return $response;
    }
}
```

---

## 📚 GIẢI THÍCH CHI TIẾT CÁC THUẬT NGỮ VÀ KHÁI NIỆM

### **🔤 BẢNG DỊCH THUẬT NGỮ TIẾNG ANH QUAN TRỌNG**

| **Tiếng Anh** | **Tiếng Việt** | **Giải thích chi tiết** |
|---------------|----------------|-------------------------|
| **Eloquent ORM** | Hệ thống ánh xạ đối tượng quan hệ Eloquent | Công cụ cho phép tương tác với cơ sở dữ liệu bằng các đối tượng PHP thay vì viết SQL thuần |
| **Blade Template** | Mẫu giao diện Blade | Engine template của Laravel, cho phép viết HTML với cú pháp PHP đơn giản |
| **Middleware** | Phần mềm trung gian | Lớp xử lý HTTP request trước khi đến controller, dùng cho authentication, logging |
| **Artisan CLI** | Giao diện dòng lệnh Artisan | Công cụ command line của Laravel để tạo file, chạy migration, clear cache |
| **Migration** | Di chuyển cơ sở dữ liệu | Script PHP để tạo, sửa đổi cấu trúc bảng database một cách có kiểm soát |
| **Seeder** | Trình gieo dữ liệu | Script tạo dữ liệu mẫu cho database, hữu ích cho testing và development |
| **Eager Loading** | Tải trước dữ liệu | Kỹ thuật tải dữ liệu liên quan cùng lúc để tránh N+1 query problem |
| **Lazy Loading** | Tải chậm dữ liệu | Tải dữ liệu chỉ khi thực sự cần thiết, tiết kiệm memory |
| **Pessimistic Locking** | Khóa bi quan | Khóa dữ liệu ngay khi truy cập để tránh xung đột |
| **Optimistic Locking** | Khóa lạc quan | Kiểm tra xung đột chỉ khi cập nhật dữ liệu |
| **Race Condition** | Điều kiện đua | Xung đột khi nhiều process cùng truy cập/sửa đổi dữ liệu |
| **Double Booking** | Đặt lịch trùng lặp | Tình huống nhiều người đặt cùng một slot thời gian |
| **Queue Jobs** | Công việc hàng đợi | Xử lý các tác vụ nặng bất đồng bộ (gửi email, xử lý file) |
| **Event-Driven** | Hướng sự kiện | Kiến trúc dựa trên events và listeners để tách biệt logic |
| **Repository Pattern** | Mẫu kho lưu trữ | Design pattern tách biệt logic truy cập dữ liệu khỏi business logic |
| **Factory Pattern** | Mẫu nhà máy | Design pattern để tạo objects, đặc biệt hữu ích trong testing |
| **Observer Pattern** | Mẫu quan sát | Design pattern theo dõi và phản ứng với thay đổi của objects |
| **CSRF Protection** | Bảo vệ chống giả mạo yêu cầu | Bảo mật chống tấn công Cross-Site Request Forgery |
| **XSS Protection** | Bảo vệ chống tấn công XSS | Bảo mật chống Cross-Site Scripting attacks |
| **SQL Injection** | Tấn công chèn SQL | Loại tấn công chèn mã SQL độc hại vào query |
| **Polymorphic Relationship** | Mối quan hệ đa hình | Một model có thể thuộc về nhiều loại model khác |
| **Soft Delete** | Xóa mềm | Đánh dấu bản ghi là đã xóa thay vì xóa vĩnh viễn |
| **UUID** | Định danh duy nhất toàn cầu | Chuỗi 36 ký tự duy nhất, bảo mật hơn auto-increment ID |
| **Chunk Processing** | Xử lý theo khối | Chia nhỏ dữ liệu lớn thành các khối nhỏ để xử lý |
| **Cursor Pagination** | Phân trang con trỏ | Phân trang hiệu quả cho dataset lớn |
| **N+1 Problem** | Vấn đề N+1 truy vấn | Thực hiện quá nhiều query không cần thiết |

### **🏗️ GIẢI THÍCH CHI TIẾT KIẾN TRÚC HỆ THỐNG**

#### **1. Mô hình MVC (Model-View-Controller)**
```
📁 app/
├── 📁 Models/          # Quản lý dữ liệu và business logic
│   ├── User.php        # Model người dùng - quản lý thông tin user
│   ├── Appointment.php # Model lịch hẹn - xử lý booking logic
│   ├── Service.php     # Model dịch vụ - quản lý services
│   ├── TimeSlot.php    # Model khung giờ - quản lý time slots
│   └── Promotion.php   # Model khuyến mãi - xử lý discounts
├── 📁 Http/
│   ├── 📁 Controllers/ # Xử lý logic ứng dụng
│   │   ├── AppointmentController.php  # Xử lý booking workflow
│   │   ├── ServiceController.php      # Quản lý services
│   │   ├── UserController.php         # Quản lý users
│   │   └── AdminController.php        # Admin functions
│   ├── 📁 Middleware/  # Xử lý request trước khi đến controller
│   │   ├── Authenticate.php           # Kiểm tra đăng nhập
│   │   ├── CheckPermission.php        # Kiểm tra quyền
│   │   └── PerformanceMonitor.php     # Monitor hiệu suất
│   └── 📁 Requests/    # Validation rules
└── 📁 resources/views/ # Giao diện người dùng (Blade templates)
    ├── 📁 appointments/ # Views cho booking
    ├── 📁 admin/       # Admin interface
    └── 📁 layouts/     # Layout templates
```

**Giải thích chi tiết:**
- **Model:** Đại diện cho dữ liệu và quy tắc nghiệp vụ, tương tác với database
- **View:** Hiển thị dữ liệu cho người dùng, sử dụng Blade template engine
- **Controller:** Điều phối giữa Model và View, xử lý HTTP requests

#### **2. Cấu trúc Database và Relationships**
```sql
-- Mối quan hệ chính trong hệ thống Beauty Salon:

👤 users (Bảng người dùng)
├── id (UUID - Khóa chính, bảo mật cao)
├── first_name (Tên)
├── last_name (Họ)
├── email (Email đăng nhập, unique)
├── password (Mật khẩu đã mã hóa bcrypt)
├── phone (Số điện thoại)
├── customer_type_id (Loại khách hàng: VIP, thường)
├── email_verified_at (Thời gian xác thực email)
├── created_at (Thời gian tạo)
└── updated_at (Thời gian cập nhật)

📅 appointments (Bảng lịch hẹn)
├── id (UUID - Khóa chính)
├── user_id (Khóa ngoại → users.id)
├── time_slot_id (Khóa ngoại → time_slots.id)
├── staff_id (Nhân viên phụ trách)
├── date (Ngày hẹn)
├── status (Trạng thái: pending/confirmed/completed/cancelled)
├── notes (Ghi chú từ khách hàng)
├── total_amount (Tổng tiền)
├── discount_amount (Số tiền giảm giá)
└── payment_status (Trạng thái thanh toán)

💼 services (Bảng dịch vụ)
├── id (UUID - Khóa chính)
├── name (Tên dịch vụ)
├── description (Mô tả chi tiết)
├── price (Giá dịch vụ)
├── duration (Thời gian thực hiện - phút)
├── category_id (Khóa ngoại → categories.id)
├── is_active (Trạng thái hoạt động)
└── image_url (Hình ảnh dịch vụ)

🕐 time_slots (Bảng khung giờ)
├── id (Auto increment)
├── start_time (Giờ bắt đầu: 08:00)
├── end_time (Giờ kết thúc: 09:00)
├── max_capacity (Số lượng khách tối đa)
└── is_active (Có hoạt động không)

🎁 promotions (Bảng khuyến mãi)
├── id (UUID)
├── name (Tên chương trình)
├── description (Mô tả)
├── discount_type (Loại: percentage/fixed)
├── discount_value (Giá trị giảm)
├── start_date (Ngày bắt đầu)
├── end_date (Ngày kết thúc)
└── is_active (Trạng thái)
```

**Các loại mối quan hệ trong hệ thống:**
- **One-to-Many (1-n):**
  - Một người dùng có nhiều lịch hẹn
  - Một category có nhiều services
  - Một time_slot có nhiều appointments
- **Many-to-Many (n-n):**
  - Một appointment có nhiều services (qua bảng appointment_services)
  - Một service có nhiều promotions (qua bảng service_promotions)
- **Polymorphic:**
  - Notifications có thể thuộc về User, Appointment, hoặc Service

### **🔐 GIẢI THÍCH HỆ THỐNG BẢO MẬT CHI TIẾT**

#### **1. Authentication (Xác thực người dùng)**
```php
// Xác thực qua email và password
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // Attempt login với rate limiting
    if (Auth::attempt($credentials, $request->remember)) {
        $request->session()->regenerate(); // Tạo session ID mới

        // Log successful login
        Log::info('User logged in', [
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->intended('/dashboard');
    }

    // Log failed login attempt
    Log::warning('Failed login attempt', [
        'email' => $request->email,
        'ip' => $request->ip()
    ]);

    return back()->withErrors([
        'email' => 'Thông tin đăng nhập không chính xác.',
    ]);
}

// Xác thực qua API token (cho mobile app)
public function apiLogin(Request $request)
{
    if (Auth::attempt($request->only('email', 'password'))) {
        $user = auth()->user();
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    return response()->json(['message' => 'Unauthorized'], 401);
}
```

#### **2. Authorization (Phân quyền chi tiết)**
```php
// Middleware kiểm tra quyền
class CheckPermission
{
    public function handle($request, Closure $next, $permission)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('/login');
        }

        // Kiểm tra quyền cụ thể
        if (!$user->hasPermission($permission)) {
            abort(403, "Bạn không có quyền {$permission}");
        }

        return $next($request);
    }
}

// Model User với dynamic permissions
class User extends Model
{
    public function hasPermission($permission)
    {
        // Cache permissions để tăng performance
        $userPermissions = Cache::remember(
            "user_permissions_{$this->id}",
            3600,
            fn() => $this->permissions()->pluck('name')->toArray()
        );

        return in_array($permission, $userPermissions);
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }
}

// Policy classes cho fine-grained authorization
class AppointmentPolicy
{
    public function view(User $user, Appointment $appointment)
    {
        // User chỉ xem được appointment của mình
        // Hoặc staff có quyền view_all_appointments
        return $user->id === $appointment->user_id ||
               $user->hasPermission('appointments.view_all');
    }

    public function update(User $user, Appointment $appointment)
    {
        // Chỉ cho phép update nếu appointment chưa completed
        // Và user có quyền hoặc là chủ appointment
        return $appointment->status !== 'completed' &&
               ($user->id === $appointment->user_id ||
                $user->hasPermission('appointments.update'));
    }

    public function cancel(User $user, Appointment $appointment)
    {
        // Chỉ cancel được nếu appointment chưa bắt đầu
        $canCancelTime = $appointment->date->subHours(2);

        return now() < $canCancelTime &&
               ($user->id === $appointment->user_id ||
                $user->hasPermission('appointments.cancel'));
    }
}
```

#### **3. Data Security (Bảo mật dữ liệu)**
```php
// Mã hóa password với bcrypt
class User extends Model
{
    protected $hidden = ['password', 'remember_token'];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    // Verify password
    public function checkPassword($password)
    {
        return Hash::check($password, $this->password);
    }
}

// CSRF Protection trong forms
<!-- Blade template -->
<form method="POST" action="{{ route('appointments.store') }}">
    @csrf <!-- Laravel tự động tạo CSRF token -->
    <input type="text" name="service_id" value="{{ old('service_id') }}">
    <button type="submit">Đặt lịch</button>
</form>

// XSS Protection
<!-- Tự động escape output -->
<p>Tên khách hàng: {{ $user->name }}</p>

<!-- Raw HTML (cần cẩn thận) -->
<div class="content">{!! $sanitizedHtmlContent !!}</div>

// SQL Injection Prevention với Eloquent
// ✅ SAFE: Sử dụng Eloquent ORM
$users = User::where('email', $request->email)->get();

// ✅ SAFE: Parameter binding
$users = DB::select('SELECT * FROM users WHERE email = ?', [$email]);

// ❌ DANGEROUS: Raw SQL concatenation
$users = DB::select("SELECT * FROM users WHERE email = '$email'");
```

### **⚡ GIẢI THÍCH TỐI ƯU HIỆU SUẤT CHI TIẾT**

#### **1. Database Query Optimization (Tối ưu truy vấn cơ sở dữ liệu)**
```php
// ❌ BAD: N+1 Problem - Tạo ra quá nhiều queries
$appointments = Appointment::all(); // 1 query
foreach ($appointments as $appointment) {
    echo $appointment->user->name;     // N queries (1 cho mỗi appointment)
    echo $appointment->services->count(); // N queries nữa
}
// Tổng cộng: 1 + N + N = 2N + 1 queries

// ✅ GOOD: Eager Loading - Chỉ cần 3 queries
$appointments = Appointment::with(['user', 'services'])->get();
foreach ($appointments as $appointment) {
    echo $appointment->user->name;     // Không có query thêm
    echo $appointment->services->count(); // Không có query thêm
}
// Tổng cộng: 3 queries (appointments, users, services)

// ✅ BETTER: Selective Loading - Chỉ lấy cột cần thiết
$appointments = Appointment::with([
    'user:id,first_name,last_name,email', // Chỉ lấy các cột cần thiết
    'services:id,name,price',
    'timeSlot:id,start_time,end_time'
])->select('id', 'user_id', 'time_slot_id', 'date', 'status')
  ->get();

// ✅ BEST: Conditional Eager Loading
$appointments = Appointment::with([
    'user:id,first_name,last_name',
    'services' => function ($query) {
        $query->select('id', 'name', 'price')
              ->where('is_active', true); // Chỉ lấy services đang hoạt động
    }
])->where('date', '>=', today())
  ->orderBy('date', 'asc')
  ->get();

// Advanced: Lazy Eager Loading khi cần
$appointments = Appointment::all();
if ($needUserInfo) {
    $appointments->load('user:id,name,email');
}
```

#### **2. Database Indexing Strategy (Chiến lược đánh chỉ mục)**
```php
// Migration tạo indexes
Schema::table('appointments', function (Blueprint $table) {
    // Single column indexes
    $table->index('user_id');        // Tìm appointments theo user
    $table->index('date');           // Tìm theo ngày
    $table->index('status');         // Lọc theo trạng thái

    // Composite indexes (thứ tự quan trọng)
    $table->index(['user_id', 'date']);     // Tìm appointments của user theo ngày
    $table->index(['date', 'status']);      // Lọc theo ngày và trạng thái
    $table->index(['time_slot_id', 'date']); // Kiểm tra slot availability

    // Unique constraints
    $table->unique(['time_slot_id', 'date', 'staff_id']); // Ngăn double booking
});

// Sử dụng EXPLAIN để kiểm tra query performance
DB::enableQueryLog();
$appointments = Appointment::where('user_id', 1)
    ->where('date', '>=', today())
    ->get();
dd(DB::getQueryLog()); // Xem query đã thực hiện

// Raw query với EXPLAIN
$explain = DB::select('EXPLAIN SELECT * FROM appointments WHERE user_id = ? AND date >= ?', [1, today()]);
```

#### **3. Caching Strategy (Chiến lược Cache)**
```php
// 1. Query Result Caching
class ServiceRepository
{
    public function getActiveServices()
    {
        return Cache::tags(['services'])
            ->remember('active_services', 3600, function () {
                return Service::with('category')
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get();
            });
    }

    public function getServicesByCategory($categoryId)
    {
        return Cache::tags(['services', 'categories'])
            ->remember("services_category_{$categoryId}", 1800, function () use ($categoryId) {
                return Service::where('category_id', $categoryId)
                    ->where('is_active', true)
                    ->with('promotions')
                    ->get();
            });
    }

    // Cache invalidation khi có thay đổi
    public function updateService($id, $data)
    {
        $service = Service::findOrFail($id);
        $service->update($data);

        // Xóa cache liên quan
        Cache::tags(['services'])->flush();
        Cache::forget("service_{$id}");

        return $service;
    }
}

// 2. Model Attribute Caching
class User extends Model
{
    public function getFullNameAttribute()
    {
        return Cache::remember("user_full_name_{$this->id}", 3600, function () {
            return $this->first_name . ' ' . $this->last_name;
        });
    }

    public function getTotalSpentAttribute()
    {
        return Cache::remember("user_total_spent_{$this->id}", 1800, function () {
            return $this->appointments()
                ->where('status', 'completed')
                ->sum('total_amount');
        });
    }
}

// 3. View Caching
class AppointmentController extends Controller
{
    public function dashboard()
    {
        $cacheKey = "dashboard_data_" . auth()->id();

        $dashboardData = Cache::remember($cacheKey, 600, function () {
            return [
                'upcoming_appointments' => auth()->user()
                    ->appointments()
                    ->with(['services', 'timeSlot'])
                    ->where('date', '>=', today())
                    ->orderBy('date')
                    ->limit(5)
                    ->get(),
                'recent_services' => Service::popular()->limit(6)->get(),
                'active_promotions' => Promotion::active()->limit(3)->get()
            ];
        });

        return view('dashboard', $dashboardData);
    }
}

// 4. Cache Warming Command
class CacheWarmupCommand extends Command
{
    protected $signature = 'cache:warmup';
    protected $description = 'Warm up application cache';

    public function handle()
    {
        $this->info('Warming up cache...');

        // Warm up frequently accessed data
        app(ServiceRepository::class)->getActiveServices();

        Category::with('services')->get()->each(function ($category) {
            app(ServiceRepository::class)->getServicesByCategory($category->id);
        });

        Promotion::active()->get(); // Cache active promotions

        $this->info('Cache warmed up successfully!');
    }
}
```

#### **4. Memory Management (Quản lý bộ nhớ)**
```php
// Chunk processing cho large datasets
public function generateMonthlyReport()
{
    $totalRevenue = 0;
    $appointmentCount = 0;

    // Xử lý 1000 records một lần để tránh memory overflow
    Appointment::where('status', 'completed')
        ->whereMonth('date', now()->month)
        ->chunk(1000, function ($appointments) use (&$totalRevenue, &$appointmentCount) {
            foreach ($appointments as $appointment) {
                $totalRevenue += $appointment->total_amount;
                $appointmentCount++;
            }

            // Giải phóng memory sau mỗi chunk
            unset($appointments);
        });

    return [
        'total_revenue' => $totalRevenue,
        'appointment_count' => $appointmentCount,
        'average_revenue' => $appointmentCount > 0 ? $totalRevenue / $appointmentCount : 0
    ];
}

// Lazy Collections cho memory efficiency
public function exportAppointments()
{
    return Appointment::with(['user:id,first_name,last_name', 'services:id,name'])
        ->cursor() // Trả về LazyCollection thay vì Collection
        ->map(function ($appointment) {
            return [
                'code' => $appointment->code,
                'customer' => $appointment->user->full_name,
                'services' => $appointment->services->pluck('name')->join(', '),
                'date' => $appointment->date->format('d/m/Y'),
                'amount' => number_format($appointment->total_amount) . ' VNĐ'
            ];
        });
}

// Generator functions cho large data processing
public function processLargeDataset()
{
    foreach ($this->getAppointmentsBatch() as $batch) {
        // Xử lý từng batch
        $this->processBatch($batch);

        // Giải phóng memory
        gc_collect_cycles();
    }
}

private function getAppointmentsBatch()
{
    $offset = 0;
    $limit = 1000;

    do {
        $appointments = Appointment::offset($offset)->limit($limit)->get();

        if ($appointments->isNotEmpty()) {
            yield $appointments;
            $offset += $limit;
        }
    } while ($appointments->count() === $limit);
}
```

### **🔄 GIẢI THÍCH XỬ LÝ ĐỒNG THỜI (CONCURRENCY) CHI TIẾT**

#### **1. Pessimistic Locking (Khóa bi quan)**
```php
// Ngăn chặn Double Booking với Database Locking
public function bookAppointment(array $data)
{
    return DB::transaction(function () use ($data) {
        // Bước 1: Khóa time slot để tránh race condition
        $timeSlot = TimeSlot::where('id', $data['time_slot_id'])
            ->lockForUpdate() // SELECT ... FOR UPDATE
            ->first();

        if (!$timeSlot) {
            throw new InvalidTimeSlotException('Time slot không tồn tại');
        }

        // Bước 2: Kiểm tra availability với lock
        $existingBooking = Appointment::where([
            'time_slot_id' => $data['time_slot_id'],
            'date' => $data['date'],
            'status' => ['confirmed', 'pending']
        ])->lockForUpdate()->first();

        if ($existingBooking) {
            throw new BookingConflictException('Time slot đã được đặt');
        }

        // Bước 3: Kiểm tra capacity
        $currentBookings = Appointment::where([
            'time_slot_id' => $data['time_slot_id'],
            'date' => $data['date']
        ])->whereIn('status', ['confirmed', 'pending'])->count();

        if ($currentBookings >= $timeSlot->max_capacity) {
            throw new CapacityExceededException('Time slot đã đầy');
        }

        // Bước 4: Tạo appointment
        $appointment = Appointment::create([
            'id' => Str::uuid(),
            'user_id' => auth()->id(),
            'time_slot_id' => $data['time_slot_id'],
            'date' => $data['date'],
            'status' => 'pending',
            'total_amount' => $this->calculateTotalAmount($data['service_ids']),
            'created_at' => now()
        ]);

        // Bước 5: Attach services
        $appointment->services()->attach($data['service_ids']);

        // Bước 6: Update statistics
        $timeSlot->increment('total_bookings');

        return $appointment;
    });
}

// Retry mechanism cho failed transactions
public function bookAppointmentWithRetry(array $data, int $maxRetries = 3)
{
    $attempt = 0;

    while ($attempt < $maxRetries) {
        try {
            return $this->bookAppointment($data);
        } catch (BookingConflictException $e) {
            $attempt++;

            if ($attempt >= $maxRetries) {
                throw $e;
            }

            // Wait before retry (exponential backoff)
            usleep(pow(2, $attempt) * 100000); // 0.1s, 0.2s, 0.4s
        }
    }
}
```

#### **2. Optimistic Locking (Khóa lạc quan)**
```php
// Version-based optimistic locking
class Appointment extends Model
{
    protected $fillable = ['version', ...];

    public function updateWithVersion(array $data)
    {
        $currentVersion = $this->version;
        $data['version'] = $currentVersion + 1;
        $data['updated_at'] = now();

        // Chỉ update nếu version chưa thay đổi
        $updated = $this->where('id', $this->id)
            ->where('version', $currentVersion)
            ->update($data);

        if (!$updated) {
            throw new OptimisticLockException(
                'Dữ liệu đã được cập nhật bởi người khác. Vui lòng tải lại trang.'
            );
        }

        return $this->fresh();
    }

    // Timestamp-based optimistic locking
    public function updateWithTimestamp(array $data, $lastUpdated)
    {
        if ($this->updated_at->timestamp !== $lastUpdated) {
            throw new OptimisticLockException(
                'Dữ liệu đã được thay đổi. Vui lòng tải lại trang.'
            );
        }

        return $this->update($data);
    }
}

// Usage trong Controller
public function update(Request $request, Appointment $appointment)
{
    try {
        $appointment->updateWithVersion($request->validated());

        return response()->json([
            'message' => 'Cập nhật thành công',
            'appointment' => $appointment->fresh()
        ]);
    } catch (OptimisticLockException $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'current_data' => $appointment->fresh()
        ], 409); // Conflict
    }
}
```

#### **3. Queue System cho Background Processing**
```php
// Job class cho heavy tasks
class SendAppointmentConfirmationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $appointment;
    public $tries = 3;
    public $timeout = 60;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function handle()
    {
        // Gửi email confirmation
        Mail::to($this->appointment->user)
            ->send(new AppointmentConfirmationMail($this->appointment));

        // Gửi SMS notification
        $this->sendSMSNotification();

        // Update statistics
        $this->updateStatistics();
    }

    public function failed(Exception $exception)
    {
        Log::error('Failed to send appointment confirmation', [
            'appointment_id' => $this->appointment->id,
            'error' => $exception->getMessage()
        ]);
    }

    private function sendSMSNotification()
    {
        // SMS sending logic
    }

    private function updateStatistics()
    {
        // Statistics update logic
    }
}

// Dispatch job sau khi tạo appointment
public function store(Request $request)
{
    $appointment = $this->bookAppointment($request->validated());

    // Dispatch background jobs
    SendAppointmentConfirmationJob::dispatch($appointment);
    UpdateDailyStatisticsJob::dispatch($appointment->date);

    return response()->json([
        'message' => 'Đặt lịch thành công',
        'appointment' => $appointment
    ]);
}

// Batch jobs cho bulk operations
public function sendMonthlyReminders()
{
    $upcomingAppointments = Appointment::where('date', today()->addDay())
        ->where('status', 'confirmed')
        ->with('user')
        ->get();

    $jobs = $upcomingAppointments->map(function ($appointment) {
        return new SendReminderJob($appointment);
    });

    // Dispatch batch
    Bus::batch($jobs)
        ->then(function (Batch $batch) {
            Log::info('Monthly reminders sent successfully');
        })
        ->catch(function (Batch $batch, Throwable $e) {
            Log::error('Failed to send monthly reminders', ['error' => $e->getMessage()]);
        })
        ->dispatch();
}
```
