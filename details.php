<?php include('layout/header.php') ?>
<?php
require_once 'db/dbhelper.php';

?>

<!-- Header Info End -->
<!-- Header End -->

<!-- Page Add Section Begin -->
<section class="page-add cart-page-add">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="page-breadcrumb">
                    <h2>My account<span>.</span></h2>
                </div>
            </div>
            <?php
                include ('layout/discount.php');
            ?>
        </div>
    </div>
</section>
<!-- Page Add Section End -->

<!-- Cart Page Section Begin -->
<div class="cart-page">
    <div class="container">
        <h2>Order history</h2>
        <div class="cart-table">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Payment Status</th>
                        <th>Complete Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- Cart Page Section End -->

<!-- Footer Section Begin -->
<?php include('layout/footer.php') ?>