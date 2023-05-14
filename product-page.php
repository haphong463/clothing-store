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
                    <h2>Shirts<span>.</span></h2>
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

<!-- Product Page Section Beign -->
<section class="product-page">
    <div class="container">
        <div class="product-control">
            <a href="#">Previous</a>
            <a href="#">Next</a>
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
                                    <img src="<?php echo $imagePath; ?>" width="500px" height="500px" alt="">
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
                    <p>
                        <?php
                        echo $info_product['description'];
                        ?>
                    </p>
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
                    <div class="product-quantity">
                        <div class="pro-qty">
                            <input type="text" value="1">
                        </div>
                    </div>
                    <a href="#" class="primary-btn pc-btn">Add to cart</a>
                    <ul class="p-info">
                        <li>Product Information</li>
                        <li>Reviews</li>
                        <li>Product Care</li>
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
                <div class="section-title">
                    <h2>Related Products</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="single-product-item">
                    <figure>
                        <a href="#"><img src="img/products/img-1.jpg" alt=""></a>
                        <div class="p-status">new</div>
                    </figure>
                    <div class="product-text">
                        <h6>Green Dress with details</h6>
                        <p>$22.90</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="single-product-item">
                    <figure>
                        <a href="#"><img src="img/products/img-2.jpg" alt=""></a>
                        <div class="p-status sale">sale</div>
                    </figure>
                    <div class="product-text">
                        <h6>Yellow Maxi Dress</h6>
                        <p>$25.90</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="single-product-item">
                    <figure>
                        <a href="#"><img src="img/products/img-3.jpg" alt=""></a>
                        <div class="p-status">new</div>
                    </figure>
                    <div class="product-text">
                        <h6>One piece bodysuit</h6>
                        <p>$19.90</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="single-product-item">
                    <figure>
                        <a href="#"><img src="img/products/img-4.jpg" alt=""></a>
                        <div class="p-status popular">popular</div>
                    </figure>
                    <div class="product-text">
                        <h6>Blue Dress with details</h6>
                        <p>$35.50</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Related Product Section End -->

<!-- Footer Section Begin -->
<?php include('layout/footer.php') ?>