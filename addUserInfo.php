<?php
session_start();
include './config/db_connection.php';

if (!isset($_SESSION['username']) || $_SESSION['username'] === '' || $_SESSION['username'] === 'admin') {
    header('Location: personalPage.php');
    exit();
}

if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: personalPage.php');
    exit();
}

function aui_find_first_table(mysqli $conn, array $candidateTables): ?string
{
    foreach ($candidateTables as $table) {
        $escaped = $conn->real_escape_string($table);
        $result = $conn->query("SHOW TABLES LIKE '{$escaped}'");
        if ($result && $result->num_rows > 0) {
            return $table;
        }
    }

    return null;
}

function aui_find_first_column(mysqli $conn, string $table, array $candidateColumns): ?string
{
    $escapedTable = $conn->real_escape_string($table);
    $result = $conn->query("SHOW COLUMNS FROM `{$escapedTable}`");
    if (!$result) {
        return null;
    }

    $columns = [];
    while ($row = $result->fetch_assoc()) {
        $columns[strtolower($row['Field'])] = $row['Field'];
    }

    foreach ($candidateColumns as $column) {
        $key = strtolower($column);
        if (isset($columns[$key])) {
            return $columns[$key];
        }
    }

    return null;
}

$uname = $_SESSION['username'];
$email = trim($_POST['Email'] ?? '');
$fullName = trim($_POST['fullname'] ?? '');
$address = trim($_POST['add'] ?? '');
$phone = trim($_POST['phone'] ?? '');

$legacyTable = aui_find_first_table($conn, ['userinfos']);
$newTable = aui_find_first_table($conn, ['USERS', 'users']);

$updated = false;

if ($legacyTable) {
    $userNameCol = aui_find_first_column($conn, $legacyTable, ['userName']);
    $emailCol = aui_find_first_column($conn, $legacyTable, ['email']);
    $fullNameCol = aui_find_first_column($conn, $legacyTable, ['fullName']);
    $addressCol = aui_find_first_column($conn, $legacyTable, ['address']);
    $phoneCol = aui_find_first_column($conn, $legacyTable, ['phone']);

    if ($userNameCol && $emailCol && $addressCol && $phoneCol) {
        if ($fullNameCol) {
            $sql = "UPDATE `{$legacyTable}` SET `{$emailCol}` = ?, `{$fullNameCol}` = ?, `{$addressCol}` = ?, `{$phoneCol}` = ? WHERE `{$userNameCol}` = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param('sssss', $email, $fullName, $address, $phone, $uname);
                $updated = $stmt->execute();
                $stmt->close();
            }
        } else {
            $sql = "UPDATE `{$legacyTable}` SET `{$emailCol}` = ?, `{$addressCol}` = ?, `{$phoneCol}` = ? WHERE `{$userNameCol}` = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param('ssss', $email, $address, $phone, $uname);
                $updated = $stmt->execute();
                $stmt->close();
            }
        }
    }
} elseif ($newTable) {
    $userNameCol = aui_find_first_column($conn, $newTable, ['USER_Name', 'userName']);
    $emailCol = aui_find_first_column($conn, $newTable, ['USER_Email', 'email']);
    $addressCol = aui_find_first_column($conn, $newTable, ['USER_Address', 'address']);
    $phoneCol = aui_find_first_column($conn, $newTable, ['USER_Phone', 'phone']);

    if ($userNameCol && $emailCol && $addressCol && $phoneCol) {
        $sql = "UPDATE `{$newTable}` SET `{$emailCol}` = ?, `{$addressCol}` = ?, `{$phoneCol}` = ? WHERE `{$userNameCol}` = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('ssss', $email, $address, $phone, $uname);
            $updated = $stmt->execute();
            $stmt->close();
        }
    }
}

if ($updated) {
    echo "<script>alert('Cập nhật thông tin thành công!'); window.location.href = 'personalPage.php';</script>";
} else {
    echo "<script>alert('Không thể cập nhật thông tin. Vui lòng thử lại!'); window.location.href = 'personalPage.php';</script>";
}
