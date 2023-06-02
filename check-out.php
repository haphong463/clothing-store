    <?php include('layout/header.php') ?>
    <?php
    require_once('db/dbhelper.php');
    if (isset($_SESSION['c_username_email'])) {
        $c_id = $_SESSION['c_username_email'];
        $username = $c_id['username'];
        $email = $c_id['email'];

        $sql = "SELECT * FROM cart WHERE c_id = '$email'";
        $cartItems = executeResult($sql);

        if (empty($cartItems)) {
            echo '<script>alert("Your cart is empty. Please add some products to your cart before proceeding to checkout.");</script>';
            echo '<script>window.location.href = "shopping-cart.php";</script>';
            exit();
        }

        if (isset($_POST['checkout'])) {
            $_SESSION['shipping_method'] = isset($_POST['cs']) ? $_POST['cs'] : '';
            $_SESSION['total'] = isset($_POST['total']) ? $_POST['total'] : 0;
            $_SESSION['shipping_fee'] = isset($_POST['shipping_fee']) ? $_POST['shipping_fee'] : 0;
            $_SESSION['coupon_code'] = isset($_POST['coupon_code']) ? $_POST['coupon_code'] : 0;
            echo '<script>window.location.href = "check-out.php";</script>';
            exit();
        }
        echo $_SESSION['total'];
        echo $_SESSION['shipping_fee'];
        echo $_SESSION['coupon_code'];
        echo $_SESSION['shipping_method'];




        $TOTAL = $_SESSION['total'] + $_SESSION['shipping_fee'];

        $sql = "SELECT * FROM users WHERE username = '$username' or email = '$email'";

        $users = executeSingleResult($sql);
        $fullname = $users['full_name'];
        $address = $users['address'];
        $contact = $users['contact'];
        $dob = $users['date_of_birth'];


        function generateOrderCode()
        {
            $digits = '0123456789';
            $orderCode = 'VN';
            for ($i = 0; $i < 6; $i++) {
                $orderCode .= $digits[rand(0, 9)];
            }
            return $orderCode;
        }

        function generateTransactionID()
        {
            $transactionID = '#';
            for ($i = 0; $i < 7; $i++) {
                $transactionID .= rand(0, 9);
            }
            return $transactionID;
        }

        function resetTotalValue()
        {
            $_SESSION['total'] = 0;
            $_SESSION['shipping_fee'] = 0;
            $_SESSION['coupon_code'] = 0;
            $TOTAL = 0;
        }

        $orderCode = generateOrderCode();
        $transactionCode = generateTransactionID();

        $sql_cart = "SELECT * FROM cart WHERE c_id = '$email'";
        $run_sql_cart = executeResult($sql_cart);

        $quantity = "SELECT sum(quantity) as quantity FROM cart WHERE c_id = '$email'";
        $run_quantity = executeSingleResult($quantity);

        $sizeMapping = array(
            'S' => 'Small',
            'M' => 'Medium',
            'L' => 'Large',
            'XL' => 'Extra Large'
        );



        if (isset($_POST['order']) or isset($_POST['payment'])) {

            $orderDetails = array();

            foreach ($run_sql_cart as $item) {
                $productId = $item['pid'];
                $quantity = $item['quantity'];
                $size = $item['size'];

                $orderDetails[] = array(
                    'pid' => $productId,
                    'quantity' => $quantity,
                    'size' => $size
                );  
            }

            foreach ($orderDetails as $detail) {
                $productId = $detail['pid'];
                $quantity = $detail['quantity'];
                $size = $detail['size'];

                $sql_order_details = "INSERT INTO order_details (c_id, order_id, pid, quantity, size) VALUES ('$email', '$orderCode', $productId, $quantity, '$size')";
                execute($sql_order_details);
            }


            $quantity = $run_quantity['quantity'];
            $cartQuantity = 0;

            $sizes = array();
            $products = array();

            if (isset($_POST['payment'])) {
                $payment_method = 'Paypal';
                $payment_status = 1;
                $status = '0';
            } elseif (isset($_POST['get_package'])) {
                $payment_method = $_POST['get_package'];
                $payment_status = 0;
                $status = '0';
            }





            foreach ($run_sql_cart as $cart) {
                $size = $cart['size'];
                $pid = $cart['pid'];

                $cartQuery = "SELECT quantity FROM cart WHERE c_id = '$email' AND pid = '$pid' AND size = '$size'";
                $cartResult = executeSingleResult($cartQuery);

                if ($cartResult) {
                    $cartQuantity = $cartResult['quantity'];

                    if (isset($sizeMapping[$size])) {
                        $displaySize = $sizeMapping[$size];
                    } else {
                        $displaySize = $size;
                    }

                    switch ($displaySize) {
                        case 'Medium':
                            $displaySize = 'M';
                            break;
                        case 'Large':
                            $displaySize = 'L';
                            break;
                        case 'Extra Large':
                            $displaySize = 'XL';
                            break;
                        case 'Small':
                            $displaySize = 'S';
                            break;
                    }

                    $quantityQuery = "UPDATE product_variant SET quantity = quantity - $cartQuantity WHERE pid = $pid AND size = '$displaySize'";
                    execute($quantityQuery);

                    $product_query = "SELECT name FROM product WHERE pid = '$pid'";
                    $product_result = executeSingleResult($product_query);
                    $product_name = $product_result['name'];

                    $products[] = $product_name;
                    $sizes[] = $size;
                }
            }

            $productString = implode(', ', $products);
            $sizeString = implode(', ', $sizes);

            $shipping_method = isset($_SESSION['shipping_method']) ? $_SESSION['shipping_method'] : '';
            $shipping_fee = isset($_SESSION['shipping_fee']) ? $_SESSION['shipping_fee'] : 0;

            if (isset($_SESSION['coupon_code'])) {
                $couponCode = $_SESSION['coupon_code'];
                $sqlDiscount = "UPDATE discount SET quantity = quantity - 1 WHERE coupon_code = '$couponCode'";
                execute($sqlDiscount);
            }

            $sql = "INSERT INTO orders (order_id, date, quantity, size, c_id, shipping_method, contact, shipping_fee, address, products, total, voucher) 
                    VALUES ('$orderCode', NOW(), $quantity, '$sizeString', '$email', '$shipping_method', '$contact',  '$shipping_fee', '$address','$productString', $TOTAL, '$couponCode')";
            execute($sql);

            $sql_transaction = "INSERT INTO transaction (transaction_id, order_id, payment_method, transaction_date, amount, status, payment_status, created_at, updated_at)
                                                VALUES ('$transactionCode', '$orderCode', '$payment_method', NOW(), $TOTAL, '$status', '$payment_status', NOW(), NOW())";
            execute($sql_transaction);

            $sqlDeleteCart = "DELETE FROM cart WHERE c_id = '$email'";
            execute($sqlDeleteCart);

            resetTotalValue();

            echo '<script>window.location.href = "thanks.php";</script>';
            exit();
        }



    ?>


        <!-- Page Add Section Begin -->
        <section class="page-add">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="page-breadcrumb">
                            <h2>Checkout<span>.</span></h2>
                        </div>
                    </div>
                    <?php
                    include('layout/discount.php');
                    ?>
                </div>
            </div>
        </section>
        <!-- Page Add Section End -->

        <!-- Cart Total Page Begin -->
        <section class="cart-total-page spad">
            <div class="container">
                <form action="check-out.php" class="checkout-form" id="theForm" method="post">
                    <input type="hidden" name="coupon" value="<?php echo $_SESSION['coupon_code'] ?>">
                    <input type="hidden" name="shipping_fee" value="<?php echo $_SESSION['shipping_fee'] ?>">
                    <div class="row">
                        <div class="col-lg-12">
                            <h3>Your Information</h3>
                        </div>
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-lg-2">
                                    <p class="in-name">Name*</p>
                                </div>
                                <div class="col-lg-10">
                                    <input type="text" value="<?php echo $fullname ?>" readonly>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-2">
                                    <p class="in-name">Email*</p>
                                </div>
                                <div class="col-lg-10">
                                    <input type="text" value="<?php echo $email ?>" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2">
                                    <p class="in-name">Street Address*</p>
                                </div>
                                <div class="col-lg-10">
                                    <input type="text" value="<?php echo $address ?>" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2">
                                    <p class="in-name">Date of birth*</p>
                                </div>
                                <div class="col-lg-10">
                                    <input type="text" value="<?php echo $dob ?>" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2">
                                    <p class="in-name">Phone*</p>
                                </div>
                                <div class="col-lg-10">
                                    <input type="text" value="<?php echo $contact ?>" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <div class="diff-addr">
                                        <input type="radio" id="one">
                                        <label for="one">Ship to different address</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="order-table">
                                <div class="cart-item">
                                    <span>Product</span>
                                    <?php
                                    if ($run_sql_cart != null) {
                                        foreach ($run_sql_cart as $item) {
                                            $pid = $item['pid'];
                                            $sql_product = "SELECT * FROM product WHERE pid = '$pid'";
                                            $product = executeResult($sql_product);
                                            if ($product != null) {
                                                foreach ($product as $p) {

                                                    echo '
                                                    <p class="product-name">' . $p['name'] . '</p>   
                                                    <br>                               
                                                    ';
                                                }
                                            }
                                        }
                                    } else {
                                        echo '<p class="product-name">No items</p>';
                                    }
                                    ?>
                                </div>
                                <div class="cart-item">
                                    <span>Price</span>
                                    <p>$<?php echo number_format($_SESSION['total'], 2, '.') ?></p>
                                </div>
                                <div class="cart-item">
                                    <span>Quantity</span>
                                    <p>
                                        <?php
                                        if ($run_quantity['quantity'] == NULL) {
                                            echo 0;
                                        } else {
                                            echo $run_quantity['quantity'];
                                        } ?>
                                    </p>
                                </div>
                                <div class="cart-item">
                                    <span>Shipping</span>
                                    <p>$<?php echo $_SESSION['shipping_fee'] ?></p>
                                </div>

                                <div class="cart-total">
                                    <span>Total</span>
                                    <p>$<?php echo number_format($TOTAL, 2, '.')  ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="payment-method">
                                <h3>Payment</h3>
                                <ul>
                                    <li>

                                        <div id="payment"></div>
                                        <script src="https://www.paypal.com/sdk/js?client-id=ATGXcrNc5l8akd8iyRwk-OI4GXTyXAQy_nybdU9fGSfHpFA3crp3AUjbFIHEKYuiGyTkLpczjCgFS2GH">
                                            // Required. Replace YOUR_CLIENT_ID with your sandbox client ID.
                                        </script>
                                        <div id="paypal-button-container" type="submit"></div>
                                        <script>
                                            paypal.Buttons({
                                                createOrder: function(data, actions) {
                                                    // This function sets up the details of the transaction, including the amount and line item details.
                                                    return actions.order.create({
                                                        purchase_units: [{
                                                            amount: {
                                                                value: "<?php echo ($TOTAL); ?>"
                                                            }
                                                        }]
                                                    });
                                                },
                                                onApprove: function(data, actions) {
                                                    // This function captures the funds from the transaction.
                                                    return actions.order.capture().then(function(details) {
                                                        // This function shows a transaction success message to your buyer.
                                                        document.getElementById('payment').innerHTML = '<input name="payment" value="true" hidden>'
                                                        document.getElementById('theForm').submit();
                                                    });


                                                }
                                            }).render('#paypal-button-container');
                                            //This function displays Smart Payment Buttons on your web page.
                                        </script>
                                    </li>
                                    <li>
                                        <label for="two">Pay when you get the package</label>
                                        <input type="radio" required name="get_package" value="Pay on receipt the package" id="two">
                                    </li>
                                </ul>

                                <button type="submit" name="order">Place your order</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        <!-- Cart Total Page End -->

        <?php include('layout/footer.php') ?>
    <?php
    } else {
        echo '<script>window.location.href = "signin.php";</script>';
        exit();
    }

    ?>