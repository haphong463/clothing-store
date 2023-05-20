<?php
require_once 'db/dbhelper.php';

if (isset($_POST['add-to-cart'])) {
    $pid = $_POST['pid'];
    $quantity = $_POST['quantity'];
    $size = $_POST['size'];
    $color = $_POST['color'];


    $sql = "SELECT * FROM cart WHERE pid =$pid";
    $existstingProduct = executeSingleResult($sql);

    if ($existstingProduct) {
        echo '<script>alert("Product Added")</script>';
    } else {
        $sql = "INSERT INTO cart (pid, quantity, size, color, date) VALUES ('$pid', '$quantity', '$size', '$color', NOW())";
        execute($sql);

        $sql = "SELECT cart.*, product.name, product.price, product_thumbnail.thumbnail 
        FROM cart 
        INNER JOIN product ON cart.pid = product.pid
        INNER JOIN (
            SELECT MIN(id) AS minID, thumbnail, pid
            FROM product_thumbnail
            GROUP BY pid
        ) AS product_thumbnail ON product.pid = product_thumbnail.pid";
        $cartItems = executeResult($sql);
    }
}



?>

<?php include('layout/header.php') ?>
<!-- Header Info End -->
<!-- Header End -->

<!-- Page Add Section Begin -->
<section class="page-add cart-page-add">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="page-breadcrumb">
                    <h2>Cart<span>.</span></h2>
                    <a href="#">Home</a>
                    <a href="#">Dresses</a>
                    <a class="active" href="#">Night Dresses</a>
                </div>
            </div>
            <div class="col-lg-8">
                <img src="img/add.jpg" alt="">
            </div>
        </div>
    </div>
</section>
<!-- Page Add Section End -->

<!-- Cart Page Section Begin -->
<div class="cart-page">
    <div class="container">
        <div class="cart-table">
            <table>
                <thead>
                    <tr>
                        <th class="product-h">Product</th>
                        <th>Price</th>
                        <th class="quan">Quantity</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($cartItems) && !empty($cartItems)) :
                        foreach ($cartItems as $cartItem) : ?>
                            <tr>
                                <td class="product-col">
                                    <img src="<?php echo $cartItem['thumbnail'] ?>" alt="">
                                    <div class="p-title">
                                        <h5><?php echo $cartItem['name'] ?></h5>
                                    </div>
                                </td>
                                <td class="price-col"><?php echo $cartItem['price'] ?></td>
                                <td class="quantity-col">
                                    <div class="pro-qty">
                                        <input type="text" value="1">
                                    </div>
                                </td>
                                <td class="total">$29</td>
                                <td class="product-close">x</td>
                            </tr>
                        <?php
                        endforeach;
                    else :

                        ?>
                        <p>No product to display!</p>
                    <?php
                    endif
                    ?>
                </tbody>
            </table>
        </div>
        <div class="cart-btn">
            <div class="row">
                <div class="col-lg-6">
                    <div class="coupon-input">
                        <input type="text" placeholder="Enter cupone code">
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1 text-left text-lg-right">
                    <div class="site-btn clear-btn">Clear Cart</div>
                    <div class="site-btn update-btn">Update Cart</div>
                </div>
            </div>
        </div>
    </div>
    <div class="shopping-method">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shipping-info">
                        <h5>Choose a shipping</h5>
                        <div class="chose-shipping">
                            <div class="cs-item">
                                <input type="radio" name="cs" id="one">
                                <label for="one" class="active">
                                    Free Standard shhipping
                                    <span>Estimate for New York</span>
                                </label>
                            </div>
                            <div class="cs-item">
                                <input type="radio" name="cs" id="two">
                                <label for="two">
                                    Next Day delievery $10
                                </label>
                            </div>
                            <div class="cs-item last">
                                <input type="radio" name="cs" id="three">
                                <label for="three">
                                    In Store Pickup - Free
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="total-info">
                        <div class="total-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Total</th>
                                        <th>Subtotal</th>
                                        <th>Shipping</th>
                                        <th class="total-cart">Total Cart</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="total">$29</td>
                                        <td class="sub-total">$29</td>
                                        <td class="shipping">$10</td>
                                        <td class="total-cart-p">$39</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <a href="#" class="primary-btn chechout-btn">Proceed to checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cart Page Section End -->

<!-- Footer Section Begin -->
<?php include('layout/footer.php') ?>