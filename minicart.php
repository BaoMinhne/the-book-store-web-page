<?php
if (!isset($conn) || !($conn instanceof mysqli)) {
    include './config/db_connection.php';
}

if (!function_exists('mc_find_first_table')) {
    function mc_find_first_table(mysqli $conn, array $candidates): ?string
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

if (!function_exists('mc_find_first_column')) {
    function mc_find_first_column(mysqli $conn, string $table, array $candidates): ?string
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
$legacyUserTable = mc_find_first_table($conn, ['userinfos']);

if ($legacyUserTable) {
    $sql = "SELECT o.orderID AS orderID, g.genName AS genName, b.bookName AS bookName, b.bgURL AS bgURL, b.bookPrice AS bookPrice, d.orderQuantity AS orderQuantity, d.orderCost AS orderCost, o.status AS status
            FROM orders o
            JOIN detailOrders d ON o.orderID = d.orderID
            JOIN books b ON d.bookID = b.bookID
            JOIN genres g ON b.genID = g.genID
            JOIN userinfos u ON o.userID = u.userID
            WHERE u.userName = ?";
} else {
    $ordersTable = mc_find_first_table($conn, ['orders', 'ORDERS']);
    $detailsTable = mc_find_first_table($conn, ['detailOrders', 'DETAIL_ORDERS']);
    $booksTable = mc_find_first_table($conn, ['books', 'BOOKS']);
    $genresTable = mc_find_first_table($conn, ['genres', 'GENRES']);
    $usersTable = mc_find_first_table($conn, ['users', 'USERS']);

    if (!$ordersTable || !$detailsTable || !$booksTable || !$genresTable || !$usersTable) {
        return;
    }

    $orId = mc_find_first_column($conn, $ordersTable, ['orderID', 'OR_ID']);
    $orderUserId = mc_find_first_column($conn, $ordersTable, ['userID', 'USER_ID']);
    $orderStatus = mc_find_first_column($conn, $ordersTable, ['status', 'OR_Status']);

    $detailOrderId = mc_find_first_column($conn, $detailsTable, ['orderID', 'OR_ID']);
    $detailBookId = mc_find_first_column($conn, $detailsTable, ['bookID', 'BOOK_ID']);
    $detailAmount = mc_find_first_column($conn, $detailsTable, ['orderQuantity', 'OD_Amount']);
    $detailCost = mc_find_first_column($conn, $detailsTable, ['orderCost', 'OD_Cost']);

    $bookId = mc_find_first_column($conn, $booksTable, ['bookID', 'BOOK_ID']);
    $bookName = mc_find_first_column($conn, $booksTable, ['bookName', 'BOOK_Name']);
    $bookImage = mc_find_first_column($conn, $booksTable, ['bgURL', 'bookImage', 'BOOK_Image']);
    $bookPrice = mc_find_first_column($conn, $booksTable, ['bookPrice', 'BOOK_PRICE']);
    $bookGenId = mc_find_first_column($conn, $booksTable, ['genID', 'GEN_ID']);

    $genId = mc_find_first_column($conn, $genresTable, ['genID', 'GEN_ID']);
    $genName = mc_find_first_column($conn, $genresTable, ['genName', 'GEN_Name']);

    $userId = mc_find_first_column($conn, $usersTable, ['userID', 'USER_ID']);
    $userName = mc_find_first_column($conn, $usersTable, ['userName', 'USER_Name']);

    if (!$orId || !$orderUserId || !$orderStatus || !$detailOrderId || !$detailBookId || !$detailAmount || !$detailCost || !$bookId || !$bookName || !$bookImage || !$bookPrice || !$bookGenId || !$genId || !$genName || !$userId || !$userName) {
        return;
    }

    $sql = "SELECT o.`{$orId}` AS orderID, g.`{$genName}` AS genName, b.`{$bookName}` AS bookName, b.`{$bookImage}` AS bgURL, b.`{$bookPrice}` AS bookPrice, d.`{$detailAmount}` AS orderQuantity, d.`{$detailCost}` AS orderCost, o.`{$orderStatus}` AS status
            FROM `{$ordersTable}` o
            JOIN `{$detailsTable}` d ON o.`{$orId}` = d.`{$detailOrderId}`
            JOIN `{$booksTable}` b ON d.`{$detailBookId}` = b.`{$bookId}`
            JOIN `{$genresTable}` g ON b.`{$bookGenId}` = g.`{$genId}`
            JOIN `{$usersTable}` u ON o.`{$orderUserId}` = u.`{$userId}`
            WHERE u.`{$userName}` = ?";
}

$stmt = $conn->prepare($sql);
if (!$stmt) {
    return;
}

$stmt->bind_param('s', $uname);
$stmt->execute();
$resultCart = $stmt->get_result();
if (!$resultCart || $resultCart->num_rows === 0) {
    return;
}

while ($row = $resultCart->fetch_assoc()) {
    $orderID = $row['orderID'];
    $btype = $row['genName'];
    $bname = $row['bookName'];
    $img = $row['bgURL'];
    $bprices = $row['bookPrice'];
    $amount = $row['orderQuantity'];
    $status = $row['status'];
    $formatprice = number_format((int) $bprices, 0, ',', '.');

    if ($status === 'chưa thanh toán') {
        echo '
        <li class="header__cart-item">
        <img src="' . htmlspecialchars((string) $img, ENT_QUOTES) . '" alt="" class="header__cart-img">
        <div class="header__cart-item-info">
            <div class="header__cart-item-head">
                <h5 class="header__cart-item-name">' . htmlspecialchars((string) $bname) . '</h5>
                <div class="header__cart-item-price-wrap">
                    <span class="header__cart-item-price">' . $formatprice . 'đ</span>
                    <span class="header__cart-item-multiply">x</span>
                    <span class="header__cart-item-qnt">' . (int) $amount . '</span>
                </div>
            </div>

            <div class="header__cart-item-body">
                <span class="header__cart-item-description">
                    Phân loại sách: ' . htmlspecialchars((string) $btype) . '
                </span>
                <span class="header__cart-item-remove">
                <form action="./delCart.php" method="post">
                    <input type="hidden" name="orderId" value="' . (int) $orderID . '">
                    <button type="submit" class = "product-del-button" onclick="return confirmDelete();">
                    Xoá <i class="fa-solid fa-trash product-del-icon"></i>
                    </button>
            </form>
                </span>
            </div>
        </div>
    </li>
        ';
    }
}
