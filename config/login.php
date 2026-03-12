<?php
session_start();

include 'db_connection.php'; // kết nối database

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['u_name'] ?? '');
    $password = trim($_POST['u_pass'] ?? '');

    if ($username === '' || $password === '') {
        echo "
            <script>
            alert('Bạn Phải Nhập Đầy đủ Thông Tin!!');
            window.location.href = '../index.php';
            </script>";
        exit();
    }

    $sql_query = $conn->prepare('SELECT userName, userRole, userPass FROM userInfos WHERE userName = ? LIMIT 1');

    if (!$sql_query) {
        echo "<script>
            alert('Hệ thống đang bận, vui lòng thử lại.');
            window.location.href = '../index.php';
        </script>";
        exit();
    }

    $sql_query->bind_param('s', $username);
    $sql_query->execute();
    $result = $sql_query->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if ($row['userPass'] !== $password) {
            echo "<script>
                alert('Invalid username or password.');
                window.location.href = '../index.php';
            </script>";
            exit();
        }

        $_SESSION['username'] = $row['userName'];

        if ($row['userName'] === 'admin' && (int) $row['userRole'] === 1) {
            header('Location: ../admin.php');
            exit();
        }

        header('Location: ../homepage.php');
        exit();
    }

    echo "<script>
        alert('Invalid username or password.');
        window.location.href = '../index.php';
    </script>";
    exit();
}
