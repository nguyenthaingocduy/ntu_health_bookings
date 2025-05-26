# Hướng dẫn chi tiết về Biểu đồ Thống kê Doanh thu

## Tổng quan

Hệ thống thống kê doanh thu của Beauty Salon sử dụng **Chart.js** để hiển thị các biểu đồ trực quan, giúp admin dễ dàng theo dõi và phân tích doanh thu theo thời gian.

## 1. Thư viện sử dụng

### Chart.js v4.x
- **CDN**: `https://cdn.jsdelivr.net/npm/chart.js`
- **Lý do chọn**:
  - Miễn phí và mã nguồn mở
  - Responsive tự động
  - Hỗ trợ nhiều loại biểu đồ
  - Dễ tùy chỉnh và có tài liệu phong phú
  - Hiệu suất cao với canvas rendering

## 2. Các loại biểu đồ trong hệ thống

### 2.1 Biểu đồ Doanh thu 30 ngày gần đây (Line Chart)

#### Mục đích
- Hiển thị xu hướng doanh thu hàng ngày trong 30 ngày gần đây
- Giúp admin nhận biết các ngày có doanh thu cao/thấp
- Phát hiện xu hướng tăng/giảm doanh thu

#### Cấu hình kỹ thuật
```javascript
// ID canvas: dailyRevenueChart
type: 'line'
data: {
    labels: dailyLabels,        // Mảng ngày (format: dd/mm)
    datasets: [{
        label: 'Doanh thu (VNĐ)',
        data: dailyValues,      // Mảng giá trị doanh thu
        borderColor: 'rgb(59, 130, 246)',      // Màu xanh dương
        backgroundColor: 'rgba(59, 130, 246, 0.1)', // Nền trong suốt
        tension: 0.1,           // Độ cong của đường
        fill: true              // Tô màu vùng dưới đường
    }]
}
```

#### Dữ liệu đầu vào
```php
// Từ Controller: RevenueController::getDailyRevenue()
$dailyData = [
    [
        'date' => '2024-01-01',
        'date_formatted' => '01/01',
        'revenue' => 1500000
    ],
    // ... 29 ngày khác
];
```

#### Tính năng đặc biệt
- **Tooltip tùy chỉnh**: Hiển thị số tiền định dạng Việt Nam (1.500.000 VNĐ)
- **Trục Y**: Bắt đầu từ 0, tự động scale
- **Responsive**: Tự động điều chỉnh kích thước theo container
- **Fill area**: Tô màu vùng dưới đường để dễ nhìn

### 2.2 Biểu đồ Doanh thu 12 tháng gần đây (Bar Chart)

#### Mục đích
- So sánh doanh thu giữa các tháng
- Nhận biết tháng có doanh thu cao nhất/thấp nhất
- Phân tích xu hướng theo mùa

#### Cấu hình kỹ thuật
```javascript
// ID canvas: monthlyRevenueChart
type: 'bar'
data: {
    labels: monthlyLabels,      // Mảng tháng (format: mm/yyyy)
    datasets: [{
        label: 'Doanh thu (VNĐ)',
        data: monthlyValues,    // Mảng giá trị doanh thu
        backgroundColor: 'rgba(34, 197, 94, 0.8)', // Màu xanh lá
        borderColor: 'rgb(34, 197, 94)',           // Viền xanh lá
        borderWidth: 1
    }]
}
```

#### Dữ liệu đầu vào
```php
// Từ Controller: RevenueController::getMonthlyRevenue()
$monthlyData = [
    [
        'month' => '2024-01',
        'month_formatted' => '01/2024',
        'revenue' => 45000000
    ],
    // ... 11 tháng khác
];
```

#### Tính năng đặc biệt
- **Hover effect**: Thanh sáng lên khi hover
- **Tooltip**: Hiển thị tháng và số tiền định dạng
- **Grid lines**: Đường lưới giúp đọc giá trị chính xác

## 3. Xử lý dữ liệu Backend

### 3.1 Nguồn dữ liệu
```php
// Doanh thu từ 2 nguồn chính:
1. Appointments (status = 'completed') -> final_price
2. Invoices (payment_status = 'paid') -> total
```

### 3.2 Logic tính toán
```php
private function getRevenueByPeriod($startDate, $endDate)
{
    // Doanh thu từ lịch hẹn hoàn thành
    $appointmentRevenue = Appointment::where('status', 'completed')
        ->whereBetween('date_appointments', [$startDate, $endDate])
        ->sum('final_price');

    // Doanh thu từ hóa đơn đã thanh toán
    $invoiceRevenue = Invoice::where('payment_status', 'paid')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->sum('total');

    return $appointmentRevenue + $invoiceRevenue;
}
```

### 3.3 Tối ưu hóa hiệu suất
- Sử dụng `whereBetween()` thay vì multiple `where()`
- Chỉ select các trường cần thiết
- Cache kết quả cho các truy vấn phức tạp (có thể implement sau)

## 4. Tùy chỉnh giao diện

### 4.1 Màu sắc
```javascript
// Biểu đồ ngày (Line Chart)
borderColor: 'rgb(59, 130, 246)'        // Blue-500
backgroundColor: 'rgba(59, 130, 246, 0.1)' // Blue-500 với opacity 10%

// Biểu đồ tháng (Bar Chart)
backgroundColor: 'rgba(34, 197, 94, 0.8)'  // Green-500 với opacity 80%
borderColor: 'rgb(34, 197, 94)'            // Green-500
```

### 4.2 Responsive Design
```javascript
options: {
    responsive: true,           // Tự động responsive
    maintainAspectRatio: true,  // Giữ tỷ lệ khung hình
    // ...
}
```

### 4.3 Định dạng số tiền
```javascript
// Callback function cho tooltip và trục Y
ticks: {
    callback: function(value) {
        return new Intl.NumberFormat('vi-VN').format(value) + ' VNĐ';
    }
}
```

## 5. Cách thêm biểu đồ mới

### Bước 1: Thêm canvas vào view
```html
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">Tên biểu đồ</h3>
    <canvas id="newChartId" width="400" height="200"></canvas>
</div>
```

### Bước 2: Chuẩn bị dữ liệu trong Controller
```php
private function getNewChartData()
{
    // Logic lấy dữ liệu
    return $data;
}

// Trong method index()
$newChartData = $this->getNewChartData();
return view('admin.revenue.index', compact('newChartData', ...));
```

### Bước 3: Thêm JavaScript
```javascript
// Trong script section
const newData = @json($newChartData);
const newCtx = document.getElementById('newChartId').getContext('2d');
new Chart(newCtx, {
    type: 'bar', // hoặc 'line', 'pie', 'doughnut'
    data: {
        labels: newData.map(item => item.label),
        datasets: [{
            label: 'Label',
            data: newData.map(item => item.value),
            // ... cấu hình khác
        }]
    },
    options: {
        // ... options
    }
});
```

## 6. Troubleshooting

### 6.1 Biểu đồ không hiển thị
**Nguyên nhân có thể:**
- Canvas element không tồn tại
- Dữ liệu JSON không hợp lệ
- Chart.js chưa được load

**Cách khắc phục:**
```javascript
// Kiểm tra element tồn tại
const canvas = document.getElementById('chartId');
if (!canvas) {
    console.error('Canvas element not found');
    return;
}

// Kiểm tra dữ liệu
console.log('Chart data:', chartData);

// Đảm bảo Chart.js đã load
if (typeof Chart === 'undefined') {
    console.error('Chart.js not loaded');
    return;
}
```

### 6.2 Dữ liệu không chính xác
**Kiểm tra:**
- Query SQL trong Controller
- Format dữ liệu trước khi truyền vào view
- Timezone settings

### 6.3 Hiệu suất chậm
**Tối ưu hóa:**
- Giới hạn số lượng data points
- Sử dụng pagination cho dữ liệu lớn
- Implement caching
- Lazy loading cho biểu đồ

## 7. Mở rộng tương lai

### 7.1 Biểu đồ có thể thêm
- **Pie Chart**: Phân bố doanh thu theo dịch vụ
- **Doughnut Chart**: Tỷ lệ khách hàng theo loại
- **Radar Chart**: So sánh hiệu suất nhân viên
- **Scatter Plot**: Mối quan hệ giá - số lượng

### 7.2 Tính năng nâng cao
- **Real-time updates**: WebSocket hoặc polling
- **Interactive filters**: Lọc dữ liệu trực tiếp trên biểu đồ
- **Export charts**: Xuất biểu đồ thành hình ảnh
- **Drill-down**: Click vào biểu đồ để xem chi tiết

### 7.3 Tích hợp AI/ML
- **Dự đoán doanh thu**: Sử dụng machine learning
- **Phát hiện anomaly**: Cảnh báo doanh thu bất thường
- **Recommendation**: Gợi ý tối ưu hóa doanh thu

## 8. Bảo mật và Performance

### 8.1 Bảo mật dữ liệu
- Chỉ admin mới được xem thống kê doanh thu
- Validate input parameters
- Sanitize dữ liệu trước khi hiển thị

### 8.2 Tối ưu Performance
- Sử dụng database indexing
- Implement caching cho queries phức tạp
- Lazy loading cho biểu đồ không cần thiết
- Optimize Chart.js configuration

## 9. Code Examples chi tiết

### 9.1 Ví dụ hoàn chỉnh - Biểu đồ Line Chart

#### HTML Structure
```html
<!-- Container cho biểu đồ -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">Doanh thu 30 ngày gần đây</h3>
    <canvas id="dailyRevenueChart" width="400" height="200"></canvas>
</div>
```

#### PHP Controller Code
```php
private function getDailyRevenue($days = 30)
{
    $endDate = Carbon::now();
    $startDate = $endDate->copy()->subDays($days - 1);

    $dailyData = [];
    for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
        $dayStart = $date->copy()->startOfDay();
        $dayEnd = $date->copy()->endOfDay();

        $revenue = $this->getRevenueByPeriod($dayStart, $dayEnd);

        $dailyData[] = [
            'date' => $date->format('Y-m-d'),           // 2024-01-15
            'date_formatted' => $date->format('d/m'),    // 15/01
            'revenue' => $revenue                        // 1500000
        ];
    }

    return $dailyData;
}
```

#### JavaScript Implementation
```javascript
document.addEventListener('DOMContentLoaded', function() {
    // Lấy dữ liệu từ PHP
    const dailyData = @json($dailyRevenue);

    // Chuẩn bị labels và values
    const dailyLabels = dailyData.map(item => item.date_formatted);
    const dailyValues = dailyData.map(item => item.revenue);

    // Lấy context của canvas
    const dailyCtx = document.getElementById('dailyRevenueChart').getContext('2d');

    // Tạo biểu đồ
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: dailyValues,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.1,
                fill: true,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Ngày'
                    },
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Doanh thu (VNĐ)'
                    },
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + ' VNĐ';
                        }
                    },
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1,
                    callbacks: {
                        title: function(context) {
                            return 'Ngày ' + context[0].label;
                        },
                        label: function(context) {
                            return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' VNĐ';
                        }
                    }
                }
            },
            elements: {
                line: {
                    borderWidth: 3
                }
            }
        }
    });
});
```

### 9.2 Ví dụ Bar Chart với Animation

```javascript
// Bar Chart với animation và gradient
const monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');

// Tạo gradient
const gradient = monthlyCtx.createLinearGradient(0, 0, 0, 400);
gradient.addColorStop(0, 'rgba(34, 197, 94, 0.8)');
gradient.addColorStop(1, 'rgba(34, 197, 94, 0.2)');

new Chart(monthlyCtx, {
    type: 'bar',
    data: {
        labels: monthlyLabels,
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: monthlyValues,
            backgroundColor: gradient,
            borderColor: 'rgb(34, 197, 94)',
            borderWidth: 2,
            borderRadius: 4,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        animation: {
            duration: 2000,
            easing: 'easeInOutQuart'
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('vi-VN').format(value) + ' VNĐ';
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' VNĐ';
                    }
                }
            }
        }
    }
});
```

## 10. Database Schema liên quan

### 10.1 Bảng Appointments
```sql
-- Các trường quan trọng cho thống kê
appointments:
- id (UUID)
- customer_id (UUID)
- service_id (UUID)
- employee_id (UUID)
- date_appointments (DATE)
- status (ENUM: pending, confirmed, completed, cancelled)
- final_price (DECIMAL 12,2)  -- Giá cuối cùng sau giảm giá
- discount_amount (DECIMAL 12,2)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

### 10.2 Bảng Invoices
```sql
-- Bảng hóa đơn
invoices:
- id (UUID)
- invoice_number (VARCHAR)
- user_id (UUID)
- appointment_id (UUID, nullable)
- subtotal (DECIMAL 12,2)
- tax (DECIMAL 12,2)
- discount (DECIMAL 12,2)
- total (DECIMAL 12,2)  -- Tổng tiền cuối cùng
- payment_status (ENUM: pending, paid, cancelled, refunded)
- created_at (TIMESTAMP)
```

### 10.3 Query Examples
```sql
-- Doanh thu theo ngày
SELECT
    DATE(date_appointments) as date,
    SUM(final_price) as daily_revenue
FROM appointments
WHERE status = 'completed'
    AND date_appointments BETWEEN '2024-01-01' AND '2024-01-31'
GROUP BY DATE(date_appointments)
ORDER BY date;

-- Doanh thu theo dịch vụ
SELECT
    s.name as service_name,
    COUNT(a.id) as appointment_count,
    SUM(a.final_price) as total_revenue,
    AVG(a.final_price) as avg_revenue
FROM appointments a
JOIN services s ON a.service_id = s.id
WHERE a.status = 'completed'
    AND a.date_appointments BETWEEN '2024-01-01' AND '2024-01-31'
GROUP BY s.id, s.name
ORDER BY total_revenue DESC
LIMIT 10;
```

## 11. Testing và Debugging

### 11.1 Test Cases
```javascript
// Test data validation
function testChartData() {
    const testData = [
        { date: '2024-01-01', revenue: 1000000 },
        { date: '2024-01-02', revenue: 1500000 }
    ];

    console.assert(testData.length > 0, 'Data should not be empty');
    console.assert(testData[0].revenue >= 0, 'Revenue should be non-negative');
}

// Test chart rendering
function testChartRendering() {
    const canvas = document.getElementById('dailyRevenueChart');
    console.assert(canvas !== null, 'Canvas element should exist');

    const ctx = canvas.getContext('2d');
    console.assert(ctx !== null, 'Canvas context should be available');
}
```

### 11.2 Debug Console Commands
```javascript
// Kiểm tra dữ liệu biểu đồ
console.log('Daily Revenue Data:', @json($dailyRevenue));
console.log('Monthly Revenue Data:', @json($monthlyRevenue));

// Kiểm tra Chart.js version
console.log('Chart.js Version:', Chart.version);

// Lấy instance của biểu đồ để debug
const chartInstance = Chart.getChart('dailyRevenueChart');
console.log('Chart Instance:', chartInstance);
```

## 12. Performance Monitoring

### 12.1 Metrics cần theo dõi
- **Thời gian load dữ liệu**: < 2 giây
- **Thời gian render biểu đồ**: < 1 giây
- **Memory usage**: < 50MB cho tất cả biểu đồ
- **Database query time**: < 500ms

### 12.2 Optimization Tips
```php
// Cache expensive queries
Cache::remember('daily_revenue_30_days', 3600, function() {
    return $this->getDailyRevenue(30);
});

// Use database indexing
// Index on: date_appointments, status, employee_id, service_id

// Limit data points
$maxDataPoints = 100;
if (count($dailyData) > $maxDataPoints) {
    $dailyData = array_slice($dailyData, -$maxDataPoints);
}
```

---

**Tài liệu tham khảo:**
- [Chart.js Documentation](https://www.chartjs.org/docs/)
- [Laravel Carbon Documentation](https://carbon.nesbot.com/docs/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)

**Phiên bản:** 1.0
**Cập nhật lần cuối:** {{ date('d/m/Y') }}
**Tác giả:** Beauty Salon Development Team
