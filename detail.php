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
        $genre = $row['genName'] ?? 'Sách';

        $purchaseDateRaw = $row['bookPurchaseDate'] ?? null;
        $purchaseDate = 'Đang cập nhật';
        if (!empty($purchaseDateRaw) && strtotime((string) $purchaseDateRaw) !== false) {
            $purchaseDate = date('d/m/Y', strtotime((string) $purchaseDateRaw));
        }

        $imageRaw = trim((string) ($row['bgURL'] ?? ''));
        $bgURL = $imageRaw !== '' ? $imageRaw : asset_url('assets/img/logo/logo2.png');

        $estimatedOldPrice = (int) round($productPrice * 1.15);
        $formattedOldPrice = number_format($estimatedOldPrice, 0, ',', '.');
        $discountPercent = $estimatedOldPrice > 0 ? max(0, (int) round((1 - ($productPrice / $estimatedOldPrice)) * 100)) : 0;

        $stockStatusLabel = $productQuantity > 0 ? 'Còn hàng' : 'Hết hàng';
        $stockClass = $productQuantity > 0 ? 'in-stock' : 'out-stock';
        $introText = '“' . $productName . '” là đầu sách thuộc thể loại ' . $genre . ', được phát hành bởi ' . $publisher . '. Phù hợp cho người đọc muốn mở rộng kiến thức và tận hưởng trải nghiệm đọc hiện đại.';

        echo '
        <div class="grid grid__bg">
            <div class="grid__row detail-layout">
                <div class="detail-gallery">
                    <div class="detail-product__img" style="background-image: url(' . htmlspecialchars($bgURL, ENT_QUOTES) . ');"></div>
                    <div class="detail-gallery__meta">
                        <span class="meta-chip"><i class="fa-solid fa-shield"></i> Sách chính hãng</span>
                        <span class="meta-chip"><i class="fa-solid fa-truck-fast"></i> Giao nhanh toàn quốc</span>
                        <span class="meta-chip"><i class="fa-solid fa-rotate-left"></i> Đổi trả 7 ngày</span>
                    </div>
                </div>

                <div class="detail-product__info modern-info-card">
                    <div class="detail-product__label">
                        <span class="detail-product__genre">' . htmlspecialchars($genre) . '</span>
                        <h1 class="detail-product__name">' . htmlspecialchars($productName) . '</h1>
                        <p class="detail-product__author-line">Tác giả: <strong>' . htmlspecialchars($authorName) . '</strong></p>
                    </div>

                    <div class="detail-product__condition">
                        <div class="detail-product__rating">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i>
                            <span>4.0 (128 đánh giá)</span>
                        </div>
                        <div class="detail-product__inventory ' . $stockClass . '">' . $stockStatusLabel . ' · ' . $productQuantity . ' cuốn</div>
                    </div>

                    <div class="detail-product__price">
                        <span class="detail-product__price-info">' . $formattedPrice . 'đ</span>
                        <span class="detail-product__old-price">' . $formattedOldPrice . 'đ</span>
                        <span class="detail-product__discount">-' . $discountPercent . '%</span>
                    </div>

                    <p class="detail-product__intro">' . htmlspecialchars($introText) . '</p>

                    <form action="./addToCart.php" method="post">
                        <div class="detail-product__add-quantity">
                            <h3 class="add-quantity__title">Số Lượng</h3>
                            <div class="add-quantity__button">
                                <button type="button" class="add-quantity__minus" onclick="decrement()">-</button>
                                <input type="hidden" name="productId" value="' . $bookID . '">
                                <input type="number" name="quantity" class="add-quantity__input" value="1" min="1" max="' . max(1, $productQuantity) . '">
                                <button type="button" class="add-quantity__plus" onclick="increment()">+</button>
                            </div>
                        </div>
                        <div class="detail-product__add-pro-btn">
                            <button type="submit" class="detail-product__add-cart-btn"><i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ hàng</button>
                            <a href="./cart.php" class="detail-product__buy-btn"><i class="fa-solid fa-credit-card"></i> Mua ngay</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
';

        echo '
        <div class="grid grid__bg detail-extra-wrapper">
            <div class="grid__row detail-extra-card">
                <div class="detail-product__origin-quantity-pub">
                    <h2>THÔNG TIN CHI TIẾT</h2>
                    <div class="detail-product__section">
                        <div class="detail-product__author"><label class="title">Tác Giả</label><div class="info">' . htmlspecialchars($authorName) . '</div></div>
                        <div class="detail-product__inventory"><label class="title">Kho Hàng</label><div class="info">' . $productQuantity . ' cuốn</div></div>
                        <div class="detail-prodcut__publish"><label class="title">Nhà Xuất Bản</label><div class="info">' . htmlspecialchars($publisher) . '</div></div>
                        <div class="detail-product__origin"><label class="title">Xuất Xứ</label><div class="info">' . htmlspecialchars($origin) . '</div></div>
                        <div class="detail-product__origin"><label class="title">Ngày nhập</label><div class="info">' . htmlspecialchars($purchaseDate) . '</div></div>
                        <div class="detail-product__origin"><label class="title">Mã sách</label><div class="info">#' . $bookID . '</div></div>
                    </div>
                </div>
            </div>
        </div>
';

        $relatedSql = "SELECT b.`{$bookIdCol}` AS bookID,
                              b.`{$bookNameCol}` AS bookName,
                              b.`{$bookPriceCol}` AS bookPrice,
                              b.`{$bookImageCol}` AS bgURL
                       FROM books b
                       WHERE b.`{$bookGenFkCol}` = (SELECT b2.`{$bookGenFkCol}` FROM books b2 WHERE b2.`{$bookIdCol}` = ? LIMIT 1)
                         AND b.`{$bookIdCol}` <> ?
                       LIMIT 4";

        $relatedStmt = $conn->prepare($relatedSql);
        $relatedStmt->bind_param('ii', $bookID, $bookID);
        $relatedStmt->execute();
        $relatedResult = $relatedStmt->get_result();

        if ($relatedResult && $relatedResult->num_rows > 0) {
            echo '<div class="grid grid__bg"><div class="grid__row related-books"><h2>Sản phẩm tương tự</h2><div class="related-books__list">';

            while ($related = $relatedResult->fetch_assoc()) {
                $relatedId = (int) ($related['bookID'] ?? 0);
                $relatedName = (string) ($related['bookName'] ?? 'Sách');
                $relatedPrice = number_format((int) ($related['bookPrice'] ?? 0), 0, ',', '.');
                $relatedImage = trim((string) ($related['bgURL'] ?? ''));
                $relatedImage = $relatedImage !== '' ? $relatedImage : asset_url('assets/img/logo/logo2.png');

                echo '<a class="related-book-card" href="./detailProduct.php?id=' . $relatedId . '">
                        <div class="related-book-card__img" style="background-image: url(' . htmlspecialchars($relatedImage, ENT_QUOTES) . ');"></div>
                        <h3>' . htmlspecialchars($relatedName) . '</h3>
                        <p>' . $relatedPrice . 'đ</p>
                      </a>';
            }

            echo '</div></div></div>';
        }
    }
}
