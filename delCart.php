<?php
session_start();
include './config/db_connection.php';

if (isset($_SESSION['username'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $oID = intval($_POST['orderId']);

        $delCart = 'p_del_cart';
        $sql_query = $conn->prepare("CALL $delCart(?)");
        $sql_query->bind_param("i", $oID);
        $result = $sql_query->execute();

        if ($result) {
            echo "
            <script>
                window.location.href = 'cart.php'
                alert ('Đã Xoá Sản Phẩm Khỏi Đơn Hàng');
            </script>     
            ";
        }
    }
}

?>

<script>
    window.location.href = 'viewcart.php'
    alert('Đã Xoá Sản Phẩm Khỏi Đơn Hàng');
</script>