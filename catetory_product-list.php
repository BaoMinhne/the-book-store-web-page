<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bookName'])) {
    $searchName = $_POST['bookName'];
    $query = "SELECT * FROM Books 
    JOIN publishers ON Books.pubID = publishers.pubID 
    JOIN origins ON books.oriID = origins.oriID
    WHERE Books.bookName LIKE '%$searchName%'";
} else {
    if (isset($_GET['category'])) {
        // Lấy giá trị của tham số danh mục từ URL
        $category = $_GET['category'];
        switch ($category) {
            case 'sach_giao_khoa':
                // Truy vấn sách giáo khoa
                $query = "SELECT * FROM Books join publishers on Books.pubID = publishers.pubID join origins on books.oriID = origins.oriID WHERE genID = 'SGK' ORDER BY bookID ASC";
                break;

            case 'tieu_thuyet':
                // Truy vấn tiểu thuyết
                $query = "SELECT * FROM Books join publishers on Books.pubID = publishers.pubID join origins on books.oriID = origins.oriID WHERE genID = 'TT' ORDER BY bookID ASC";
                break;

            case 'truyen_tranh':
                // Truy vấn truyện tranh
                $query = "SELECT * FROM Books join publishers on Books.pubID = publishers.pubID join origins on books.oriID = origins.oriID WHERE genID = 'TRT' ORDER BY bookID ASC";
                break;

            case 'kinh_doanh':
                $query = "SELECT * FROM Books join publishers on Books.pubID = publishers.pubID join origins on books.oriID = origins.oriID WHERE genID = 'KD' ORDER BY bookID ASC";
                break;

            case 'khoa_hoc':
                $query = "SELECT * FROM Books join publishers on Books.pubID = publishers.pubID join origins on books.oriID = origins.oriID WHERE genID = 'KH' ORDER BY bookID ASC";
                break;

            case 'giao_trinh':
                $query = "SELECT * FROM Books join publishers on Books.pubID = publishers.pubID join origins on books.oriID = origins.oriID WHERE genID = 'GT' ORDER BY bookID ASC";
                break;

            case 'y_hoc':
                $query = "SELECT * FROM Books join publishers on Books.pubID = publishers.pubID join origins on books.oriID = origins.oriID WHERE genID = 'YH' ORDER BY bookID ASC";
                break;

            case 'tham_khao':
                $query = "SELECT * FROM Books join publishers on Books.pubID = publishers.pubID join origins on books.oriID = origins.oriID WHERE genID = 'STK' ORDER BY bookID ASC";
                break;

            case 'cong_nghe':
                $query = "SELECT * FROM Books join publishers on Books.pubID = publishers.pubID join origins on books.oriID = origins.oriID WHERE genID = 'CN' ORDER BY bookID ASC";
                break;

            case 'lich_su':
                $query = "SELECT * FROM Books join publishers on Books.pubID = publishers.pubID join origins on books.oriID = origins.oriID WHERE genID = 'LS' ORDER BY bookID ASC";
                break;

            case 'all':
                // Nếu không phải là một loại danh mục hợp lệ, hiển thị tất cả sản phẩm
                $query = "SELECT * FROM Books join publishers on Books.pubID = publishers.pubID join origins on books.oriID = origins.oriID ORDER BY bookID ASC";
                break;

            case 'small_to_large':
                // Nếu không phải là một loại danh mục hợp lệ, hiển thị tất cả sản phẩm
                $query = "SELECT * FROM Books join publishers on Books.pubID = publishers.pubID join origins on books.oriID = origins.oriID ORDER BY bookPrice ASC";
                break;

            case 'large_to_small':
                // Nếu không phải là một loại danh mục hợp lệ, hiển thị tất cả sản phẩm
                $query = "SELECT * FROM Books join publishers on Books.pubID = publishers.pubID join origins on books.oriID = origins.oriID ORDER BY bookPrice DESC";
                break;

            default:
                // Nếu không phải là một loại danh mục hợp lệ, hiển thị tất cả sản phẩm
                $query = "SELECT * FROM Books join publishers on Books.pubID = publishers.pubID join origins on books.oriID = origins.oriID ORDER BY bookID ASC";
                break;
        }
    } else {
        $query = "SELECT * FROM Books join publishers on Books.pubID = publishers.pubID join origins on books.oriID = origins.oriID";
    }
    // Truy vấn cơ sở dữ liệu để lấy dữ liệu từ bảng Books
}

$result = mysqli_query($conn, $query);
// Kiểm tra xem có dữ liệu được trả về không
if (mysqli_num_rows($result) > 0) {
    // Duyệt qua từng hàng dữ liệu và tạo thẻ div tương ứng
    while ($row = mysqli_fetch_assoc($result)) {
        $bookPrice = $row['bookPrice'];
        $formattedPrice = number_format($bookPrice, 0, ',', '.');
        echo '<div class="grid__column-2-4">';
        echo '<a class="home-product-item" href="detailProduct.php?id=' . $row['bookID'] . '">';
        echo '<div class="home-product-item__img" style="background-image: url(' . $row['bgURL'] . ');"></div>';
        echo '<h4 class="home-product-item__name">' . $row['bookName'] . '</h4>';
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
        echo '<span class="home-product-item__brand">' . $row['pubID'] . '</span>';
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
    // Hiển thị thông báo nếu không có dữ liệu
    echo 'Không có dữ liệu.';
    echo "Error: " . $sql_query->error; // Hiển thị lỗi SQL nếu có
    echo "Error: " . $conn->error; // Hiển thị lỗi kết nối nếu có
}


// Đóng kết nối cơ sở dữ liệu
mysqli_close($conn);
