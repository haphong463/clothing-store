<?php
require_once('db/dbhelper.php');



if (isset($_POST['register'])) {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $passwordInput = trim($_POST['password']);
    $email = trim($_POST['email']);
    $date = trim($_POST['date']);
    $address = trim($_POST['address']);
    $contact = trim($_POST['contact']);
    $errors = array();

    if (empty($fullname) || empty($username) || empty($passwordInput) || empty($email) || empty($date) || empty($address) || empty($contact)) {
        $errors[] = "All fields are required";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<span class="error">' . $error . '</span><br>';
        }
    } else {
        $hashedPassword = password_hash($passwordInput, PASSWORD_DEFAULT);
        // Chuyển đổi định dạng ngày tháng dd/mm/yyyy thành yyyy-mm-dd
        $dateParts = explode('/', $date);
        $formattedDate = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
        $sql = "INSERT INTO users (username, full_name, password, email, date_of_birth, address, contact) 
                VALUES ('$username','$fullname', '$hashedPassword', '$email', '$formattedDate', '$address', '$contact')";
        execute($sql);
    }
}
?>

<?php include('layout/header.php');

if (!isset($_SESSION['c_username_email'])) {
?>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var form = document.getElementById("logform");
            var emailInput = document.getElementById("email");
            var emailError = document.getElementById("email-error");

            form.addEventListener("submit", function(event) {
                if (!validateEmail(emailInput.value)) {
                    emailError.textContent = "Invalid email format";
                    event.preventDefault();
                } else {
                    emailError.textContent = "";
                }
            });

            function validateEmail(email) {
                var pattern = /^\S+@\S+\.\S+$/;
                return pattern.test(email);
            }
        });
    </script>

    <section class="page-add">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="page-breadcrumb">
                        <h2>SIGN UP<span>.</span></h2>
                    </div>
                </div>
                <?php
                include('layout/discount.php');
                ?>
            </div>
        </div>
    </section>

    <div class="register-login-section spad">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="register-form">
                        <form action="register.php" method="post" id="logform">
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
                                <label for="con">Username *</label>
                                <input type="text" id="con" name="username" required>
                            </div>
                            <div class="group-input">
                                <label for="email">Email *</label>
                                <input type="text" id="email" name="email" required>
                                <span id="email-error" class="error"></span>
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
                            <a href="signin.php" class="or-login">ALREADY HAVE AN ACCOUNT?</a>
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