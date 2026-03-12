<?php
include './config/db_connection.php';
include './config/url_helper.php';

session_start();
if (!isset($_SESSION['username'])) {
    // Chuyển hướng người dùng đến trang đăng nhập
    header("Location: ./index.php");
    exit(); // Dừng kịch bản hiện tại
}

$debugMode = isset($_GET['debug']) && $_GET['debug'] === '1';
$productRenderOutput = '';
$productRenderErrors = [];
$catalogDebug = [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="<?= asset_url('assets/css/base.css'); ?>">
    <link rel="stylesheet" href="<?= asset_url('assets/css/main.css'); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset_url('assets/fonts/fontawesome-free-6.5.1-web/css/all.min.css'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= asset_url('assets/img/logo/4482549.jpg'); ?>">
    <title>BookLand</title>
</head>

<body>
    <div class="main">
        <header class="header">
            <div class="grid">
                <nav class="header__navbar">
                    <ul class="header__navbar-list">
                        <li class="header__navbar-item header__navbar-item--has-qr header__navbar-item--separate">
                            Vào cửa hàng trên ứng dụng
                            <!-- QR code  -->
                            <div class="header__qr">
                                <img src="<?= asset_url('assets/img/QR_code.png'); ?>" alt="QR code" class="header__qr-img">
                                <div class="header__qr-apps">
                                    <a href="" class="header__qr-link">
                                        <img src="<?= asset_url('assets/img/CH_play.png'); ?>" alt="CH play" class="header__qr-download-img">
                                    </a>
                                    <a href="" class="header__qr-link">
                                        <img src="<?= asset_url('assets/img/App_store.png'); ?>" alt="App store" class="header__qr-download-img">
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="header__navbar-item">
                            <span class="header__navbar-title--no-pointer">Kết nối</span>
                            <a href="https://www.facebook.com/" class="header__navbar-icon-link">
                                <i class="header__navbar-icon fa-brands fa-facebook"></i>
                            </a>
                            <a href="https://www.instagram.com/" class="header__navbar-icon-link">
                                <i class="header__navbar-icon fa-brands fa-instagram"></i>
                            </a>
                        </li>
                    </ul>

                    <ul class="header__navbar-list">
                        <!-- <li class="header__navbar-item header__navbar-item--has-notifi">
                            <a href="#" class="header__navbar-item-link">
                                <i class="header__navbar-icon fa-regular fa-bell"></i>
                                Thông báo
                            </a>
                            <div class="header__notify">
                                <header class="header__notify-header">
                                    <h3>Thông báo mới nhận</h3>
                                </header>
                                <ul class="header__notify-list">
                                    <li class="header__notify-item header__notify-item--viewed">
                                        <a href="" class="header__notify-link">
                                            <img src="<?= asset_url('assets/img/SGK/tv1-cd.jpg'); ?>" alt="" class="header__notify-img">
                                            <div class="header__notify-info">
                                                <span class="header__notify-name">Sách Giáo Khoa Tiêng Việt</span>
                                                <span class="header__notify-desc">Mô tả</span>
                                            </div>
                                        </a>
                                    </li>

                                    <li class="header__notify-item header__notify-item--viewed">
                                        <a href="" class="header__notify-link">
                                            <img src="<?= asset_url('assets/img/SGK/t1-cd.jpg'); ?>" alt="" class="header__notify-img">
                                            <div class="header__notify-info">
                                                <span class="header__notify-name">Sách Giáo Khoa Tiêng Việt</span>
                                                <span class="header__notify-desc">Mô tả</span>
                                            </div>
                                        </a>
                                    </li>

                                    <li class="header__notify-item">
                                        <a href="" class="header__notify-link">
                                            <img src="<?= asset_url('assets/img/SGK/tnxh1-cd.jpg'); ?>" alt="" class="header__notify-img">
                                            <div class="header__notify-info">
                                                <span class="header__notify-name">Sách Giáo Khoa Tiêng Việt</span>
                                                <span class="header__notify-desc">Mô tả</span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                                <footer class="header__notify-footer">
                                    <a href="" class="header__notify-footer-btn">
                                        xem tất cả
                                    </a>
                                </footer>
                            </div>
                        </li> -->
                        <li class="header__navbar-item">
                            <a href="#" class="header__navbar-item-link">
                                <i class="header__navbar-icon fa-regular fa-circle-question"></i> Trợ giúp</a>
                        </li>

                        <li class="header__navbar-item header__navbar-user">
                            <img src="<?= asset_url('assets/img/user-img/blank.jpg'); ?>" alt="" class="header__navbar-user-img">
                            <span class="header__navbar-user-name"><?= htmlspecialchars($_SESSION['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>

                            <ul class="header__navbar-user-menu">
                                <li class="header__navbar-user-item">
                                    <a href="./personalPage.php">Tài khoản của tôi</a>
                                </li>

                                <li class="header__navbar-user-item">
                                    <a href="./cart.php">Đơn mua</a>
                                </li>

                                <li class="header__navbar-user-item header__navbar-user-item--seperate">
                                    <a href="<?= asset_url('config/logout.php'); ?>" onclick="return confirmLogOut();">Đăng xuất</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>

                <!--header search -->
                <div class="header-with-search">
                    <div class="header__logo">
                        <!-- chưa hoàn thiện -->
                        <a href="./homepage.php" class="header__logo-link">
                            <img src="<?= asset_url('assets/img/logo/logotest1.png'); ?>" alt="" class="header__logo-img">
                        </a>
                    </div>

                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="search__form-container">
                        <div class="header__search">
                            <div class="header__search-input-wrap">
                                <input type="text" name="bookName" id="header__search-input" class="header__search-input" placeholder="Nhập để tìm kiếm sản phẩm" onkeyup="search(); showResultSearch();">

                                <!-- history search -->
                                <div class="header__search-history">
                                    <h3 class="header__search-history-heading">Kết quả tìm kiếm</h3>
                                    <ul class="header__search-history-list" id="header__search-list">
                                        <!-- <li class="header__search-history-item">
                                                <a href="www.facebook.com">Sách giáo khoa</a>
                                            </li> -->
                                    </ul>
                                </div>
                            </div>
                            <button class="header__search-btn" type="submit">
                                <i class="header__search-btn-icon fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Cart -->
                    <div class="header__cart">
                        <div class="header__cart-wrap">
                            <i class="header__cart-icon fa-solid fa-cart-shopping"></i>
                            <!-- No cart: header__cart-list--no-cart -->
                            <div class="header__cart-list">
                                <img src="<?= asset_url('assets/img/no-cart.png'); ?>" alt="" class="header__cart-no-cart-img">
                                <span class="header__cart-list-no-cart-msg">Chưa có sản phẩm</span>

                                <h3 class="header__cart-heading">
                                    Sản Phẩm đã thêm
                                </h3>
                                <ul class="header__cart-list-item">
                                    <!-- Cart item -->
                                    <?php include './minicart.php' ?>
                                </ul>
                                <a href="./cart.php" class="header__cart-view-cart btn btn--primary">Xem giỏ hàng</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="app__container">
            <div class="grid">
                <div class="grid__row app__content">
                    <div class="grid__column-2">
                        <nav class="category">
                            <h3 class="category__heading">
                                <i class="category__heading-icon fa-solid fa-list-ul"></i>
                                Danh mục
                            </h3>

                            <?php
                            $currentCategory = $_GET['category'] ?? 'all';
                            $currentSort = $_GET['sort'] ?? 'default';
                            $currentStock = $_GET['stock'] ?? 'all';
                            $currentPriceRange = $_GET['price_range'] ?? 'all';
                            $filterQueryBase = [
                                'sort' => $currentSort,
                                'stock' => $currentStock,
                                'price_range' => $currentPriceRange,
                            ];
                            ?>
                            <ul class="category-list">
                                <?php
                                $categoryItems = [
                                    'all' => 'Sản phẩm',
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

                                foreach ($categoryItems as $categoryKey => $categoryLabel):
                                    $categoryLink = 'homepage.php?' . http_build_query(array_merge($filterQueryBase, ['category' => $categoryKey]));
                                ?>
                                    <li class="category-item">
                                        <a href="<?= htmlspecialchars($categoryLink, ENT_QUOTES, 'UTF-8'); ?>" class="category-item__link <?= $currentCategory === $categoryKey ? 'category-item__link--active' : ''; ?>">
                                            <?= htmlspecialchars($categoryLabel, ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </nav>
                    </div>

                    <div class="grid__column-10 ">
                        <div class="home-filter">
                            <div class="home-filter__top">
                                <span class="home-filter__title">Bộ lọc sản phẩm</span>
                                <a class="home-filter__reset" href="homepage.php?category=<?= urlencode($currentCategory); ?>">Đặt lại filter</a>
                            </div>

                            <form class="home-filter__form" method="GET" action="homepage.php">
                                <input type="hidden" name="category" value="<?= htmlspecialchars($currentCategory, ENT_QUOTES, 'UTF-8'); ?>">

                                <div class="home-filter__group">
                                    <label class="home-filter__group-label" for="sort-select">Sắp xếp</label>
                                    <select id="sort-select" class="home-filter__select" name="sort">
                                        <option value="default" <?= $currentSort === 'default' ? 'selected' : ''; ?>>Mặc định</option>
                                        <option value="newest" <?= $currentSort === 'newest' ? 'selected' : ''; ?>>Mới nhất</option>
                                        <option value="price_asc" <?= $currentSort === 'price_asc' ? 'selected' : ''; ?>>Giá tăng dần</option>
                                        <option value="price_desc" <?= $currentSort === 'price_desc' ? 'selected' : ''; ?>>Giá giảm dần</option>
                                        <option value="name_asc" <?= $currentSort === 'name_asc' ? 'selected' : ''; ?>>Tên A-Z</option>
                                        <option value="name_desc" <?= $currentSort === 'name_desc' ? 'selected' : ''; ?>>Tên Z-A</option>
                                    </select>
                                </div>

                                <div class="home-filter__group">
                                    <label class="home-filter__group-label" for="stock-select">Tình trạng</label>
                                    <select id="stock-select" class="home-filter__select" name="stock">
                                        <option value="all" <?= $currentStock === 'all' ? 'selected' : ''; ?>>Tất cả</option>
                                        <option value="in_stock" <?= $currentStock === 'in_stock' ? 'selected' : ''; ?>>Còn hàng</option>
                                        <option value="low_stock" <?= $currentStock === 'low_stock' ? 'selected' : ''; ?>>Sắp hết hàng (≤10)</option>
                                        <option value="out_of_stock" <?= $currentStock === 'out_of_stock' ? 'selected' : ''; ?>>Hết hàng</option>
                                    </select>
                                </div>

                                <div class="home-filter__group">
                                    <label class="home-filter__group-label" for="price-range-select">Khoảng giá</label>
                                    <select id="price-range-select" class="home-filter__select" name="price_range">
                                        <option value="all" <?= $currentPriceRange === 'all' ? 'selected' : ''; ?>>Tất cả mức giá</option>
                                        <option value="under_100k" <?= $currentPriceRange === 'under_100k' ? 'selected' : ''; ?>>Dưới 100.000đ</option>
                                        <option value="100k_300k" <?= $currentPriceRange === '100k_300k' ? 'selected' : ''; ?>>100.000đ - 300.000đ</option>
                                        <option value="300k_500k" <?= $currentPriceRange === '300k_500k' ? 'selected' : ''; ?>>300.000đ - 500.000đ</option>
                                        <option value="over_500k" <?= $currentPriceRange === 'over_500k' ? 'selected' : ''; ?>>Trên 500.000đ</option>
                                    </select>
                                </div>

                                <button class="home-filter__submit" type="submit">Áp dụng</button>
                            </form>

                            <div class="home-filter__chips">
                                <?php
                                $quickFilters = [
                                    'con_hang' => ['label' => 'Còn hàng', 'params' => ['stock' => 'in_stock']],
                                    'gia_tot' => ['label' => 'Dưới 100k', 'params' => ['price_range' => 'under_100k']],
                                    'moi_nhat' => ['label' => 'Mới nhất', 'params' => ['sort' => 'newest']],
                                ];

                                foreach ($quickFilters as $quickKey => $quickConfig):
                                    $chipParams = array_merge([
                                        'category' => $currentCategory,
                                        'sort' => $currentSort,
                                        'stock' => $currentStock,
                                        'price_range' => $currentPriceRange,
                                    ], $quickConfig['params']);
                                    $chipHref = 'homepage.php?' . http_build_query($chipParams);
                                    $isChipActive = true;
                                    foreach ($quickConfig['params'] as $paramKey => $paramValue) {
                                        if (($_GET[$paramKey] ?? null) !== $paramValue) {
                                            $isChipActive = false;
                                            break;
                                        }
                                    }
                                ?>
                                    <a href="<?= htmlspecialchars($chipHref, ENT_QUOTES, 'UTF-8'); ?>" class="home-filter__chip <?= $isChipActive ? 'home-filter__chip--active' : ''; ?>">
                                        <?= htmlspecialchars($quickConfig['label'], ENT_QUOTES, 'UTF-8'); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="home-product">
                            <!-- grid -> row -> col -->
                            <div class="grid__row">
                                <?php
                                if ($debugMode) {
                                    set_error_handler(static function ($severity, $message, $file, $line) use (&$productRenderErrors): bool {
                                        $productRenderErrors[] = "[{$severity}] {$message} at {$file}:{$line}";

                                        return false;
                                    });
                                }

                                ob_start();
                                include './catetory_product-list.php';
                                $productRenderOutput = ob_get_clean();

                                if ($debugMode) {
                                    restore_error_handler();
                                }

                                $catalogDebug = $GLOBALS['catalogDebug'] ?? [];
                                echo $productRenderOutput;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="grid">
                <div class="grid__row">

                </div>
            </div>
        </footer>
    </div>



    <?php if ($debugMode): ?>
        <div style="position:fixed;right:12px;bottom:12px;z-index:99999;background:#111;color:#fff;max-width:520px;max-height:45vh;overflow:auto;padding:12px;border-radius:8px;font-size:12px;line-height:1.5;box-shadow:0 6px 18px rgba(0,0,0,.35);">
            <strong>[DEBUG homepage.php]</strong><br>
            User: <?= htmlspecialchars((string) ($_SESSION['username'] ?? ''), ENT_QUOTES, 'UTF-8'); ?><br>
            Product HTML length: <?= strlen($productRenderOutput); ?><br>
            PHP include warnings: <?= count($productRenderErrors); ?><br>
            SQL error: <?= htmlspecialchars((string) ($catalogDebug['sql_error'] ?? 'none'), ENT_QUOTES, 'UTF-8'); ?><br>
            Rows: <?= htmlspecialchars((string) ($catalogDebug['num_rows'] ?? 'null'), ENT_QUOTES, 'UTF-8'); ?><br>
            <?php if (!empty($productRenderErrors)): ?>
                <pre style="white-space:pre-wrap;color:#ffb4b4;"><?= htmlspecialchars(implode("\n", $productRenderErrors), ENT_QUOTES, 'UTF-8'); ?></pre>
            <?php endif; ?>
            <?php if (!empty($catalogDebug)): ?>
                <pre style="white-space:pre-wrap;color:#9f9;"><?= htmlspecialchars(json_encode($catalogDebug, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8'); ?></pre>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>




<!-- After Login Logic -->
<script>
    function confirmLogOut() {
        return window.confirm("Bạn Có Muốn Đăng Xuất Hay Không???");
    }

    function confirmDelete() {
        return window.confirm("Bạn Có Chắc Muốn Xoá Sản Phẩm Hay Không???");
    }

    document.addEventListener("DOMContentLoaded", function() {
        var cartList = document.querySelector('.header__cart-list');
        var cartItems = document.querySelectorAll('.header__cart-list-item li');
        var cartHeading = document.querySelector('.header__cart-heading');
        var cartBtn = document.querySelector('.header__cart-view-cart');
        // Nếu giỏ hàng không có sản phẩm, thêm class header__cart-list--no-cart
        if (cartItems.length === 0) {
            cartList.classList.add('header__cart-list--no-cart');
            cartHeading.style.display = 'none'; // Ẩn tiêu đề
            cartBtn.style.display = 'none';
        } else {
            cartList.classList.remove('header__cart-list--no-cart');
        }
    });

    <?php if ($debugMode): ?>
    window.addEventListener('error', function(event) {
        console.error('[DEBUG][window.error]', {
            message: event.message,
            source: event.filename,
            line: event.lineno,
            column: event.colno,
            stack: event.error ? event.error.stack : null
        });
    });

    window.addEventListener('unhandledrejection', function(event) {
        console.error('[DEBUG][unhandledrejection]', event.reason);
    });

    console.warn('[DEBUG] Nếu bạn thấy lỗi "Unchecked runtime.lastError...", thường là từ extension trình duyệt, không phải PHP app.');
    const phpDebug = <?= json_encode($catalogDebug, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
    console.log('[DEBUG][PHP catalogDebug]', phpDebug);
    <?php endif; ?>

    const container = document.querySelector('.app__container');
    container.addEventListener('click', function() {
        document.querySelector('.header__search-history').style.display = 'none';
    })
</script>

<script src="./search.js">
</script>

</html>