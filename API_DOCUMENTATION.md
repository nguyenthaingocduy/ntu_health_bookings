# 📚 API DOCUMENTATION - HỆ THỐNG ĐẶT LỊCH BEAUTY SPA

## 🎯 TỔNG QUAN HỆ THỐNG

Hệ thống quản lý đặt lịch spa với các API RESTful phục vụ cho:
- **Frontend Web Application** (Blade templates)
- **Mobile App** (tương lai)
- **Third-party integrations**

---

## 🔗 DANH SÁCH API ENDPOINTS

### 1. 🕐 **TIME SLOT MANAGEMENT APIs**

#### **GET /api/available-time-slots**
- **Mục đích**: Lấy danh sách khung giờ khả dụng cho đặt lịch
- **Parameters**:
  - `date` (required): Ngày cần kiểm tra (YYYY-MM-DD)
  - `service_id` (optional): ID dịch vụ cụ thể
- **Response**: Danh sách khung giờ với số lượng chỗ trống
- **Use case**: Hiển thị lịch đặt cho khách hàng

#### **GET /api/time-slots/{id}**
- **Mục đích**: Lấy thông tin chi tiết một khung giờ
- **Parameters**: `id` - ID của time slot
- **Response**: Thông tin chi tiết khung giờ
- **Use case**: Xem chi tiết khung giờ trước khi đặt

#### **GET /api/check-available-slots** (Legacy)
- **Mục đích**: Kiểm tra khung giờ khả dụng (phiên bản cũ)
- **Parameters**: `date`, `service_id`
- **Response**: Danh sách khung giờ khả dụng với debug info
- **Use case**: Backward compatibility

#### **GET /api/times**
- **Mục đích**: Lấy tất cả khung giờ trong hệ thống
- **Parameters**: `date` (optional)
- **Response**: Danh sách tất cả khung giờ
- **Use case**: Quản lý khung giờ

---

### 2. 🛍️ **SERVICE MANAGEMENT APIs**

#### **GET /api/services/{id}**
- **Mục đích**: Lấy thông tin chi tiết dịch vụ
- **Parameters**: `id` - ID của service
- **Response**: Thông tin dịch vụ (tên, giá, mô tả, thời gian)
- **Use case**: Hiển thị chi tiết dịch vụ cho khách hàng

---

### 3. 🎁 **PROMOTION MANAGEMENT APIs**

#### **GET /api/active-promotions**
- **Mục đích**: Lấy danh sách khuyến mãi đang hoạt động
- **Parameters**: Không
- **Response**: Danh sách promotions với services áp dụng
- **Use case**: Hiển thị khuyến mãi cho khách hàng

#### **POST /api/validate-promotion**
- **Mục đích**: Validate mã khuyến mãi
- **Parameters**:
  - `code` (required): Mã khuyến mãi
  - `service_id` (optional): ID dịch vụ
- **Response**: Thông tin khuyến mãi và mức giảm giá
- **Use case**: Kiểm tra mã khuyến mãi khi đặt lịch

#### **GET /api/check-promotion**
- **Mục đích**: Kiểm tra khuyến mãi (GET method)
- **Parameters**: `code`, `service_id`
- **Response**: Thông tin khuyến mãi
- **Use case**: Alternative cho validate-promotion

---

### 4. 👥 **CUSTOMER MANAGEMENT APIs**

#### **GET /api/customers/search**
- **Mục đích**: Tìm kiếm khách hàng
- **Parameters**:
  - `q` (required): Từ khóa tìm kiếm
  - `page` (optional): Trang hiện tại
- **Response**: Danh sách khách hàng phù hợp
- **Use case**: Staff tìm kiếm khách hàng khi đặt lịch

---

### 5. 🔧 **TECHNICIAN AVAILABILITY APIs**

#### **GET /api/check-technician-availability**
- **Mục đích**: Kiểm tra tính khả dụng của nhân viên kỹ thuật
- **Parameters**:
  - `date` (required): Ngày kiểm tra
  - `time_slot_id` (required): ID khung giờ
  - `technician_id` (required): ID nhân viên
- **Response**: Trạng thái khả dụng của nhân viên
- **Use case**: Đặt lịch với nhân viên cụ thể

---

### 6. 👨‍⚕️ **TECHNICIAN APIs** (Authenticated)

**Base URL**: `/api/nvkt/` (Requires `auth:sanctum`)

#### **GET /api/nvkt/appointments**
- **Mục đích**: Lấy danh sách lịch hẹn của nhân viên kỹ thuật
- **Parameters**:
  - `date` (optional): Lọc theo ngày
  - `status` (optional): Lọc theo trạng thái
  - `limit` (optional): Số lượng kết quả (max 50)
- **Response**: Danh sách appointments với pagination
- **Use case**: Nhân viên xem lịch làm việc

#### **GET /api/nvkt/appointments/{id}**
- **Mục đích**: Lấy chi tiết lịch hẹn
- **Parameters**: `id` - ID appointment
- **Response**: Chi tiết appointment + lịch sử khách hàng
- **Use case**: Nhân viên xem chi tiết buổi làm việc

#### **PUT /api/nvkt/appointments/{id}/status**
- **Mục đích**: Cập nhật trạng thái lịch hẹn
- **Parameters**:
  - `status` (required): pending|confirmed|in_progress|completed|cancelled
  - `notes` (optional): Ghi chú
- **Response**: Thông tin appointment đã cập nhật
- **Use case**: Nhân viên cập nhật tiến trình làm việc

#### **POST /api/nvkt/professional-notes**
- **Mục đích**: Thêm ghi chú chuyên môn
- **Parameters**:
  - `appointment_id` (required): ID appointment
  - `notes` (required): Nội dung ghi chú
  - `recommendations` (optional): Khuyến nghị
- **Response**: Thông tin ghi chú đã tạo
- **Use case**: Nhân viên ghi chú sau buổi làm việc

---

### 7. 🔐 **AUTHENTICATION APIs**

#### **GET /api/user** (Authenticated)
- **Mục đích**: Lấy thông tin user hiện tại
- **Middleware**: `auth:sanctum`
- **Response**: Thông tin user đang đăng nhập
- **Use case**: Xác thực và lấy profile

---

## 🛡️ AUTHENTICATION & AUTHORIZATION

### **Authentication Methods**:
1. **Laravel Sanctum** - Cho mobile/SPA apps
2. **Session-based** - Cho web application

### **Rate Limiting**:
- **60 requests/minute** per user/IP
- Configured in `RouteServiceProvider`

### **Middleware Groups**:
- `api` - Rate limiting, JSON responses
- `auth:sanctum` - Requires authentication token
- `web` - Session-based authentication

---

## 📊 RESPONSE FORMATS

### **Success Response**:
```json
{
    "success": true,
    "message": "Thành công",
    "data": {...}
}
```

### **Error Response**:
```json
{
    "success": false,
    "message": "Lỗi mô tả",
    "errors": {...},
    "error_type": "ExceptionClass",
    "error_line": 123
}
```

### **Validation Error**:
```json
{
    "success": false,
    "message": "Dữ liệu không hợp lệ",
    "errors": {
        "field": ["Error message"]
    }
}
```

---

## 🔧 TECHNICAL IMPLEMENTATION

### **Controllers Structure**:
- `App\Http\Controllers\Api\*` - API controllers
- RESTful naming conventions
- Consistent error handling
- Input validation using Laravel Requests

### **Models & Relationships**:
- Eloquent ORM với relationships
- UUID primary keys
- Soft deletes where applicable
- Caching for performance

### **Database Design**:
- Normalized structure
- Foreign key constraints
- Indexes for performance
- Migration-based schema

---

## 🎯 USE CASES & BUSINESS LOGIC

### **Booking Flow**:
1. Customer chọn service → `GET /api/services/{id}`
2. Xem khung giờ khả dụng → `GET /api/available-time-slots`
3. Áp dụng khuyến mãi → `POST /api/validate-promotion`
4. Tạo appointment (Web form submission)

### **Staff Workflow**:
1. Login → `GET /api/user`
2. Xem lịch làm việc → `GET /api/nvkt/appointments`
3. Cập nhật trạng thái → `PUT /api/nvkt/appointments/{id}/status`
4. Thêm ghi chú → `POST /api/nvkt/professional-notes`

### **Admin Operations**:
- Quản lý qua Web interface
- API chủ yếu cho data retrieval
- Real-time updates via AJAX

---

## 📈 PERFORMANCE CONSIDERATIONS

### **Caching Strategy**:
- User permissions cached (5 minutes)
- Time slots cached per date
- Service data cached
- Promotion validation cached

### **Database Optimization**:
- Eager loading relationships
- Query optimization
- Index on frequently queried fields
- Pagination for large datasets

### **Error Handling**:
- Try-catch blocks in all API methods
- Detailed logging for debugging
- User-friendly error messages
- Graceful degradation

---

## 🚀 DEPLOYMENT & MONITORING

### **Environment Configuration**:
- `.env` file for sensitive data
- Different configs for dev/staging/prod
- Database connection pooling
- Redis for caching (if configured)

### **Logging & Monitoring**:
- Laravel Log facade
- API request/response logging
- Error tracking
- Performance monitoring

### **Security Measures**:
- CSRF protection
- SQL injection prevention
- XSS protection
- Rate limiting
- Input validation
- Authentication required for sensitive operations

---

## 📝 DEVELOPMENT GUIDELINES

### **API Design Principles**:
1. **RESTful** - Follow REST conventions
2. **Consistent** - Same response format
3. **Documented** - Clear parameter descriptions
4. **Validated** - Input validation on all endpoints
5. **Secure** - Authentication where needed
6. **Performant** - Optimized queries and caching

### **Testing Strategy**:
- Unit tests for business logic
- Feature tests for API endpoints
- Integration tests for workflows
- Performance testing for high-load scenarios

### **Code Quality**:
- PSR-12 coding standards
- Type hints and return types
- Comprehensive documentation
- Code reviews before deployment

---

---

## 🎓 CÂU HỎI PHỎNG VẤN CHO GIẢNG VIÊN & NHÀ TUYỂN DỤNG

### **A. CÂU HỎI VỀ KIẾN TRÚC API**

#### **1. Thiết kế API Architecture**
**Q**: Tại sao bạn chọn RESTful API thay vì GraphQL cho hệ thống này?
**A**: RESTful API phù hợp vì:
- Đơn giản, dễ implement và maintain
- Caching tốt hơn với HTTP methods
- Team quen thuộc với REST
- Không cần flexibility phức tạp của GraphQL
- Performance tốt cho use cases cụ thể

#### **2. Authentication Strategy**
**Q**: Giải thích việc sử dụng Laravel Sanctum thay vì JWT?
**A**: Laravel Sanctum được chọn vì:
- Native Laravel integration
- Hỗ trợ cả SPA và mobile apps
- Đơn giản hơn JWT setup
- Built-in token management
- CSRF protection cho SPA

#### **3. Rate Limiting**
**Q**: Tại sao set rate limit 60 requests/minute? Có hợp lý không?
**A**: 60 req/min hợp lý vì:
- Đủ cho normal user behavior
- Ngăn chặn abuse/spam
- Có thể adjust theo user type
- Balance giữa UX và security

### **B. CÂU HỎI VỀ DATABASE & PERFORMANCE**

#### **4. Database Design**
**Q**: Tại sao sử dụng UUID thay vì auto-increment ID?
**A**: UUID có lợi ích:
- Security: Không đoán được ID
- Scalability: Unique across systems
- Distributed systems friendly
- No collision khi merge data

#### **5. N+1 Query Problem**
**Q**: Làm thế nào tránh N+1 queries trong API responses?
**A**: Sử dụng:
- Eager loading với `with()`
- Lazy eager loading với `load()`
- Query optimization
- Database indexing

#### **6. Caching Strategy**
**Q**: Explain caching strategy cho time slots API?
**A**:
- Cache available slots per date
- Invalidate khi có booking mới
- Use Redis cho distributed cache
- TTL based on business logic

### **C. CÂU HỎI VỀ BUSINESS LOGIC**

#### **7. Appointment Booking Logic**
**Q**: Xử lý race condition khi 2 users đặt cùng slot?
**A**:
- Database transactions
- Optimistic locking
- Queue system cho booking
- Real-time availability check

#### **8. Promotion System**
**Q**: Làm sao validate promotion code efficiently?
**A**:
- Cache active promotions
- Validate business rules
- Check date ranges
- Prevent duplicate usage

#### **9. Time Zone Handling**
**Q**: Xử lý multiple time zones như thế nào?
**A**:
- Store UTC in database
- Convert to user timezone
- Use Carbon for date manipulation
- Consistent timezone config

### **D. CÂU HỎI VỀ SECURITY**

#### **10. API Security**
**Q**: Những biện pháp security nào được implement?
**A**:
- Authentication required
- Input validation
- SQL injection prevention
- XSS protection
- Rate limiting
- HTTPS enforcement

#### **11. Permission System**
**Q**: Giải thích role-based vs permission-based access?
**A**:
- Role-based: Group permissions
- Permission-based: Granular control
- Hybrid approach: Best of both
- Dynamic permission checking

### **E. CÂU HỎI VỀ ERROR HANDLING**

#### **12. Error Response Design**
**Q**: Tại sao standardize error response format?
**A**:
- Consistent client handling
- Better debugging
- User-friendly messages
- API documentation clarity

#### **13. Exception Handling**
**Q**: Strategy cho handling different exception types?
**A**:
- Try-catch blocks
- Custom exception classes
- Logging for debugging
- Graceful degradation

### **F. CÂU HỎI VỀ TESTING**

#### **14. API Testing Strategy**
**Q**: Làm sao test API endpoints effectively?
**A**:
- Unit tests cho business logic
- Feature tests cho endpoints
- Integration tests cho workflows
- Mock external dependencies

#### **15. Test Data Management**
**Q**: Quản lý test data như thế nào?
**A**:
- Database factories
- Seeders cho consistent data
- Separate test database
- Clean up after tests

### **G. CÂU HỎI VỀ SCALABILITY**

#### **16. Horizontal Scaling**
**Q**: API có thể scale horizontally không?
**A**:
- Stateless design
- Database connection pooling
- Load balancer ready
- Session storage externalized

#### **17. Performance Optimization**
**Q**: Optimize API performance như thế nào?
**A**:
- Database indexing
- Query optimization
- Caching layers
- Response compression
- Pagination for large datasets

### **H. CÂU HỎI VỀ MONITORING**

#### **18. API Monitoring**
**Q**: Monitor API health và performance?
**A**:
- Response time tracking
- Error rate monitoring
- Request volume analysis
- Database query performance

#### **19. Logging Strategy**
**Q**: Log gì và ở mức độ nào?
**A**:
- Request/response logging
- Error logging với stack trace
- Performance metrics
- Security events

### **I. CÂU HỎI VỀ DOCUMENTATION**

#### **20. API Documentation**
**Q**: Maintain API documentation như thế nào?
**A**:
- Code comments
- Postman collections
- OpenAPI/Swagger specs
- Example requests/responses

### **J. CÂU HỎI NÂNG CAO**

#### **21. Microservices Migration**
**Q**: Nếu migrate sang microservices architecture?
**A**:
- Service boundaries definition
- Data consistency strategies
- Inter-service communication
- Deployment complexity

#### **22. Real-time Features**
**Q**: Implement real-time notifications?
**A**:
- WebSocket connections
- Server-sent events
- Push notifications
- Event-driven architecture

#### **23. API Versioning**
**Q**: Strategy cho API versioning?
**A**:
- URL versioning (/api/v1/)
- Header versioning
- Backward compatibility
- Deprecation strategy

#### **24. Third-party Integration**
**Q**: Integrate với external APIs?
**A**:
- HTTP client abstraction
- Error handling
- Rate limiting
- Webhook handling

#### **25. Data Migration**
**Q**: Migrate data giữa API versions?
**A**:
- Database migrations
- Data transformation
- Rollback strategies
- Zero-downtime deployment

---

## 🎯 TIPS CHO PHỎNG VẤN

### **Cho Ứng Viên**:
1. **Hiểu rõ business logic** - Không chỉ technical
2. **Prepare examples** - Có thể demo code
3. **Explain trade-offs** - Tại sao chọn solution này
4. **Show problem-solving** - Cách debug issues
5. **Discuss improvements** - Gì có thể làm tốt hơn

### **Cho Interviewer**:
1. **Test practical knowledge** - Không chỉ theory
2. **Ask about real scenarios** - Race conditions, errors
3. **Evaluate architecture thinking** - Scalability, maintainability
4. **Check debugging skills** - Cách tìm và fix bugs
5. **Assess communication** - Explain technical concepts clearly

---

*📅 Last Updated: December 2024*
*🔄 Version: 1.0*
*👨‍💻 Maintained by: Development Team*
