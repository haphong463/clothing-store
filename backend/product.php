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

<!DOCTYPE html>
<html lang="en">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Bigdeal admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Bigdeal admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="../assets/images/favicon/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="../assets/images/favicon/favicon.png" type="image/x-icon">
    <title>Bigdeal - Premium Admin Template</title>

    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.css">

    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="../assets/css/flag-icon.css">

    <!-- jsgrid css-->
    <link rel="stylesheet" type="text/css" href="../assets/css/jsgrid.css">

    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css">

    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="../assets/css/admin.css">
</head>

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
                                                        <th scope="col">Active</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if ($product != null) {
                                                        foreach ($product as $p) {
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

                                                                <?php
                                                                foreach ($variant as $v) {
                                                                    if ($v['p_id'] == $p['pid']) {
                                                                ?>
                                                                        <td><?php echo $v['color']  ?></td>
                                                                        <td><?php echo $v['size']  ?></td>
                                                                        <td><?php echo $v['quantity']  ?></td>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td width="30%"><?php echo $p['description'] ?></td>
                                                                <td>
                                                                    <input type="checkbox" class="product-active" data-product-id="<?php echo $p['pid']; ?>" <?php echo ($p['active'] == 1) ? 'checked' : ''; ?>>
                                                                </td>
                                                                <td><a href="product-update.php?pid=<?php echo $p['pid'] ?>"><button class="btn btn-info">Edit</button></a> | <a href="process/category-delete.php?id=<?php echo $p['pid']; ?>"><button class="btn btn-danger">Delete</button></a> </td>


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

            <!-- footer start-->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 footer-copyright">
                            <p class="mb-0">Copyright 2019 © Bigdeal All rights reserved.</p>
                        </div>
                        <div class="col-md-6">
                            <p class="pull-right mb-0">Hand crafted & made with<i class="fa fa-heart"></i></p>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- footer end-->

        </div>

    </div>

    <!-- latest jquery-->

    <!-- Jsgrid js-->




    <script src="../assets/js/jquery-3.3.1.min.js"></script>

    <!-- Bootstrap js-->
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.js"></script>

    <!-- feather icon js-->
    <script src="../assets/js/icons/feather-icon/feather.min.js"></script>
    <script src="../assets/js/icons/feather-icon/feather-icon.js"></script>

    <!-- Sidebar jquery-->
    <script src="../assets/js/sidebar-menu.js"></script>

    <!-- Jsgrid js-->
    <script src="../assets/js/jsgrid/jsgrid.min.js"></script>
    <script src="../assets/js/jsgrid/griddata-digital.js"></script>
    <script src="../assets/js/jsgrid/jsgrid-manage-product.js"></script>

    <!--Customizer admin-->
    <script src="../assets/js/admin-customizer.js"></script>

    <!-- lazyload js-->
    <script src="../assets/js/lazysizes.min.js"></script>



    <!--right sidebar js-->
    <script src="../assets/js/chat-menu.js"></script>

    <!--script admin-->
    <script src="../assets/js/admin-script.js"></script>

</body>

</html>