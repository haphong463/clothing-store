<?php include('layout/header.php') ?>

<section class="page-add">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="page-breadcrumb">
                    <h2>SIGN IN<span>.</span></h2>
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
                        <div class="group-input">
                            <label for="email">Email/Username *</label>
                            <input type="text" id="email" name="email" required>
                        </div>
                        <div class="group-input">
                            <label for="pass">Password *</label>
                            <input type="password" id="pass" name="password" required>
                        </div>
                        <button type="submit" class="site-btn login-btn" name="login">LOGIN</button>
                    </form>
                    <div class="switch-login">
                        <a href="register.php" class="or-login">Or Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('layout/footer.php') ?>