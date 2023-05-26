<?php
require_once '../../db/dbhelper.php';

if (isset($_POST['update-product'])) {
    $id = $_POST['pid'];
    $name = $_POST['name'];
    $category = $_POST['cat_id'];
    $type = $_POST['p_cat_id'];
    $description = $_POST['desc'];
    $colors = explode(',', $_POST['color']);
    $sizes = explode(',', $_POST['size']);
    $hexs = explode(',', $_POST['hex']);
    $quantity = explode(',', $_POST['quantity']);
    $price = number_format($_POST['price'], '2', '.');
    $keyword = $_POST['keyword'];
    $desc = addslashes($description);

    $description = addslashes($description);

    $sql_delete_variant = "DELETE FROM product_variant WHERE p_id = $id";
    execute($sql_delete_variant);

    $sql_delete_size = "DELETE FROM product_size WHERE pid = $id";
    execute($sql_delete_size);

    $sql_delete_quantity = "DELETE FROM product_quantity WHERE pid = $id";
    execute($sql_delete_quantity);

    $sql_delete_color = "DELETE FROM product_color WHERE pid = $id";
    execute($sql_delete_color);
    $sql = "UPDATE product SET cat_id = $category, p_cat_id = $type, name = '$name', price = $price, description = '$description', updated_at = NOW() WHERE pid = $id";
    execute($sql);



    // Kiểm tra xem người dùng đã tải lên ảnh mới hay chưa
    if (!empty($_FILES['image']['name'][0])) {
        // Lấy các đường dẫn hình ảnh cũ từ cơ sở dữ liệu
        $sql_image_old = "SELECT pi.image_path, pt.thumbnail FROM product_image AS pi INNER JOIN product_thumbnail AS pt ON pi.pid = pt.pid WHERE pi.pid = $id";
        $result_image_old = executeResult($sql_image_old);

        // Tạo một mảng lưu trữ các đường dẫn của các hình ảnh cũ
        $old_image_paths = array();
        foreach ($result_image_old as $image_old) {
            $old_image_paths[] = ($image_old['image_path']);
            $old_image_paths[] = ($image_old['thumbnail']);
        }

        // Xóa các hình ảnh cũ trong thư mục và cơ sở dữ liệu
        foreach ($old_image_paths as $old_image_path) {
            $file_path = '../../' . $old_image_path;
            if (file_exists($file_path)) {
                unlink($file_path); // Xóa ảnh thực tế trong thư mục
            }
            $sql_delete_image = "DELETE FROM product_image WHERE image_path = '$old_image_path'";
            execute($sql_delete_image); // Xóa ảnh trong cơ sở dữ liệu

            $sql_delete_thumbnail = "DELETE FROM product_thumbnail WHERE thumbnail = '$old_image_path'";
            execute($sql_delete_thumbnail); // Xóa ảnh trong cơ sở dữ liệu
        }
    }
    $takeid = $id;
    include('uploadImage.php');

    $sql_variant = "INSERT INTO product_variant (p_id, keyword) 
    VALUES ($id, '$keyword')";
    execute($sql_variant);


    $variant_values = array();
    foreach ($sizes as $size) {
        $size = trim($size);
        $variant_values[] = "($id, '$size')";
    }
    if (!empty($variant_values)) {
        $sql_variant = "INSERT INTO product_size (pid, size)
VALUES " . implode(', ', $variant_values);
        execute($sql_variant);
    }

    // Thêm thông tin về quantity (màu sắc và số lượng)
    $quantity_values = array();
    foreach ($sizes as $size) {
        foreach ($colors as $index => $color) {
            $qty = isset($quantity[$index]) ? intval($quantity[$index]) : 0;
            $quantity_values[] = "($id, '$size', '$color', $qty)";
        }
    }
    if (!empty($quantity_values)) {
        $sql_quantity = "INSERT INTO product_quantity (pid, size, color, quantity)
VALUES " . implode(', ', $quantity_values);
        execute($sql_quantity);
    }

    // Thêm thông tin về màu sắc
    $color_values = array();
    foreach ($colors as $index => $color) {
        $color = trim($color);
        $hex = isset($hexs[$index]) ? trim($hexs[$index]) : '';
        $color_values[] = "($id, '$color', '$hex')";
    }
    if (!empty($color_values)) {
        $sql_color = "INSERT INTO product_color (pid, color_name, hex)
VALUES " . implode(', ', $color_values);
        execute($sql_color);
    }
}
header('location: ../product.php');
