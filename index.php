<?php
require_once('db/dbhelper.php');
$sql = "SELECT * FROM product_category";
$p_category = executeResult($sql);
?>

<!-- Header Section Begin -->
<?php include('layout/header.php') ?>
<!-- Header Section End -->

<!-- Hero Slider Begin -->
<section class="hero-slider">
    <div class="hero-items owl-carousel">
        <?php
        $sql = "SELECT * FROM slider";
        $slider = executeResult($sql);

        if ($slider != null) {
            foreach ($slider as $s) {
                $year = $s['year'];
                $image = $s['image'];
                $heading = $s['heading'];
                echo '
    
                <div class="single-slider-item set-bg" data-setbg="' . $image . '">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <h1>' . $year . '</h1>
                                <h2>' . $heading . '</h2>
                            </div>
                        </div>
                    </div>
                </div>
    
                ';
            }
        }
        ?>
    </div>
</section>
<!-- Hero Slider End -->

<!-- Latest Section Begin -->
<!-- Latest Product Begin -->
<section class="latest-products spad">
    <div class="container">
        <div class="product-filter">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="section-title">
                        <h2>Latest Products</h2>
                    </div>
                    <ul class="product-controls">
                        <li data-filter="*">All</li>
                        <?php
                        foreach ($p_category as $c) {

                        ?>
                            <li data-filter=".<?php echo strtolower($c['p_cat_name']) ?>"><?php echo $c['p_cat_name'] ?></li>
                        <?php
                        }

                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row" id="product-list">
            <?php
            $product_info = "SELECT product.*, product_image.image_path
                    FROM product
                    LEFT JOIN (
                        SELECT pid, MIN(id) AS min_id
                        FROM product_image
                        GROUP BY pid
                    ) AS sub ON product.pid = sub.pid
                    LEFT JOIN product_image ON sub.min_id = product_image.id LIMIT 8";
            $resultInfo = executeResult($product_info);

            foreach ($resultInfo as $info) {
                $pid = $info['pid'];
                $name = $info['name'];
                $price = $info['price'];
                $imagePath = $info['image_path'];

                $p_cat_name = '';
                foreach ($p_category as $c) {
                    if ($info['p_cat_id'] == $c['p_cat_id']) {
                        $p_cat_name = strtolower($c['p_cat_name']);
                        break;
                    }
                }
            ?>

                <div class="col-lg-3 col-sm-6 mix all <?php echo $p_cat_name; ?>">
                    <div class="single-product-item">
                        <figure>
                            <a href="product-page.php?pid=<?php echo $pid ?>"><img src="<?php echo $imagePath ?>" width="300px" height="300px" alt=""></a>
                            <div class="p-status">NEW</div>
                        </figure>
                        <div class="product-text">
                            <h6><?php echo $name ?></h6>
                            <p>$<?php echo $price ?></p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>

    </div>







    </div>
</section>
<!-- Latest Product End -->
<!-- Latest Section End -->




<!-- Lookbok Section Begin -->
<section class="lookbok-section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 offset-lg-1">
                <div class="lookbok-left">
                    <div class="section-title">
                        <h2>2019 <br />#lookbook</h2>
                    </div>
                    <p>Fusce urna quam, euismod sit amet mollis quis, vestibulum quis velit. Vestibulum malesuada
                        aliquet libero viverra cursus. Aliquam erat volutpat. Morbi id dictum quam, ut commodo
                        lorem. In at nisi nec arcu porttitor aliquet vitae at dui. Sed sollicitudin nulla non leo
                        viverra scelerisque. Phasellus facilisis lobortis metus, sit amet viverra lectus finibus ac.
                        Aenean non felis dapibus, placerat libero auctor, ornare ante. Morbi quis ex eleifend,
                        sodales nulla vitae, scelerisque ante. Nunc id vulputate dui. Suspendisse consectetur rutrum
                        metus nec scelerisque. s</p>
                    <a href="#" class="primary-btn look-btn">See More</a>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <div class="lookbok-pic">
                    <img src="img/lookbok.jpg" alt="">
                    <div class="pic-text">fashion</div>
                </div>
            </div>
        </div>

    </div>
</section>
<!-- Lookbok Section End -->

<!-- Logo Section Begin -->
<div class="logo-section spad">
    <div class="logo-items owl-carousel">
        <div class="logo-item">
            <img src="img/logos/logo-1.png" alt="">
        </div>
        <div class="logo-item">
            <img src="img/logos/logo-2.png" alt="">
        </div>
        <div class="logo-item">
            <img src="img/logos/logo-3.png" alt="">
        </div>
        <div class="logo-item">
            <img src="img/logos/logo-4.png" alt="">
        </div>
        <div class="logo-item">
            <img src="img/logos/logo-5.png" alt="">
        </div>
    </div>
</div>
<!-- Logo Section End -->

<?php include('layout/footer.php') ?>