<?php
function find_first_column(mysqli $conn, string $table, array $candidates): ?string
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

$bookIdCol = find_first_column($conn, 'books', ['bookID', 'BOOK_ID']);
$bookNameCol = find_first_column($conn, 'books', ['bookName', 'BOOK_Name']);
$bookPriceCol = find_first_column($conn, 'books', ['bookPrice', 'BOOK_PRICE']);
$bookQuantityCol = find_first_column($conn, 'books', ['bookQuantity', 'BOOK_Amount']);
$bookImageCol = find_first_column($conn, 'books', ['bgURL', 'bookImage', 'BOOK_Image']);
$bookGenreCol = find_first_column($conn, 'books', ['genID', 'GEN_ID']);
$bookPubFkCol = find_first_column($conn, 'books', ['pubID', 'PUB_ID']);
$bookOriFkCol = find_first_column($conn, 'books', ['oriID', 'ORI_ID']);

$pubJoinCol = find_first_column($conn, 'publishers', ['pubID', 'PUB_ID']);
$pubBrandCol = find_first_column($conn, 'publishers', ['pubID', 'pubName', 'PUB_ID', 'PUB_Name']);
$oriJoinCol = find_first_column($conn, 'origins', ['oriID', 'ORI_ID']);
$oriNameCol = find_first_column($conn, 'origins', ['oriName', 'ORI_Name']);

if (!$bookIdCol || !$bookNameCol || !$bookPriceCol || !$bookQuantityCol || !$bookImageCol || !$bookGenreCol || !$bookPubFkCol || !$bookOriFkCol || !$pubJoinCol || !$pubBrandCol || !$oriJoinCol || !$oriNameCol) {
    echo 'Cấu trúc dữ liệu chưa đúng hoặc thiếu cột cần thiết.';
    return;
}

$baseQuery = "SELECT b.`{$bookIdCol}` AS bookID, b.`{$bookNameCol}` AS bookName, b.`{$bookPriceCol}` AS bookPrice, b.`{$bookQuantityCol}` AS bookQuantity, b.`{$bookImageCol}` AS bgURL, p.`{$pubBrandCol}` AS pubBrand, o.`{$oriNameCol}` AS oriName FROM books b JOIN publishers p ON b.`{$bookPubFkCol}` = p.`{$pubJoinCol}` JOIN origins o ON b.`{$bookOriFkCol}` = o.`{$oriJoinCol}`";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bookName'])) {
    $searchName = $conn->real_escape_string($_POST['bookName']);
    $query = $baseQuery . " WHERE b.`{$bookNameCol}` LIKE '%{$searchName}%'";
} else {
    $category = $_GET['category'] ?? null;
    switch ($category) {
        case 'sach_giao_khoa':
            $query = $baseQuery . " WHERE b.`{$bookGenreCol}` = 'SGK' ORDER BY b.`{$bookIdCol}` ASC";
            break;
        case 'tieu_thuyet':
            $query = $baseQuery . " WHERE b.`{$bookGenreCol}` = 'TT' ORDER BY b.`{$bookIdCol}` ASC";
            break;
        case 'truyen_tranh':
            $query = $baseQuery . " WHERE b.`{$bookGenreCol}` = 'TRT' ORDER BY b.`{$bookIdCol}` ASC";
            break;
        case 'kinh_doanh':
            $query = $baseQuery . " WHERE b.`{$bookGenreCol}` = 'KD' ORDER BY b.`{$bookIdCol}` ASC";
            break;
        case 'khoa_hoc':
            $query = $baseQuery . " WHERE b.`{$bookGenreCol}` = 'KH' ORDER BY b.`{$bookIdCol}` ASC";
            break;
        case 'giao_trinh':
            $query = $baseQuery . " WHERE b.`{$bookGenreCol}` = 'GT' ORDER BY b.`{$bookIdCol}` ASC";
            break;
        case 'y_hoc':
            $query = $baseQuery . " WHERE b.`{$bookGenreCol}` = 'YH' ORDER BY b.`{$bookIdCol}` ASC";
            break;
        case 'tham_khao':
            $query = $baseQuery . " WHERE b.`{$bookGenreCol}` = 'STK' ORDER BY b.`{$bookIdCol}` ASC";
            break;
        case 'cong_nghe':
            $query = $baseQuery . " WHERE b.`{$bookGenreCol}` = 'CN' ORDER BY b.`{$bookIdCol}` ASC";
            break;
        case 'lich_su':
            $query = $baseQuery . " WHERE b.`{$bookGenreCol}` = 'LS' ORDER BY b.`{$bookIdCol}` ASC";
            break;
        case 'small_to_large':
            $query = $baseQuery . " ORDER BY b.`{$bookPriceCol}` ASC";
            break;
        case 'large_to_small':
            $query = $baseQuery . " ORDER BY b.`{$bookPriceCol}` DESC";
            break;
        case 'all':
        default:
            $query = $baseQuery . " ORDER BY b.`{$bookIdCol}` ASC";
            break;
    }
}

$result = mysqli_query($conn, $query);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bookPrice = (int) ($row['bookPrice'] ?? 0);
        $formattedPrice = number_format($bookPrice, 0, ',', '.');
        echo '<div class="grid__column-2-4">';
        $imageRaw = trim((string) ($row['bgURL'] ?? ''));
        $safeImage = $imageRaw !== '' ? $imageRaw : asset_url('assets/img/logo/logo2.png');
        echo '<a class="home-product-item" href="detailProduct.php?id=' . $row['bookID'] . '">';
        echo '<div class="home-product-item__img" style="background-image: url(' . htmlspecialchars($safeImage, ENT_QUOTES) . ');"></div>';
        echo '<h4 class="home-product-item__name">' . htmlspecialchars((string) $row['bookName']) . '</h4>';
        echo '<div class="home-product-item__price">';
        echo '<span class="home-product-item__price-new">' . $formattedPrice . 'đ</span>';
        echo '</div>';
        echo '<div class="home-product-item__action">';
        echo '<span class="home-product-item__like home-product-item__like--liked">';
        echo '<i class="home-product-item__like-icon-fill fa-solid fa-heart"></i>';
        echo '</span>';
        echo '<div class="home-product-item__rating">';
        echo '<i class="home-product-item__rated fa-solid fa-star"></i>';
        echo '<i class="home-product-item__rated fa-solid fa-star"></i>';
        echo '<i class="home-product-item__rated fa-solid fa-star"></i>';
        echo '<i class="home-product-item__rated fa-solid fa-star"></i>';
        echo '<i class="home-product-item__rated fa-solid fa-star"></i>';
        echo '</div>';
        echo '<span class="home-product-item__sold">' . $row['bookQuantity'] . ' Còn Lại  </span>';
        echo '</div>';
        echo '<div class="home-product-item__origin">';
        echo '<span class="home-product-item__brand">' . $row['pubBrand'] . '</span>';
        echo '<span class="home-product-item__origin">' . $row['oriName'] . '</span>';
        echo '</div>';
        echo '<div class="home-product-item__favourite">';
        echo '<i class="fa-solid fa-check"></i>';
        echo '<span>Yêu thích</span>';
        echo '</div>';
        echo '</a>';
        echo '</div>';
    }
} else {
    echo 'Không có dữ liệu.';
    echo 'Error: ' . $conn->error;
}

mysqli_close($conn);
