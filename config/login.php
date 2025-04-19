<?php
session_start();

include 'db_connection.php'; // kết nối database
// Xử lý dữ liệu đăng nhập
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['u_name'];
    $password = $_POST['u_pass'];

    if ($username === "" || $password === "") {
        echo "
            <script>
            alert('Bạn Phải Nhập Đầy đủ Thông Tin!!');
            window.location.href = '../index.php';
            </script>";
    } else {
        // Kiểm tra thông tin đăng nhập trong cơ sở dữ liệu
        $sql_query = "SELECT * FROM userInfos WHERE userName = '$username' AND userPass = '$password'";
        $result = mysqli_query($conn, $sql_query);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result); // Lấy dữ liệu từ kết quả truy vấn
            $userRole = $row['userRole']; // Lấy vai trò của người dùng

            // Đăng nhập thành công, tạo phiên làm việc và chuyển hướng người dùng
            if ($username == "admin" && $userRole == 1) {
                $_SESSION['username'] = $username;
                header('Location: ../admin.php');
                exit(); // Kết thúc kịch bản để ngăn chặn mã tiếp tục chạy
            } else {
                $_SESSION['username'] = $username;
                header("Location: ../homepage.php"); // Chuyển hướng đến trang chính
                exit(); // Kết thúc kịch bản
            }
        } else {

            echo "<script>
                alert('Invalid username or password.');    
                window.location.href = '../index.php';
            </script>";
        }
    }
}
