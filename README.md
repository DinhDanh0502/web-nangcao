
# MusicManager

# Hệ Thống Quản Lý Bài Hát, Nghệ Sĩ

## Được Phát Triển Bởi:


## link repo



## Mô Tả Ứng Dụng
Music Web (MusicManager) là một ứng dụng web quản lý bài hát và nghệ sĩ được phát triển bởi Lê Đình Đức Anh. Đây là hệ thống quản lý nhạc toàn diện giúp người dùng quản lý thông tin về người dùng, nghệ sĩ, bài hát, thể loại và danh sách phát một cách hiệu quả.

## Mục Đích
- Quản lý thông tin người dùng và nghệ sĩ
- Quản lý chi tiết bài hát (tiêu đề, file nhạc, ảnh bìa, thời lượng)
- Phân loại bài hát theo thể loại và tạo danh sách phát
- Cung cấp giao diện người dùng thân thiện và dễ sử dụng
- Hiển thị dữ liệu hiệu quả thông qua DataTables
## Công nghệ sử dụng
### Backend
- Laravel Framework (phiên bản mới nhất - Laravel 11)
- PHP 8.2
- MySQL/SQLite (hỗ trợ cả hai hệ quản trị cơ sở dữ liệu)
- Laravel AdminLTE (giao diện quản trị)
- Laravel UI (xây dựng giao diện người dùng)
### Frontend
- HTML, CSS, JavaScript
- Tailwind CSS (framework CSS)
- Vite (công cụ build frontend)
- DataTables với jQuery (hiển thị và quản lý dữ liệu dạng bảng)
## Kiến trúc và mô hình
- Laravel Repository Pattern (tách biệt logic truy cập dữ liệu)
- Laravel Service Pattern (quản lý business logic)
- Laravel Events & Listeners (xử lý các tác vụ không đồng bộ)
## Tính năng chính
### Quản lý người dùng :
- Đăng ký, đăng nhập, quản lý hồ sơ
- Phân quyền người dùng (admin, người dùng thường)
### Quản lý nghệ sĩ :
- Thêm, sửa, xóa thông tin nghệ sĩ
- Quản lý bài hát của nghệ sĩ
### Quản lý bài hát :
- Upload file nhạc (hỗ trợ mp3, wav, ogg, m4a, aac)
- Upload ảnh bìa (hỗ trợ jpg, jpeg, png, gif, webp)
- Gán nghệ sĩ chính và nghệ sĩ hợp tác
- Phân loại theo thể loại
### Quản lý thể loại :
- Thêm, sửa, xóa thể loại
- Phân loại bài hát theo nhiều thể loại
### Quản lý danh sách phát :
- Tạo và quản lý danh sách phát
- Thêm/xóa bài hát vào danh sách phát
### Tính năng yêu thích :
- Người dùng có thể đánh dấu bài hát yêu thích
- Xem danh sách bài hát yêu thích trong hồ sơ

## Chu trình phát triển
### Phân tích : Xác định yêu cầu và thiết kế cơ sở dữ liệu
### Thiết kế : Áp dụng các mẫu thiết kế (Repository, Service)
### Triển khai : Viết mã theo các mẫu đã thiết kế
### Kiểm thử : Thực hiện Unit tests và Feature tests
### Triển khai : Thiết lập quy trình CI/CD

## Cấu trúc dữ liệu
### Ứng dụng sử dụng mô hình quan hệ phức tạp giữa các đối tượng:

- User : Người dùng hệ thống
- Artist : Nghệ sĩ với thông tin cá nhân và sự nghiệp
- Song : Bài hát với thông tin về file nhạc, ảnh bìa, thời lượng
- Genre : Thể loại nhạc
- Playlist : Danh sách phát do người dùng tạo
### Các mối quan hệ chính:

- Một bài hát thuộc về một nghệ sĩ chính và một thể loại chính
- Một bài hát có thể thuộc nhiều thể loại và có nhiều nghệ sĩ hợp tác
- Người dùng có thể tạo nhiều danh sách phát và đánh dấu nhiều bài hát yêu thích
Đây là một ứng dụng web âm nhạc toàn diện, được xây dựng với các công nghệ hiện đại và theo các mẫu thiết kế tốt nhất, cung cấp trải nghiệm quản lý âm nhạc hiệu quả cho cả người dùng và quản trị viên.


