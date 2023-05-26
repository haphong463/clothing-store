    <?php
    require_once '../../db/dbhelper.php';
    if (isset($_POST['create-product'])) {
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

        // Kiểm tra số lượng ảnh tải lên không vượt quá 9
        // if (is_array($images['name'])) {
        //     if (count($images['name']) > 9) {
        //         echo 'You can upload up to 9 images only';
        //         exit();
        //     }
        // } else {
        //     $images['name'] = [$images['name']];
        //     $images['type'] = [$images['type']];
        //     $images['tmp_name'] = [$images['tmp_name']];
        //     $images['error'] = [$images['error']];
        //     $images['size'] = [$images['size']];
        // }

        // Thêm thông tin sản phẩm vào bảng "Product"
        $sql = "INSERT INTO product (cat_id, p_cat_id, name, price, description, created_at)
                VALUES ($category, $type, '$name', $price, '$desc', NOW())";
        execute($sql);

        // Lấy id của sản phẩm vừa thêm
        $sql_max = "SELECT max(pid) as maxID FROM product";
        $result = executeSingleResult($sql_max);
        $product_id = $result['maxID'];


        $takeid = $product_id;
        include('uploadImage.php');




        $sql_variant = "INSERT INTO product_variant (p_id, keyword) 
                                VALUES ($product_id, '$keyword')";
        execute($sql_variant);


        $variant_values = array();
        foreach ($sizes as $size) {
            $size = trim($size);
            $variant_values[] = "($product_id, '$size')";
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
                $quantity_values[] = "($product_id, '$size', '$color', $qty)";
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
            $color_values[] = "($product_id, '$color', '$hex')";
        }
        if (!empty($color_values)) {
            $sql_color = "INSERT INTO product_color (pid, color_name, hex)
              VALUES " . implode(', ', $color_values);
            execute($sql_color);
        }

        // header('location: ../product.php');
    }
