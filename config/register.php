<?php
session_start();
include 'db_connection.php'; // kết nối database

// Xử lý dữ liệu đăng nhập
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['u_name_re'];
    $password = $_POST['u_pass_re'];
    $passcfm = $_POST['u_passcf_re'];

    if ($username === "" || $password === "" ||  $passcfm === "") {
        echo "
            <script>
            alert('Bạn Phải Nhập Đầy đủ Thông Tin!!');
            window.location.href = '../index.php';
            </script>";
    } else {
        // Sử dụng prepared statement để tránh SQL injection
        $nameCheck = $conn->prepare("SELECT * FROM userinfos WHERE userName = ?");
        $nameCheck->bind_param("s", $username);
        $nameCheck->execute();
        $checkRe = $nameCheck->get_result();

        if ($checkRe->num_rows > 0) {
            echo "
                <script>
                alert('Tài Khoản Đã Tồn Tại! Vui Lòng Thử Lại!!');
                window.location.href = '../index.php';
                showForm();
                </script>";
        } else {
            if ($password == $passcfm) {
                $registration = "p_register";
                // Kiểm tra thông tin đăng nhập trong cơ sở dữ liệu
                $sql_register = "CALL $registration('$username', '$password')";
                $result = $conn->query($sql_register);

                if ($result) {
                    $_SESSION[$username] = $username;
                    echo "<script>
                    alert('Đăng Ký Tài Khoản Thành Công!');
                    window.location.href = '../index.php';
                    </script>";
                } else {
                    echo "<script>alert('Đăng ký thất bại.');</script>";
                    header('Location: ../index.php');
                }
            } else {
                echo "<script>
                    alert('Mật Khẩu Không Hợp Lệ!');
                    window.location.href = '../index.php';
                    </script>";
            }
        }
    }
}
