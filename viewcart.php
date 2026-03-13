<?php
if (!isset($conn) || !($conn instanceof mysqli)) {
    include './config/db_connection.php';
}

if (!function_exists('vc_find_first_table')) {
    function vc_find_first_table(mysqli $conn, array $candidates): ?string
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
if (!function_exists('vc_find_first_column')) {
    function vc_find_first_column(mysqli $conn, string $table, array $candidates): ?string
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
    return;
}

$uname = $_SESSION['username'];
$legacy = vc_find_first_table($conn, ['userinfos']);
$rows = [];
$orderIDs = [];
$totalRaw = 0;

if ($legacy) {
    $sql = "SELECT o.orderID AS orderID, b.bookName AS bookName, b.bgURL AS bgURL, b.bookPrice AS bookPrice, d.orderQuantity AS orderQuantity, d.orderCost AS orderCost, o.status AS status
            FROM orders o
            JOIN detailOrders d ON o.orderID = d.orderID
            JOIN books b ON d.bookID = b.bookID
            JOIN userinfos u ON o.userID = u.userID
            WHERE u.userName = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return;
    }
    $stmt->bind_param('s', $uname);
    $stmt->execute();
    $resultCart = $stmt->get_result();
    while ($resultCart && ($row = $resultCart->fetch_assoc())) {
        if (($row['status'] ?? '') === 'chưa thanh toán') {
            $rows[] = $row;
            $orderIDs[] = (int) $row['orderID'];
            $totalRaw += (float) ($row['orderCost'] ?? 0);
        }
    }
} else {
    $ordersTable = vc_find_first_table($conn, ['orders', 'ORDERS']);
    $detailsTable = vc_find_first_table($conn, ['detailOrders', 'DETAIL_ORDERS']);
    $booksTable = vc_find_first_table($conn, ['books', 'BOOKS']);
    $usersTable = vc_find_first_table($conn, ['users', 'USERS']);
    if (!$ordersTable || !$detailsTable || !$booksTable || !$usersTable) {
        return;
    }

    $orderId = vc_find_first_column($conn, $ordersTable, ['orderID', 'OR_ID']);
    $orderUserId = vc_find_first_column($conn, $ordersTable, ['userID', 'USER_ID']);
    $orderStatus = vc_find_first_column($conn, $ordersTable, ['status', 'OR_Status']);

    $detailOrderId = vc_find_first_column($conn, $detailsTable, ['orderID', 'OR_ID']);
    $detailBookId = vc_find_first_column($conn, $detailsTable, ['bookID', 'BOOK_ID']);
    $detailAmount = vc_find_first_column($conn, $detailsTable, ['orderQuantity', 'OD_Amount']);
    $detailCost = vc_find_first_column($conn, $detailsTable, ['orderCost', 'OD_Cost']);

    $bookId = vc_find_first_column($conn, $booksTable, ['bookID', 'BOOK_ID']);
    $bookName = vc_find_first_column($conn, $booksTable, ['bookName', 'BOOK_Name']);
    $bookImage = vc_find_first_column($conn, $booksTable, ['bgURL', 'bookImage', 'BOOK_Image']);
    $bookPrice = vc_find_first_column($conn, $booksTable, ['bookPrice', 'BOOK_PRICE']);

    $userId = vc_find_first_column($conn, $usersTable, ['userID', 'USER_ID']);
    $userName = vc_find_first_column($conn, $usersTable, ['userName', 'USER_Name']);

    if (!$orderId || !$orderUserId || !$orderStatus || !$detailOrderId || !$detailBookId || !$detailAmount || !$detailCost || !$bookId || !$bookName || !$bookImage || !$bookPrice || !$userId || !$userName) {
        return;
    }

    $sql = "SELECT o.`{$orderId}` AS orderID, b.`{$bookName}` AS bookName, b.`{$bookImage}` AS bgURL, b.`{$bookPrice}` AS bookPrice, d.`{$detailAmount}` AS orderQuantity, d.`{$detailCost}` AS orderCost, o.`{$orderStatus}` AS status
            FROM `{$ordersTable}` o
            JOIN `{$detailsTable}` d ON o.`{$orderId}` = d.`{$detailOrderId}`
            JOIN `{$booksTable}` b ON d.`{$detailBookId}` = b.`{$bookId}`
            JOIN `{$usersTable}` u ON o.`{$orderUserId}` = u.`{$userId}`
            WHERE u.`{$userName}` = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return;
    }
    $stmt->bind_param('s', $uname);
    $stmt->execute();
    $resultCart = $stmt->get_result();
    while ($resultCart && ($row = $resultCart->fetch_assoc())) {
        if (($row['status'] ?? '') === 'chưa thanh toán') {
            $rows[] = $row;
            $orderIDs[] = (int) $row['orderID'];
            $totalRaw += (float) ($row['orderCost'] ?? 0);
        }
    }
}

if (empty($rows)) {
    return;
}

$formatTotal = number_format((int) $totalRaw, 0, ',', '.');
$num = 1;
foreach ($rows as $row) {
    $orderID = (int) ($row['orderID'] ?? 0);
    $bname = (string) ($row['bookName'] ?? '');
    $img = (string) ($row['bgURL'] ?? '');
    $bprices = (int) ($row['bookPrice'] ?? 0);
    $amount = (int) ($row['orderQuantity'] ?? 0);
    $cost = (float) ($row['orderCost'] ?? 0);
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
    <form action="./delCart.php" method="post">
        <td class="product-del">
            <input type="hidden" name="orderId" value="' . $orderID . '">
            <button type="submit" class = "product-del-button" onclick="return confirmDelete();">
            Xoá <i class="fa-solid fa-trash product-del-icon"></i>
            </button>
        </td>
    </form>
    </tr>';
    $num++;
}

echo '
<tr>
    <th class="product-num"></th>
    <th class="product-name">Tổng Tiền</th>
    <th class="product-img"></th>
    <th class="product-price"></th>
    <th class="product-quantity"></th>
    <th class="product-total">' . $formatTotal . '</th>
    <th class="product-del"></th>
</tr>';

echo '
<form action="./payment.php" method="post" class = "form-payment">
    <input type="hidden" name="orderIDs" value="' . implode(',', $orderIDs) . '">
    <button type="submit" class="cart__container-payment">Thanh Toán <i class="cart__container-payment-icon fa-solid fa-money-bill"></i></button>
</form>';
