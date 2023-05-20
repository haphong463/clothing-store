<?php
session_start();
require_once('db/dbhelper.php');
$sql = "SELECT * FROM product_category";
$product_categories = executeResult($sql);

$sql2 = "SELECT * FROM category";
$product = executeResult($sql2);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Yoga Studio Template">
    <meta name="keywords" content="Yoga, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Parisienne | Store</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Amatic+SC:400,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="assets/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/assets/css/font-awesome.min.css">

    <style>
        .hero-items .owl-nav button[type=button].owl-next {
            left: auto;
            right: 60px;
            display: inline-block;
        }

        button.add-to-cart {
            height: 56px;
            width: 173px;
            border: 2px solid #EEF1F2;
            border-radius: 50px;
            cursor: pointer;
            color: white;
            background-color: black;
            font-weight: 600
        }

        .hero-items .owl-nav button[type=button] {
            background-color: transparent !important;
        }

        .single-product-item figure img.product-image {
            height: 250px;
        }

        .header-section {
            padding-left: 0;
            padding-right: 0;
        }

        .product-img figure img {
            height: 55vh;
        }

        .inner-header .main-menu ul li .sub-menu {
            height: 30vh;
        }

        .pro-quantity {
            display: flex;
        }

        .pro-quantity input[type=number] {
            border: 1px solid #333;
            width: 40px;
            height: 40px;
            text-align: center;

        }


        .pro-quantity input[type="number"]::-webkit-inner-spin-button,
        .pro-quantity input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .pro-quantity input[type="number"] {
            -moz-appearance: textfield;
            /* Firefox */
        }

        button.quantity {
            border: 1px solid #333;
            background-color: #fff;
            padding: 2px 4px;
            width: 40px;
            height: 40px;
            cursor: pointer;
        }

        button.add {
            color: #fff;
            background-color: #333;
            border-radius: 10px;
            cursor: pointer;
            width: 150px;
            padding: 8px 12px;
            margin: 15px 0;
        }

        .pd-size-choose {
            margin-bottom: 30px;
        }

        .pd-size-choose .sc-item {
            display: inline-block;
            margin-right: 5px;
        }

        .pd-size-choose .sc-item:last-child {
            margin-right: 0;
        }

        .pd-size-choose .sc-item input {
            position: absolute;
            visibility: hidden;
        }

        .pd-size-choose .sc-item label {
            font-size: 16px;
            color: #252525;
            font-weight: 700;
            height: 40px;
            width: 47px;
            border: 1px solid #ebebeb;
            text-align: center;
            line-height: 40px;
            text-transform: uppercase;
            cursor: pointer;
        }

        .pd-size-choose .sc-item input[type="radio"]:checked+label {
            background-color: #333;
            color: #ffffff;
        }

        .square-radio {
            display: inline-block;
            position: relative;
            width: 40px;
            height: 40px;
            border: 2px solid #e6e6e6;
            border-radius: 50%;
            cursor: pointer;
        }

        .square-radio input[type="radio"] {
            display: none;
        }

        .square-radio span {
            display: block;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        /* Thay đổi màu nền của hình tròn bên trong */
        .square-radio input[type="radio"]:checked+span {
            background-color: var(--color);
        }

        .register-login-section {
            padding-top: 72px;
            padding-bottom: 80px;
        }

        .register-form h2,
        .login-form h2 {
            color: #252525;
            font-weight: 700;
            text-align: center;
            margin-bottom: 35px;
        }

        .register-form form .group-input,
        .login-form form .group-input {
            margin-bottom: 25px;
        }

        .register-form form .group-input label,
        .login-form form .group-input label {
            display: block;
            font-size: 18px;
            color: #252525;
            margin-bottom: 13px;
        }

        .register-form form .group-input input,
        .login-form form .group-input input {
            border: 1px solid #ebebeb;
            height: 50px;
            width: 100%;
            padding-left: 20px;
            padding-right: 15px;
        }

        .register-form form .register-btn,
        .register-form form .login-btn,
        .login-form form .register-btn,
        .login-form form .login-btn {
            width: 100%;
            letter-spacing: 2px;
            margin-top: 5px;
        }

        .register-form .switch-login,
        .login-form .switch-login {
            text-align: center;
            margin-top: 22px;
        }

        .register-form .switch-login .or-login,
        .login-form .switch-login .or-login {
            color: #252525;
            font-size: 14px;
            letter-spacing: 2px;
            text-transform: uppercase;
            position: relative;
        }

        .register-form .switch-login .or-login:before,
        .login-form .switch-login .or-login:before {
            position: absolute;
            left: 0;
            bottom: 0;
            height: 2px;
            width: 100%;
            background: #9f9f9f;
            content: "";
        }
    </style>
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Search model -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch">+</div>
            <form class="search-model-form" action="categories.php" method="get">
                <input type="text" id="search-input" name="search-product" placeholder="Search here.....">
            </form>
        </div>
    </div>
    <!-- Search model end -->

    <header class="header-section">
        <div class="container-fluid">
            <div class="inner-header">
                <div class="logo">
                    <a href="./index.php"><img src="image/logo.png" alt=""></a>
                </div>
                <?php

                // Kiểm tra sự tồn tại của session email hoặc username
                if (isset($_SESSION['email']) || isset($_SESSION['username'])) {
                    // Hiển thị phần header-right
                ?>
                    <div class="header-right">
                        <img src="assets/img/icons/search.png" alt="" class="search-trigger">
                        <img src="assets/img/icons/man.png" alt="">
                        <a href="shopping-cart.php">
                            <img src="assets/img/icons/bag.png" alt="">
                            <span>0</span>
                        </a>
                    </div>
                <?php
                }
                ?>
                <div class="user-access">
                    <a href="register.php">Register</a>
                    <a href="#" class="in">Sign in</a>
                </div>
                <nav class="main-menu mobile-menu">
                    <ul>
                        <li><a class="active" href="./index.php">Home</a></li>
                        <li><a href="./categories.php">Shop</a>
                            <ul class="sub-menu">
                                <?php
                                foreach ($product as $p) {
                                ?>
                                    <li><a href="categories.php?cat_id=<?php echo $p['cat_id'] ?>"><?php echo $p['cat_name'] ?></a></li>
                                <?php
                                }

                                ?>
                            </ul>
                            <ul class="sub-menu" style="margin-left:100px">
                                <?php
                                foreach ($product_categories as $c) {
                                ?>
                                    <li><a href="categories.php?p_cat_id=<?php echo $c['p_cat_id'] ?>"><?php echo $c['p_cat_name'] ?></a></li>
                                <?php
                                }

                                ?>
                            </ul>
                        </li>
                        <li><a href="./about-us.php">About</a></li>
                        <li><a href="./contact.php">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <!-- Header Info Begin -->
    <div class="header-info">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="header-item">
                        <img src="assets/img/icons/delivery.png" alt="">
                        <p>Free shipping on orders over $30 in USA</p>
                    </div>
                </div>
                <div class="col-md-4 text-left text-lg-center">
                    <div class="header-item">
                        <img src="assets/img/icons/voucher.png" alt="">
                        <p>20% Student Discount</p>
                    </div>
                </div>
                <div class="col-md-4 text-left text-xl-right">
                    <div class="header-item">
                        <img src="assets/img/icons/sales.png" alt="">
                        <p>30% off on dresses. Use code: 30OFF</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Info End -->
    <!-- Header End -->