# Webcafe — Website quản lý và bán cà phê

## Giới thiệu

Webcafe là dự án website bán cà phê được xây dựng bằng PHP và MySQL. Dự án thực hiện các chức năng phục vụ khách hàng và quản trị viên, đồng thời giúp áp dụng kiến thức về phát triển web, cơ sở dữ liệu và quản lý mã nguồn bằng Git/GitHub.

## Công nghệ sử dụng

- **Backend:** PHP
- **Database:** MySQL / MariaDB
- **Frontend:** HTML, CSS, JavaScript
- **Môi trường phát triển:** XAMPP
- **Quản lý mã nguồn:** Git và GitHub

## Chức năng chính

### Khách hàng
- Đăng ký và đăng nhập tài khoản.
- Xem danh sách và thông tin sản phẩm.
- Quản lý giỏ hàng và thực hiện đặt hàng.
- Quản lý danh sách yêu thích.
- Xem thông tin tài khoản và đơn hàng.
- Gửi thông tin liên hệ.

### Quản trị viên
- Truy cập trang quản trị.
- Thêm, sửa và quản lý sản phẩm.
- Quản lý tài khoản.
- Xem và xử lý đơn hàng.
- Xem thông tin liên hệ hoặc tin nhắn của khách hàng.

*Lưu ý: Chỉ giữ các chức năng đã được triển khai và kiểm thử thành công.*

## Cấu trúc dự án

- `api/` — Các API xử lý yêu cầu từ website.
- `components/` — Các thành phần PHP dùng chung.
- `database/` — Tệp cơ sở dữ liệu hoặc script SQL.
- `image/`, `img/` — Tài nguyên hình ảnh.
- `dashboard.php` — Trang quản trị.
- `login.php`, `register.php` — Đăng nhập và đăng ký.
- `home.php` — Trang chủ.
- `product.php` — Trang sản phẩm.
- `cart.php`, `checkout.php` — Giỏ hàng và thanh toán.
- `orders.php`, `order.php` — Các trang liên quan đến đơn hàng.
- `style.css`, `admin_style.css`, `script.js` — Tài nguyên giao diện.

## Cài đặt và chạy dự án

### Yêu cầu

- XAMPP có Apache và MySQL/MariaDB.
- Trình duyệt web.
- Git (nếu muốn tải mã nguồn bằng Git).

### Bước 1: Tải mã nguồn

```bash
git clone https://github.com/nqviet0905/Webcafe.git
```

### Bước 2: Đưa dự án vào XAMPP

Sao chép thư mục dự án vào:

```text
C:\xampp\htdocs\webcafe
```

Đảm bảo các file PHP nằm đúng vị trí để đường dẫn chạy website phù hợp với cấu hình hiện tại.

### Bước 3: Tạo cơ sở dữ liệu

1. Khởi động Apache và MySQL trong XAMPP.
2. Mở `http://localhost/phpmyadmin`.
3. Tạo database theo tên được cấu hình trong mã nguồn.
4. Import tệp SQL trong thư mục `database/` nếu dự án có cung cấp.

### Bước 4: Cấu hình kết nối

Kiểm tra các file kết nối cơ sở dữ liệu, ví dụ `components/connection.php` hoặc `db.php`. Cập nhật tên database, username và password cho phù hợp với môi trường cục bộ.

Không sử dụng mật khẩu hoặc thông tin kết nối cá nhân trong bản mã nguồn công khai.

### Bước 5: Chạy website

Mở đường dẫn tương ứng với vị trí thư mục dự án, ví dụ:

```text
http://localhost/webcafe/
```

Nếu trang chủ không nằm tại `index.php`, hãy truy cập file trang chủ thực tế, chẳng hạn `home.php` hoặc `login.php`.

## Hướng phát triển

- Hoàn thiện kiểm tra dữ liệu đầu vào và xử lý lỗi.
- Tăng cường bảo mật xác thực và phân quyền.
- Cải thiện giao diện trên điện thoại.
- Bổ sung kiểm thử các luồng đặt hàng.
- Hoàn thiện tài liệu cài đặt và hình ảnh minh họa.

## Tác giả

GitHub: https://github.com/nqviet0905

## Giấy phép

Chưa xác định giấy phép phân phối. Vui lòng liên hệ tác giả trước khi sử dụng lại mã nguồn.

