<?php
session_start();
include './config/db_connection.php';

$bID = intval($_POST['productId'] ?? 0);

if (!isset($_SESSION['username'])) {
    echo "<script>
        alert('Vui Lòng Đăng Nhập Trước.');
        var ID = $bID;
        window.location.href = 'detailProduct.php?id=' + ID;
    </script>";
    exit();
}

$uname = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['productId'], $_POST['quantity'])) {
        $bID = intval($_POST['productId']);
        $amount = intval($_POST['quantity']);

        $checkAmountStmt = $conn->prepare('SELECT bookQuantity FROM books WHERE bookID = ? LIMIT 1');
        $checkAmountStmt->bind_param('i', $bID);
        $checkAmountStmt->execute();
        $checkAmount = $checkAmountStmt->get_result();

        if ($checkAmount && $checkAmount->num_rows === 1) {
            $row = mysqli_fetch_assoc($checkAmount);
            $bQuantity = (int) $row['bookQuantity'];

            if ($amount > $bQuantity) {
                echo "<script>
                    var ID = $bID;
                    window.location.href = 'detailProduct.php?id=' + ID;
                    alert('Sản Phẩm Không Đủ Số Lượng!');
                </script>";
                exit();
            }

            $add_to_cart = $conn->prepare('CALL p_add_to_cart(?, ?, ?)');
            $add_to_cart->bind_param('sii', $uname, $bID, $amount);
            $result = $add_to_cart->execute();

            if ($result) {
                echo "<script>
                        var ID = $bID;
                        window.location.href = 'detailProduct.php?id=' + ID;
                        alert('NHẬP THÀNH CÔNG!!!');
                    </script>";
                exit();
            }

            echo "<script> alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.'); </script>";
            exit();
        }

        echo "<script> alert('Có lỗi khi kiểm tra số lượng sản phẩm.'); </script>";
        exit();
    }

    echo "<script> alert('Dữ liệu không hợp lệ.'); </script>";
    exit();
}
