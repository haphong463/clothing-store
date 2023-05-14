<?php
require_once '../../db/dbhelper.php';

if (isset($_POST['create-product'])) {
    $name = $_POST['name'];
    $category = $_POST['cat_id'];
    $type = $_POST['p_cat_id'];
    $description = $_POST['desc'];
    $color = $_POST['color'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $keyword = $_POST['keyword'];
    $quantity = $_POST['quantity'];

    $target_dir = "../../image/product/";
    $images = $_FILES['image'];
    $desc = addslashes($description);

    // Kiểm tra số lượng ảnh tải lên không vượt quá 9
    if (is_array($images['name'])) {
        if (count($images['name']) > 9) {
            echo 'You can upload up to 9 images only';
            exit();
        }
    } else {
        $images['name'] = [$images['name']];
        $images['type'] = [$images['type']];
        $images['tmp_name'] = [$images['tmp_name']];
        $images['error'] = [$images['error']];
        $images['size'] = [$images['size']];
    }

    // Thêm thông tin sản phẩm vào bảng "Product"
    $sql = "INSERT INTO product (cat_id, p_cat_id, name, price, description, created_at)
            VALUES ($category, $type, '$name', $price, '$desc', NOW())";
    execute($sql);

    // Lấy id của sản phẩm vừa thêm
    $sql_max = "SELECT max(pid) as maxID FROM product";
    $result = executeSingleResult($sql_max);
    $product_id = $result['maxID'];


    // Lặp qua từng file ảnh được tải lên
    foreach ($images['name'] as $index => $image_name) {
        $target_file = $target_dir . basename($image_name);

        // Xử lý tệp tin và lưu vào thư mục đích...
        $upload_ok = 1;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Kiểm tra định dạng file ảnh
        if (!in_array($image_file_type, ['jpg', 'png', 'avif', 'webp'])) {
            echo 'Only JPG, AVIF, PNG, WEBP files are allowed';
            $upload_ok = 0;
        }

        // Kiểm tra trùng tên ảnh
        if (file_exists($target_file)) {
            echo 'The file name already exists. Please change your file name!';
            $upload_ok = 0;
        }

        // Lưu file ảnh
        if ($upload_ok == 1) {
            move_uploaded_file($images['tmp_name'][$index], $target_file);
            $image_path = 'image/product/' . $image_name;

            // Thêm thông tin hình ảnh vào bảng "Product Image"
            $sql_image = "INSERT INTO product_image (pid, image_path) VALUES ($product_id, '$image_path')";
            execute($sql_image);
        }
    }

    // Thêm thông tin biến thể sản phẩm vào bảng "Product Variant"
    $sql_variant = "INSERT INTO product_variant (p_id, size, color, quantity, keyword) VALUES ($product_id, '$size', '$color', $quantity, '$keyword')";
    execute($sql_variant);
    header('location: ../product.php');
}
