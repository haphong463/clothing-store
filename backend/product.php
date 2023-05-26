<?php
require_once '../db/dbhelper.php';
$sql = "SELECT * FROM product";
$product = executeResult($sql);

$sql_category = "SELECT * FROM category";
$categories = executeResult($sql_category);

$sql_type = "SELECT * FROM product_category";
$types = executeResult($sql_type);

$sql_variant = "SELECT * FROM product_variant";
$variant = executeResult($sql_variant);

$sql_image = "SELECT * FROM product_image";
$images = executeResult($sql_image);

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
                                    <h3>Product List
                                        <small>La Mode Parisienne</small>
                                    </h3>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <ol class="breadcrumb pull-right">
                                    <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Digital</li>
                                    <li class="breadcrumb-item active">Category</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid Ends-->

                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Digital Products</h5>
                                </div>
                                <div class="card-body">
                                    <div class="btn-popup pull-right">
                                        <a href="product-add.php">
                                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-original-title="test" data-target="#exampleModal">Add Product</button>
                                        </a>
                                    </div>
                                    <div class="table-responsive">
                                        <div id="" class="product-physical">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Category</th>
                                                        <th scope="col">Type</th>
                                                        <th scope="col">Product Name</th>
                                                        <th scope="col">Price</th>
                                                        <th scope="col">Color</th>
                                                        <th scope="col">Size</th>
                                                        <th scope="col">Quantity</th>
                                                        <th scope="col">Description</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if ($product != null) {
                                                        foreach ($product as $p) {
                                                            $color_string = '';
                                                            $hex_string = '';
                                                            $size_string = '';
                                                            $quantity_string = '';
                                                            $pid = $p['pid'];
                                                            $color_array = array();
                                                            $hex_array = array();
                                                            $size_array = array();
                                                            $quantity_array = array();
                                                            $quantityDisplayed = false;

                                                            $sql_quantity = "SELECT DISTINCT quantity FROM product_quantity where pid = $pid";
                                                            $quantity_result = executeResult($sql_quantity);

                                                            $sql_color = "SELECT * FROM product_color WHERE pid = $pid";
                                                            $colors_result = executeResult($sql_color);

                                                            $sql_size = "SELECT * FROM product_size where pid = $pid";
                                                            $sizes_result = executeResult($sql_size);

                                                            foreach ($quantity_result as $quantity) {
                                                                $quantity_array[] = $quantity['quantity'];
                                                            }

                                                            foreach ($sizes_result as $size) {
                                                                $size_array[] = $size['size'];
                                                            }
                                                            foreach ($colors_result as $color) {
                                                                $color_array[] = $color['color_name'];
                                                                $hex_array[] = $color['hex'];
                                                            }

                                                            $size_string .= implode(', ', $size_array);
                                                            $color_string .= implode(', ', $color_array);
                                                            $hex_string .= implode(', ', $hex_array);
                                                            if (!$quantityDisplayed) {
                                                                $quantity_string .= implode(', ', $quantity_array);
                                                                $quantityDisplayed = true;
                                                            }
                                                    ?>
                                                            <tr>
                                                                <?php
                                                                foreach ($categories as $c) {
                                                                    if ($c['cat_id'] == $p['cat_id']) {
                                                                ?>
                                                                        <td><?php echo $c['cat_name']  ?></td>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <?php
                                                                foreach ($types as $t) {
                                                                    if ($t['p_cat_id'] == $p['p_cat_id']) {
                                                                ?>
                                                                        <td><?php echo $t['p_cat_name']  ?></td>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>

                                                                <td><?php echo $p['name'] ?></td>
                                                                <td><?php echo $p['price'] ?></td>
                                                                <td><?php echo $color_string ?></td>
                                                                <td><?php echo $size_string ?></td>
                                                                <td><?php echo $quantity_string ?></td>
                                                                <td width="30%"><?php echo strlen($p['description']) > 150 ? substr($p['description'], 0, 150) . '...' : $p['description']; ?></td>
                                                                <td><a href="product-update.php?pid=<?php echo $p['pid'] ?>">
                                                                        <button class="btn">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                                                            </svg>
                                                                        </button>
                                                                    </a>
                                                                    |
                                                                    <a href="process/category-delete.php?id=<?php echo $p['pid']; ?>">
                                                                        <button class="btn">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z" />
                                                                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z" />
                                                                            </svg>
                                                                        </button>
                                                                    </a>
                                                                </td>


                                                            </tr>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid Ends-->

            </div>

            <?php include('part/footer.php') ?>