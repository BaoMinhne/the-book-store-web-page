<?php
include "./config/db_connection.php";

if (isset($_SESSION['username'])) {
    $uname = $_SESSION['username'];

    // First query to get cart contents
    $sql_query = "SELECT * FROM orders
    JOIN detailOrders ON orders.orderID = detailOrders.orderID
    JOIN books ON detailOrders.bookID = books.bookID
    JOIN userinfos ON orders.userID = userinfos.userID
    WHERE userName = '$uname' and status like 'đã thanh toán'";

    $resultCart = mysqli_query($conn, $sql_query);

    if (mysqli_num_rows($resultCart) > 0) {
        $num = 1;
        while ($row = mysqli_fetch_assoc($resultCart)) {
            $orderID = $row['orderID'];
            $bname = $row['bookName'];
            $img = $row['bgURL'];
            $bprices = $row['bookPrice'];
            $amount = $row['orderQuantity'];
            $cost = $row['orderCost'];
            $status = $row['status'];
            $formatCost = number_format($cost, 0, ',', '.');
            $formatprice = number_format($bprices, 0, ',', '.');

            // In ra chỉ những đơn hàng đã thanh toán
            if ($status === 'đã thanh toán') {
                echo '
                <tr>
                    <td class="product-num">' . $num . '</td>
                    <td class="product-name">' . $bname . '</td> 
                    <td class="product-img"><img src="' . $img . '" alt=""></td> 
                    <td class="product-price">' . $formatprice . '</td>
                    <td class="product-quantity">' . $amount . '</td>
                    <td class="product-total">' . $formatCost . '</td>
                    <td class="product-status">' . $status . '</td>
                </tr>';
                $num++;
            }
        }
    }
} else {
    echo "Bạn chưa đăng nhập";
}
