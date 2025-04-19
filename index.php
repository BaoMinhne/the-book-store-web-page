<?php
session_start();
include './config/db_connection.php';
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
                        <!-- <div id="after-login__section" style="display: none;">
                            <li class="header__navbar-item header__navbar-user">
                                <img src="../Shop_project/assets/img/user-img/blank.jpg" alt="" class="header__navbar-user-img">
                                <span class="header__navbar-user-name">Khúc Bảo Minh</span>

                                <ul class="header__navbar-user-menu">
                                    <li class="header__navbar-user-item">
                                        <a href="">Tài khoản của tôi</a>
                                    </li>

                                    <li class="header__navbar-user-item">
                                        <a href="">Đơn mua</a>
                                    </li>

                                    <li class="header__navbar-user-item header__navbar-user-item--seperate">
                                        <a href="../Shop_project/config/logout.php">Đăng xuất</a>
                                    </li>
                                </ul>
                            </li>
                        </div> -->
                    </ul>
                </nav>

                <!--header search -->
                <div class="header-with-search">
                    <div class="header__logo">
                        <!-- chưa hoàn thiện -->
                        <a href="./index.php" class="header__logo-link">
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
                            <!-- <span class="header__cart-notice">3</span> -->
                            <!-- No cart: header__cart-list--no-cart -->
                            <div class="header__cart-list header__cart-list--no-cart">
                                <img src="../Shop_project/assets/img/no-cart.png" alt="" class="header__cart-no-cart-img">
                                <span class="header__cart-list-no-cart-msg">Chưa có sản phẩm</span>
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
                                    <a href="index.php?category=all" class="category-item__link">Sản phẩm</a>
                                </li>

                                <li class="category-item">
                                    <a href="index.php?category=sach_giao_khoa" class="category-item__link">Sách giáo khoa</a>
                                </li>

                                <li class="category-item">
                                    <a href="index.php?category=tieu_thuyet" class="category-item__link">Tiểu thuyết</a>
                                </li>

                                <li class="category-item">
                                    <a href="index.php?category=truyen_tranh" class="category-item__link">Truyện tranh</a>
                                </li>

                                <li class="category-item">
                                    <a href="index.php?category=kinh_doanh" class="category-item__link">Kinh doanh</a>
                                </li>

                                <li class="category-item">
                                    <a href="index.php?category=khoa_hoc" class="category-item__link">Khoa học</a>
                                </li>

                                <li class="category-item">
                                    <a href="index.php?category=giao_trinh" class="category-item__link">Giáo trình</a>
                                </li>

                                <li class="category-item">
                                    <a href="index.php?category=y_hoc" class="category-item__link">Y học</a>
                                </li>

                                <li class="category-item">
                                    <a href="index.php?category=tham_khao" class="category-item__link">Sách tham khảo</a>
                                </li>

                                <li class="category-item">
                                    <a href="index.php?category=cong_nghe" class="category-item__link">Công nghệ</a>
                                </li>

                                <li class="category-item">
                                    <a href="index.php?category=lich_su" class="category-item__link">Lịch sử</a>
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
                                            <a href="index.php?category=large_to_small">Giá: Cao đến Thấp</a>
                                        </span>
                                    </li>

                                    <li class="select-input__item">
                                        <span class="select-input__link">
                                            <a href="index.php?category=small_to_large">Giá: Thấp đến cao</a>
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
                                <!-- product item -->
                                <?php
                                include './catetory_product-list.php';
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

    <!-- Modal Layout -->
    <div class="modal">
        <div class="modal__overlay"></div>
        <div class="modal__body">
            <!-- Register Form -->
            <form action="../Shop_project/config/register.php" method="POST">
                <div class="auth-form auth-form-register ">
                    <div class="auth-form__container">
                        <div class="auth-form__header">
                            <h3 class="auth-form__heading">
                                Đăng Ký
                            </h3>
                            <span class="auth-form__switch-btn auth-form__switch-btn--login">
                                Đăng nhập
                            </span>
                        </div>

                        <div class="auth-form__form">
                            <div class="auth-form__group">
                                <input type="text" class="auth-form__input" name="u_name_re" placeholder="Chọn tên đăng nhập">
                            </div>

                            <div class="auth-form__group">
                                <input type="password" class="auth-form__input" name="u_pass_re" placeholder="Mật khẩu của bạn">
                            </div>

                            <div class="auth-form__group">
                                <input type="password" name="u_passcf_re" class="auth-form__input" placeholder="Nhập lại mật khẩu">
                            </div>
                        </div>

                        <div class="auth-form__aside">
                            <p class="auth-form__policy-text">
                                Bằng việc đăng kí, bạn đã đồng ý với SHOP về
                                <a href="" class="auth-form__text-link">Điều khoản dịch vụ</a> &
                                <a href="" class="auth-form__text-link">Chính sách bảo mật</a>
                            </p>
                        </div>

                        <div class="auth-form__controls">
                            <button class="btn btn--normal auth-form__controls-back">TRỞ LẠI</button>
                            <button type="submit" class="btn btn--primary">ĐĂNG KÝ</button>
                        </div>
                    </div>

                    <div class="auth-form__socials">
                        <a href="#" class="auth-form__socials--facebook btn btn--size-s btn--with-icon">
                            <i class="auth-form__socials-icon fa-brands fa-square-facebook"></i>
                            <span class="auth-form__socials-title">Kết nối với Facebook </span>
                        </a>

                        <a href="#" class="auth-form__socials--google btn btn--size-s btn--with-icon">
                            <i class="auth-form__socials-icon fa-brands fa-google"></i>
                            <span class="auth-form__socials-title">Kết nối với Google </span>
                        </a>
                    </div>
                </div>
            </form>

            <!-- Login Form -->
            <form action="../Shop_project/config/login.php" method="POST">
                <div class="auth-form auth-form-login open">
                    <div class="auth-form__container">
                        <div class="auth-form__header">
                            <h3 class="auth-form__heading">
                                Đăng Nhập
                            </h3>
                            <span class="auth-form__switch-btn auth-form__switch-btn--register">
                                Đăng ký
                            </span>
                        </div>

                        <div class="auth-form__form">
                            <div class="auth-form__group">
                                <input type="text" name="u_name" class="auth-form__input" placeholder="Tên đăng nhập">
                            </div>

                            <div class="auth-form__group">
                                <input type="password" name="u_pass" class="auth-form__input" placeholder="Mật khẩu của bạn">
                            </div>

                            <span class="invalid_passwd">

                            </span>

                        </div>

                        <div class="auth-form__aside">
                            <div class="auth-form__help">
                                <a href="#" class="auth-form__help-link auth-form__help-forgot">Quên mật khẩu</a>
                                <span class="auth-form__help-seperate"></span>
                                <a href="#" class="auth-form__help-link">Cần trợ giúp?</a>
                            </div>
                        </div>

                        <div class="auth-form__controls">
                            <button class="btn btn--normal auth-form__controls-back">TRỞ LẠI</button>
                            <button type="submit" class="btn btn--primary">ĐĂNG NHẬP</button>
                        </div>
                    </div>

                    <div class="auth-form__socials">
                        <a href="" class="auth-form__socials--facebook btn btn--size-s btn--with-icon">
                            <i class="auth-form__socials-icon fa-brands fa-square-facebook"></i>
                            <span class="auth-form__socials-title">Kết nối với Facebook </span>
                        </a>

                        <a href="" class="auth-form__socials--google btn btn--size-s btn--with-icon">
                            <i class="auth-form__socials-icon fa-brands fa-google"></i>
                            <span class="auth-form__socials-title">Kết nối với Google </span>
                        </a>
                    </div>
                </div>
            </form>

        </div>
    </div>
</body>

<!-- Modal Logic -->
<script>
    //Khai Bao Bien
    const modal = document.querySelector('.modal');
    const registerBtn = document.querySelector('.header__navbar-item-register');
    const overlay = document.querySelector('.modal__overlay');
    const register = document.querySelector('.auth-form-register');
    const backBtns = document.querySelectorAll('.auth-form__controls-back');
    const loginBtn = document.querySelector('.header__navbar-item-login');
    const login = document.querySelector('.auth-form-login');
    const switchBtnToLogin = document.querySelector('.auth-form__switch-btn--login');
    const switchBtnToRegister = document.querySelector('.auth-form__switch-btn--register');

    //Hien Form ĐK
    function showForm() {
        register.classList.add('open');
        overlay.classList.add('open');
        modal.classList.add('open');
        login.classList.remove('open');
    }

    //Hien Form DN
    function showLogIn() {
        modal.classList.add('open');
        overlay.classList.add('open');
        login.classList.add('open');
    }

    //An Form
    function hideForm() {
        register.classList.remove('open');
        overlay.classList.remove('open');
        modal.classList.remove('open');
    }

    //Cac Su Kien
    overlay.addEventListener('click', hideForm);
    registerBtn.addEventListener('click', showForm);
    loginBtn.addEventListener('click', showLogIn);

    for (const backBtn of backBtns) {
        backBtn.addEventListener('click', hideForm);
    }

    function switchToLogin() {
        register.classList.remove('open');
        showLogIn();
    }

    switchBtnToLogin.addEventListener('click', switchToLogin);
    switchBtnToRegister.addEventListener('click', showForm);

    document.querySelector('.app__container').addEventListener('click', function() {
        document.querySelector('.header__search-history').style.display = 'none';
    })
</script>

<script src="./search.js"></script>





</html>