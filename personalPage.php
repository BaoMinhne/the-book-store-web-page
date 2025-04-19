<?php
include './config/db_connection.php';

session_start();
if (isset($_SESSION['username']) && $_SESSION['username'] !== '') {
} else {
    // Người dùng chưa đăng nhập hoặc không phải là admin, chuyển hướng hoặc xử lý tương ứng
    header('Location: ../index.php');
    // Kết thúc kịch bản sau khi chuyển hướng
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="../Shop_project/assets/css/base.css">
    <link rel="stylesheet" href="../Shop_project/assets/css/main.css">
    <link rel="stylesheet" href="../Shop_project/assets/css/personal.css">
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
                        <li class="header__navbar-item">
                            <a href="#" class="header__navbar-item-link">
                                <i class="header__navbar-icon fa-regular fa-circle-question"></i> Trợ giúp</a>
                        </li>

                        <!-- After Login -->
                        <div id="after-login__section" style="display: none;">
                            <li class="header__navbar-item header__navbar-user">
                                <img src="../Shop_project/assets/img/user-img/blank.jpg" alt="" class="header__navbar-user-img">
                                <span class="header__navbar-user-name">Khúc Bảo Minh</span>

                                <ul class="header__navbar-user-menu">

                                    <li class="header__navbar-user-item">
                                        <a href="./homepage.php">Trở về trang chủ</a>
                                    </li>

                                    <li class="header__navbar-user-item">
                                        <a href="#">Tài khoản của tôi</a>
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

                    <div class="header__search">
                        <div class="header__search-input-wrap">
                            <input type="text" class="header__search-input" placeholder="Nhập để tìm kiếm sản phẩm">

                            <!-- history search -->
                            <div class="header__search-history">
                                <h3 class="header__search-history-heading">Lịch sử tìm kiếm</h3>
                                <ul class="header__search-history-list">
                                    <li class="header__search-history-item">
                                        <a href="">Sách giáo khoa</a>
                                    </li>
                                    <li class="header__search-history-item">
                                        <a href="">Tiểu thuyết</a>
                                    </li>
                                    <li class="header__search-history-item">
                                        <a href="">Sách tham khảo</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <button class="header__search-btn">
                            <i class="header__search-btn-icon fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>

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
                                <i class="category__heading-icon fa-regular fa-user"></i>
                                Hồ Sơ Của Tôi
                            </h3>

                            <ul class="category-list">
                                <li class="category-item">
                                    <a href="#user-info__adding" class="category-item__link">Chỉnh sửa hồ sơ</a>
                                </li>

                                <li class="category-item">
                                    <a href="#user-info__viewing" class="category-item__link">Xem Hồ Sơ</a>
                                </li>

                                <li class="category-item">
                                    <a href="./homepage.php" class="category-item__link">Trở Về Trang Chủ</a>
                                </li>

                                <li class="category-item">
                                    <a href="./cart.php" class="category-item__link">Đơn Hàng Của Tôi</a>
                                </li>

                                <li class="category-item">
                                    <a href="./historyCart.php" class="category-item__link">Đơn Hàng Đã Mua</a>
                                </li>

                                <!-- <li class="category-item">
                                    <a href="homepage.php?category=truyen_tranh" class="category-item__link">Đổi Mật Khẩu</a>
                                </li> -->
                            </ul>
                        </nav>
                    </div>

                    <div class="grid__column-10 ">
                        <!-- grid -> row -> col -->
                        <div class="grid__row">
                            <div id="user-info__adding">
                                <div class="user-info__header">
                                    <h1 class="user-info__heading">Thêm Thông Tin Hồ Sơ</h1>
                                    <p class="user-info__title">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
                                </div>
                                <div class="user-info__form-container">
                                    <form action="./addUserInfo.php" method="post" class="user-info__form">
                                        <table>
                                            <tr>
                                                <td><label for="">Email</label></td>
                                                <td>
                                                    <div class="input-data">
                                                        <input type="email" name="Email" id="" placeholder="example@gmail.com">
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><label for="">Họ và Tên</label></td>
                                                <td>
                                                    <div class="input-data">
                                                        <input type="text" name="fullname" id="" placeholder="Nhập Họ Tên Của Bạn">
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><label for="">Địa chỉ</label></td>
                                                <td>
                                                    <div class="input-data">
                                                        <input type="text" name="add" id="" placeholder="Nhập địa chỉ của bạn">
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><label for="">Số Điện Thoại</label></td>
                                                <td>
                                                    <div class="input-data">
                                                        <input type="tel" name="phone" id="" placeholder="Nhập số điện thoại của bạn" class="phone-num">
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td></td>
                                                <td><input type="submit" value="Xác Nhận" class="user-add__submit"></td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>

                            <div id="user-info__viewing">
                                <div class="user-info__header">
                                    <h1 class="user-info__heading">Xem Thông tin Hồ Sơ</h1>
                                    <p class="user-info__title">Những thông tin chi tiết về hồ sơ của bạn</p>
                                </div>
                                <?php
                                include './viewUserInfo.php';
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
        // Hiển thị username
        document.querySelector(".header__navbar-user-name").textContent = username;
        console.log("Đăng nhập thành công");
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
</script>


</html>