<?php
if (!isset($conn) || !($conn instanceof mysqli)) {
    include './config/db_connection.php';
}

if (!function_exists('vh_find_first_table')) {
    function vh_find_first_table(mysqli $conn, array $candidates): ?string
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
if (!function_exists('vh_find_first_column')) {
    function vh_find_first_column(mysqli $conn, string $table, array $candidates): ?string
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
    echo 'Bạn chưa đăng nhập';
    return;
}

$uname = $_SESSION['username'];
$rows = [];

if (vh_find_first_table($conn, ['userinfos'])) {
    $sql = "SELECT o.orderID AS orderID, b.bookName AS bookName, b.bgURL AS bgURL, b.bookPrice AS bookPrice, d.orderQuantity AS orderQuantity, d.orderCost AS orderCost, o.status AS status
            FROM orders o
            JOIN detailOrders d ON o.orderID = d.orderID
            JOIN books b ON d.bookID = b.bookID
            JOIN userinfos u ON o.userID = u.userID
            WHERE u.userName = ? AND o.status = 'đã thanh toán'";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('s', $uname);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($result && ($row = $result->fetch_assoc())) {
            $rows[] = $row;
        }
    }
} else {
    $ordersTable = vh_find_first_table($conn, ['orders', 'ORDERS']);
    $detailsTable = vh_find_first_table($conn, ['detailOrders', 'DETAIL_ORDERS']);
    $booksTable = vh_find_first_table($conn, ['books', 'BOOKS']);
    $usersTable = vh_find_first_table($conn, ['users', 'USERS']);
    if (!$ordersTable || !$detailsTable || !$booksTable || !$usersTable) {
        return;
    }

    $orderId = vh_find_first_column($conn, $ordersTable, ['orderID', 'OR_ID']);
    $orderUserId = vh_find_first_column($conn, $ordersTable, ['userID', 'USER_ID']);
    $orderStatus = vh_find_first_column($conn, $ordersTable, ['status', 'OR_Status']);

    $detailOrderId = vh_find_first_column($conn, $detailsTable, ['orderID', 'OR_ID']);
    $detailBookId = vh_find_first_column($conn, $detailsTable, ['bookID', 'BOOK_ID']);
    $detailAmount = vh_find_first_column($conn, $detailsTable, ['orderQuantity', 'OD_Amount']);
    $detailCost = vh_find_first_column($conn, $detailsTable, ['orderCost', 'OD_Cost']);

    $bookId = vh_find_first_column($conn, $booksTable, ['bookID', 'BOOK_ID']);
    $bookName = vh_find_first_column($conn, $booksTable, ['bookName', 'BOOK_Name']);
    $bookImage = vh_find_first_column($conn, $booksTable, ['bgURL', 'bookImage', 'BOOK_Image']);
    $bookPrice = vh_find_first_column($conn, $booksTable, ['bookPrice', 'BOOK_PRICE']);

    $userId = vh_find_first_column($conn, $usersTable, ['userID', 'USER_ID']);
    $userName = vh_find_first_column($conn, $usersTable, ['userName', 'USER_Name']);

    if (!$orderId || !$orderUserId || !$orderStatus || !$detailOrderId || !$detailBookId || !$detailAmount || !$detailCost || !$bookId || !$bookName || !$bookImage || !$bookPrice || !$userId || !$userName) {
        return;
    }

    $sql = "SELECT o.`{$orderId}` AS orderID, b.`{$bookName}` AS bookName, b.`{$bookImage}` AS bgURL, b.`{$bookPrice}` AS bookPrice, d.`{$detailAmount}` AS orderQuantity, d.`{$detailCost}` AS orderCost, o.`{$orderStatus}` AS status
            FROM `{$ordersTable}` o
            JOIN `{$detailsTable}` d ON o.`{$orderId}` = d.`{$detailOrderId}`
            JOIN `{$booksTable}` b ON d.`{$detailBookId}` = b.`{$bookId}`
            JOIN `{$usersTable}` u ON o.`{$orderUserId}` = u.`{$userId}`
            WHERE u.`{$userName}` = ? AND o.`{$orderStatus}` = 'đã thanh toán'";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('s', $uname);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($result && ($row = $result->fetch_assoc())) {
            $rows[] = $row;
        }
    }
}

$num = 1;
foreach ($rows as $row) {
    $bname = (string) ($row['bookName'] ?? '');
    $img = (string) ($row['bgURL'] ?? '');
    $bprices = (int) ($row['bookPrice'] ?? 0);
    $amount = (int) ($row['orderQuantity'] ?? 0);
    $cost = (float) ($row['orderCost'] ?? 0);
    $status = (string) ($row['status'] ?? '');
    $formatCost = number_format((int) $cost, 0, ',', '.');
    $formatprice = number_format($bprices, 0, ',', '.');

    echo '
    <tr>
        <td class="product-num">' . $num . '</td>
        <td class="product-name">' . htmlspecialchars($bname) . '</td>
        <td class="product-img"><img src="' . htmlspecialchars($img, ENT_QUOTES) . '" alt=""></td>
        <td class="product-price">' . $formatprice . '</td>
        <td class="product-quantity">' . $amount . '</td>
        <td class="product-total">' . $formatCost . '</td>
        <td class="product-status">' . htmlspecialchars($status) . '</td>
    </tr>';
    $num++;
}
