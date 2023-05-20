<?php include('layout/header.php') ?>

<section class="page-add">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="page-breadcrumb">
                    <h2>SIGN UP<span>.</span></h2>
                </div>
            </div>
            <div class="col-lg-8">
                <img src="img/add.jpg" alt="">
            </div>
        </div>
    </div>
</section>

<div class="register-login-section spad">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="register-form">
                    <form action="register.php" method="post" enctype="multipart/form-data" id="logform">
                        <div class="row">
                            <div class="group-input col-md-6">
                                <label for="fullname">Full Name *</label>
                                <input type="text" id="fullname" name="fullname" required>
                            </div>
                            <div class="group-input col-md-6">
                                <label for="date">Date of birth *</label>
                                <input type="text" id="date" name="date" required autocomplete="off" placeholder="dd/mm/yyyy" pattern="\d{1,2}/\d{1,2}/\d{4}">

                            </div>
                        </div>
                        <div class="group-input">
                            <label for="email">Email *</label>
                            <input type="text" id="email" name="email" required>
                        </div>
                        <div class="group-input">
                            <label for="pass">Password *</label>
                            <input type="password" id="pass" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="con-pass">Address *</label>
                            <input type="text" id="con-pass" name="address" required>
                        </div>
                        <div class="group-input">
                            <label for="con">Contact *</label>
                            <input type="text" id="con" name="contact" required>
                        </div>

                        <button type="submit" class="site-btn register-btn" name="register">REGISTER</button>
                    </form>
                    <div class="switch-login">
                        <a href="signin.php" class="or-login">Or Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('layout/footer.php') ?>