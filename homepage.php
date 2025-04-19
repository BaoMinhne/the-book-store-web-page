<?php
include './config/db_connection.php';

session_start();
if (!isset($_SESSION['username'])) {
    // Chuyển hướng người dùng đến trang đăng nhập
    header("Location: ./index.php");
    exit(); // Dừng kịch bản hiện tại
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="../Shop_project/assets/css/base.css">
    <link rel="stylesheet" href="../Shop_project/assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Shop_project/assets/fonts/fontawesome-free-6.5.1-web/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../Shop_project/assets/img/logo/4482549.jpg">
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
                                <img src="../Shop_project/assets/img/QR_code.png" alt="QR code" class="header__qr-img">
                                <div class="header__qr-apps">
                                    <a href="" class="header__qr-link">
                                        <img src="../Shop_project/assets/img/CH_play.png" alt="CH play" class="header__qr-download-img">
                                    </a>
                                    <a href="" class="header__qr-link">
                                        <img src="../Shop_project/assets/img/App_store.png" alt="App store" class="header__qr-download-img">
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
                                            <img src="../Shop_project/assets/img/SGK/tv1-cd.jpg" alt="" class="header__notify-img">
                                            <div class="header__notify-info">
                                                <span class="header__notify-name">Sách Giáo Khoa Tiêng Việt</span>
                                                <span class="header__notify-desc">Mô tả</span>
                                            </div>
                                        </a>
                                    </li>

                                    <li class="header__notify-item header__notify-item--viewed">
                                        <a href="" class="header__notify-link">
                                            <img src="../Shop_project/assets/img/SGK/t1-cd.jpg" alt="" class="header__notify-img">
                                            <div class="header__notify-info">
                                                <span class="header__notify-name">Sách Giáo Khoa Tiêng Việt</span>
                                                <span class="header__notify-desc">Mô tả</span>
                                            </div>
                                        </a>
                                    </li>

                                    <li class="header__notify-item">
                                        <a href="" class="header__notify-link">
                                            <img src="../Shop_project/assets/img/SGK/tnxh1-cd.jpg" alt="" class="header__notify-img">
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

                        <!-- Not Login -->
                        <div id="not-login__section" style="display: flex;">
                            <li class="header__navbar-item header__navbar-item-register header__navbar-item--strong header__navbar-item--separate ">Đăng ký</li>
                            <li class="header__navbar-item header__navbar-item-login header__navbar-item--strong">Đăng nhập</li>
                        </div>

                        <!-- After Login -->
                        <div id="after-login__section" style="display: none;">
                            <li class="header__navbar-item header__navbar-user">
                                <img src="../Shop_project/assets/img/user-img/blank.jpg" alt="" class="header__navbar-user-img">
                                <span class="header__navbar-user-name">Khúc Bảo Minh</span>

                                <ul class="header__navbar-user-menu">
                                    <li class="header__navbar-user-item">
                                        <a href="./personalPage.php">Tài khoản của tôi</a>
                                    </li>

                                    <li class="header__navbar-user-item">
                                        <a href="./cart.php">Đơn mua</a>
                                    </li>

                                    <li class="header__navbar-user-item header__navbar-user-item--seperate">
                                        <a href="../Shop_project/config/logout.php" onclick="return confirmLogOut();">Đăng xuất</a>
                                    </li>
                                </ul>
                            </li>
                        </div>
                    </ul>
                </nav>

                <!--header search -->
                <div class="header-with-search">
                    <div class="header__logo">
                        <!-- chưa hoàn thiện -->
                        <a href="./homepage.php" class="header__logo-link">
                            <img src="../Shop_project/assets/img/logo/logotest1.png" alt="" class="header__logo-img">
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
                                <img src="../Shop_project/assets/img/no-cart.png" alt="" class="header__cart-no-cart-img">
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

                            <ul class="category-list">
                                <li class="category-item">
                                    <a href="homepage.php?category=all" class="category-item__link">Sản phẩm</a>
                                </li>

                                <li class="category-item">
                                    <a href="homepage.php?category=sach_giao_khoa" class="category-item__link">Sách giáo khoa</a>
                                </li>

                                <li class="category-item">
                                    <a href="homepage.php?category=tieu_thuyet" class="category-item__link">Tiểu thuyết</a>
                                </li>

                                <li class="category-item">
                                    <a href="homepage.php?category=truyen_tranh" class="category-item__link">Truyện tranh</a>
                                </li>

                                <li class="category-item">
                                    <a href="homepage.php?category=kinh_doanh" class="category-item__link">Kinh doanh</a>
                                </li>

                                <li class="category-item">
                                    <a href="homepage.php?category=khoa_hoc" class="category-item__link">Khoa học</a>
                                </li>

                                <li class="category-item">
                                    <a href="homepage.php?category=giao_trinh" class="category-item__link">Giáo trình</a>
                                </li>

                                <li class="category-item">
                                    <a href="homepage.php?category=y_hoc" class="category-item__link">Y học</a>
                                </li>

                                <li class="category-item">
                                    <a href="homepage.php?category=tham_khao" class="category-item__link">Sách tham khảo</a>
                                </li>

                                <li class="category-item">
                                    <a href="homepage.php?category=cong_nghe" class="category-item__link">Công nghệ</a>
                                </li>

                                <li class="category-item">
                                    <a href="homepage.php?category=lich_su" class="category-item__link">Lịch sử</a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <div class="grid__column-10 ">
                        <div class="home-filter">
                            <span class="home-filter__label">Sắp xếp theo</span>
                            <!-- <button class="home-filter__btn btn">Phổ biến</button>
                            <button class="home-filter__btn btn btn--primary">Mới nhất</button>
                            <button class="home-filter__btn btn">Bán chạy</button> -->

                            <div class="select-input">
                                <span class="select-input__label">Giá</span>
                                <i class="select-input__icon fa-solid fa-angle-down"></i>
                                <!-- list options -->
                                <ul class="select-input__list">
                                    <li class="select-input__item">
                                        <span class="select-input__link">
                                            <a href="homepage.php?category=large_to_small">Giá: Cao đến Thấp</a>
                                        </span>
                                    </li>

                                    <li class="select-input__item">
                                        <span class="select-input__link">
                                            <a href="homepage.php?category=small_to_large">Giá: Thấp đến cao</a>
                                        </span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Thanh điều hướng -->
                            <!-- <div class="home-filter__page">
                                <span class="home-filter__page-num">
                                    <span class="home-filter__page-current">1</span>/14
                                </span>

                                <div class="home-filter__page-control">
                                    <a href="" class="home-filter__page-btn home-filter__page-btn--disabled">
                                        <i class="home-filter__page-icon fa-solid fa-angle-left"></i>
                                    </a>
                                    <a href="" class="home-filter__page-btn">
                                        <i class="home-filter__page-icon fa-solid fa-angle-right"></i>
                                    </a>
                                </div>
                            </div> -->
                        </div>

                        <div class="home-product">
                            <!-- grid -> row -> col -->
                            <div class="grid__row">
                                <?php
                                include './catetory_product-list.php'
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


</body>



<!-- After Login Logic -->
<script>
    var username = "<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>";

    if (username !== '' && username != 'admin') {
        // Hiện phần after-login__section
        document.getElementById("after-login__section").style.display = "flex";
        // Ẩn phần not-login__section
        document.getElementById("not-login__section").style.display = "none";
        // Hiển thị username
        document.querySelector(".header__navbar-user-name").textContent = username;
        console.log("Đăng nhập thành công");
    } else {
        // Người dùng chưa đăng nhập, hiển thị phần not-login__section và ẩn phần after-login__section
        document.getElementById("not-login__section").style.display = "flex";
        document.getElementById("after-login__section").style.display = "none";
        console.log('Người dùng chưa đăng nhập');
    }

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

    const container = document.querySelector('.app__container');
    container.addEventListener('click', function() {
        document.querySelector('.header__search-history').style.display = 'none';
    })
</script>

<script src="./search.js">
</script>

</html>