<?php
session_start();
include './config/db_connection.php';

if (!function_exists('dc_find_first_table')) {
    function dc_find_first_table(mysqli $conn, array $candidates): ?string
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
if (!function_exists('dc_find_first_column')) {
    function dc_find_first_column(mysqli $conn, string $table, array $candidates): ?string
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

if (!isset($_SESSION['username']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit();
}

$oID = intval($_POST['orderId'] ?? 0);
if ($oID <= 0) {
    header('Location: cart.php');
    exit();
}

$legacy = dc_find_first_table($conn, ['detailOrders']);
if ($legacy) {
    $stmt = $conn->prepare('DELETE FROM detailOrders WHERE orderID = ?');
    if ($stmt) {
        $stmt->bind_param('i', $oID);
        $stmt->execute();
    }
    $stmt2 = $conn->prepare('DELETE FROM orders WHERE orderID = ?');
    if ($stmt2) {
        $stmt2->bind_param('i', $oID);
        $stmt2->execute();
    }

    echo "<script>alert('Đã Xoá Sản Phẩm Khỏi Đơn Hàng'); window.location.href='cart.php';</script>";
    exit();
}

$ordersTable = dc_find_first_table($conn, ['orders', 'ORDERS']);
$detailsTable = dc_find_first_table($conn, ['detailOrders', 'DETAIL_ORDERS']);
if (!$ordersTable || !$detailsTable) {
    header('Location: cart.php');
    exit();
}

$orderIdCol = dc_find_first_column($conn, $ordersTable, ['orderID', 'OR_ID']);
$detailOrderCol = dc_find_first_column($conn, $detailsTable, ['orderID', 'OR_ID']);
if (!$orderIdCol || !$detailOrderCol) {
    header('Location: cart.php');
    exit();
}

$conn->begin_transaction();
try {
    $d = $conn->prepare("DELETE FROM `{$detailsTable}` WHERE `{$detailOrderCol}` = ?");
    $d->bind_param('i', $oID);
    $d->execute();

    $o = $conn->prepare("DELETE FROM `{$ordersTable}` WHERE `{$orderIdCol}` = ?");
    $o->bind_param('i', $oID);
    $o->execute();

    $conn->commit();
} catch (Throwable $e) {
    $conn->rollback();
}

echo "<script>alert('Đã Xoá Sản Phẩm Khỏi Đơn Hàng'); window.location.href='cart.php';</script>";
exit();
