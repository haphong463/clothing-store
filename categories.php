<?php
require_once('db/dbhelper.php');
$sql = "SELECT * FROM Product";
$p_cat_id = isset($_GET['p_cat_id']) ? $_GET['p_cat_id'] : null;
$cat_id = isset($_GET['cat_id']) ? $_GET['cat_id'] : null;

if ($p_cat_id) {
    $sql = "SELECT * FROM Product WHERE p_cat_id = $p_cat_id";
    $sql2 = "SELECT p_cat_name from product_category where p_cat_id = $p_cat_id";
    $p_cat = executeSingleResult($sql2);
    $name = $p_cat['p_cat_name'];
} elseif ($cat_id) {
    // Nếu có cat_id, truy vấn các sản phẩm thuộc vào cat_id đó
    $sql = "SELECT * FROM Product WHERE cat_id = $cat_id";
    $sql2 = "SELECT cat_name from category where cat_id = $cat_id";
    $cat = executeSingleResult($sql2);
    $name = $cat['cat_name'];
} else {
    $sql = "SELECT * FROM Product";
    $name = "Shop";
}


$products = executeResult($sql);
$totalProducts = count($products); // Tổng số sản phẩm
$limit = 12; // Số sản phẩm hiển thị trên mỗi trang
$totalPages = ceil($totalProducts / $limit); // Tổng số trang
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1; // Trang hiện tại

$offset = ($currentPage - 1) * $limit; // Offset của trang hiện tại trong danh sách sản phẩm

// Lấy danh sách sản phẩm của trang hiện tại
$sql .= " LIMIT $limit OFFSET $offset";
$products = executeResult($sql);


?>

<?php include('layout/header.php') ?>

<!-- Page Add Section Begin -->
<section class="page-add">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="page-breadcrumb">
                    <h2><?php echo $name ?><span>.</span></h2>
                    <a href="index.php">Home</a>
                    <a href="categories.php"><?php echo $name ?></a>
                </div>
            </div>
            <div class="col-lg-8">
                <img src="img/add.jpg" alt="">
            </div>
        </div>
    </div>
</section>
<!-- Page Add Section End -->

<!-- Categories Page Section Begin -->
<section class="categories-page spad">
    <div class="container">
        <div class="categories-controls">
            <div class="row">
                <div class="col-lg-12">
                    <div class="categories-filter">
                        <div class="cf-left">
                            <form action="categories.php">
                                <select class="sort" name="sort">
                                    <option value="">Sort by</option>
                                    <option value="orders">Orders</option>
                                    <option value="newest">Newest</option>
                                    <option value="price">Price</option>
                                </select>
                            </form>
                        </div>
                        <div class="cf-right">
                            <span><?php echo $totalProducts; ?> Products</span>

                            <?php
                            for ($i = 1; $i <= $totalPages; $i++) {
                                $activeClass = ($i == $currentPage) ? 'active' : '';
                                echo '<a href="categories.php?page=' . $i . '" class="' . $activeClass . '">' . $i . '</a>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <?php foreach ($products as $product) {
                $pid = $product['pid'];
                $name = $product['name'];
                $price = $product['price'];

                // Lấy hình ảnh đầu tiên từ bảng Product_Image
                $sqlImage = "SELECT image_path FROM Product_Image WHERE pid = $pid LIMIT 1";
                $resultImage = executeSingleResult($sqlImage);
                $imagePath = ($resultImage != null) ? $resultImage['image_path'] : "img/default-image.jpg";
            ?>
                <div class="col-lg-3 col-md-6">
                    <div class="single-product-item">
                        <figure>
                            <img src="<?php echo $imagePath; ?>" class="product-image" alt="">
                            <div class="hover-icon">
                                <a href="<?php echo $imagePath; ?>" class="pop-up"><img src="img/icons/zoom-plus.png" alt=""></a>
                            </div>
                        </figure>
                        <div class="product-text">
                            <a href="product-page.php?pid=<?php echo $pid ?>">
                                <h6><?php echo $name ?></h6>
                            </a>
                            <p>$<?php echo $price ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="more-product">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <?php if ($currentPage < $totalPages) { ?>
                        <a href="categories.php?page=<?php echo $currentPage + 1; ?>" class="primary-btn">Load More</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Page Section End -->

<?php include('layout/footer.php') ?>