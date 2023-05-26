<?php
require_once('db/dbhelper.php');



if (!isset($_GET['pid']) || !is_numeric($_GET['pid'])) {
    header("Location: index.php"); // Chuyển hướng về trang index.php
    exit(); // Kết thúc thực thi mã
}

$pid = $_GET['pid'];

// Kiểm tra xem pid có tồn tại trong bảng Product hay không
$product_query = "SELECT * FROM product WHERE pid = $pid";
$product_result = executeSingleResult($product_query);

$p_cat_id = $product_result['p_cat_id'];
$sql_p_cat = "SELECT * FROM product_category WHERE p_cat_id = $p_cat_id";
$p_cat_result = executeSingleResult($sql_p_cat);



// Nếu không tìm thấy pid trong bảng Product, chuyển hướng về trang index.php
if (!$product_result) {
    header("Location: index.php"); // Chuyển hướng về trang index.php
    exit(); // Kết thúc thực thi mã
}


?>

<?php include('layout/header.php') ?>

<!-- Page Add Section Begin -->
<section class="page-add">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="page-breadcrumb">
                    <h2><?php echo $p_cat_result['p_cat_name'] ?><span>.</span></h2>
                    <a href="index.php">Home</a>
                    <a href="categories.php?p_cat_id=<?php echo $p_cat_id ?>"><?php echo $p_cat_result['p_cat_name'] ?></a>
                </div>
            </div>
            <?php
                include ('layout/discount.php');
            ?>
        </div>
    </div>
</section>
<!-- Page Add Section End -->

<!-- Product Page Section Beign -->
<section class="product-page">
    <div class="container">
        <?php
        $sql = "SELECT * FROM product";
        $products = executeResult($sql);
        $current_pid = isset($_GET['pid']) ? $_GET['pid'] : null; // Lấy pid hiện tại

        $previous_pid = null; // Pid của sản phẩm trước đó
        $next_pid = null; // Pid của sản phẩm tiếp theo

        // Tìm vị trí của pid hiện tại trong danh sách sản phẩm
        $current_product_index = -1;
        foreach ($products as $index => $product) {
            if ($product['pid'] == $current_pid) {
                $current_product_index = $index;
                break;
            }
        }

        // Lấy pid của sản phẩm trước đó và tiếp theo
        if ($current_product_index !== -1) {
            $previous_pid = ($current_product_index > 0) ? $products[$current_product_index - 1]['pid'] : null;
            $next_pid = ($current_product_index < count($products) - 1) ? $products[$current_product_index + 1]['pid'] : null;
        }
        ?>
        <div class="product-control">
            <?php if ($previous_pid !== null) { ?>
                <a href="product-page.php?pid=<?php echo $previous_pid ?>">Previous</a>
            <?php } ?>
            <?php if ($next_pid !== null) { ?>
                <a href="product-page.php?pid=<?php echo $next_pid ?>">Next</a>
            <?php } ?>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="product-slider owl-carousel">
                    <?php

                    $sqlCount = "SELECT COUNT(*) AS totalImages FROM Product_Image WHERE pid = $pid";
                    $resultCount = executeSingleResult($sqlCount);

                    if ($resultCount != null) {
                        $totalImages = $resultCount['totalImages']; // Tổng số hình ảnh
                    } else {
                        $totalImages = 0;
                    }
                    // Câu truy vấn SQL để lấy các hình ảnh sản phẩm
                    $sql = "SELECT image_path FROM Product_Image WHERE pid = $pid LIMIT 7";
                    $result = executeResult($sql);

                    if ($result != null) {
                        // Duyệt qua từng dòng dữ liệu
                        $count = 1;
                        foreach ($result as $r) {
                            $imagePath = $r['image_path'];
                            $statusText = ($count <= $totalImages) ? "$count/$totalImages" : ""; // Hiển thị "2/3" khi $count = 2
                    ?>
                            <div class="product-img">
                                <figure>
                                    <img src="<?php echo $imagePath; ?>" width="300px" height="500px" alt="">
                                    <div class="p-status"><?php echo $statusText ?></div>
                                </figure>
                            </div>
                    <?php
                            $count++;
                        }
                    } else {
                    }
                    ?>
                </div>
            </div>


            <!-- <?php
                    $sql = "SELECT * FROM PRODUCT where pid = $pid";
                    $info_product = executeSingleResult($sql);
                    $sql_cat = "SELECT * FROM category";
                    $result = executeSingleResult($sql_cat);
                    $sql_p_cat = "SELECT * FROM product_category";
                    $result_p_cat = executeResult($sql_p_cat);
                    $sql_variant = "SELECT * FROM product_variant";
                    $result_variant = executeResult($sql_variant);
                    ?> -->
            <div class="col-lg-6">
                <div class="product-content">
                    <h2><?php echo $info_product['name'] ?></h2>
                    <div class="pc-meta">
                        <h5>
                            <div class="price">
                                <span>$<?php echo number_format($product_result['price'], 2, '.', '.') ?> </span>
                            </div>
                        </h5>
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                    </div>


                    <?php
                    $description = $info_product['description'];
                    $description = str_replace('<ul>', '<ul style="color: #838383; font-size: 14px; font-weight: 500; line-height: 30px; margin-bottom: 35px; margin-left:15px">', $description);
                    echo $description;
                    ?>



                    <?php


                    $size_query = "SELECT size FROM product_size WHERE pid = $pid";
                    $size_result = executeResult($size_query);

                    $sql_color_hex = "SELECT * FROM product_color where pid = $pid";
                    $color_hex = executeResult($sql_color_hex);

                    ?>





                    <form action="shopping-cart.php" method="post">
                        <input type="hidden" name="pid" value="<?php echo $pid; ?>">
                        <div id="selected-color"></div>
                        <div class="product-color">
                            <?php
                            foreach ($color_hex as $hex) {
                                $firstColor = $color_hex[0]['color_name'];

                            ?>
                                <label class="square-radio">
                                    <input type="radio" name="color" value="<?php echo $hex['color_name'] ?>" <?php if ($firstColor == $hex['color_name']) {
                                                                                                                    echo 'checked';
                                                                                                                } ?>>
                                    <span style="background-color:<?php echo $hex['hex'] ?>;"><span>
                                </label>
                            <?php

                            }
                            ?>
                        </div>


                        <div class="form-group">
                            <!-- form-group Begin -->
                            <div class='pd-size-choose'>
                                <?php
                                foreach ($size_result as $size) {
                                    $firstSize = $size_result[0]['size'];

                                    $value = '';
                                    if ($size['size'] == "M") {
                                        $value = "Medium";
                                    } elseif ($size['size'] == "S") {
                                        $value = "Small";
                                    } elseif ($size['size'] == "L") {
                                        $value = "Large";
                                    } else if ($size['size'] == "XL") {
                                        $value = "Extra Large";
                                    }

                                ?>
                                    <div class='sc-item'>
                                        <input type='radio' id='<?php echo $size['size'] ?>-size' class="form-control" name='size' value="<?php echo $value ?>" <?php if ($size['size'] == $firstSize) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> required novalidate>
                                        <label for='<?php echo $size['size'] ?>-size'><?php echo $size['size'] ?></label>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>

                        </div>

                        <div class="pro-quantity">
                            <button type="button" class="quantity" onclick="decrement()">-</button>
                            <input type="number" id="quantity" name="quantity" min="1" value="1">
                            <button type="button" class="quantity" onclick="increment()">+</button>
                        </div>
                        <?php
                        if (isset($_SESSION['c_username_email'])) {
                        ?>
                            <button name="add-to-cart" class="add">Add to cart</button>
                        <?php
                        } else {
                        ?>
                            <button class="add"><a href="signin.php">Add to cart</a></button>
                        <?php
                        }
                        ?>
                    </form>

                    <ul class="tags">

                        <li><span>Category: </span>
                            <?php
                            if ($result['cat_id'] == $info_product['cat_id']) {
                                echo $result['cat_name'];
                            }
                            ?>,

                            <?php
                            foreach ($result_p_cat as $r) {
                                if ($r['p_cat_id'] == $info_product['p_cat_id']) {
                                    echo $r['p_cat_name'];
                                }
                            }
                            ?>
                        </li>



                        <li><span>Tags :</span> <?php
                                                foreach ($result_variant as $v) {
                                                    if ($v['p_id'] == $info_product['pid']) {
                                                        echo $v['keyword'];
                                                    }
                                                }
                                                ?></li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Product Page Section End -->

<!-- Related Product Section Begin -->
<section class="related-product spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <?php
                $related_product_query = "SELECT * FROM product WHERE p_cat_id = $p_cat_id AND pid != $pid LIMIT 4";
                $related_product_result = executeResult($related_product_query);

                if (count($related_product_result) > 0) : ?>
                    <h2 style="font-weight: 700">Related Products</h2>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <?php
            foreach ($related_product_result as $related_product) {
                $related_product_id = $related_product['pid'];

                // Lấy thông tin hình ảnh từ bảng product_image
                $image_query = "SELECT image_path FROM product_image WHERE pid = $related_product_id LIMIT 1";
                $image_result = executeSingleResult($image_query);

                // Kiểm tra xem có hình ảnh liên quan không
                if ($image_result) {
                    $related_product_image = $image_result['image_path'];
                } else {
                    $related_product_image = 'default_image.jpg'; // Hình ảnh mặc định nếu không có hình ảnh liên quan
                }
            ?>
                <div class="col-lg-3 col-sm-6">
                    <div class="single-product-item">
                        <figure>
                            <a href="product-page.php?pid=<?php echo $related_product_id ?>"><img src="<?php echo $related_product_image ?>" height="300px" alt=""></a>
                            <div class="p-status">NEW</div>
                        </figure>
                        <div class="product-text">
                            <h6><?php echo $related_product['name'] ?></h6>
                            <p>$<?php echo $related_product['price'] ?></p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>
<!-- Related Product Section End -->

<!-- Footer Section Begin -->
<script>
    var colorInputs = document.querySelectorAll('input[name="color"]');
    var selectedColorElement = document.getElementById('selected-color');

    // Lấy thông tin màu và tên màu đầu tiên
    var firstColor = colorInputs[0].value;
    var firstColorHex = colorInputs[0].nextElementSibling.style.backgroundColor;
    var firstColorStyle = 'color: ' + firstColorHex + ';';

    // Hiển thị màu và tên màu đầu tiên ban đầu
    selectedColorElement.innerHTML = 'Color: <span style="' + firstColorStyle + '">' + firstColor + '</span>';

    for (var i = 0; i < colorInputs.length; i++) {
        colorInputs[i].addEventListener('change', function() {
            var selectedColor = this.value;
            var selectedColorHex = this.nextElementSibling.style.backgroundColor;
            var selectedColorStyle = 'color: ' + selectedColorHex + ';';

            selectedColorElement.innerHTML = 'Color: <span style="' + selectedColorStyle + '">' + selectedColor + '</span>';
        });
    }
</script>


<script>
    function decrement() {
        var quantityInput = document.getElementById('quantity');
        var currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    }

    function increment() {
        var quantityInput = document.getElementById('quantity');
        var currentValue = parseInt(quantityInput.value);
        quantityInput.value = currentValue + 1;
    }
</script>
<?php include('layout/footer.php') ?>