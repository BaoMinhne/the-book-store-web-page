<?php
include  '../config/db_connection.php';
session_start();
ob_start();

if (!isset($_SESSION['username'])) {
    // Chuyển hướng người dùng đến trang đăng nhập
    echo '
    <script>
        alert("Bạn Phải Đăng Nhập Trước");
        window.location.href = "../index.php";
    </script>
    ';
    exit(); // Dừng kịch bản hiện tại

} else if ($_SESSION['username'] !== 'admin') {
    echo '
        <script>
            alert("Bạn Phải Dùng Quyền Admin Để Truy Cập");
            window.location.href = "../homepage.php";
        </script>';
    exit(); // Dừng kịch bản hiện tại
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
        <link rel="stylesheet" href="../assets/css/base.css">
        <link rel="stylesheet" href="../assets/css/main.css">
        <link rel="stylesheet" href="../assets/css/admin.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../assets/fonts/fontawesome-free-6.5.1-web/css/all.min.css">
        <link rel="icon" href="../assets/img/logo/4482549.jpg">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                                    <img src="../assets/img/QR_code.png" alt="QR code" class="header__qr-img">
                                    <div class="header__qr-apps">
                                        <a href="" class="header__qr-link">
                                            <img src="../assets/img/CH_play.png" alt="CH play" class="header__qr-download-img">
                                        </a>
                                        <a href="" class="header__qr-link">
                                            <img src="../assets/img/App_store.png" alt="App store" class="header__qr-download-img">
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="header__navbar-item">
                                <span class="header__navbar-title--no-pointer">Kết nối</span>
                                <a href="" class="header__navbar-icon-link">
                                    <i class="header__navbar-icon fa-brands fa-facebook"></i>
                                </a>
                                <a href="" class="header__navbar-icon-link">
                                    <i class="header__navbar-icon fa-brands fa-instagram"></i>
                                </a>
                            </li>
                        </ul>


                        <li class="header__navbar-item">
                            <a href="#" class="header__navbar-item-link">
                                <i class="header__navbar-icon fa-regular fa-circle-question"></i> Trợ giúp</a>
                        </li>

                        <!-- Not Login -->
                        <div id="not-login__section" style="display: none;">
                            <li class="header__navbar-item header__navbar-item-register header__navbar-item--strong header__navbar-item--separate ">Đăng ký</li>
                            <li class="header__navbar-item header__navbar-item-login header__navbar-item--strong">Đăng nhập</li>
                        </div>

                        <!-- After Login -->
                        <div id="after-login__section" style="display: flex;">
                            <li class="header__navbar-item header__navbar-user">
                                <img src="../assets/img/admin.png" alt="" class="header__navbar-user-img">
                                <span class="header__navbar-user-name"></span>

                                <ul class="header__navbar-user-menu">
                                    <li class="header__navbar-user-item header__navbar-user-item--seperate">
                                        <a href="../config/logout.php">Đăng xuất</a>
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
                            <a href="#" class="header__logo-link">
                                <img src="../assets/img/logo/logotest1.png" alt="" class="header__logo-img">
                            </a>
                        </div>
                        <div class="header__task">
                            <ul class="header__task-list">
                                <li class="header__task-item"><a href="../admin.php" class="header__task-link">Trở Lại</a></li>
                                <li class="header__task-item"><a href="../controller/addProduct.php" class="header__task-link">Thêm Sản Phẩm</a></li>
                                <li class="header__task-item"><a href="../controller/upProduct.php" class="header__task-link">Cập Nhật Sản Phẩm</a></li>
                                <li class="header__task-item"><a href="../controller/delProduct.php" class="header__task-link">Xoá Sản Phẩm</a></li>
                            </ul>
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
                                    Chức năng
                                </h3>

                                <ul class="category-list">
                                    <li class="category-item">
                                        <a href="#" class="category-item__link category-item__link--active js-view-all">Xem tất cả sản phẩm</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        <div class="grid__column-10 ">
                            <div class="Application-view">
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="gen__book-form-container">
                                    <input type="text" name="genBook" class="gen__book-form-input ipt" placeholder="Nhập Loại Sách">
                                    <!-- <span class="js-alert"></span> -->
                                    <button type="submit" class="gen__book-form-submit sbt">
                                        <!-- gen__book-form-submit--disabled -->
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                    <button type="submit" class="gen__book-form-view-all sbt">Xem Tất Cả</button>
                                </form>

                                <table id='genTable' class="genBook__table">
                                    <tr>
                                        <th class="genBook__table-STT">Mã Sách</th>
                                        <th class="genBook__table-name">Tên Sách</th>
                                        <th class="genBook__table-price">Đơn Giá</th>
                                        <th class="genBook__table-amount">Số Lượng</th>
                                        <th class="genBook__table-type">Thể Loại</th>
                                    </tr>

                                    <?php
                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                        // Gọi thủ tục
                                        $viewBook = "p_view_gen_books";
                                        if (isset($_POST['genBook'])) {
                                            // Quy định người dùng nhập vào là chuỗi và không có KTĐB
                                            $genBook = '%' . $conn->real_escape_string($_POST['genBook']) . '%';
                                            // Kiểm tra xem dữ liệu đã tồn tại trong cơ sở dữ liệu hay không
                                            $genCheck = $conn->prepare("SELECT * FROM genres WHERE genName like ?");
                                            $genCheck->bind_param("s", $genBook);
                                            $genCheck->execute();
                                            $genresult = $genCheck->get_result();

                                            // Nếu có ít nhất một dòng trả về, tức là dữ liệu đã tồn tại
                                            if ($genresult && $genresult->num_rows > 0) {
                                                $sql_query = $conn->prepare("CALL $viewBook(?)");
                                                $sql_query->bind_param("s", $genBook);
                                                // dùng bind_param () để ráng giá trị tham số vào câu lệnh 
                                                $sql_query->execute();

                                                // Lấy kết quả của thủ tục lưu trữ
                                                $result = $sql_query->get_result();

                                                if ($result && $result->num_rows > 0) {
                                                    // Duyệt qua các dòng kết quả và hiển thị
                                                    while ($row = $result->fetch_assoc()) {
                                                        $bPrice = $row['bookPrice'];
                                                        $formattedPrice = number_format($bPrice, 0, ',', '.');
                                                        echo "<tr>";
                                                        echo "<td class='genBook__table-STT'>" . $row['MaSach'] . "</td>";
                                                        echo "<td class='genBook__table-name'>" . $row['bookName'] . "</td>";
                                                        echo "<td class='genBook__table-price'>" . $formattedPrice . "đ</td>";
                                                        echo "<td class='genBook__table-amount'>" . $row['bookQuantity'] . "</td>";
                                                        echo "<td class='genBook__table-type'>" . $row['genName'] . "</td>";
                                                        echo "</tr>";
                                                    }
                                                    echo "</table>";
                                                } else {
                                                    echo "<script> alert('Không có dữ liệu phù hợp!!') </script>";
                                                }
                                                // Đóng statement
                                                // $sql_query->close();
                                            } else {
                                                echo "<script> alert('Loại Sách Này Không Tồn Tại!!!') </script>";
                                            }
                                        }
                                    }
                                    ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>

    <!-- Username Logic -->
    <script>
        var username = "<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>";
        if (username !== '') {
            // Người dùng đã đăng nhập, thực hiện các hành động phù hợp ở đây
            document.querySelector(".header__navbar-user-name").textContent = username;
        } else {
            // Người dùng chưa đăng nhập
            console.log('Người dùng chưa đăng nhập');
        }
    </script>

    <script>
        const viewBook = document.querySelector('.js-view-all');
        const viewAllBtn = document.querySelector('.view-all');

        // viewBook.addEventListener('click', function(event) {
        //     event.preventDefault();
        //     var tables = document.getElementsByTagName('table');
        //     for (var i = 0; i < tables.length; i++) {
        //         var table = tables[i];
        //         // Xoá bảng
        //         table.parentNode.removeChild(table);
        //     }
        // });

        // let isEmpty = true;
        // genSubmitBook.disabled = true;
        document.addEventListener("DOMContentLoaded", function() {
            const genInputBook = document.querySelector('.gen__book-form-input');
            const genSubmitBook = document.querySelector('.gen__book-form-submit');
            if (genInputBook.value.trim() === "") {
                // Nếu rỗng, disabled nút submit và thêm class disabled
                genSubmitBook.classList.add('gen__book-form-submit--disabled');
                genSubmitBook.disabled = true;
            }
            // Bắt sự kiện khi người dùng gõ vào input
            genInputBook.addEventListener('keyup', function() {
                // Kiểm tra nếu giá trị của input là rỗng
                // Nếu không rỗng, enable nút submit và loại bỏ class disabled
                genSubmitBook.classList.remove('gen__book-form-submit--disabled');
                genSubmitBook.disabled = false;
            });
        });
    </script>

    </html>
</body>