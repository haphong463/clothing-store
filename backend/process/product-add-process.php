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


        $takeid = $product_id;
        include('uploadImage.php');

        $sql_variant = "INSERT INTO product_variant (p_id, size, color, quantity, keyword) VALUES ($product_id, '$size', '$color', $quantity, '$keyword')";
        execute($sql_variant);
        header('location: ../product.php');
    }
