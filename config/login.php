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

    $useLegacyTable = false;
    $tableCheck = $conn->query("SHOW TABLES LIKE 'userinfos'");
    if ($tableCheck && $tableCheck->num_rows > 0) {
        $useLegacyTable = true;
    }

    if ($useLegacyTable) {
        $sql_query = $conn->prepare('SELECT userName, userRole, userPass FROM userinfos WHERE userName = ? LIMIT 1');
    } else {
        $sql_query = $conn->prepare('SELECT u.USER_Name AS userName, COALESCE(ur.UR_ROLE, 0) AS userRole, u.USER_Password AS userPass FROM USERS u LEFT JOIN USER_ROLE ur ON ur.USER_ID = u.USER_ID WHERE u.USER_Name = ? LIMIT 1');
    }

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
