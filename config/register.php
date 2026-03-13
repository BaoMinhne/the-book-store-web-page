<?php
session_start();
include 'db_connection.php'; // kết nối database

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['u_name_re'] ?? '');
    $password = trim($_POST['u_pass_re'] ?? '');
    $passcfm = trim($_POST['u_passcf_re'] ?? '');

    if ($username === '' || $password === '' || $passcfm === '') {
        echo "
            <script>
            alert('Bạn Phải Nhập Đầy đủ Thông Tin!!');
            window.location.href = '../index.php';
            </script>";
        exit();
    }

    if ($password !== $passcfm) {
        echo "<script>
            alert('Mật Khẩu Không Hợp Lệ!');
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
        $nameCheck = $conn->prepare('SELECT userName FROM userinfos WHERE userName = ? LIMIT 1');
        $nameCheck->bind_param('s', $username);
        $nameCheck->execute();
        $checkRe = $nameCheck->get_result();
    } else {
        $nameCheck = $conn->prepare('SELECT USER_Name FROM USERS WHERE USER_Name = ? LIMIT 1');
        $nameCheck->bind_param('s', $username);
        $nameCheck->execute();
        $checkRe = $nameCheck->get_result();
    }

    if ($checkRe && $checkRe->num_rows > 0) {
        echo "
            <script>
            alert('Tài Khoản Đã Tồn Tại! Vui Lòng Thử Lại!!');
            window.location.href = '../index.php';
            </script>";
        exit();
    }

    if ($useLegacyTable) {
        $registration = $conn->prepare('INSERT INTO userinfos (userName, userPass, userRole) VALUES (?, ?, 0)');
        if (!$registration) {
            echo "<script>alert('Đăng ký thất bại.'); window.location.href = '../index.php';</script>";
            exit();
        }

        $registration->bind_param('ss', $username, $password);
        $result = $registration->execute();
    } else {
        $conn->begin_transaction();
        $result = false;

        $stmtUser = $conn->prepare('INSERT INTO USERS (USER_Name, USER_Password) VALUES (?, ?)');
        if ($stmtUser) {
            $stmtUser->bind_param('ss', $username, $password);
            if ($stmtUser->execute()) {
                $newUserId = (int) $conn->insert_id;
                $stmtRole = $conn->prepare('INSERT INTO USER_ROLE (USER_ID, UR_ROLE) VALUES (?, 0)');
                if ($stmtRole) {
                    $stmtRole->bind_param('i', $newUserId);
                    $result = $stmtRole->execute();
                }
            }
        }

        if ($result) {
            $conn->commit();
        } else {
            $conn->rollback();
        }
    }

    if ($result) {
        $_SESSION['username'] = $username;
        echo "<script>
            alert('Đăng Ký Tài Khoản Thành Công!');
            window.location.href = '../index.php';
            </script>";
        exit();
    }

    echo "<script>alert('Đăng ký thất bại.'); window.location.href = '../index.php';</script>";
    exit();
}
