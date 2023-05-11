<?php
require_once '../../db/dbhelper.php';
$name = $image = '';
if (isset($_POST['name'])) {
    $name = $_POST['name'];
}
if (isset($_FILES['image'])) {
    $target_dir = "../../image/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $upload_ok = 1;
    $image_file_type =
        strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    //kiem tra dinh dang file anh
    if (
        $image_file_type != "jpg" && $image_file_type != "png"
        && $image_file_type != "jpeg" && $image_file_type != "gif"
    ) {
        echo 'Only JPG, JPEG, PNG, GIF files are allowed';
        $upload_ok = 0;
    }

    //kiem tra trung ten anh
    if (file_exists($target_file)) {
        echo 'The file name already exits. Pls change your file name!';
        $upload_ok = 0;
    }

    //lay file anh
    if ($upload_ok == 1) {
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        //echo 'upload successfully!';     
    }
    $image = '../image/' . $_FILES["image"]["name"];
}
$sql = "INSERT INTO category (image, name) VALUES ('$image','$name') ";
execute($sql);
header('Location: ../category.php');
