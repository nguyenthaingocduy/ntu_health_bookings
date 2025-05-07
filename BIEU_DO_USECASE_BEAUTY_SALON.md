# BIỂU ĐỒ USECASE HỆ THỐNG BEAUTY SALON

## Biểu đồ Usecase Tổng quát

```
+-------------------------------------------------------------------------------------------------------------------------------------+
|                                                    HỆ THỐNG BEAUTY SALON                                                             |
+-------------------------------------------------------------------------------------------------------------------------------------+
|                                                                                                                                     |
|    +-------------------+                                                                                                            |
|    |                   |                                                                                                            |
|    |     Khách hàng    |-----> Đăng ký tài khoản                                                                                    |
|    |                   |-----> Đăng nhập                                                                                            |
|    |                   |-----> Đặt lịch hẹn                                                                                         |
|    |                   |-----> Xem lịch sử lịch hẹn                                                                                 |
|    |                   |-----> Quản lý thông tin cá nhân                                                                            |
|    |                   |-----> Đánh giá dịch vụ                                                                                     |
|    |                   |-----> Xem thông tin dịch vụ                                                                                |
|    |                   |-----> Xem khuyến mãi                                                                                       |
|    +-------------------+                                                                                                            |
|                                                                                                                                     |
|                                                                                                                                     |
|    +-------------------+                                                                                                            |
|    |                   |                                                                                                            |
|    |      Lễ tân       |-----> Đăng nhập                                                                                            |
|    |                   |-----> Quản lý lịch hẹn --------> Xem lịch hẹn                                                              |
|    |                   |                        |-------> Tạo lịch hẹn                                                              |
|    |                   |                        |-------> Chỉnh sửa lịch hẹn                                                        |
|    |                   |                        |-------> Xác nhận lịch hẹn                                                         |
|    |                   |                        |-------> Hủy lịch hẹn                                                              |
|    |                   |                                                                                                            |
|    |                   |-----> Quản lý khách hàng ------> Xem thông tin khách hàng                                                  |
|    |                   |                          |-----> Tạo tài khoản khách hàng                                                  |
|    |                   |                          |-----> Cập nhật thông tin khách hàng                                             |
|    |                   |                                                                                                            |
|    |                   |-----> Tư vấn dịch vụ                                                                                       |
|    |                   |-----> Quản lý thanh toán ------> Tạo hóa đơn                                                               |
|    |                   |                           |----> Xem lịch sử thanh toán                                                    |
|    |                   |                           |----> In hóa đơn                                                                |
|    +-------------------+                                                                                                            |
|                                                                                                                                     |
|                                                                                                                                     |
|    +-------------------+                                                                                                            |
|    |                   |                                                                                                            |
|    |  Nhân viên kỹ thuật |-----> Đăng nhập                                                                                          |
|    |                   |-----> Xem lịch làm việc                                                                                    |
|    |                   |-----> Cập nhật trạng thái dịch vụ                                                                          |
|    |                   |-----> Ghi chú chuyên môn                                                                                   |
|    |                   |-----> Xem thông tin khách hàng                                                                             |
|    +-------------------+                                                                                                            |
|                                                                                                                                     |
|                                                                                                                                     |
|    +-------------------+                                                                                                            |
|    |                   |                                                                                                            |
|    |      Admin        |-----> Đăng nhập                                                                                            |
|    |                   |-----> Quản lý nhân viên --------> Xem thông tin nhân viên                                                  |
|    |                   |                         |-------> Thêm nhân viên                                                           |
|    |                   |                         |-------> Chỉnh sửa thông tin nhân viên                                            |
|    |                   |                         |-------> Xóa nhân viên                                                            |
|    |                   |                                                                                                            |
|    |                   |-----> Quản lý dịch vụ ---------> Xem thông tin dịch vụ                                                     |
|    |                   |                        |-------> Thêm dịch vụ                                                              |
|    |                   |                        |-------> Chỉnh sửa thông tin dịch vụ                                               |
|    |                   |                        |-------> Xóa dịch vụ                                                               |
|    |                   |                        |-------> Phân loại dịch vụ                                                         |
|    |                   |                                                                                                            |
|    |                   |-----> Quản lý khuyến mãi ------> Xem thông tin khuyến mãi                                                  |
|    |                   |                           |----> Tạo khuyến mãi                                                            |
|    |                   |                           |----> Chỉnh sửa thông tin khuyến mãi                                            |
|    |                   |                           |----> Xóa khuyến mãi                                                            |
|    |                   |                           |----> Kích hoạt/hủy kích hoạt khuyến mãi                                        |
|    |                   |                                                                                                            |
|    |                   |-----> Thống kê báo cáo --------> Xem báo cáo doanh thu                                                     |
|    |                   |                         |-------> Xem báo cáo lịch hẹn                                                     |
|    |                   |                         |-------> Xem báo cáo khách hàng                                                   |
|    |                   |                         |-------> Xuất báo cáo                                                             |
|    |                   |                                                                                                            |
|    |                   |-----> Quản lý phân quyền ------> Xem quyền của vai trò                                                     |
|    |                   |                           |----> Thêm vai trò                                                              |
|    |                   |                           |----> Chỉnh sửa quyền của vai trò                                               |
|    |                   |                           |----> Xóa vai trò                                                               |
|    +-------------------+                                                                                                            |
|                                                                                                                                     |
+-------------------------------------------------------------------------------------------------------------------------------------+
```

## Mô tả Biểu đồ Usecase

Biểu đồ usecase trên mô tả tổng quát các chức năng của hệ thống Beauty Salon, được phân chia theo từng đối tượng người dùng (actor):

### 1. Khách hàng
- **Đăng ký tài khoản**: Cho phép khách hàng tạo tài khoản mới trong hệ thống
- **Đăng nhập**: Xác thực người dùng để truy cập vào hệ thống
- **Đặt lịch hẹn**: Cho phép khách hàng đặt lịch sử dụng dịch vụ
- **Xem lịch sử lịch hẹn**: Xem các lịch hẹn đã đặt, đang chờ hoặc đã hoàn thành
- **Quản lý thông tin cá nhân**: Cập nhật thông tin cá nhân, đổi mật khẩu
- **Đánh giá dịch vụ**: Đánh giá sau khi sử dụng dịch vụ
- **Xem thông tin dịch vụ**: Xem chi tiết các dịch vụ có sẵn
- **Xem khuyến mãi**: Xem các chương trình khuyến mãi đang áp dụng

### 2. Lễ tân
- **Đăng nhập**: Xác thực người dùng để truy cập vào hệ thống
- **Quản lý lịch hẹn**: Bao gồm xem, tạo, chỉnh sửa, xác nhận và hủy lịch hẹn
- **Quản lý khách hàng**: Xem thông tin, tạo tài khoản và cập nhật thông tin khách hàng
- **Tư vấn dịch vụ**: Tư vấn và giới thiệu dịch vụ phù hợp cho khách hàng
- **Quản lý thanh toán**: Tạo hóa đơn, xem lịch sử thanh toán và in hóa đơn

### 3. Nhân viên kỹ thuật
- **Đăng nhập**: Xác thực người dùng để truy cập vào hệ thống
- **Xem lịch làm việc**: Xem lịch hẹn được phân công
- **Cập nhật trạng thái dịch vụ**: Cập nhật tiến độ thực hiện dịch vụ
- **Ghi chú chuyên môn**: Thêm ghi chú chuyên môn về khách hàng và dịch vụ
- **Xem thông tin khách hàng**: Xem thông tin của khách hàng được phục vụ

### 4. Admin
- **Đăng nhập**: Xác thực người dùng để truy cập vào hệ thống
- **Quản lý nhân viên**: Xem, thêm, chỉnh sửa, xóa thông tin nhân viên
- **Quản lý dịch vụ**: Xem, thêm, chỉnh sửa, xóa và phân loại dịch vụ
- **Quản lý khuyến mãi**: Xem, tạo, chỉnh sửa, xóa và kích hoạt/hủy kích hoạt khuyến mãi
- **Thống kê báo cáo**: Xem và xuất báo cáo doanh thu, lịch hẹn, khách hàng
- **Quản lý phân quyền**: Xem, thêm, chỉnh sửa, xóa vai trò và quyền

## Mối quan hệ giữa các Usecase

1. **Mối quan hệ Include**:
   - Usecase "Đặt lịch hẹn" include usecase "Xem thông tin dịch vụ"
   - Usecase "Quản lý thanh toán" include usecase "Tạo hóa đơn"
   - Usecase "Cập nhật trạng thái dịch vụ" include usecase "Xem lịch làm việc"

2. **Mối quan hệ Extend**:
   - Usecase "Tư vấn dịch vụ" extend usecase "Đặt lịch hẹn"
   - Usecase "Ghi chú chuyên môn" extend usecase "Cập nhật trạng thái dịch vụ"
   - Usecase "Đánh giá dịch vụ" extend usecase "Xem lịch sử lịch hẹn"

Biểu đồ usecase này cung cấp cái nhìn tổng quan về các chức năng của hệ thống Beauty Salon và mối quan hệ giữa các chức năng, giúp hiểu rõ hơn về phạm vi và yêu cầu của hệ thống.
