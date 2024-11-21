<?php

include('../../conn.php');

if (isset($_POST['update'])) {
    $ID = $_POST['id'];
    $NAME  = mysqli_real_escape_string($conn, $_POST['name']);
    $PRICE = mysqli_real_escape_string($conn, $_POST['price']);
    $cattigur = mysqli_real_escape_string($conn, $_POST['cattigur']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $imageNames = [];

    if (isset($_FILES['image'])) {
        foreach ($_FILES['image']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['image']['name'][$key];
            $file_tmp = $_FILES['image']['tmp_name'][$key];
            $file_size = $_FILES['image']['size'][$key];
            $file_error = $_FILES['image']['error'][$key];

            if ($file_error === 0) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $file_type = mime_content_type($file_tmp);

                if (in_array($file_type, $allowedTypes) && $file_size <= 2000000) {
                    $uploadPath = "../des/images/" . basename($file_name);

                    if (move_uploaded_file($file_tmp, $uploadPath)) {
                        $imageNames[] = $file_name;
                    } else {
                        echo "فشل في رفع الصورة: $file_name.<br>";
                    }
                } else {
                    echo "نوع الملف غير مدعوم أو حجم الملف كبير جدًا: $file_name.<br>";
                }
            } else {
                echo "خطأ في رفع الملف: $file_name.<br>";
            }
        }

        if (empty($imageNames)) {
            echo "لم يتم رفع أي صور.";
        } else {
            // JSON-encode the array and escape it for SQL
            $jsonImageNames = mysqli_real_escape_string($conn, json_encode($imageNames));

            // Update query with escaped JSON string
            $update = "UPDATE salles SET name='$NAME', price='$PRICE', cattigur='$cattigur', brand='$brand', cover='$jsonImageNames' WHERE id='$ID'";

            if (mysqli_query($conn, $update)) {
                header('Location: http://localhost/my_site3/admin/all_pro.php');
                exit();
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        }
    }
} else {
    echo "لم يتم اختيار أي صور.";
}
