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
                                <!-- QR code -->
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
                                <li class="header__task-item"><a href="../controller/viewProduct.php" class="header__task-link">Xem Sản Phẩm</a></li>
                                <li class="header__task-item"><a href="../controller/addProduct.php" class="header__task-link">Thêm Sản Phẩm</a></li>
                                <li class="header__task-item"><a href="../controller/upProduct.php" class="header__task-link">Cập nhật Sản Phẩm</a></li>
                                <li class="header__task-item"><a href="../admin.php" class="header__task-link">Trở Lại</a></li>
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
                                        <button class="category-item__link category-item__link--active">Xoá Sách</button>
                                    </li>

                                </ul>
                            </nav>
                        </div>

                        <div class="grid__column-10 ">
                            <table class="bookTable">
                                <tr>
                                    <th class="book-id">Loại Sách</th>
                                    <th class="book-type">Tên Sách</th>
                                    <th class="book-name">Đơn Giá</th>
                                    <th class="book-amount">Số Lượng Hiện Tại</th>
                                    <th class="book-delete">Xoá Sách</th>
                                </tr>

                                <?php
                                $sql_query = "SELECT g.genName, b.bookID, b.bookName, b.bookPrice, b.bookQuantity from books b
                                join genres g on b.genID = g.genID";
                                $result = mysqli_query($conn, $sql_query);

                                if ($result && mysqli_num_rows($result) > 0) {
                                    // Duyệt qua các dòng kết quả và hiển thị
                                    while ($row = $result->fetch_assoc()) {
                                        $bID = $row['bookID'];
                                        $bTName = $row['genName'];
                                        $bName = $row['bookName'];
                                        $bPrice = $row['bookPrice'];
                                        $bAmount = $row['bookQuantity'];
                                        $formattedPrice = number_format($bPrice, 0, ',', '.');

                                        echo '
                                            <tr>
                                                <td class="book-type">' . $bTName . '</td>
                                                <td class="book-name">' . $bName . '</td>
                                                <td class="book-price">' . $formattedPrice . 'đ</td>
                                                <td class="book-amount">' . $bAmount . '</td>
                                                    <td  class="book-delete">
                                                    <form action="' . $_SERVER['PHP_SELF'] . '" method="post">
                                                        <input type="hidden" name="bID" value="' . $bID . '">
                                                        <button type="submit" class="btn-del" onclick ="return confirmDelete();">Xoá <i class="fa-solid fa-trash"></i></button>
                                                    </form>
                                                    </td>
                                            </tr>
                                                ';
                                    }
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Modal Layout -->

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

        function confirmDelete() {
            return window.confirm("Bạn Có Muốn Xoá Sách Này Hay Không???");
        }
    </script>



</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bID'])) {
    // Gọi thủ tục
    $delBook = 'p_del_book';
    $bookID = intval($_POST['bID']);
    $delStmt = $conn->prepare("CALL $delBook(?)");
    $delStmt->bind_param("i", $bookID);
    $result = $delStmt->execute();
    if ($result) {
        echo "<script> 
                alert('Đã Xoá Sách Này!!!');
                window.location.href = 'delProduct.php';
            </script>";
    } else {
        echo "<script> 
                alert('Xoá Thất Bại!!!');
                window.location.href = 'delProduct.php';
            </script>";
    }
    $delStmt->close();
}
?>