# 🚀 API QUICK REFERENCE - BEAUTY SPA BOOKING SYSTEM

## 📋 API ENDPOINTS SUMMARY

### 🕐 **Time Management**
```
GET  /api/available-time-slots     # Lấy khung giờ khả dụng
GET  /api/time-slots/{id}          # Chi tiết khung giờ
GET  /api/times                    # Tất cả khung giờ
GET  /api/check-available-slots    # Legacy endpoint
```

### 🛍️ **Services**
```
GET  /api/services/{id}            # Chi tiết dịch vụ
```

### 🎁 **Promotions**
```
GET  /api/active-promotions        # Khuyến mãi đang hoạt động
POST /api/validate-promotion       # Validate mã khuyến mãi
GET  /api/check-promotion          # Kiểm tra khuyến mãi
```

### 👥 **Customers**
```
GET  /api/customers/search         # Tìm kiếm khách hàng
```

### 🔧 **Technician Availability**
```
GET  /api/check-technician-availability  # Kiểm tra nhân viên khả dụng
```

### 👨‍⚕️ **Technician APIs** (Auth Required)
```
GET  /api/nvkt/appointments        # Danh sách lịch hẹn
GET  /api/nvkt/appointments/{id}   # Chi tiết lịch hẹn
PUT  /api/nvkt/appointments/{id}/status  # Cập nhật trạng thái
POST /api/nvkt/professional-notes  # Thêm ghi chú chuyên môn
```

### 🔐 **Authentication**
```
GET  /api/user                     # Thông tin user hiện tại (Auth)
```

---

## 🔑 KEY FEATURES

### **Authentication**
- **Laravel Sanctum** cho API authentication
- **Session-based** cho web interface
- **Rate limiting**: 60 requests/minute

### **Response Format**
```json
{
    "success": true|false,
    "message": "Mô tả",
    "data": {...},
    "errors": {...}  // Chỉ khi có lỗi
}
```

### **Common Parameters**
- `date`: Format YYYY-MM-DD
- `limit`: Max 50 cho pagination
- `page`: Trang hiện tại
- `q`: Query string cho search

---

## 🎯 BUSINESS RULES

### **Time Slots**
- Mỗi khung giờ chỉ phục vụ 1 dịch vụ
- Khách hàng chỉ đặt được 1 dịch vụ/khung giờ
- Không thể đặt lịch trong quá khứ
- Giờ làm việc: 8:00 - 20:00 hàng ngày

### **Promotions**
- Validate theo ngày hiệu lực
- Kiểm tra áp dụng cho dịch vụ cụ thể
- Không duplicate sử dụng

### **Appointments**
- Trạng thái: pending → confirmed → in_progress → completed
- Có thể cancel ở bất kỳ trạng thái nào
- Nhân viên chỉ xem được lịch của mình

---

## 🛡️ SECURITY & PERMISSIONS

### **API Security**
- Input validation trên tất cả endpoints
- SQL injection prevention
- XSS protection
- HTTPS enforcement (production)

### **Permission System**
- Role-based permissions (Admin, Receptionist, Technician, Customer)
- Individual user permissions
- Dynamic permission checking
- Cached permissions (5 minutes TTL)

---

## 🔧 TECHNICAL STACK

### **Backend**
- **Laravel 10** - PHP Framework
- **MySQL** - Primary database
- **Redis** - Caching (optional)
- **Laravel Sanctum** - API authentication

### **Frontend Integration**
- **Blade Templates** - Server-side rendering
- **AJAX** - Dynamic content loading
- **Tailwind CSS** - Styling framework

### **Development Tools**
- **Artisan Commands** - Custom commands
- **Database Migrations** - Schema management
- **Seeders & Factories** - Test data
- **Laravel Tinker** - REPL for debugging

---

## 🚀 QUICK START

### **Setup Development Environment**
```bash
# Clone repository
git clone [repository-url]
cd beauty-spa-booking

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Start development server
php artisan serve
npm run dev
```

### **Test API Endpoints**
```bash
# Test time slots
curl "http://localhost:8000/api/available-time-slots?date=2024-12-20"

# Test service details
curl "http://localhost:8000/api/services/1"

# Test promotions
curl "http://localhost:8000/api/active-promotions"
```

### **Authentication Testing**
```bash
# Login to get token (if using Sanctum)
curl -X POST "http://localhost:8000/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'

# Use token for authenticated requests
curl "http://localhost:8000/api/nvkt/appointments" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## 🐛 DEBUGGING

### **Common Issues**
1. **403 Forbidden** → Check permissions
2. **422 Validation Error** → Check required parameters
3. **500 Server Error** → Check logs in `storage/logs/`
4. **Rate Limited** → Wait or increase limit

### **Debug Commands**
```bash
# Check permissions for user
php artisan debug:permissions user@example.com

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# View logs
tail -f storage/logs/laravel.log
```

### **Database Queries**
```bash
# Enable query logging in .env
DB_LOG_QUERIES=true

# Or use Tinker
php artisan tinker
>>> DB::enableQueryLog();
>>> // Run your code
>>> DB::getQueryLog();
```

---

## 📊 MONITORING

### **Key Metrics**
- API response times
- Error rates by endpoint
- Authentication failures
- Database query performance
- Cache hit rates

### **Log Locations**
- **Application logs**: `storage/logs/laravel.log`
- **Web server logs**: `/var/log/nginx/` or `/var/log/apache2/`
- **Database logs**: MySQL slow query log

---

## 🔄 DEPLOYMENT

### **Production Checklist**
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper database credentials
- [ ] Set up HTTPS
- [ ] Configure caching (Redis)
- [ ] Set up monitoring
- [ ] Configure backup strategy

### **Performance Optimization**
```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

---

## 📞 SUPPORT

### **Documentation**
- **Full API Docs**: `API_DOCUMENTATION.md`
- **Laravel Docs**: https://laravel.com/docs
- **Sanctum Docs**: https://laravel.com/docs/sanctum

### **Development Team**
- **Backend**: Laravel API development
- **Frontend**: Blade templates + Tailwind CSS
- **Database**: MySQL optimization
- **DevOps**: Deployment & monitoring

---

*⚡ Quick Reference - For detailed documentation see API_DOCUMENTATION.md*
