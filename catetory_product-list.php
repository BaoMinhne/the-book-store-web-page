<?php
$debugMode = isset($_GET['debug']) && $_GET['debug'] === '1';
$catalogDebug = [
    'tables' => [],
    'columns' => [],
    'query' => null,
    'num_rows' => null,
    'sql_error' => null,
    'message' => null,
];

function find_first_table(mysqli $conn, array $candidates): ?string
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

$booksTable = find_first_table($conn, ['books', 'BOOKS']);
$publishersTable = find_first_table($conn, ['publishers', 'PUBLISHERS']);
$originsTable = find_first_table($conn, ['origins', 'ORIGINS']);

$catalogDebug['tables'] = [
    'books' => $booksTable,
    'publishers' => $publishersTable,
    'origins' => $originsTable,
];

if (!$booksTable || !$publishersTable || !$originsTable) {
    $catalogDebug['message'] = 'Cấu trúc dữ liệu chưa đúng hoặc thiếu bảng cần thiết.';
    $GLOBALS['catalogDebug'] = $catalogDebug;
    echo $catalogDebug['message'];
    if ($debugMode) {
        echo '<pre style="background:#111;color:#9f9;padding:10px;border-radius:6px;white-space:pre-wrap;">' . htmlspecialchars(json_encode($catalogDebug, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') . '</pre>';
    }

    return;
}

$bookIdCol = find_first_column($conn, $booksTable, ['bookID', 'BOOK_ID']);
$bookNameCol = find_first_column($conn, $booksTable, ['bookName', 'BOOK_Name']);
$bookPriceCol = find_first_column($conn, $booksTable, ['bookPrice', 'BOOK_PRICE']);
$bookQuantityCol = find_first_column($conn, $booksTable, ['bookQuantity', 'BOOK_Amount']);
$bookImageCol = find_first_column($conn, $booksTable, ['bgURL', 'bookImage', 'BOOK_Image']);
$bookGenreCol = find_first_column($conn, $booksTable, ['genID', 'GEN_ID']);
$bookPubFkCol = find_first_column($conn, $booksTable, ['pubID', 'PUB_ID']);
$bookOriFkCol = find_first_column($conn, $booksTable, ['oriID', 'ORI_ID']);

$pubJoinCol = find_first_column($conn, $publishersTable, ['pubID', 'PUB_ID']);
$pubBrandCol = find_first_column($conn, $publishersTable, ['pubID', 'pubName', 'PUB_ID', 'PUB_Name']);
$oriJoinCol = find_first_column($conn, $originsTable, ['oriID', 'ORI_ID']);
$oriNameCol = find_first_column($conn, $originsTable, ['oriName', 'ORI_Name']);

$catalogDebug['columns'] = [
    'bookIdCol' => $bookIdCol,
    'bookNameCol' => $bookNameCol,
    'bookPriceCol' => $bookPriceCol,
    'bookQuantityCol' => $bookQuantityCol,
    'bookImageCol' => $bookImageCol,
    'bookGenreCol' => $bookGenreCol,
    'bookPubFkCol' => $bookPubFkCol,
    'bookOriFkCol' => $bookOriFkCol,
    'pubJoinCol' => $pubJoinCol,
    'pubBrandCol' => $pubBrandCol,
    'oriJoinCol' => $oriJoinCol,
    'oriNameCol' => $oriNameCol,
];

if (!$bookIdCol || !$bookNameCol || !$bookPriceCol || !$bookQuantityCol || !$bookImageCol || !$bookGenreCol || !$bookPubFkCol || !$bookOriFkCol || !$pubJoinCol || !$pubBrandCol || !$oriJoinCol || !$oriNameCol) {
    $catalogDebug['message'] = 'Cấu trúc dữ liệu chưa đúng hoặc thiếu cột cần thiết.';
    $GLOBALS['catalogDebug'] = $catalogDebug;
    echo $catalogDebug['message'];
    if ($debugMode) {
        echo '<pre style="background:#111;color:#9f9;padding:10px;border-radius:6px;white-space:pre-wrap;">' . htmlspecialchars(json_encode($catalogDebug, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') . '</pre>';
    }

    return;
}

$baseQuery = "SELECT b.`{$bookIdCol}` AS bookID, b.`{$bookNameCol}` AS bookName, b.`{$bookPriceCol}` AS bookPrice, b.`{$bookQuantityCol}` AS bookQuantity, b.`{$bookImageCol}` AS bgURL, p.`{$pubBrandCol}` AS pubBrand, o.`{$oriNameCol}` AS oriName FROM `{$booksTable}` b JOIN `{$publishersTable}` p ON b.`{$bookPubFkCol}` = p.`{$pubJoinCol}` JOIN `{$originsTable}` o ON b.`{$bookOriFkCol}` = o.`{$oriJoinCol}`";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bookName'])) {
    $searchName = $conn->real_escape_string($_POST['bookName']);
    $query = $baseQuery . " WHERE b.`{$bookNameCol}` LIKE '%{$searchName}%'";
} else {
    $category = $_GET['category'] ?? 'all';
    $sort = $_GET['sort'] ?? 'default';
    $stock = $_GET['stock'] ?? 'all';
    $priceRange = $_GET['price_range'] ?? 'all';

    if ($category === 'small_to_large' || $category === 'large_to_small') {
        $sort = $category === 'small_to_large' ? 'price_asc' : 'price_desc';
        $category = 'all';
    }

    $genreMap = [
        'sach_giao_khoa' => 'SGK',
        'tieu_thuyet' => 'TT',
        'truyen_tranh' => 'TRT',
        'kinh_doanh' => 'KD',
        'khoa_hoc' => 'KH',
        'giao_trinh' => 'GT',
        'y_hoc' => 'YH',
        'tham_khao' => 'STK',
        'cong_nghe' => 'CN',
        'lich_su' => 'LS',
    ];

    $whereClauses = [];
    if (isset($genreMap[$category])) {
        $whereClauses[] = "b.`{$bookGenreCol}` = '{$genreMap[$category]}'";
    }

    switch ($stock) {
        case 'in_stock':
            $whereClauses[] = "b.`{$bookQuantityCol}` > 0";
            break;
        case 'low_stock':
            $whereClauses[] = "b.`{$bookQuantityCol}` BETWEEN 1 AND 10";
            break;
        case 'out_of_stock':
            $whereClauses[] = "b.`{$bookQuantityCol}` = 0";
            break;
    }

    switch ($priceRange) {
        case 'under_100k':
            $whereClauses[] = "b.`{$bookPriceCol}` < 100000";
            break;
        case '100k_300k':
            $whereClauses[] = "b.`{$bookPriceCol}` BETWEEN 100000 AND 300000";
            break;
        case '300k_500k':
            $whereClauses[] = "b.`{$bookPriceCol}` BETWEEN 300001 AND 500000";
            break;
        case 'over_500k':
            $whereClauses[] = "b.`{$bookPriceCol}` > 500000";
            break;
    }

    $orderBy = "b.`{$bookIdCol}` ASC";
    switch ($sort) {
        case 'price_asc':
            $orderBy = "b.`{$bookPriceCol}` ASC";
            break;
        case 'price_desc':
            $orderBy = "b.`{$bookPriceCol}` DESC";
            break;
        case 'name_asc':
            $orderBy = "b.`{$bookNameCol}` ASC";
            break;
        case 'name_desc':
            $orderBy = "b.`{$bookNameCol}` DESC";
            break;
        case 'newest':
            $orderBy = "b.`{$bookIdCol}` DESC";
            break;
    }

    $query = $baseQuery;
    if (!empty($whereClauses)) {
        $query .= " WHERE " . implode(' AND ', $whereClauses);
    }
    $query .= " ORDER BY {$orderBy}";
}

$catalogDebug['query'] = $query;
$result = mysqli_query($conn, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $catalogDebug['num_rows'] = mysqli_num_rows($result);
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
    $catalogDebug['num_rows'] = $result ? 0 : null;
    $catalogDebug['sql_error'] = $conn->error ?: null;
    $catalogDebug['message'] = 'Không có dữ liệu sản phẩm phù hợp.';

    $selectedCategory = $_GET['category'] ?? 'all';
    $selectedSort = $_GET['sort'] ?? 'default';
    $selectedStock = $_GET['stock'] ?? 'all';
    $selectedPriceRange = $_GET['price_range'] ?? 'all';

    if ($selectedCategory === 'small_to_large' || $selectedCategory === 'large_to_small') {
        $selectedSort = $selectedCategory === 'small_to_large' ? 'price_asc' : 'price_desc';
        $selectedCategory = 'all';
    }

    $categoryLabelMap = [
        'all' => 'Tất cả sản phẩm',
        'sach_giao_khoa' => 'Sách giáo khoa',
        'tieu_thuyet' => 'Tiểu thuyết',
        'truyen_tranh' => 'Truyện tranh',
        'kinh_doanh' => 'Kinh doanh',
        'khoa_hoc' => 'Khoa học',
        'giao_trinh' => 'Giáo trình',
        'y_hoc' => 'Y học',
        'tham_khao' => 'Sách tham khảo',
        'cong_nghe' => 'Công nghệ',
        'lich_su' => 'Lịch sử',
    ];
    $sortLabelMap = [
        'default' => 'Mặc định',
        'price_asc' => 'Giá tăng dần',
        'price_desc' => 'Giá giảm dần',
        'name_asc' => 'Tên A-Z',
        'name_desc' => 'Tên Z-A',
        'newest' => 'Mới nhất',
    ];
    $stockLabelMap = [
        'all' => 'Tồn kho bất kỳ',
        'in_stock' => 'Còn hàng',
        'low_stock' => 'Sắp hết hàng',
        'out_of_stock' => 'Hết hàng',
    ];
    $priceLabelMap = [
        'all' => 'Mọi mức giá',
        'under_100k' => 'Dưới 100.000đ',
        '100k_300k' => '100.000đ - 300.000đ',
        '300k_500k' => '300.000đ - 500.000đ',
        'over_500k' => 'Trên 500.000đ',
    ];

    $activeFilterLabels = [];
    if ($selectedCategory !== 'all') {
        $activeFilterLabels[] = $categoryLabelMap[$selectedCategory] ?? 'Danh mục';
    }
    if ($selectedSort !== 'default') {
        $activeFilterLabels[] = $sortLabelMap[$selectedSort] ?? 'Sắp xếp';
    }
    if ($selectedStock !== 'all') {
        $activeFilterLabels[] = $stockLabelMap[$selectedStock] ?? 'Tồn kho';
    }
    if ($selectedPriceRange !== 'all') {
        $activeFilterLabels[] = $priceLabelMap[$selectedPriceRange] ?? 'Mức giá';
    }

    $selectedFilterSummary = !empty($activeFilterLabels) ? implode(' · ', $activeFilterLabels) : 'Tất cả sản phẩm';
    $currentPage = basename($_SERVER['PHP_SELF']);

    echo '<div class="home-empty-state">';
    echo '  <div class="home-empty-state__icon"><i class="fa-solid fa-box-open"></i></div>';
    echo '  <h3 class="home-empty-state__title">Chưa có sản phẩm để hiển thị</h3>';
    echo '  <p class="home-empty-state__desc">Bộ lọc <strong>' . htmlspecialchars($selectedFilterSummary, ENT_QUOTES, 'UTF-8') . '</strong> hiện chưa có dữ liệu hoặc chưa phù hợp với từ khóa tìm kiếm.</p>';
    echo '  <div class="home-empty-state__actions">';
    echo '      <a href="' . htmlspecialchars($currentPage, ENT_QUOTES, 'UTF-8') . '?category=all" class="home-empty-state__btn home-empty-state__btn--primary">Xem tất cả sản phẩm</a>';
    echo '      <a href="' . htmlspecialchars($currentPage, ENT_QUOTES, 'UTF-8') . '" class="home-empty-state__btn">Đặt lại bộ lọc</a>';
    echo '  </div>';
    echo '</div>';

    if ($conn->error) {
        echo '<p class="home-empty-state__error">SQL Error: ' . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8') . '</p>';
    }
    if ($debugMode) {
        echo '<pre style="background:#111;color:#9f9;padding:10px;border-radius:6px;white-space:pre-wrap;">' . htmlspecialchars(json_encode($catalogDebug, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') . '</pre>';
    }
}

$GLOBALS['catalogDebug'] = $catalogDebug;
