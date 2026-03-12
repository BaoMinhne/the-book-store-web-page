<?php
session_start();
include './config/db_connection.php';

if (!function_exists('pay_find_first_table')) {
    function pay_find_first_table(mysqli $conn, array $candidates): ?string
    {
        foreach ($candidates as $table) {
            $escaped = $conn->real_escape_string($table);
            $check = $conn->query("SHOW TABLES LIKE '{$escaped}'");
            if ($check && $check->num_rows > 0) {
                return $table;
            }
        }
        return null;
    }
}
if (!function_exists('pay_find_first_column')) {
    function pay_find_first_column(mysqli $conn, string $table, array $candidates): ?string
    {
        foreach ($candidates as $column) {
            $escapedTable = str_replace('`', '``', $table);
            $escapedColumn = $conn->real_escape_string($column);
            $check = $conn->query("SHOW COLUMNS FROM `{$escapedTable}` LIKE '{$escapedColumn}'");
            if ($check && $check->num_rows > 0) {
                return $column;
            }
        }
        return null;
    }
}

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawOrderIDs = $_POST['orderIDs'] ?? '';
    $orderIDs = array_values(array_filter(array_map('intval', explode(',', $rawOrderIDs)), static fn($id) => $id > 0));

    if (empty($orderIDs)) {
        echo "<script>alert('Không có đơn hàng hợp lệ để thanh toán'); window.location.href = 'cart.php';</script>";
        exit();
    }

    $ordersTable = pay_find_first_table($conn, ['orders', 'ORDERS']);
    if (!$ordersTable) {
        echo "<script>alert('Không tìm thấy bảng đơn hàng'); window.location.href = 'cart.php';</script>";
        exit();
    }

    $orderIdCol = pay_find_first_column($conn, $ordersTable, ['orderID', 'OR_ID']);
    $statusCol = pay_find_first_column($conn, $ordersTable, ['status', 'OR_Status']);
    if (!$orderIdCol || !$statusCol) {
        echo "<script>alert('Thiếu cột đơn hàng'); window.location.href = 'cart.php';</script>";
        exit();
    }

    $sql_update = $conn->prepare("UPDATE `{$ordersTable}` SET `{$statusCol}` = 'đã thanh toán' WHERE `{$orderIdCol}` = ?");
    if (!$sql_update) {
        echo "<script>alert('Lỗi chuẩn bị thanh toán'); window.location.href = 'cart.php';</script>";
        exit();
    }

    foreach ($orderIDs as $oID) {
        $sql_update->bind_param('i', $oID);
        if (!$sql_update->execute()) {
            echo "<script>alert('Lỗi cập nhật đơn hàng #{$oID}'); window.location.href = 'cart.php';</script>";
            exit();
        }
    }

    echo "<script>alert('Bạn đã thanh toán thành công'); window.location.href = 'cart.php';</script>";
    exit();
}
