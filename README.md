# BanHangOnline
Đồ án môn thương mại điện tử
Dưới đây là nội dung hoàn chỉnh bạn có thể copy và lưu vào file `README.md`:

```markdown
# Hướng Dẫn Cài Đặt & Triển Khai Ứng Dụng

Hướng dẫn này giúp bạn thiết lập môi trường cho dự án sử dụng Laragon 6.0, MySQL và WordPress, cũng như clone repository từ Git.

---

## 1. Môi Trường Sử Dụng

- **Laragon 6.0:**  
  - Tải và cài đặt từ [trang chủ Laragon](https://laragon.org/download/).
  - Sau cài đặt, khởi động Laragon và kiểm tra các dịch vụ như Apache và MySQL đang chạy.

- **MySQL:**  
  - Sử dụng MySQL đi kèm trong Laragon. Quản lý cơ sở dữ liệu qua [phpMyAdmin](http://localhost/phpmyadmin).

- **WordPress:**  
  - Sản phẩm tích hợp để triển khai và quản lý nội dung web.

- **Git:**  
  - Đảm bảo đã cài đặt Git (tải từ [Git SCM](https://git-scm.com/)). Dùng Git để clone repository về máy.

---

## 2. Clone Repository từ Git

1. Mở **Command Prompt** hoặc **Git Bash**.
2. Chạy lệnh sau để clone repository:

   ```bash
   git clone <URL-repository>
   ```

   > **Lưu ý:** Thay `<URL-repository>` bằng URL của repository cần clone. Sau khi clone, thư mục chứa mã nguồn sẽ được tạo trong thư mục hiện tại.

---

## 3. Cấu Hình Cơ Sở Dữ Liệu MySQL

1. Mở [phpMyAdmin](http://localhost/phpmyadmin) thông qua Laragon.
2. Tạo một cơ sở dữ liệu mới. Bạn có thể sử dụng giao diện của phpMyAdmin hoặc chạy câu lệnh SQL dưới đây:

   ```sql
   CREATE DATABASE ten_database;
   ```

   > **Lưu ý:** Thay `ten_database` bằng tên mà bạn mong muốn.

---

## 4. Cài Đặt WordPress

1. **Di chuyển mã nguồn WordPress:**
   - Nếu repository đã bao gồm mã nguồn WordPress, hãy di chuyển hoặc giải nén nội dung đó vào thư mục `www` của Laragon.
   
2. **Bắt đầu cài đặt WordPress:**
   - Mở trình duyệt, truy cập `http://localhost/<ten-thu-muc>` (thay `<ten-thu-muc>` bằng tên thư mục chứa mã nguồn WordPress).
   - Trong quá trình cài đặt, bạn sẽ được yêu cầu nhập thông tin cơ sở dữ liệu:
     - **Database Name:** Tên cơ sở dữ liệu bạn đã tạo.
     - **Username:** `root` (mặc định của Laragon).
     - **Password:** Để trống (mặc định của Laragon).
     
3. Làm theo các bước hướng dẫn trên màn hình để hoàn tất cài đặt.

---

## 5. Thông Tin Tài Khoản WordPress

Sau khi cài đặt xong, bạn có thể đăng nhập vào WordPress với thông tin sau:

- **Tài khoản:** `dut-vnua`
- **Mật khẩu:** `5YstEa*dOM4rB%)iZw`

> **Lưu ý bảo mật:** Đây là thông tin mặc định, bạn nên thay đổi mật khẩu để đảm bảo an toàn khi sử dụng trong môi trường thực tế.

---

## 6. Kiểm Tra & Chạy Ứng Dụng

- Sau khi cài đặt, mở trình duyệt và truy cập:
  
  ```
  http://localhost/<ten-thu-muc>
  ```

  để kiểm tra hoạt động của WordPress.

- Nếu có lỗi xảy ra, hãy kiểm tra lại file `wp-config.php` để đảm bảo thông tin kết nối cơ sở dữ liệu (Database Name, Username, Password, Host) được cấu hình chính xác.

---

Nếu bạn gặp khó khăn trong quá trình cài đặt hoặc cần hỗ trợ thêm, hãy xem lại các bước hướng dẫn hoặc liên hệ với nhóm phát triển dự án.
```

---

Nội dung này bao gồm đầy đủ các bước cài đặt môi trường, clone repository, cấu hình MySQL, cài đặt WordPress, cũng như thông tin tài khoản đăng nhập WordPress. Khi cần mở rộng hoặc chỉnh sửa thêm, bạn chỉ cần cập nhật file này theo yêu cầu dự án của mình. Nếu có thêm câu hỏi, hãy tiếp tục trao đổi!
