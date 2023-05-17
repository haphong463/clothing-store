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
    <style>
        .product-physical{
            max-height:600px;
            overflow:auto;
        }
    </style>
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
                                                                <td width="30%"><?php echo strlen($p['description']) > 150 ? substr($p['description'], 0, 150) . '...' : $p['description']; ?></td>
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

            <?php include('part/footer.php') ?>