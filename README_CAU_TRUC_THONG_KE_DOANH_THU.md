# Cấu trúc Hệ thống Thống kê Doanh thu - Beauty Salon

## Tổng quan Kiến trúc

Hệ thống thống kê doanh thu được xây dựng theo mô hình MVC (Model-View-Controller) của Laravel, tích hợp với Chart.js để hiển thị biểu đồ trực quan.

```
┌─────────────────────────────────────────────────────────────┐
│                    FRONTEND (View Layer)                    │
├─────────────────────────────────────────────────────────────┤
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐  │
│  │   HTML/CSS  │  │ JavaScript  │  │     Chart.js        │  │
│  │ (Tailwind)  │  │   (Vanilla) │  │   (Biểu đồ)        │  │
│  └─────────────┘  └─────────────┘  └─────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                 BACKEND (Controller Layer)                  │
├─────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────────────┐ │
│  │         RevenueController.php                           │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────────┐   │ │
│  │  │   index()   │ │  export()   │ │ Private Methods │   │ │
│  │  └─────────────┘ └─────────────┘ └─────────────────┘   │ │
│  └─────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                   DATA LAYER (Models)                       │
├─────────────────────────────────────────────────────────────┤
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐  │
│  │ Appointment │  │   Invoice   │  │      Service        │  │
│  │    Model    │  │    Model    │  │      Model          │  │
│  └─────────────┘  └─────────────┘  └─────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                     DATABASE LAYER                          │
├─────────────────────────────────────────────────────────────┤
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐  │
│  │appointments │  │  invoices   │  │     services        │  │
│  │   table     │  │   table     │  │      table          │  │
│  └─────────────┘  └─────────────┘  └─────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

## Cấu trúc File và Thư mục

```
beauty_salon_project/
├── app/
│   └── Http/
│       └── Controllers/
│           └── Admin/
│               └── RevenueController.php          # Controller chính
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── admin.blade.php                    # Layout admin (sidebar)
│       └── admin/
│           └── revenue/
│               └── index.blade.php                # View thống kê doanh thu
├── routes/
│   └── admin.php                                  # Routes admin
├── public/
│   └── assets/                                    # Static assets (nếu có)
└── README_BIEU_DO_THONG_KE_DOANH_THU.md          # Tài liệu chi tiết
```

## Flow xử lý dữ liệu

### 1. Request Flow
```
User Request → Route → Middleware → Controller → Model → Database
     ↓
Response ← View ← Controller ← Model ← Database
```

### 2. Chi tiết từng bước

#### Bước 1: User Request
```
GET /admin/revenue?start_date=2024-01-01&end_date=2024-01-31
```

#### Bước 2: Route Resolution
```php
// routes/admin.php
Route::get('/revenue', [RevenueController::class, 'index'])->name('revenue.index');
```

#### Bước 3: Middleware Check
```php
// Kiểm tra authentication và admin permission
['auth', \App\Http\Middleware\AdminMiddleware::class]
```

#### Bước 4: Controller Processing
```php
// app/Http/Controllers/Admin/RevenueController.php
public function index(Request $request)
{
    // 1. Validate và parse request parameters
    $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
    $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

    // 2. Gọi các method private để lấy dữ liệu
    $revenueOverview = $this->getRevenueOverview();
    $periodRevenue = $this->getPeriodRevenue($startDate, $endDate);
    $dailyRevenue = $this->getDailyRevenue(30);
    $serviceRevenue = $this->getServiceRevenue($startDate, $endDate);
    $employeeRevenue = $this->getEmployeeRevenue($startDate, $endDate);
    $monthlyRevenue = $this->getMonthlyRevenue(12);

    // 3. Return view với data
    return view('admin.revenue.index', compact(...));
}
```

#### Bước 5: Data Processing Methods

##### getRevenueOverview()
```php
private function getRevenueOverview()
{
    return [
        'today' => $this->getRevenueByPeriod(Carbon::today(), Carbon::today()->endOfDay()),
        'this_week' => $this->getRevenueByPeriod(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()),
        'this_month' => $this->getRevenueByPeriod(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()),
        'this_year' => $this->getRevenueByPeriod(Carbon::now()->startOfYear(), Carbon::now()->endOfYear()),
    ];
}
```

##### getRevenueByPeriod()
```php
private function getRevenueByPeriod($startDate, $endDate)
{
    // Query appointments
    $appointmentRevenue = Appointment::where('status', 'completed')
        ->whereBetween('date_appointments', [$startDate, $endDate])
        ->sum('final_price');

    // Query invoices  
    $invoiceRevenue = Invoice::where('payment_status', 'paid')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->sum('total');

    return $appointmentRevenue + $invoiceRevenue;
}
```

#### Bước 6: View Rendering
```php
// resources/views/admin/revenue/index.blade.php
@extends('layouts.admin')

// Hiển thị dữ liệu trong HTML
// Truyền dữ liệu vào JavaScript cho Chart.js
<script>
const dailyData = @json($dailyRevenue);
// ... Chart.js code
</script>
```

## Data Models và Relationships

### 1. Appointment Model
```php
class Appointment extends Model
{
    // Relationships
    public function user() { return $this->belongsTo(User::class, 'customer_id'); }
    public function service() { return $this->belongsTo(Service::class); }
    public function employee() { return $this->belongsTo(Employee::class); }
    
    // Scopes for revenue calculation
    public function scopeCompleted($query) {
        return $query->where('status', 'completed');
    }
    
    public function scopeInPeriod($query, $startDate, $endDate) {
        return $query->whereBetween('date_appointments', [$startDate, $endDate]);
    }
}
```

### 2. Invoice Model
```php
class Invoice extends Model
{
    // Relationships
    public function user() { return $this->belongsTo(User::class); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
    
    // Scopes
    public function scopePaid($query) {
        return $query->where('payment_status', 'paid');
    }
}
```

## Security và Performance

### 1. Security Measures
```php
// Middleware protection
Route::middleware(['auth', AdminMiddleware::class])

// Input validation
$request->validate([
    'start_date' => 'nullable|date',
    'end_date' => 'nullable|date|after_or_equal:start_date'
]);

// SQL Injection prevention (Eloquent ORM)
// XSS prevention (Blade templating)
```

### 2. Performance Optimizations
```php
// Database indexing
Schema::table('appointments', function (Blueprint $table) {
    $table->index(['status', 'date_appointments']);
    $table->index(['employee_id', 'status']);
    $table->index(['service_id', 'status']);
});

// Query optimization
Appointment::with(['service', 'employee'])  // Eager loading
    ->select(['id', 'service_id', 'employee_id', 'final_price', 'date_appointments'])
    ->where('status', 'completed')
    ->whereBetween('date_appointments', [$startDate, $endDate]);

// Caching (future implementation)
Cache::remember('revenue_overview', 3600, function() {
    return $this->getRevenueOverview();
});
```

## API Endpoints

### 1. Main Revenue Page
```
GET /admin/revenue
Parameters:
- start_date (optional): YYYY-MM-DD
- end_date (optional): YYYY-MM-DD
Response: HTML page with charts
```

### 2. Export Revenue Data
```
GET /admin/revenue/export
Parameters:
- start_date (optional): YYYY-MM-DD  
- end_date (optional): YYYY-MM-DD
Response: CSV file download
```

## Error Handling

### 1. Controller Level
```php
try {
    $revenueData = $this->getRevenueOverview();
} catch (\Exception $e) {
    Log::error('Revenue calculation failed: ' . $e->getMessage());
    return back()->with('error', 'Không thể tải dữ liệu thống kê');
}
```

### 2. JavaScript Level
```javascript
try {
    const dailyData = @json($dailyRevenue);
    if (!dailyData || dailyData.length === 0) {
        console.warn('No daily revenue data available');
        return;
    }
    // Create chart...
} catch (error) {
    console.error('Chart creation failed:', error);
    document.getElementById('dailyRevenueChart').innerHTML = 
        '<p class="text-center text-gray-500">Không thể tải biểu đồ</p>';
}
```

## Testing Strategy

### 1. Unit Tests
```php
// tests/Unit/RevenueControllerTest.php
public function test_revenue_calculation_is_accurate()
{
    // Create test data
    $appointment = Appointment::factory()->create([
        'status' => 'completed',
        'final_price' => 100000,
        'date_appointments' => Carbon::today()
    ]);
    
    // Test calculation
    $controller = new RevenueController();
    $revenue = $controller->getRevenueByPeriod(Carbon::today(), Carbon::today());
    
    $this->assertEquals(100000, $revenue);
}
```

### 2. Feature Tests
```php
// tests/Feature/RevenuePageTest.php
public function test_admin_can_access_revenue_page()
{
    $admin = User::factory()->admin()->create();
    
    $response = $this->actingAs($admin)->get('/admin/revenue');
    
    $response->assertStatus(200);
    $response->assertViewIs('admin.revenue.index');
}
```

## Deployment Considerations

### 1. Environment Variables
```env
# .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=beauty_salon
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=redis  # For production caching
```

### 2. Production Optimizations
```php
// config/app.php
'debug' => env('APP_DEBUG', false),

// Optimize Composer autoloader
composer install --optimize-autoloader --no-dev

// Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

**Lưu ý quan trọng:**
- Hệ thống được thiết kế để dễ mở rộng và bảo trì
- Tất cả dữ liệu được validate và sanitize
- Performance được tối ưu hóa cho production
- Code tuân thủ PSR standards và Laravel best practices
