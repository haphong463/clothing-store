<?php
header('Content-Type: text/html; charset=utf-8');
// Kết nối cơ sở dữ liệu
$conn = mysqli_connect('localhost', 'root', '', 'data') or die('Connection errors');
mysqli_set_charset($conn, "utf8");

// Dùng isset để kiểm tra Form
if (isset($_POST['signin'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $errors = array(); // Khởi tạo mảng lưu trữ lỗi

    // Kiểm tra các trường bắt buộc
    if (empty($username)) {
        $errors[] = "Username is required";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    if (!empty($errors)) {
        // Hiển thị lỗi nếu có
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
        die(); // Dừng chương trình nếu có lỗi
    }

    // Kiểm tra username trong CSDL
    $sql = "SELECT * FROM member WHERE username = '$username'";

    // Thực thi câu truy vấn
    $result = mysqli_query($conn, $sql);

    // Nếu có kết quả trả về
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $hashedPassword = $row['password'];

        // Kiểm tra mật khẩu
        if (password_verify($password, $hashedPassword)) {
            echo '<script language="javascript">alert("Sign in successful!"); window.location="index.php";</script>';
        } else {
            echo '<script language="javascript">alert("Invalid username or password!"); window.location="signin.php";</script>';
        }
    } else {
        echo '<script language="javascript">alert("Invalid username or password!"); window.location="signin.php";</script>';
    }
}

// Kiểm tra thông tin đăng nhập thành công
if ($login_successful) {
    // Chuyển hướng người dùng đến trang index
    header("Location: index.php");
    exit(); // Đảm bảo dừng việc thực thi mã sau khi chuyển hướng
}

mysqli_close($conn);
?>
