<?php
include "./config/db_connection.php";

if (isset($_SESSION['username'])) {
    $uname = $_SESSION['username'];

    // First query to get cart contents
    $sql_query = "SELECT * FROM orders
    JOIN detailOrders ON orders.orderID = detailOrders.orderID
    JOIN books ON detailOrders.bookID = books.bookID
    JOIN userinfos ON orders.userID = userinfos.userID
    WHERE userName = '$uname'";

    // Second query to get total cost
    $sql_query2 = "SELECT userinfos.userID, SUM(orderCost) AS totalCost
    FROM detailOrders 
    JOIN orders ON orders.orderID = detailOrders.orderID
    JOIN userinfos ON orders.userID = userinfos.userID
    WHERE userName = '$uname' and orders.status = 'chưa thanh toán'
    GROUP BY userinfos.userID";

    $formatTotal = 0;
    $resultCart = mysqli_query($conn, $sql_query);
    $totalResult = mysqli_query($conn, $sql_query2);
    if (mysqli_num_rows($totalResult) > 0) {
        $total = mysqli_fetch_assoc($totalResult);
        $formatTotal = number_format($total['totalCost'], 0, ',', '.');
    }


    $orderIDs = [];

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

            if ($status === 'chưa thanh toán') {
                echo '
                <tr>
                <td class="product-num">' . $num . '</td>
                <td class="product-name">' . $bname . '</td> 
                <td class="product-img"><img src="' . $img . '" alt=""></td> 
                <td class="product-price">' . $formatprice . '</td>
                <td class="product-quantity">' . $amount . '</td>
                <td class="product-total">' . $formatCost . '</td>
                <form action="./delCart.php" method="post">
                    <td class="product-del">
                        <input type="hidden" name="orderId" value="' . $orderID . '">
                        <button type="submit" class = "product-del-button" onclick="return confirmDelete();">
                        Xoá <i class="fa-solid fa-trash product-del-icon"></i>
                        </button>
                    </td>
                </form>
                </tr>';
                $orderIDs[] = $orderID;
                $num++;
            }
        }
        // Total row
        echo '
        <tr>
            <th class="product-num"></th>
            <th class="product-name">Tổng Tiền</th>
            <th class="product-img"></th>
            <th class="product-price"></th>
            <th class="product-quantity"></th>
            <th class="product-total">' . $formatTotal . '</th>
            <th class="product-del"></th>
        </tr>';

        echo '
                <form action="./payment.php" method="post" class = "form-payment">
                                    <input type="hidden" name="orderIDs" value="' . implode(',', $orderIDs) . '">
                                    <button type="submit" class="cart__container-payment">Thanh Toán <i class="cart__container-payment-icon fa-solid fa-money-bill"></i></i></button>
                                </form>
                ';
    }
}
