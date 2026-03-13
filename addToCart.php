<?php
session_start();
include './config/db_connection.php';

if (!function_exists('atc_find_first_table')) {
    function atc_find_first_table(mysqli $conn, array $candidates): ?string
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

if (!function_exists('atc_find_first_column')) {
    function atc_find_first_column(mysqli $conn, string $table, array $candidates): ?string
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

$bID = intval($_POST['productId'] ?? 0);

if (!isset($_SESSION['username'])) {
    echo "<script>
        alert('Vui Lòng Đăng Nhập Trước.');
        var ID = $bID;
        window.location.href = 'detailProduct.php?id=' + ID;
    </script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['productId'], $_POST['quantity'])) {
    echo "<script>alert('Dữ liệu không hợp lệ.'); window.location.href='homepage.php';</script>";
    exit();
}

$uname = $_SESSION['username'];
$bID = intval($_POST['productId']);
$amount = max(1, intval($_POST['quantity']));

$legacyUserTable = atc_find_first_table($conn, ['userinfos']);

if ($legacyUserTable) {
    $checkAmountStmt = $conn->prepare('SELECT bookQuantity FROM books WHERE bookID = ? LIMIT 1');
    $checkAmountStmt->bind_param('i', $bID);
    $checkAmountStmt->execute();
    $checkAmount = $checkAmountStmt->get_result();

    if (!$checkAmount || $checkAmount->num_rows !== 1) {
        echo "<script>alert('Không tìm thấy sản phẩm.'); window.location.href='homepage.php';</script>";
        exit();
    }

    $row = $checkAmount->fetch_assoc();
    $bQuantity = (int) ($row['bookQuantity'] ?? 0);

    if ($amount > $bQuantity) {
        echo "<script>
            var ID = $bID;
            window.location.href = 'detailProduct.php?id=' + ID;
            alert('Sản Phẩm Không Đủ Số Lượng!');
        </script>";
        exit();
    }

    $add_to_cart = $conn->prepare('CALL p_add_to_cart(?, ?, ?)');
    if (!$add_to_cart) {
        echo "<script>alert('Không thể thêm giỏ hàng.'); window.location.href='detailProduct.php?id=$bID';</script>";
        exit();
    }

    $add_to_cart->bind_param('sii', $uname, $bID, $amount);
    $result = $add_to_cart->execute();

    if ($result) {
        echo "<script>
                var ID = $bID;
                window.location.href = 'detailProduct.php?id=' + ID;
                alert('NHẬP THÀNH CÔNG!!!');
            </script>";
        exit();
    }

    echo "<script>alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.'); window.location.href='detailProduct.php?id=$bID';</script>";
    exit();
}

$booksTable = atc_find_first_table($conn, ['books', 'BOOKS']);
$usersTable = atc_find_first_table($conn, ['users', 'USERS']);
$ordersTable = atc_find_first_table($conn, ['orders', 'ORDERS']);
$detailsTable = atc_find_first_table($conn, ['detailOrders', 'DETAIL_ORDERS']);

if (!$booksTable || !$usersTable || !$ordersTable || !$detailsTable) {
    echo "<script>alert('Cấu trúc CSDL không hợp lệ.'); window.location.href='homepage.php';</script>";
    exit();
}

$bookIdCol = atc_find_first_column($conn, $booksTable, ['bookID', 'BOOK_ID']);
$bookQuantityCol = atc_find_first_column($conn, $booksTable, ['bookQuantity', 'BOOK_Amount']);
$bookPriceCol = atc_find_first_column($conn, $booksTable, ['bookPrice', 'BOOK_PRICE']);
$userIdCol = atc_find_first_column($conn, $usersTable, ['userID', 'USER_ID']);
$userNameCol = atc_find_first_column($conn, $usersTable, ['userName', 'USER_Name']);
$orderIdCol = atc_find_first_column($conn, $ordersTable, ['orderID', 'OR_ID']);
$orderUserCol = atc_find_first_column($conn, $ordersTable, ['userID', 'USER_ID']);
$orderStatusCol = atc_find_first_column($conn, $ordersTable, ['status', 'OR_Status']);
$orderDateCol = atc_find_first_column($conn, $ordersTable, ['orderDate', 'OR_DATE']);
$detailOrderCol = atc_find_first_column($conn, $detailsTable, ['orderID', 'OR_ID']);
$detailBookCol = atc_find_first_column($conn, $detailsTable, ['bookID', 'BOOK_ID']);
$detailAmountCol = atc_find_first_column($conn, $detailsTable, ['orderQuantity', 'OD_Amount']);
$detailCostCol = atc_find_first_column($conn, $detailsTable, ['orderCost', 'OD_Cost']);

if (!$bookIdCol || !$bookQuantityCol || !$bookPriceCol || !$userIdCol || !$userNameCol || !$orderIdCol || !$orderUserCol || !$orderStatusCol || !$detailOrderCol || !$detailBookCol || !$detailAmountCol || !$detailCostCol) {
    echo "<script>alert('Thiếu cột dữ liệu cần thiết.'); window.location.href='homepage.php';</script>";
    exit();
}

$bookStmt = $conn->prepare("SELECT `{$bookQuantityCol}` AS qty, `{$bookPriceCol}` AS price FROM `{$booksTable}` WHERE `{$bookIdCol}` = ? LIMIT 1");
$bookStmt->bind_param('i', $bID);
$bookStmt->execute();
$bookResult = $bookStmt->get_result();
if (!$bookResult || $bookResult->num_rows !== 1) {
    echo "<script>alert('Không tìm thấy sản phẩm.'); window.location.href='homepage.php';</script>";
    exit();
}
$book = $bookResult->fetch_assoc();
$bQuantity = (int) ($book['qty'] ?? 0);
$price = (int) ($book['price'] ?? 0);

if ($amount > $bQuantity) {
    echo "<script>var ID = $bID; window.location.href = 'detailProduct.php?id=' + ID; alert('Sản Phẩm Không Đủ Số Lượng!');</script>";
    exit();
}

$userStmt = $conn->prepare("SELECT `{$userIdCol}` AS uid FROM `{$usersTable}` WHERE `{$userNameCol}` = ? LIMIT 1");
$userStmt->bind_param('s', $uname);
$userStmt->execute();
$userResult = $userStmt->get_result();
if (!$userResult || $userResult->num_rows !== 1) {
    echo "<script>alert('Không tìm thấy người dùng.'); window.location.href='homepage.php';</script>";
    exit();
}
$userId = (int) ($userResult->fetch_assoc()['uid'] ?? 0);

$conn->begin_transaction();
$ok = false;

try {
    if ($orderDateCol) {
        $orderStmt = $conn->prepare("INSERT INTO `{$ordersTable}` (`{$orderUserCol}`, `{$orderStatusCol}`, `{$orderDateCol}`) VALUES (?, 'chưa thanh toán', CURRENT_DATE)");
    } else {
        $orderStmt = $conn->prepare("INSERT INTO `{$ordersTable}` (`{$orderUserCol}`, `{$orderStatusCol}`) VALUES (?, 'chưa thanh toán')");
    }

    $orderStmt->bind_param('i', $userId);
    $ok = $orderStmt->execute();

    if ($ok) {
        $newOrderId = (int) $conn->insert_id;
        $cost = $price * $amount;
        $detailStmt = $conn->prepare("INSERT INTO `{$detailsTable}` (`{$detailOrderCol}`, `{$detailBookCol}`, `{$detailAmountCol}`, `{$detailCostCol}`) VALUES (?, ?, ?, ?)");
        $detailStmt->bind_param('iiid', $newOrderId, $bID, $amount, $cost);
        $ok = $detailStmt->execute();

        if ($ok) {
            $updateStmt = $conn->prepare("UPDATE `{$booksTable}` SET `{$bookQuantityCol}` = `{$bookQuantityCol}` - ? WHERE `{$bookIdCol}` = ?");
            $updateStmt->bind_param('ii', $amount, $bID);
            $ok = $updateStmt->execute();
        }
    }

    if ($ok) {
        $conn->commit();
        echo "<script>var ID = $bID; window.location.href = 'detailProduct.php?id=' + ID; alert('NHẬP THÀNH CÔNG!!!');</script>";
        exit();
    }

    $conn->rollback();
} catch (Throwable $e) {
    $conn->rollback();
}

echo "<script>alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.'); window.location.href='detailProduct.php?id=$bID';</script>";
exit();
