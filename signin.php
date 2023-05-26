<?php include('layout/header.php') ?>
<?php
if (!isset($_SESSION['c_username_email'])) {

    require_once('db/dbhelper.php');

    // Kiểm tra xem kết nối có thành công không
    $con = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);
    if (!$con) {
        echo 'Lỗi kết nối cơ sở dữ liệu: ' . mysqli_connect_error();
        exit(); // Dừng chương trình nếu có lỗi
    }

    if (isset($_POST['signin'])) {
        $email_or_username = trim($_POST['email_or_username']);
        $password = trim($_POST['password']);
        $errors = array();

        if (empty($email_or_username)) {
            $errors[] = "Username or Email is required";
        }
        if (empty($password)) {
            $errors[] = "Password is required";
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
            exit();
        }

        $email_or_username = mysqli_real_escape_string($con, $email_or_username); // Chống SQL injection

        $sql = "SELECT * FROM users WHERE username = '$email_or_username' OR email = '$email_or_username'";
        $result = executeResult($sql);
        if ($result && count($result) > 0) {
            $row = $result[0];
            $hashedPassword = $row['password'];

            if (password_verify($password, $hashedPassword)) {
                echo '<script language="javascript">window.location="index.php";</script>';
                $_SESSION['c_username_email'] = array(
                    'email' => $row['email'],
                    'username' => $row['username']
                );
                exit();
            }
        } else {
            echo '<script language="javascript">alert("Invalid email or username!"); window.location="signin.php";</script>';
            exit();
        }
    }
?>


    <section class="page-add">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="page-breadcrumb">
                        <h2>SIGN IN<span>.</span></h2>
                    </div>
                </div>
                <?php
                include ('layout/discount.php');
            ?>
            </div>
        </div>
    </section>

    <div class="register-login-section spad">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="register-form">
                        <form action="signin.php" method="post" enctype="multipart/form-data" id="logform">
                            <div class="group-input">
                                <label for="email">Email/Username *</label>
                                <input type="text" id="email" name="email_or_username" required>
                            </div>
                            <div class="group-input">
                                <label for="pass">Password *</label>
                                <input type="password" id="pass" name="password" required>
                            </div>
                            <button type="submit" class="site-btn login-btn" name="signin">SIGN IN</button>
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

<?php
} else {
    echo '<script language="javascript">window.location="index.php";</script>';
    exit();
}
?>