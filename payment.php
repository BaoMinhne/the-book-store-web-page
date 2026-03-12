<?php
session_start();
include './config/db_connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawOrderIDs = $_POST['orderIDs'] ?? '';
    $orderIDs = array_filter(array_map('intval', explode(',', $rawOrderIDs)), function ($id) {
        return $id > 0;
    });

    if (empty($orderIDs)) {
        echo "<script>alert('Không có đơn hàng hợp lệ để thanh toán'); window.location.href = 'cart.php';</script>";
        exit();
    }

    $sql_update = $conn->prepare("UPDATE orders SET status = 'đã thanh toán' WHERE orderID = ?");

    if (!$sql_update) {
        echo "Error preparing payment query.";
        exit();
    }

    foreach ($orderIDs as $oID) {
        $sql_update->bind_param('i', $oID);
        $updated = $sql_update->execute();

        if (!$updated) {
            echo "Error updating status for order with ID: $oID";
            exit();
        }
    }

    echo "<script>
        alert('Bạn đã thanh toán thành công');
        window.location.href = 'cart.php';
    </script>";
    exit();
}
