<?php
// Khởi động phiên làm việc
session_start();

// Xóa tất cả các biến trong phiên làm việc
session_unset();

// Hủy bỏ toàn bộ phiên làm việc
session_destroy();

// Chuyển hướng người dùng đến trang đăng nhập hoặc trang chính
header("Location: ../index.php"); // Thay đổi đường dẫn tùy theo cài đặt của bạn
exit(); // Kết thúc kịch bản sau khi chuyển hướng
