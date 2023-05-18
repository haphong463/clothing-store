<?php
require_once '../../db/dbhelper.php';

if (isset($_POST['update-product'])) {
    $id = $_POST['pid'];
    $name = $_POST['name'];
    $category = $_POST['cat_id'];
    $type = $_POST['p_cat_id'];
    $description = $_POST['desc'];
    $color = $_POST['color'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $keyword = $_POST['keyword'];
    $quantity = $_POST['quantity'];
    $description = addslashes($description);

    $target_dir = "../../image/product/";
    $images = $_FILES['image'];

    $sql = "UPDATE product SET cat_id = $category, p_cat_id = $type, name = '$name', price = $price, description = '$description', updated_at = NOW() WHERE pid = $id";
    execute($sql);

    // Kiểm tra xem người dùng đã tải lên ảnh mới hay chưa
    if (!empty($_FILES['image']['name'][0])) {
        // Lấy các đường dẫn hình ảnh cũ từ cơ sở dữ liệu
        $sql_image_old = "SELECT * FROM product_image WHERE pid = $id";
        $result_image_old = executeResult($sql_image_old);

        // Tạo một mảng lưu trữ các đường dẫn của các hình ảnh cũ
        $old_image_paths = array();
        foreach ($result_image_old as $image_old) {
            $old_image_paths[] = $image_old['image_path'];
        }

        // Xóa các hình ảnh cũ trong thư mục và cơ sở dữ liệu
        foreach ($old_image_paths as $old_image_path) {
            $file_path = '../../' . $old_image_path;
            if (file_exists($file_path)) {
                unlink($file_path); // Xóa ảnh thực tế trong thư mục
            }
            $sql_delete_image = "DELETE FROM product_image WHERE image_path = '$old_image_path'";
            execute($sql_delete_image); // Xóa ảnh trong cơ sở dữ liệu
        }

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

        // Tải lên và lưu các ảnh mới
       
        $takeid = $product_id;
        include('uploadImage.php');
        // Cập nhật thông tin biến thể sản phẩm trong bảng "Product Variant"
        $sql_variant = "UPDATE product_variant SET size = '$size', color = '$color', quantity = $quantity, keyword = '$keyword' WHERE p_id = $id";
        execute($sql_variant);

        // Chuyển hướng về trang quản lý sản phẩm

    }
}
header('location: ../product.php');
