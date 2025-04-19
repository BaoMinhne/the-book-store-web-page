<?php

if (isset($_GET['id'])) {
    $ID = $_GET['id'];
    $product = "SELECT * FROM Books 
    join publishers on Books.pubID = publishers.pubID 
    join origins on Books.oriID = origins.oriID 
    join Genres on Books.genID = Genres.genID
    WHERE bookID = $ID";

    $resultProd = mysqli_query($conn, $product);
    if (mysqli_num_rows($resultProd) > 0) {
        $row = mysqli_fetch_assoc($resultProd);
        // Lấy thông tin sản phẩm từ hàng kết quả
        $bookID = $row['bookID'];
        $productName = $row['bookName'];
        $authorName = $row['authorName'];
        $productPrice = $row['bookPrice'];
        $formattedPrice = number_format($productPrice, 0, ',', '.');
        $purchaseDate = $row['bookPurchaseDate'];
        $productQuantity = $row['bookQuantity'];
        $genre = $row['genName'];
        $publisher = $row['pubName'];
        $origin = $row['oriName'];
        $bgURL = $row['bgURL'];
        echo '
        <div class="grid grid__bg">
            <div class="grid__row">
                <div class="detail-product__img" style="background-image: url( ' . $bgURL . ');"></div>
                <div class="detail-product__info">
                    <div class="detail-product__label">
                    <span class="detail-product__name">' . $productName . '</span>
                </div>

                <div class="detail-product__condition">
                    <div class="detail-product__comment">Chưa có đánh giá</div>
                    <div class="detail-product__inventory">' . $productQuantity . ' Còn Lại</div>
                </div>

                <div class="detail-product__price">
                    <span class="detail-product__price-info">' . $formattedPrice . 'đ</span>
                </div>
                
                <form action="./addToCart.php" method="post">
                    <div class="detail-product__add-quantity">
                        <h3 class="add-quantity__title">Số Lượng</h3>
                        <div class="add-quantity__button">
                            <button type="button" class="add-quantity__minus" onclick="decrement()">-</button>
                            <input type="hidden" name="productId" value="' . $bookID . '">
                            <input type="number" name="quantity" id="" class="add-quantity__input" value ="1">
                            <button  type="button" class="add-quantity__plus" onclick="increment()">+</button>
                        </div>
                    </div>
                    <div class="detail-product__add-pro-btn">
                        <button type = "submit" class="detail-product__add-cart-btn">Thêm Vào Giỏ Hàng</button>
                        <button type = "button" class="detail-product__buy-btn">
                            <a href="./cart.php" style="text-decoration: none; color: var(--white-color);">Mua Ngay</a>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        </div>
';

        echo '
        <div class="grid grid__bg">
            <div class="grid__row">
                <div class="detail-product__origin-quantity-pub">
                    <h2>CHI TIẾT SẢN PHẨM</h2>
                    <div class="detail-product__section">
                        <div class="detail-product__author">
                            <label for="" class="title">Tác Giả</label>
                            <div class="info">' . $authorName . '</div>
                        </div>

                        <div class="detail-product__inventory">
                            <label for="" class="title">Kho Hàng</label>
                            <div class="info">' . $productQuantity . '</div>
                        </div>

                        <div class="detail-prodcut__publish">
                            <label for="" class="title">Nhà Xuất Bản</label>
                            <div class="info">' . $publisher . '</div>
                        </div>

                        <div class="detail-product__origin">
                            <label for="" class="title">Xuất Xứ</label>
                            <div class="info">' . $origin . '</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>';
    }
}
