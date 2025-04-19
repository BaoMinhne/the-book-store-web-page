<?php
// Thông tin kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "orcl";
$database = "bookstore";

// Tạo kết nối
$conn = mysqli_connect($servername, $username, $password, $database);
// echo "<script> console.log('Kết Nối Thành Công'); </script>";


// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
} else {
}


// Đóng kết nối
// mysqli_close($conn);
