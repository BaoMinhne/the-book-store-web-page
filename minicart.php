<?php
include "./config/db_connection.php";

if (isset($_SESSION['username'])) {
    $uname = $_SESSION['username'];

    // First query to get cart contents
    $sql_query = "SELECT * FROM orders
    JOIN detailOrders ON orders.orderID = detailOrders.orderID
    JOIN books ON detailOrders.bookID = books.bookID
    join genres on books.genID = genres.genID
    JOIN userinfos ON orders.userID = userinfos.userID
    WHERE userName = '$uname'";

    $resultCart = mysqli_query($conn, $sql_query);

    if (mysqli_num_rows($resultCart) > 0) {
        $num = 1;
        while ($row = mysqli_fetch_assoc($resultCart)) {
            $orderID = $row['orderID'];
            $btype = $row['genName'];
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
                <li class="header__cart-item">
                <img src="' . $img . '" alt="" class="header__cart-img">
                <div class="header__cart-item-info">
                    <div class="header__cart-item-head">
                        <h5 class="header__cart-item-name">' . $bname . '</h5>
                        <div class="header__cart-item-price-wrap">
                            <span class="header__cart-item-price">' . $formatprice . 'đ</span>
                            <span class="header__cart-item-multiply">x</span>
                            <span class="header__cart-item-qnt">' . $amount . '</span>
                        </div>
                    </div>

                    <div class="header__cart-item-body">
                        <span class="header__cart-item-description">
                            Phân loại sách: ' . $btype . '
                        </span>
                        <span class="header__cart-item-remove">
                        <form action="./delCart.php" method="post">
                            <input type="hidden" name="orderId" value="' . $orderID . '">
                            <button type="submit" class = "product-del-button" onclick="return confirmDelete();">
                            Xoá <i class="fa-solid fa-trash product-del-icon"></i>
                            </button>
                    </form>
                        </span>
                    </div>
                </div>
            </li>
                ';
                $num++;
            }
        }
    }
}
