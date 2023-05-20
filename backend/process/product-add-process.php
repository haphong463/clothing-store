    <?php
    require_once '../../db/dbhelper.php';
    if (isset($_POST['create-product'])) {
        $name = $_POST['name'];
        $category = $_POST['cat_id'];
        $type = $_POST['p_cat_id'];
        $description = $_POST['desc'];
        $colors = $_POST['color'];
        $sizes = $_POST['size'];
        $price = number_format($_POST['price'], '2', '.');
        $keyword = $_POST['keyword'];
        $quantity = intval($_POST['quantity']);
        $hexs = $_POST['hex'];
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


        $takeid = $product_id;
        include('uploadImage.php');

        $sql_variant = "INSERT INTO product_variant (p_id, size, color, quantity, keyword) 
                                VALUES ($product_id, '$sizes', '$colors', $quantity, '$keyword')";
        execute($sql_variant);


        $color_array = explode(',', $colors);
        $hex_array = explode(',', $hexs);
        
        $count = min(count($color_array), count($hex_array)); // Đảm bảo số lượng phần tử bằng nhau
        
        for ($i = 0; $i < $count; $i++) {
            $color = trim($color_array[$i]);
            $hex = trim($hex_array[$i]);
        
            $sql_color = "INSERT INTO color (pid, color_name, hex) VALUES ($product_id, '$color', '$hex')";
            execute($sql_color);
        }
        
        header('location: ../product.php');
    }
