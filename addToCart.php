<?php
session_start();
include './config/db_connection.php';

if (!isset($_SESSION['username'])) {
    $bID = intval($_POST['productId']);

    echo "<script> alert('Vui Lòng Đăng Nhập Trước.'); 
        var ID = $bID;
        window.location.href = 'detailProduct.php?id=' + ID;</script>";

    exit(); // Thêm dòng này để ngăn mã tiếp tục thực thi
}

if (isset($_SESSION['username'])) {
    $uname = $_SESSION['username'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['productId']) && isset($_POST['quantity'])) {

            $bID = intval($_POST['productId']);
            $amount = intval($_POST['quantity']);

            // Truy vấn số lượng sách hiện có
            $sql_query = "SELECT bookQuantity from books where bookID = '$bID'";
            $checkAmount = mysqli_query($conn, $sql_query);

            if ($checkAmount) {
                $row = mysqli_fetch_assoc($checkAmount);
                $bQuantity = $row['bookQuantity'];

                if ($amount > $bQuantity) {
                    echo "<script> 
                        var ID = $bID;
                        window.location.href = 'detailProduct.php?id=' + ID;
                        alert('Sản Phẩm Không Đủ Số Lượng!');
                    </script>";
                } else {

                    $add_to_cart = 'p_add_to_cart';
                    $sql_query = $conn->prepare("CALL $add_to_cart(?, ?, ?)");
                    $sql_query->bind_param("sii", $uname, $bID, $amount);
                    $result = $sql_query->execute();

                    if ($result) {
                        echo "<script> 
                                var ID = $bID;
                                window.location.href = 'detailProduct.php?id=' + ID;
                                alert('NHẬP THÀNH CÔNG!!!');
                            </script>";
                        exit();
                    } else {
                        echo "<script> alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.'); </script>";
                    }
                }
            } else {
                echo "<script> alert('Có lỗi khi kiểm tra số lượng sản phẩm.'); </script>";
            }
        } else {
            echo "<script> alert('Dữ liệu không hợp lệ.'); </script>";
        }
    }
}
