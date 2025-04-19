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
                                <li class="header__task-item"><a href="../controller/viewProduct.php" class="header__task-link">Xem Sản Phẩm</a></li>
                                <li class="header__task-item"><a href="../admin.php" class="header__task-link">Trở Lại</a></li>
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
                                    <!-- <li class="category-item ">
                                        <a href="#" class="category-item__link js-view-all">Xem tất cả sản phẩm</a>
                                    </li> -->

                                    <li class="category-item">
                                        <button class="category-item__link category-item__link--active">Thêm sách mới</button>
                                    </li>

                                    <!-- <li class="category-item">
                                        <a href="#" class="category-item__link">Kiểm tra lượng tồn</a>
                                    </li> -->

                                    <!-- <li class="category-item">
                                        <a href="#" class="category-item__link">Cập nhật số lượng và đơn giá</a>
                                    </li> -->

                                    <!-- <li class="category-item">
                                        <a href="#" class="category-item__link">Kiểm tra thông tin sách</a>
                                    </li> -->

                                    <!-- <li class="category-item">
                                        <a href="#" class="category-item__link">Xoá Sách</a>
                                    </li> -->

                                </ul>
                            </nav>
                        </div>

                        <div class="grid__column-10 ">
                            <div class="add_book">
                                <div class="add_book-header">
                                    <label for="" class="">NHẬP DỮ LIỆU</label>
                                </div>
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="addBook__form-container">
                                    <table class="addBook__form-table">
                                        <tr>
                                            <td class="addBook__form-label">Nhập Tên Sách</td>
                                            <td class="addBook__form-data">
                                                <input type="text" name="bookName" class="addBook__form-input">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="addBook__form-label">Tên Tác Giả</td>
                                            <td class="addBook__form-data">
                                                <input type="text" name="auName" class="addBook__form-input" id="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="addBook__form-label">Giá</td>
                                            <td class="addBook__form-data">
                                                <input type="number" name="priceBook" class="addBook__form-input" id="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="addBook__form-label">Số Lượng</td>
                                            <td class="addBook__form-data">
                                                <input type="number" name="quantityBook" class="addBook__form-input" id="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="addBook__form-label">Loại Sách</td>
                                            <td class="addBook__form-data">
                                                <select name="type-name" id="type-name" class="addBook__form-select">
                                                    <option value="" selected disabled>Chọn Loại Sách</option>
                                                    <?php
                                                    // Truy vấn cơ sở dữ liệu để lấy danh sách các loại bánh
                                                    $query = "SELECT * FROM genres";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result && mysqli_num_rows($result) > 0) {
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            $typeID = $row['genID'];
                                                            $typeName = $row['genName'];
                                                            echo "<option value='$typeID'>$typeName</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="addBook__form-label">Nhà Xuất Bản</td>
                                            <td class="addBook__form-data">
                                                <select name="pubName" id="type-name" class="addBook__form-select">
                                                    <option value="" selected disabled>Nhà Xuất Bản</option>
                                                    <?php
                                                    // Truy vấn cơ sở dữ liệu để lấy danh sách các loại bánh
                                                    $query = "SELECT * FROM publishers";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result && mysqli_num_rows($result) > 0) {
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            $typeID = $row['pubID'];
                                                            $typeName = $row['pubName'];
                                                            echo "<option value='$typeID'>$typeName</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="addBook__form-label">Nguồn Gốc</td>
                                            <td class="addBook__form-data">
                                                <select name="oriName" id="type-name" class="addBook__form-select">
                                                    <option value="" selected disabled>Nguồn Gốc</option>
                                                    <?php
                                                    // Truy vấn cơ sở dữ liệu để lấy danh sách các loại bánh
                                                    $query = "SELECT * FROM origins";
                                                    $result = mysqli_query($conn, $query);

                                                    if ($result && mysqli_num_rows($result) > 0) {
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            $typeID = $row['oriID'];
                                                            $typeName = $row['oriName'];
                                                            echo "<option value='$typeID'>$typeName</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="addBook__form-label">Địa Chỉ Hình Ảnh</td>
                                            <td class="addBook__form-data">
                                                <input type="text" name="URL" class="addBook__form-input" id="">
                                            </td>
                                        </tr>
                                    </table>

                                    <button type="submit" class="addBook__form-submit">Xác Nhận</button>
                                </form>
                            </div>
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
    </script>

    <script>
        const iptDatas = document.querySelectorAll('.add-input');
        const btnSubmit = document.querySelector('.addBook-submit');
        let isEmpty = true;
        btnSubmit.disabled = true;

        function checkInputs() {
            let isEmpty = false;

            for (const data of iptDatas) {
                if (data.value.trim() === "") {
                    isEmpty = true;
                    break;
                }
            }

            if (!isEmpty) {
                btnSubmit.disabled = false;
            } else {
                btnSubmit.disabled = true; // Vô hiệu hóa nút Submit nếu có ô input rỗng
            }
        }

        iptDatas.forEach(input => {
            input.addEventListener('input', checkInputs);
        });
    </script>

    </html>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gọi thủ tục
    $addBook = 'p_add_books';
    // Lấy dữ liệu từ người dùng
    $bookName = $conn->real_escape_string($_POST['bookName']);
    $auName = $conn->real_escape_string($_POST['auName']);
    $priceBook = intval($_POST['priceBook']);
    $quantity = intval($_POST['quantityBook']);
    $genID = $conn->real_escape_string($_POST['type-name']); // Thay đổi tên tham số
    $pubID = $conn->real_escape_string($_POST['pubName']);
    $oriID = intval($_POST['oriName']);
    $URL = $conn->real_escape_string($_POST['URL']);
    if ($bookName === "" || $auName === "" || $priceBook === "" || $quantity === "" || $genID === "" || $pubID === "" || $oriID === "" || $UR === "") {
        echo "<script>alert('Vui Lòng Nhập Đầy Đủ Thông Tin!!!'); 
                window.location.href = 'addProduct.php';</script>";
    } else {
        // Kiểm tra xem dữ liệu đã tồn tại trong cơ sở dữ liệu hay không
        $nameCheck = $conn->prepare("SELECT * FROM books WHERE bookName = ?");
        $nameCheck->bind_param("s", $bookName);
        $nameCheck->execute();
        $result = $nameCheck->get_result();

        // Nếu có ít nhất một dòng trả về, tức là dữ liệu đã tồn tại
        if ($result && $result->num_rows > 0) {
            echo "<script>alert('SÁCH ĐÃ TỒN TẠI!!!')</script>";
        } else {
            // Gọi thủ tục
            $sql_query = $conn->prepare("CALL $addBook(?, ?, ?, ?, ?, ?, ?, ?)");
            $sql_query->bind_param("ssiissis", $bookName, $auName, $priceBook, $quantity, $genID, $pubID, $oriID, $URL);

            $result = $sql_query->execute();
            if ($result) {
                echo "<script>alert('NHẬP THÀNH CÔNG!!!'); 
                    window.location.href = 'addProduct.php';</script>";
            } else {
                echo "<script>alert('NHẬP THẤT BẠI!!!'); 
                    window.location.href = 'addProduct.php';</script>";
                echo "Error: " . $sql_query->error; // Hiển thị lỗi SQL nếu có
                echo "Error: " . $conn->error; // Hiển thị lỗi kết nối nếu có
                exit();
            }
            $sql_query->close();
        }
    }
}
?>