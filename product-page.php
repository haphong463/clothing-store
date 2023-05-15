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
            <div class="col-lg-8">
                <img src="img/add.jpg" alt="">
            </div>
        </div>
    </div>
</section>
<!-- Page Add Section End -->

<!-- Product Page Section Beign -->
<section class="product-page">
    <div class="container">
        <div class="product-control">
            <a href="product-page.php?pid=<?php echo $pid - 1 ?>">Previous</a>
            <a href="product-page.php?pid=<?php echo $pid + 1 ?>">Next</a>
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
                        <h5>$<?php echo $info_product['price'] ?></h5>
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                    </div>
                    <?php
                    echo $info_product['description'];
                    ?>
                    <?php
                    $sql_color_size = "SELECT DISTINCT color, size FROM product_variant WHERE p_id = $pid";
                    $result_color_size = executeSingleResult($sql_color_size);

                    $colorString = $result_color_size['color']; // Chuỗi màu sắc (ví dụ: "white,black,yellow")
                    $sizeString = $result_color_size['size'];

                    $sizes = explode(',', $sizeString);
                    $colors = explode(',', $colorString); // Chuyển chuỗi thành mảng các màu sắc
                    ?>




                    <form action="">
                        <div class="product-color">
                            <label style="color: #838383; font-size: 14px;
                                        font-weight: 600;
                                        line-height: 30px;">
                                Color:</label>
                            <select name="color" id="color-select">
                                <?php
                                foreach ($colors as $color) {
                                    echo '<option value="' . $color . '">' . $color . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="product-size">
                            <label style="color: #838383; font-size: 14px;
                                        font-weight: 600;
                                        line-height: 30px;">
                                Size :</label>
                            <select name="size" id="size-select">
                                <?php
                                foreach ($sizes as $size) {
                                    echo '<option value="' . $size . '">' . $size . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="product-quantity">
                            <div class="pro-qty">
                                <input type="text" value="1">
                            </div>
                        </div>
                        <button class="add-to-cart">Add to cart</button>
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
                            <a href="product-page.php?pid=<?php echo $related_product_id ?>"><img src="<?php echo $related_product_image ?>" alt=""></a>
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
<?php include('layout/footer.php') ?>