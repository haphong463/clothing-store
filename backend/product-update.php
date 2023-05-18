<?php
require_once '../db/dbhelper.php';
if (isset($_GET['pid'])) {
    $id = $_GET['pid'];
    $products = "SELECT * FROM product where pid = $id";
    $result = executeSingleResult($products);
}
?>

<?php include('part/header.php') ?>


<body>


    <!-- page-wrapper Start-->
    <div class="page-wrapper">

        <!-- Page Header Start-->
        <?php include('part/headerBackend.php'); ?>
        <!-- Page Header Ends -->

        <!-- Page Body Start-->
        <div class="page-body-wrapper">

            <!-- Page Sidebar Start-->
            <?php include('part/menu-left.php'); ?>
            <!-- Page Sidebar Ends-->

            <!-- Right sidebar Start-->

            <!-- Right sidebar Ends-->

            <div class="page-body">

                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <div class="page-header">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="page-header-left">
                                    <h3>NEW ONE PRODUCT
                                        <small>La Mode Parisienne</small>
                                    </h3>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <ol class="breadcrumb pull-right">
                                    <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Digital</li>
                                    <li class="breadcrumb-item active">Product</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid Ends-->

                <!-- Container-fluid starts-->

                <form action="process/product-update-process.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="pid" value="<?php echo $id ?>">
                    <div class="form-group">
                        <label for="">Category: </label>
                        <select name="cat_id" id="" required class="form-control">
                            <?php
                            $sql = "SELECT * FROM category";
                            $categories = executeResult($sql);
                            foreach ($categories as $c) {

                            ?>
                                <?php $selected = ($result && $result['cat_id'] == $c['cat_id']) ? 'selected' : '' ?>
                                <option value="<?php echo $c['cat_id'] ?>" <?php echo $selected ?>><?php echo $c['cat_name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="image">Type: </label>
                        <select name="p_cat_id" id="" required class="form-control">
                            <?php
                            $sql = "SELECT * FROM product_category";
                            $type = executeResult($sql);
                            foreach ($type as $t) {
                            ?>
                                <?php $selected = ($result && $result['p_cat_id'] == $t['p_cat_id']) ? 'selected' : '' ?>
                                <option value="<?php echo $t['p_cat_id'] ?>" <?php echo $selected ?>><?php echo $t['p_cat_name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="name">Product Name</label>
                        <input type="text" value="<?php echo $result['name'] ?>" required class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="price">Price: </label>
                        <input type="text" value="<?php echo $result['price'] ?>" required class="form-control" id="price" name="price">
                    </div>

                    <?php
                    $sql_variant = "SELECT * FROM product_variant where p_id = $id";
                    $variants = executeResult($sql_variant);
                    foreach ($variants as $variant) {

                    ?>

                        <div class="form-group">
                            <label for="size">Size: </label>
                            <input type="text" value="<?php echo $variant['size'] ?>" required class="form-control" id="size" name="size">
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity: </label>
                            <input type="text" value="<?php echo $variant['quantity'] ?>" required class="form-control" id="quantity" name="quantity">
                        </div>
                        <div class="form-group">
                            <label for="keyword">Keyword: </label>
                            <input type="text" value="<?php echo $variant['keyword'] ?>" class="form-control" id="keyword" name="keyword">
                        </div>
                        <div class="form-group">
                            <label for="color">Color: </label>
                            <input type="text" value="<?php echo $variant['color'] ?>" required class="form-control" name="color" id="color">
                        </div>

                    <?php
                    }
                    ?>

                    <div class="form-group">
                        <label for="desc">Description: </label>
                        <textarea id="desc" name="desc"><?php echo $result['description'] ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">Product Image: </label>
                        <input type="file" class="form-control" id="image" name="image[]" multiple>
                        <div class="row">
                            <?php
                            $products_image = "SELECT * FROM product_image where pid = $id";
                            $result_image = executeResult($products_image);
                            $count = 0;
                            foreach ($result_image as $r) {
                                if ($count % 3 == 0) {
                                    echo '</div><div class="row">';
                                }
                            ?>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <img src="../<?php echo $r['image_path'] ?>" alt="Product Image" class="img-fluid">
                                </div>
                            <?php
                                $count++;
                            }
                            ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" name="update-product">Update Product</button>
                </form>
                <!-- Container-fluid Ends-->

            </div>

            <script>
                tinymce.init({
                    selector: 'textarea#desc',
                    plugins: 'lists',
                    toolbar: 'undo redo | blocks fontsize | bold italic underline | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap',
                });
            </script>
            <?php include('part/footer.php') ?>