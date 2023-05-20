<?php
require_once('db/dbhelper.php');

// Dùng isset để kiểm tra Form
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $passwordInput = trim($_POST['password']);
    $passwordConfirm = trim($_POST['password_confirm']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    $errors = array(); // Khởi tạo mảng lưu trữ lỗi

    // Kiểm tra các trường bắt buộc
    if (empty($username)) {
        $errors[] = "Username is required";
    }
    if (empty($email)) {
        $errors[] = "Email is required";
    }
    if (empty($phone)) {
        $errors[] = "Phone is required";
    }
    if (empty($passwordInput)) {
        $errors[] = "Password is required";
    }
    if ($passwordInput !== $passwordConfirm) {
        $errors[] = "Password confirmation does not match";
    }

    if (!empty($errors)) {
        // Hiển thị lỗi nếu có
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
        die(); // Dừng chương trình nếu có lỗi
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Email is not valid.';
        die(); // Dừng chương trình nếu email không hợp lệ
    }

    // Kiểm tra username hoặc email có bị trùng hay không
    $sql = "SELECT * FROM member WHERE username = '$username' OR email = '$email'";

    // Thực thi câu truy vấn
    $result = executeResult($sql);

    // Nếu kết quả trả về lớn hơn 0 thì nghĩa là username hoặc email đã tồn tại trong CSDL
    if (count($result) > 0) {
        echo '<script language="javascript">alert("Duplicate or unknown name!"); window.location="register.php";</script>';

        // Dừng chương trình
        die();
    } else {
        // Mã hóa mật khẩu
        $hashedPassword = password_hash($passwordInput, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user (username, password, email, phone) VALUES ('$username','$hashedPassword','$email','$phone')";

        execute($sql);

        echo '<script language="javascript">alert("Successfully!"); window.location="register.php";</script>';
    }
}

?>


<!-- Các phần HTML khác của trang register.php -->
<!-- ... -->