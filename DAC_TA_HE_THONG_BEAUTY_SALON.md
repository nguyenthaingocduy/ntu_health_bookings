# ĐẶC TẢ HỆ THỐNG BEAUTY SALON

## 3.3.1. Đặc tả hệ thống

### Chức năng dành cho Khách hàng

#### Chức năng Đăng ký tài khoản

Bảng 3.1. Đặc tả hệ thống của chức năng Đăng ký tài khoản

| | |
|---|---|
| Mô tả | Cho phép khách hàng đăng ký tài khoản cá nhân để sử dụng các chức năng dành riêng cho khách hàng. |
| Actor | Khách hàng |
| Tiền điều kiện | Khách hàng cần phải nhấn vào nút "Đăng ký".<br>Thiết bị của khách hàng đã được kết nối internet khi thực hiện chức năng đăng ký tài khoản.<br>Khách hàng phải trên 18 tuổi. |
| Hậu điều kiện | Khách hàng tạo tài khoản thành công.<br>Hệ thống sẽ chuyển hướng đến trang chủ sau khi đã đăng ký tài khoản thành công. |
| Đảm bảo tối thiểu | Thông tin cá nhân của khách hàng được bảo mật. |
| Đảm bảo thành công | Thông tin tài khoản mới được lưu vào trong cơ sở dữ liệu.<br>Hệ thống gửi email xác nhận đến địa chỉ email đã đăng ký. |
| Kích hoạt | Khách hàng muốn đăng ký tài khoản ở hệ thống. |
| Chuỗi sự kiện chính | 1. Hệ thống hiển thị trang chủ.<br>2. Khách hàng nhấn vào nút đăng ký để tạo tài khoản.<br>3. Hệ thống hiển thị thông tin trong trang đăng ký gồm: họ tên, địa chỉ email, số điện thoại, mật khẩu, xác nhận mật khẩu.<br>4. Khách hàng nhập các thông tin để đăng ký tài khoản.<br>5. Hệ thống kiểm tra thông tin và thông tin hợp lệ.<br>6. Sau khi đăng ký thành công, hệ thống tự động chuyển hướng qua trang chủ với tài khoản đã được đăng ký. Đồng thời hệ thống sẽ gửi liên kết để xác nhận lại email đăng nhập của khách hàng.<br>Use case chức năng "Đăng ký" dừng lại. |
| Chuỗi sự kiện ngoại lệ | 5.a. Hệ thống kiểm tra thông tin và thông tin không hợp lệ.<br>5.a.1. Hệ thống yêu cầu khách hàng nhập lại thông tin đăng ký.<br>5.a.2. Khách hàng nhập lại thông tin đăng ký.<br>5.a.3. Hệ thống kiểm tra thông tin và thông tin hợp lệ Use case tiếp tục ở bước 6. |

#### Chức năng Đặt lịch hẹn

Bảng 3.2. Đặc tả hệ thống của chức năng Đặt lịch hẹn

| | |
|---|---|
| Mô tả | Cho phép khách hàng đặt lịch hẹn sử dụng dịch vụ tại salon. |
| Actor | Khách hàng |
| Tiền điều kiện | Khách hàng đã đăng nhập vào hệ thống.<br>Khách hàng đã chọn dịch vụ muốn đặt lịch. |
| Hậu điều kiện | Lịch hẹn được tạo thành công và lưu vào hệ thống.<br>Khách hàng nhận được thông báo xác nhận đặt lịch. |
| Đảm bảo tối thiểu | Thông tin đặt lịch được lưu tạm thời. |
| Đảm bảo thành công | Lịch hẹn được lưu vào cơ sở dữ liệu.<br>Hệ thống gửi email xác nhận lịch hẹn cho khách hàng.<br>Nhân viên được thông báo về lịch hẹn mới. |
| Kích hoạt | Khách hàng muốn đặt lịch sử dụng dịch vụ. |
| Chuỗi sự kiện chính | 1. Khách hàng chọn dịch vụ muốn đặt lịch.<br>2. Hệ thống hiển thị trang đặt lịch với thông tin dịch vụ đã chọn.<br>3. Khách hàng chọn ngày, tháng, năm và khung giờ muốn đặt lịch.<br>4. Hệ thống kiểm tra và hiển thị các khung giờ còn trống trong ngày đã chọn.<br>5. Khách hàng chọn khung giờ phù hợp.<br>6. Hệ thống hiển thị thông tin đặt lịch bao gồm dịch vụ, ngày giờ, và giá tiền.<br>7. Khách hàng xác nhận đặt lịch.<br>8. Hệ thống lưu thông tin đặt lịch và hiển thị thông báo đặt lịch thành công.<br>9. Hệ thống gửi email xác nhận đặt lịch cho khách hàng.<br>Use case chức năng "Đặt lịch hẹn" dừng lại. |
| Chuỗi sự kiện ngoại lệ | 4.a. Không có khung giờ trống trong ngày đã chọn.<br>4.a.1. Hệ thống thông báo không có khung giờ trống và đề xuất chọn ngày khác.<br>4.a.2. Khách hàng chọn ngày khác.<br>4.a.3. Use case tiếp tục ở bước 4.<br><br>7.a. Khách hàng đã đặt lịch trong khung giờ đã chọn.<br>7.a.1. Hệ thống thông báo khách hàng đã có lịch hẹn trong khung giờ này.<br>7.a.2. Khách hàng chọn khung giờ khác.<br>7.a.3. Use case tiếp tục ở bước 6. |

#### Chức năng Xem lịch sử lịch hẹn

Bảng 3.3. Đặc tả hệ thống của chức năng Xem lịch sử lịch hẹn

| | |
|---|---|
| Mô tả | Cho phép khách hàng xem lịch sử các lịch hẹn đã đặt, đang chờ hoặc đã hoàn thành. |
| Actor | Khách hàng |
| Tiền điều kiện | Khách hàng đã đăng nhập vào hệ thống. |
| Hậu điều kiện | Khách hàng xem được lịch sử lịch hẹn của mình. |
| Đảm bảo tối thiểu | Hiển thị danh sách lịch hẹn cơ bản. |
| Đảm bảo thành công | Hiển thị đầy đủ thông tin lịch sử lịch hẹn bao gồm trạng thái, thời gian, dịch vụ và nhân viên phục vụ. |
| Kích hoạt | Khách hàng muốn xem lịch sử lịch hẹn của mình. |
| Chuỗi sự kiện chính | 1. Khách hàng truy cập vào trang dashboard hoặc trang quản lý lịch hẹn.<br>2. Hệ thống hiển thị danh sách lịch hẹn của khách hàng, bao gồm lịch hẹn sắp tới và lịch sử lịch hẹn.<br>3. Khách hàng có thể lọc lịch hẹn theo trạng thái (đang chờ, đã xác nhận, đã hoàn thành, đã hủy).<br>4. Khách hàng chọn xem chi tiết một lịch hẹn cụ thể.<br>5. Hệ thống hiển thị thông tin chi tiết của lịch hẹn đã chọn.<br>Use case chức năng "Xem lịch sử lịch hẹn" dừng lại. |
| Chuỗi sự kiện ngoại lệ | 2.a. Khách hàng chưa có lịch hẹn nào.<br>2.a.1. Hệ thống hiển thị thông báo khách hàng chưa có lịch hẹn nào.<br>2.a.2. Use case chức năng "Xem lịch sử lịch hẹn" dừng lại. |

#### Chức năng Quản lý thông tin cá nhân

Bảng 3.4. Đặc tả hệ thống của chức năng Quản lý thông tin cá nhân

| | |
|---|---|
| Mô tả | Cho phép khách hàng xem và cập nhật thông tin cá nhân, đổi mật khẩu. |
| Actor | Khách hàng |
| Tiền điều kiện | Khách hàng đã đăng nhập vào hệ thống. |
| Hậu điều kiện | Thông tin cá nhân của khách hàng được cập nhật. |
| Đảm bảo tối thiểu | Thông tin cá nhân hiện tại được hiển thị. |
| Đảm bảo thành công | Thông tin cá nhân được cập nhật thành công trong cơ sở dữ liệu. |
| Kích hoạt | Khách hàng muốn xem hoặc cập nhật thông tin cá nhân. |
| Chuỗi sự kiện chính | 1. Khách hàng truy cập vào trang thông tin cá nhân.<br>2. Hệ thống hiển thị thông tin cá nhân hiện tại của khách hàng.<br>3. Khách hàng chọn chức năng cập nhật thông tin.<br>4. Hệ thống hiển thị form cập nhật thông tin với dữ liệu hiện tại.<br>5. Khách hàng nhập thông tin mới và xác nhận cập nhật.<br>6. Hệ thống kiểm tra thông tin và thông tin hợp lệ.<br>7. Hệ thống cập nhật thông tin mới vào cơ sở dữ liệu và hiển thị thông báo cập nhật thành công.<br>Use case chức năng "Quản lý thông tin cá nhân" dừng lại. |
| Chuỗi sự kiện ngoại lệ | 6.a. Hệ thống kiểm tra thông tin và thông tin không hợp lệ.<br>6.a.1. Hệ thống hiển thị thông báo lỗi và yêu cầu nhập lại thông tin.<br>6.a.2. Khách hàng nhập lại thông tin.<br>6.a.3. Use case tiếp tục ở bước 6. |

#### Chức năng Đánh giá dịch vụ

Bảng 3.5. Đặc tả hệ thống của chức năng Đánh giá dịch vụ

| | |
|---|---|
| Mô tả | Cho phép khách hàng đánh giá dịch vụ sau khi sử dụng. |
| Actor | Khách hàng |
| Tiền điều kiện | Khách hàng đã đăng nhập vào hệ thống.<br>Khách hàng đã sử dụng dịch vụ và lịch hẹn có trạng thái "đã hoàn thành". |
| Hậu điều kiện | Đánh giá của khách hàng được lưu vào hệ thống. |
| Đảm bảo tối thiểu | Thông tin đánh giá được ghi nhận. |
| Đảm bảo thành công | Đánh giá được lưu vào cơ sở dữ liệu và hiển thị trong thông tin dịch vụ. |
| Kích hoạt | Khách hàng muốn đánh giá dịch vụ đã sử dụng. |
| Chuỗi sự kiện chính | 1. Khách hàng truy cập vào lịch sử lịch hẹn.<br>2. Hệ thống hiển thị danh sách lịch hẹn đã hoàn thành.<br>3. Khách hàng chọn đánh giá cho một dịch vụ đã sử dụng.<br>4. Hệ thống hiển thị form đánh giá với các tùy chọn số sao và ô nhập nhận xét.<br>5. Khách hàng nhập đánh giá và xác nhận gửi.<br>6. Hệ thống lưu đánh giá vào cơ sở dữ liệu và hiển thị thông báo đánh giá thành công.<br>Use case chức năng "Đánh giá dịch vụ" dừng lại. |
| Chuỗi sự kiện ngoại lệ | 2.a. Khách hàng chưa có lịch hẹn nào đã hoàn thành.<br>2.a.1. Hệ thống hiển thị thông báo khách hàng chưa có lịch hẹn nào đã hoàn thành.<br>2.a.2. Use case chức năng "Đánh giá dịch vụ" dừng lại.<br><br>3.a. Khách hàng đã đánh giá dịch vụ này trước đó.<br>3.a.1. Hệ thống hiển thị đánh giá đã gửi trước đó và cho phép cập nhật.<br>3.a.2. Khách hàng cập nhật đánh giá.<br>3.a.3. Use case tiếp tục ở bước 6. |

### Chức năng dành cho Lễ tân

#### Chức năng Quản lý lịch hẹn

Bảng 3.6. Đặc tả hệ thống của chức năng Quản lý lịch hẹn (Lễ tân)

| | |
|---|---|
| Mô tả | Cho phép lễ tân xem, tạo, chỉnh sửa, xác nhận hoặc hủy lịch hẹn của khách hàng. |
| Actor | Lễ tân |
| Tiền điều kiện | Lễ tân đã đăng nhập vào hệ thống với quyền lễ tân. |
| Hậu điều kiện | Thông tin lịch hẹn được cập nhật trong hệ thống. |
| Đảm bảo tối thiểu | Hiển thị danh sách lịch hẹn hiện tại. |
| Đảm bảo thành công | Thông tin lịch hẹn được cập nhật thành công trong cơ sở dữ liệu.<br>Khách hàng và nhân viên kỹ thuật được thông báo về thay đổi lịch hẹn. |
| Kích hoạt | Lễ tân muốn quản lý lịch hẹn của khách hàng. |
| Chuỗi sự kiện chính | 1. Lễ tân truy cập vào trang quản lý lịch hẹn.<br>2. Hệ thống hiển thị danh sách lịch hẹn với các tùy chọn lọc theo ngày, trạng thái.<br>3. Lễ tân có thể thực hiện các thao tác:<br>   a. Xem chi tiết lịch hẹn<br>   b. Tạo lịch hẹn mới cho khách hàng<br>   c. Chỉnh sửa thông tin lịch hẹn<br>   d. Xác nhận lịch hẹn<br>   e. Hủy lịch hẹn<br>4. Hệ thống cập nhật thông tin lịch hẹn theo thao tác của lễ tân.<br>5. Hệ thống gửi thông báo cho khách hàng và nhân viên kỹ thuật về thay đổi lịch hẹn.<br>Use case chức năng "Quản lý lịch hẹn" dừng lại. |
| Chuỗi sự kiện ngoại lệ | 3.b.1. Khi tạo lịch hẹn mới, nếu khung giờ đã đầy.<br>3.b.1.1. Hệ thống thông báo khung giờ đã đầy và đề xuất khung giờ khác.<br>3.b.1.2. Lễ tân chọn khung giờ khác.<br>3.b.1.3. Use case tiếp tục ở bước 4.<br><br>3.c.1. Khi chỉnh sửa lịch hẹn, nếu thông tin không hợp lệ.<br>3.c.1.1. Hệ thống hiển thị thông báo lỗi và yêu cầu nhập lại thông tin.<br>3.c.1.2. Lễ tân nhập lại thông tin.<br>3.c.1.3. Use case tiếp tục ở bước 4. |

#### Chức năng Quản lý khách hàng

Bảng 3.7. Đặc tả hệ thống của chức năng Quản lý khách hàng (Lễ tân)

| | |
|---|---|
| Mô tả | Cho phép lễ tân xem, tạo và cập nhật thông tin khách hàng. |
| Actor | Lễ tân |
| Tiền điều kiện | Lễ tân đã đăng nhập vào hệ thống với quyền lễ tân. |
| Hậu điều kiện | Thông tin khách hàng được cập nhật trong hệ thống. |
| Đảm bảo tối thiểu | Hiển thị danh sách khách hàng hiện tại. |
| Đảm bảo thành công | Thông tin khách hàng được cập nhật thành công trong cơ sở dữ liệu. |
| Kích hoạt | Lễ tân muốn quản lý thông tin khách hàng. |
| Chuỗi sự kiện chính | 1. Lễ tân truy cập vào trang quản lý khách hàng.<br>2. Hệ thống hiển thị danh sách khách hàng với tùy chọn tìm kiếm theo tên, email, số điện thoại.<br>3. Lễ tân có thể thực hiện các thao tác:<br>   a. Xem chi tiết thông tin khách hàng<br>   b. Tạo tài khoản mới cho khách hàng<br>   c. Cập nhật thông tin khách hàng<br>4. Hệ thống cập nhật thông tin khách hàng theo thao tác của lễ tân.<br>Use case chức năng "Quản lý khách hàng" dừng lại. |
| Chuỗi sự kiện ngoại lệ | 3.b.1. Khi tạo tài khoản mới, nếu email đã tồn tại trong hệ thống.<br>3.b.1.1. Hệ thống thông báo email đã tồn tại và yêu cầu sử dụng email khác.<br>3.b.1.2. Lễ tân nhập email khác.<br>3.b.1.3. Use case tiếp tục ở bước 4.<br><br>3.c.1. Khi cập nhật thông tin, nếu thông tin không hợp lệ.<br>3.c.1.1. Hệ thống hiển thị thông báo lỗi và yêu cầu nhập lại thông tin.<br>3.c.1.2. Lễ tân nhập lại thông tin.<br>3.c.1.3. Use case tiếp tục ở bước 4. |

#### Chức năng Tư vấn dịch vụ

Bảng 3.8. Đặc tả hệ thống của chức năng Tư vấn dịch vụ

| | |
|---|---|
| Mô tả | Cho phép lễ tân tư vấn và giới thiệu dịch vụ phù hợp cho khách hàng. |
| Actor | Lễ tân |
| Tiền điều kiện | Lễ tân đã đăng nhập vào hệ thống với quyền lễ tân. |
| Hậu điều kiện | Thông tin tư vấn được lưu vào hệ thống. |
| Đảm bảo tối thiểu | Hiển thị danh sách dịch vụ hiện có. |
| Đảm bảo thành công | Thông tin tư vấn được lưu vào cơ sở dữ liệu.<br>Khách hàng nhận được thông tin về dịch vụ được tư vấn. |
| Kích hoạt | Lễ tân muốn tư vấn dịch vụ cho khách hàng. |
| Chuỗi sự kiện chính | 1. Lễ tân truy cập vào trang tư vấn dịch vụ.<br>2. Hệ thống hiển thị form tư vấn với danh sách khách hàng và dịch vụ.<br>3. Lễ tân chọn khách hàng và dịch vụ muốn tư vấn.<br>4. Lễ tân nhập thông tin tư vấn và xác nhận.<br>5. Hệ thống lưu thông tin tư vấn vào cơ sở dữ liệu.<br>6. Hệ thống gửi thông tin tư vấn cho khách hàng qua email.<br>Use case chức năng "Tư vấn dịch vụ" dừng lại. |
| Chuỗi sự kiện ngoại lệ | 3.a. Khách hàng chưa có trong hệ thống.<br>3.a.1. Lễ tân tạo tài khoản mới cho khách hàng.<br>3.a.2. Use case tiếp tục ở bước 3. |

#### Chức năng Quản lý thanh toán

Bảng 3.9. Đặc tả hệ thống của chức năng Quản lý thanh toán

| | |
|---|---|
| Mô tả | Cho phép lễ tân tạo và quản lý hóa đơn thanh toán cho khách hàng. |
| Actor | Lễ tân |
| Tiền điều kiện | Lễ tân đã đăng nhập vào hệ thống với quyền lễ tân.<br>Khách hàng đã sử dụng dịch vụ và lịch hẹn có trạng thái "đã hoàn thành". |
| Hậu điều kiện | Hóa đơn thanh toán được tạo và lưu vào hệ thống. |
| Đảm bảo tối thiểu | Hiển thị thông tin dịch vụ đã sử dụng. |
| Đảm bảo thành công | Hóa đơn thanh toán được lưu vào cơ sở dữ liệu.<br>Khách hàng nhận được hóa đơn qua email. |
| Kích hoạt | Lễ tân muốn tạo hóa đơn thanh toán cho khách hàng. |
| Chuỗi sự kiện chính | 1. Lễ tân truy cập vào trang quản lý thanh toán.<br>2. Hệ thống hiển thị danh sách lịch hẹn đã hoàn thành chưa thanh toán.<br>3. Lễ tân chọn lịch hẹn cần tạo hóa đơn.<br>4. Hệ thống hiển thị thông tin dịch vụ, giá tiền và các khuyến mãi áp dụng.<br>5. Lễ tân xác nhận thông tin và tạo hóa đơn.<br>6. Hệ thống lưu hóa đơn vào cơ sở dữ liệu và cập nhật trạng thái thanh toán của lịch hẹn.<br>7. Lễ tân có thể in hóa đơn hoặc gửi hóa đơn qua email cho khách hàng.<br>Use case chức năng "Quản lý thanh toán" dừng lại. |
| Chuỗi sự kiện ngoại lệ | 2.a. Không có lịch hẹn nào chưa thanh toán.<br>2.a.1. Hệ thống hiển thị thông báo không có lịch hẹn nào chưa thanh toán.<br>2.a.2. Use case chức năng "Quản lý thanh toán" dừng lại. |

### Chức năng dành cho Nhân viên kỹ thuật

#### Chức năng Xem lịch làm việc

Bảng 3.10. Đặc tả hệ thống của chức năng Xem lịch làm việc

| | |
|---|---|
| Mô tả | Cho phép nhân viên kỹ thuật xem lịch hẹn được phân công. |
| Actor | Nhân viên kỹ thuật |
| Tiền điều kiện | Nhân viên kỹ thuật đã đăng nhập vào hệ thống với quyền nhân viên kỹ thuật. |
| Hậu điều kiện | Nhân viên kỹ thuật xem được lịch làm việc của mình. |
| Đảm bảo tối thiểu | Hiển thị danh sách lịch hẹn được phân công. |
| Đảm bảo thành công | Hiển thị đầy đủ thông tin lịch hẹn bao gồm thời gian, dịch vụ và thông tin khách hàng. |
| Kích hoạt | Nhân viên kỹ thuật muốn xem lịch làm việc của mình. |
| Chuỗi sự kiện chính | 1. Nhân viên kỹ thuật truy cập vào trang lịch làm việc.<br>2. Hệ thống hiển thị danh sách lịch hẹn được phân công cho nhân viên kỹ thuật, với các tùy chọn lọc theo ngày, trạng thái.<br>3. Nhân viên kỹ thuật có thể xem chi tiết từng lịch hẹn.<br>4. Hệ thống hiển thị thông tin chi tiết của lịch hẹn bao gồm thời gian, dịch vụ, thông tin khách hàng.<br>Use case chức năng "Xem lịch làm việc" dừng lại. |
| Chuỗi sự kiện ngoại lệ | 2.a. Không có lịch hẹn nào được phân công.<br>2.a.1. Hệ thống hiển thị thông báo không có lịch hẹn nào được phân công.<br>2.a.2. Use case chức năng "Xem lịch làm việc" dừng lại. |

#### Chức năng Cập nhật trạng thái dịch vụ

Bảng 3.11. Đặc tả hệ thống của chức năng Cập nhật trạng thái dịch vụ

| | |
|---|---|
| Mô tả | Cho phép nhân viên kỹ thuật cập nhật tiến độ thực hiện dịch vụ. |
| Actor | Nhân viên kỹ thuật |
| Tiền điều kiện | Nhân viên kỹ thuật đã đăng nhập vào hệ thống với quyền nhân viên kỹ thuật.<br>Có lịch hẹn được phân công cho nhân viên kỹ thuật. |
| Hậu điều kiện | Trạng thái dịch vụ được cập nhật trong hệ thống. |
| Đảm bảo tối thiểu | Hiển thị thông tin lịch hẹn hiện tại. |
| Đảm bảo thành công | Trạng thái dịch vụ được cập nhật thành công trong cơ sở dữ liệu.<br>Khách hàng và lễ tân được thông báo về thay đổi trạng thái dịch vụ. |
| Kích hoạt | Nhân viên kỹ thuật muốn cập nhật trạng thái dịch vụ. |
| Chuỗi sự kiện chính | 1. Nhân viên kỹ thuật truy cập vào trang lịch làm việc.<br>2. Hệ thống hiển thị danh sách lịch hẹn được phân công.<br>3. Nhân viên kỹ thuật chọn lịch hẹn cần cập nhật trạng thái.<br>4. Hệ thống hiển thị thông tin chi tiết của lịch hẹn và các tùy chọn trạng thái (đang thực hiện, đã hoàn thành).<br>5. Nhân viên kỹ thuật chọn trạng thái mới và xác nhận cập nhật.<br>6. Hệ thống cập nhật trạng thái dịch vụ trong cơ sở dữ liệu.<br>7. Hệ thống gửi thông báo cho khách hàng và lễ tân về thay đổi trạng thái dịch vụ.<br>Use case chức năng "Cập nhật trạng thái dịch vụ" dừng lại. |
| Chuỗi sự kiện ngoại lệ | 3.a. Không có lịch hẹn nào cần cập nhật trạng thái.<br>3.a.1. Hệ thống hiển thị thông báo không có lịch hẹn nào cần cập nhật trạng thái.<br>3.a.2. Use case chức năng "Cập nhật trạng thái dịch vụ" dừng lại. |

#### Chức năng Ghi chú chuyên môn

Bảng 3.12. Đặc tả hệ thống của chức năng Ghi chú chuyên môn

| | |
|---|---|
| Mô tả | Cho phép nhân viên kỹ thuật thêm ghi chú chuyên môn về khách hàng và dịch vụ đã thực hiện. |
| Actor | Nhân viên kỹ thuật |
| Tiền điều kiện | Nhân viên kỹ thuật đã đăng nhập vào hệ thống với quyền nhân viên kỹ thuật.<br>Có lịch hẹn đã hoàn thành được phân công cho nhân viên kỹ thuật. |
| Hậu điều kiện | Ghi chú chuyên môn được lưu vào hệ thống. |
| Đảm bảo tối thiểu | Hiển thị form nhập ghi chú. |
| Đảm bảo thành công | Ghi chú chuyên môn được lưu vào cơ sở dữ liệu và liên kết với khách hàng và lịch hẹn. |
| Kích hoạt | Nhân viên kỹ thuật muốn thêm ghi chú chuyên môn. |
| Chuỗi sự kiện chính | 1. Nhân viên kỹ thuật truy cập vào trang chi tiết lịch hẹn đã hoàn thành.<br>2. Hệ thống hiển thị thông tin chi tiết của lịch hẹn và form nhập ghi chú chuyên môn.<br>3. Nhân viên kỹ thuật nhập tiêu đề và nội dung ghi chú.<br>4. Nhân viên kỹ thuật xác nhận lưu ghi chú.<br>5. Hệ thống lưu ghi chú chuyên môn vào cơ sở dữ liệu và liên kết với khách hàng và lịch hẹn.<br>Use case chức năng "Ghi chú chuyên môn" dừng lại. |
| Chuỗi sự kiện ngoại lệ | 4.a. Nội dung ghi chú không hợp lệ (quá ngắn hoặc quá dài).<br>4.a.1. Hệ thống hiển thị thông báo lỗi và yêu cầu nhập lại nội dung ghi chú.<br>4.a.2. Nhân viên kỹ thuật nhập lại nội dung ghi chú.<br>4.a.3. Use case tiếp tục ở bước 4. |

### Chức năng dành cho Admin

#### Chức năng Quản lý nhân viên

Bảng 3.13. Đặc tả hệ thống của chức năng Quản lý nhân viên

| | |
|---|---|
| Mô tả | Cho phép admin thêm, sửa, xóa thông tin nhân viên và phân quyền cho nhân viên. |
| Actor | Admin |
| Tiền điều kiện | Admin đã đăng nhập vào hệ thống với quyền admin. |
| Hậu điều kiện | Thông tin nhân viên được cập nhật trong hệ thống. |
| Đảm bảo tối thiểu | Hiển thị danh sách nhân viên hiện tại. |
| Đảm bảo thành công | Thông tin nhân viên được cập nhật thành công trong cơ sở dữ liệu. |
| Kích hoạt | Admin muốn quản lý thông tin nhân viên. |
| Chuỗi sự kiện chính | 1. Admin truy cập vào trang quản lý nhân viên.<br>2. Hệ thống hiển thị danh sách nhân viên với các tùy chọn tìm kiếm, lọc theo vai trò.<br>3. Admin có thể thực hiện các thao tác:<br>   a. Xem chi tiết thông tin nhân viên<br>   b. Thêm nhân viên mới<br>   c. Chỉnh sửa thông tin nhân viên<br>   d. Xóa nhân viên<br>   e. Phân quyền cho nhân viên<br>4. Hệ thống cập nhật thông tin nhân viên theo thao tác của admin.<br>Use case chức năng "Quản lý nhân viên" dừng lại. |
| Chuỗi sự kiện ngoại lệ | 3.b.1. Khi thêm nhân viên mới, nếu email đã tồn tại trong hệ thống.<br>3.b.1.1. Hệ thống thông báo email đã tồn tại và yêu cầu sử dụng email khác.<br>3.b.1.2. Admin nhập email khác.<br>3.b.1.3. Use case tiếp tục ở bước 4.<br><br>3.c.1. Khi chỉnh sửa thông tin, nếu thông tin không hợp lệ.<br>3.c.1.1. Hệ thống hiển thị thông báo lỗi và yêu cầu nhập lại thông tin.<br>3.c.1.2. Admin nhập lại thông tin.<br>3.c.1.3. Use case tiếp tục ở bước 4. |

#### Chức năng Quản lý dịch vụ

Bảng 3.14. Đặc tả hệ thống của chức năng Quản lý dịch vụ

| | |
|---|---|
| Mô tả | Cho phép admin thêm, sửa, xóa thông tin dịch vụ và phân loại dịch vụ theo danh mục. |
| Actor | Admin |
| Tiền điều kiện | Admin đã đăng nhập vào hệ thống với quyền admin. |
| Hậu điều kiện | Thông tin dịch vụ được cập nhật trong hệ thống. |
| Đảm bảo tối thiểu | Hiển thị danh sách dịch vụ hiện tại. |
| Đảm bảo thành công | Thông tin dịch vụ được cập nhật thành công trong cơ sở dữ liệu. |
| Kích hoạt | Admin muốn quản lý thông tin dịch vụ. |
| Chuỗi sự kiện chính | 1. Admin truy cập vào trang quản lý dịch vụ.<br>2. Hệ thống hiển thị danh sách dịch vụ với các tùy chọn tìm kiếm, lọc theo danh mục.<br>3. Admin có thể thực hiện các thao tác:<br>   a. Xem chi tiết thông tin dịch vụ<br>   b. Thêm dịch vụ mới<br>   c. Chỉnh sửa thông tin dịch vụ<br>   d. Xóa dịch vụ<br>   e. Phân loại dịch vụ theo danh mục<br>4. Hệ thống cập nhật thông tin dịch vụ theo thao tác của admin.<br>Use case chức năng "Quản lý dịch vụ" dừng lại. |
| Chuỗi sự kiện ngoại lệ | 3.b.1. Khi thêm dịch vụ mới, nếu tên dịch vụ đã tồn tại trong hệ thống.<br>3.b.1.1. Hệ thống thông báo tên dịch vụ đã tồn tại và yêu cầu sử dụng tên khác.<br>3.b.1.2. Admin nhập tên khác.<br>3.b.1.3. Use case tiếp tục ở bước 4.<br><br>3.c.1. Khi chỉnh sửa thông tin, nếu thông tin không hợp lệ.<br>3.c.1.1. Hệ thống hiển thị thông báo lỗi và yêu cầu nhập lại thông tin.<br>3.c.1.2. Admin nhập lại thông tin.<br>3.c.1.3. Use case tiếp tục ở bước 4. |

#### Chức năng Quản lý khuyến mãi

Bảng 3.15. Đặc tả hệ thống của chức năng Quản lý khuyến mãi

| | |
|---|---|
| Mô tả | Cho phép admin tạo và quản lý các chương trình khuyến mãi. |
| Actor | Admin |
| Tiền điều kiện | Admin đã đăng nhập vào hệ thống với quyền admin. |
| Hậu điều kiện | Thông tin khuyến mãi được cập nhật trong hệ thống. |
| Đảm bảo tối thiểu | Hiển thị danh sách khuyến mãi hiện tại. |
| Đảm bảo thành công | Thông tin khuyến mãi được cập nhật thành công trong cơ sở dữ liệu. |
| Kích hoạt | Admin muốn quản lý thông tin khuyến mãi. |
| Chuỗi sự kiện chính | 1. Admin truy cập vào trang quản lý khuyến mãi.<br>2. Hệ thống hiển thị danh sách khuyến mãi với các tùy chọn lọc theo trạng thái, thời gian.<br>3. Admin có thể thực hiện các thao tác:<br>   a. Xem chi tiết thông tin khuyến mãi<br>   b. Tạo khuyến mãi mới<br>   c. Chỉnh sửa thông tin khuyến mãi<br>   d. Xóa khuyến mãi<br>   e. Kích hoạt/hủy kích hoạt khuyến mãi<br>4. Hệ thống cập nhật thông tin khuyến mãi theo thao tác của admin.<br>Use case chức năng "Quản lý khuyến mãi" dừng lại. |
| Chuỗi sự kiện ngoại lệ | 3.b.1. Khi tạo khuyến mãi mới, nếu mã khuyến mãi đã tồn tại trong hệ thống.<br>3.b.1.1. Hệ thống thông báo mã khuyến mãi đã tồn tại và yêu cầu sử dụng mã khác.<br>3.b.1.2. Admin nhập mã khác.<br>3.b.1.3. Use case tiếp tục ở bước 4.<br><br>3.c.1. Khi chỉnh sửa thông tin, nếu thông tin không hợp lệ.<br>3.c.1.1. Hệ thống hiển thị thông báo lỗi và yêu cầu nhập lại thông tin.<br>3.c.1.2. Admin nhập lại thông tin.<br>3.c.1.3. Use case tiếp tục ở bước 4. |

#### Chức năng Thống kê báo cáo

Bảng 3.16. Đặc tả hệ thống của chức năng Thống kê báo cáo

| | |
|---|---|
| Mô tả | Cho phép admin xem các báo cáo thống kê về doanh thu, lịch hẹn, khách hàng. |
| Actor | Admin |
| Tiền điều kiện | Admin đã đăng nhập vào hệ thống với quyền admin. |
| Hậu điều kiện | Admin xem được các báo cáo thống kê. |
| Đảm bảo tối thiểu | Hiển thị các báo cáo cơ bản. |
| Đảm bảo thành công | Hiển thị đầy đủ các báo cáo thống kê với dữ liệu chính xác. |
| Kích hoạt | Admin muốn xem báo cáo thống kê. |
| Chuỗi sự kiện chính | 1. Admin truy cập vào trang thống kê báo cáo.<br>2. Hệ thống hiển thị các loại báo cáo có sẵn (doanh thu, lịch hẹn, khách hàng).<br>3. Admin chọn loại báo cáo muốn xem.<br>4. Hệ thống hiển thị form lọc báo cáo theo thời gian (ngày, tuần, tháng, năm).<br>5. Admin chọn khoảng thời gian và xác nhận.<br>6. Hệ thống hiển thị báo cáo thống kê theo loại và khoảng thời gian đã chọn.<br>7. Admin có thể xuất báo cáo ra file Excel hoặc PDF.<br>Use case chức năng "Thống kê báo cáo" dừng lại. |
| Chuỗi sự kiện ngoại lệ | 6.a. Không có dữ liệu trong khoảng thời gian đã chọn.<br>6.a.1. Hệ thống hiển thị thông báo không có dữ liệu trong khoảng thời gian đã chọn.<br>6.a.2. Admin chọn khoảng thời gian khác.<br>6.a.3. Use case tiếp tục ở bước 6. |

#### Chức năng Quản lý phân quyền

Bảng 3.17. Đặc tả hệ thống của chức năng Quản lý phân quyền

| | |
|---|---|
| Mô tả | Cho phép admin phân quyền cho các tài khoản trong hệ thống. |
| Actor | Admin |
| Tiền điều kiện | Admin đã đăng nhập vào hệ thống với quyền admin. |
| Hậu điều kiện | Phân quyền được cập nhật trong hệ thống. |
| Đảm bảo tối thiểu | Hiển thị danh sách vai trò và quyền hiện tại. |
| Đảm bảo thành công | Thông tin phân quyền được cập nhật thành công trong cơ sở dữ liệu. |
| Kích hoạt | Admin muốn quản lý phân quyền. |
| Chuỗi sự kiện chính | 1. Admin truy cập vào trang quản lý phân quyền.<br>2. Hệ thống hiển thị danh sách vai trò (admin, lễ tân, nhân viên kỹ thuật, khách hàng) và các quyền tương ứng.<br>3. Admin có thể thực hiện các thao tác:<br>   a. Xem chi tiết quyền của từng vai trò<br>   b. Thêm vai trò mới<br>   c. Chỉnh sửa quyền của vai trò<br>   d. Xóa vai trò<br>4. Hệ thống cập nhật thông tin phân quyền theo thao tác của admin.<br>Use case chức năng "Quản lý phân quyền" dừng lại. |
| Chuỗi sự kiện ngoại lệ | 3.b.1. Khi thêm vai trò mới, nếu tên vai trò đã tồn tại trong hệ thống.<br>3.b.1.1. Hệ thống thông báo tên vai trò đã tồn tại và yêu cầu sử dụng tên khác.<br>3.b.1.2. Admin nhập tên khác.<br>3.b.1.3. Use case tiếp tục ở bước 4.<br><br>3.d.1. Khi xóa vai trò, nếu có người dùng đang sử dụng vai trò này.<br>3.d.1.1. Hệ thống thông báo không thể xóa vai trò vì có người dùng đang sử dụng.<br>3.d.1.2. Admin có thể chọn chuyển người dùng sang vai trò khác hoặc hủy thao tác xóa.<br>3.d.1.3. Use case tiếp tục ở bước 4. |

## Kết luận

Tài liệu đặc tả hệ thống này mô tả chi tiết các chức năng của hệ thống Beauty Salon, bao gồm các chức năng dành cho khách hàng, lễ tân, nhân viên kỹ thuật và admin. Mỗi chức năng được đặc tả theo chuẩn với các thành phần: mô tả, actor, tiền điều kiện, hậu điều kiện, đảm bảo tối thiểu, đảm bảo thành công, kích hoạt, chuỗi sự kiện chính và chuỗi sự kiện ngoại lệ.

Hệ thống Beauty Salon được thiết kế để đáp ứng nhu cầu quản lý và vận hành của một salon làm đẹp hiện đại, với các chức năng chính như:

1. **Dành cho khách hàng**: Đăng ký tài khoản, đặt lịch hẹn, xem lịch sử lịch hẹn, quản lý thông tin cá nhân, đánh giá dịch vụ.

2. **Dành cho lễ tân**: Quản lý lịch hẹn, quản lý khách hàng, tư vấn dịch vụ, quản lý thanh toán.

3. **Dành cho nhân viên kỹ thuật**: Xem lịch làm việc, cập nhật trạng thái dịch vụ, ghi chú chuyên môn.

4. **Dành cho admin**: Quản lý nhân viên, quản lý dịch vụ, quản lý khuyến mãi, thống kê báo cáo, quản lý phân quyền.

Các chức năng này được thiết kế để tương tác với nhau một cách liền mạch, đảm bảo quy trình làm việc hiệu quả và trải nghiệm người dùng tốt nhất. Hệ thống cũng đảm bảo tính bảo mật và toàn vẹn dữ liệu thông qua các cơ chế kiểm tra và xác thực phù hợp.

Tài liệu này sẽ là cơ sở để phát triển và triển khai hệ thống Beauty Salon, đồng thời cũng là tài liệu tham khảo cho người dùng và đội ngũ phát triển trong quá trình sử dụng và bảo trì hệ thống.
