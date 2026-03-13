<?php
// Thông tin kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "orcl";
$database = "bookstore";

$conn = mysqli_init();

if (!$conn) {
    die("Không thể khởi tạo kết nối cơ sở dữ liệu.");
}

mysqli_options($conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);

if (!mysqli_real_connect($conn, $servername, $username, $password, $database)) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

if (!mysqli_set_charset($conn, 'utf8mb4')) {
    die("Không thể thiết lập charset utf8mb4.");
}

require_once __DIR__ . '/auto_seed.php';
run_auto_seed($conn);
