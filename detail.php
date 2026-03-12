<?php

if (!function_exists('find_first_column')) {
    function find_first_column(mysqli $conn, string $table, array $candidates): ?string
    {
        $safeTable = $conn->real_escape_string($table);
        $columns = [];
        $result = $conn->query("SHOW COLUMNS FROM `{$safeTable}`");

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $columns[] = $row['Field'];
            }
        }

        foreach ($candidates as $candidate) {
            foreach ($columns as $column) {
                if (strcasecmp($column, $candidate) === 0) {
                    return $column;
                }
            }
        }

        return null;
    }
}

if (isset($_GET['id'])) {
    $ID = (int) $_GET['id'];

    $bookIdCol = find_first_column($conn, 'books', ['bookID', 'BOOK_ID']);
    $bookNameCol = find_first_column($conn, 'books', ['bookName', 'BOOK_Name']);
    $authorCol = find_first_column($conn, 'books', ['authorName', 'BOOK_Author']);
    $bookPriceCol = find_first_column($conn, 'books', ['bookPrice', 'BOOK_PRICE']);
    $bookQuantityCol = find_first_column($conn, 'books', ['bookQuantity', 'BOOK_Amount']);
    $bookDateCol = find_first_column($conn, 'books', ['bookPurchaseDate', 'BOOK_PurchaseDate']);
    $bookImageCol = find_first_column($conn, 'books', ['bgURL', 'bookImage', 'BOOK_Image']);
    $bookPubFkCol = find_first_column($conn, 'books', ['pubID', 'PUB_ID']);
    $bookOriFkCol = find_first_column($conn, 'books', ['oriID', 'ORI_ID']);
    $bookGenFkCol = find_first_column($conn, 'books', ['genID', 'GEN_ID']);

    $pubJoinCol = find_first_column($conn, 'publishers', ['pubID', 'PUB_ID']);
    $pubNameCol = find_first_column($conn, 'publishers', ['pubName', 'PUB_Name', 'pubID', 'PUB_ID']);
    $oriJoinCol = find_first_column($conn, 'origins', ['oriID', 'ORI_ID']);
    $oriNameCol = find_first_column($conn, 'origins', ['oriName', 'ORI_Name']);
    $genJoinCol = find_first_column($conn, 'genres', ['genID', 'GEN_ID']);
    $genNameCol = find_first_column($conn, 'genres', ['genName', 'GEN_Name']);

    if (!$bookIdCol || !$bookNameCol || !$bookPriceCol || !$bookQuantityCol || !$bookImageCol || !$bookPubFkCol || !$bookOriFkCol || !$bookGenFkCol || !$pubJoinCol || !$pubNameCol || !$oriJoinCol || !$oriNameCol || !$genJoinCol || !$genNameCol) {
        echo 'Cấu trúc dữ liệu chưa đúng hoặc thiếu cột cần thiết.';
        return;
    }

    $authorSelect = $authorCol ? "b.`{$authorCol}`" : "''";
    $purchaseDateSelect = $bookDateCol ? "b.`{$bookDateCol}`" : 'NULL';

    $sql = "SELECT b.`{$bookIdCol}` AS bookID,
                   b.`{$bookNameCol}` AS bookName,
                   {$authorSelect} AS authorName,
                   b.`{$bookPriceCol}` AS bookPrice,
                   {$purchaseDateSelect} AS bookPurchaseDate,
                   b.`{$bookQuantityCol}` AS bookQuantity,
                   g.`{$genNameCol}` AS genName,
                   p.`{$pubNameCol}` AS pubName,
                   o.`{$oriNameCol}` AS oriName,
                   b.`{$bookImageCol}` AS bgURL
            FROM books b
            JOIN publishers p ON b.`{$bookPubFkCol}` = p.`{$pubJoinCol}`
            JOIN origins o ON b.`{$bookOriFkCol}` = o.`{$oriJoinCol}`
            JOIN genres g ON b.`{$bookGenFkCol}` = g.`{$genJoinCol}`
            WHERE b.`{$bookIdCol}` = ?
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $ID);
    $stmt->execute();
    $resultProd = $stmt->get_result();

    if ($resultProd && mysqli_num_rows($resultProd) > 0) {
        $row = mysqli_fetch_assoc($resultProd);
        $bookID = (int) ($row['bookID'] ?? 0);
        $productName = $row['bookName'] ?? '';
        $authorName = $row['authorName'] ?? 'Chưa cập nhật';
        $productPrice = (int) ($row['bookPrice'] ?? 0);
        $formattedPrice = number_format($productPrice, 0, ',', '.');
        $productQuantity = (int) ($row['bookQuantity'] ?? 0);
        $publisher = $row['pubName'] ?? '';
        $origin = $row['oriName'] ?? '';

        $imageRaw = trim((string) ($row['bgURL'] ?? ''));
        $bgURL = $imageRaw !== '' ? $imageRaw : asset_url('assets/img/logo/logo2.png');

        echo '
        <div class="grid grid__bg">
            <div class="grid__row">
                <div class="detail-product__img" style="background-image: url(' . htmlspecialchars($bgURL, ENT_QUOTES) . ');"></div>
                <div class="detail-product__info">
                    <div class="detail-product__label">
                    <span class="detail-product__name">' . htmlspecialchars($productName) . '</span>
                </div>

                <div class="detail-product__condition">
                    <div class="detail-product__comment">Chưa có đánh giá</div>
                    <div class="detail-product__inventory">' . $productQuantity . ' Còn Lại</div>
                </div>

                <div class="detail-product__price">
                    <span class="detail-product__price-info">' . $formattedPrice . 'đ</span>
                </div>

                <form action="./addToCart.php" method="post">
                    <div class="detail-product__add-quantity">
                        <h3 class="add-quantity__title">Số Lượng</h3>
                        <div class="add-quantity__button">
                            <button type="button" class="add-quantity__minus" onclick="decrement()">-</button>
                            <input type="hidden" name="productId" value="' . $bookID . '">
                            <input type="number" name="quantity" id="" class="add-quantity__input" value ="1">
                            <button  type="button" class="add-quantity__plus" onclick="increment()">+</button>
                        </div>
                    </div>
                    <div class="detail-product__add-pro-btn">
                        <button type = "submit" class="detail-product__add-cart-btn">Thêm Vào Giỏ Hàng</button>
                        <button type = "button" class="detail-product__buy-btn">
                            <a href="./cart.php" style="text-decoration: none; color: var(--white-color);">Mua Ngay</a>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        </div>
';

        echo '
        <div class="grid grid__bg">
            <div class="grid__row">
                <div class="detail-product__origin-quantity-pub">
                    <h2>CHI TIẾT SẢN PHẨM</h2>
                    <div class="detail-product__section">
                        <div class="detail-product__author">
                            <label for="" class="title">Tác Giả</label>
                            <div class="info">' . htmlspecialchars($authorName) . '</div>
                        </div>

                        <div class="detail-product__inventory">
                            <label for="" class="title">Kho Hàng</label>
                            <div class="info">' . $productQuantity . '</div>
                        </div>

                        <div class="detail-prodcut__publish">
                            <label for="" class="title">Nhà Xuất Bản</label>
                            <div class="info">' . htmlspecialchars($publisher) . '</div>
                        </div>

                        <div class="detail-product__origin">
                            <label for="" class="title">Xuất Xứ</label>
                            <div class="info">' . htmlspecialchars($origin) . '</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>';
    }
}
