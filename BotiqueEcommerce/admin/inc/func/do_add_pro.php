<?php
include('../../conn.php');

session_start();
$admin = isset($_SESSION['admin']) ? mysqli_real_escape_string($conn, $_SESSION['admin']) : null;

if (isset($_POST['submit'])) {
    $elname = isset($_POST['elname']) ? mysqli_real_escape_string($conn, $_POST['elname']) : '';
    $decr = isset($_POST['decr']) ? mysqli_real_escape_string($conn, $_POST['decr']) : '';
    $price = isset($_POST['price']) ? mysqli_real_escape_string($conn, $_POST['price']) : '';
    $brand = isset($_POST['brand']) ? mysqli_real_escape_string($conn, $_POST['brand']) : '';
    $cat = isset($_POST['cat']) ? mysqli_real_escape_string($conn, $_POST['cat']) : '';
    $sold = isset($_POST['sold']) ? mysqli_real_escape_string($conn, $_POST['sold']) : '';
    $count = isset($_POST['count']) ? mysqli_real_escape_string($conn, $_POST['count']) : '';

    $imageNames = [];

    if (isset($_FILES['image'])) {
        foreach ($_FILES['image']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['image']['name'][$key]; // بدون mysqli_real_escape_string()
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
            $jsonImageNames = json_encode($imageNames);

            $stmt = $conn->prepare("INSERT INTO salles (name, cattigur, brand, price, cover, content, sold, saller, count) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param("sssssssss", $elname, $cat, $brand, $price, $jsonImageNames, $decr, $sold, $admin, $count);

            if ($stmt->execute()) {
                echo "تم رفع المنتج بنجاح.";
                header('Location: ../../all_pro.php');
                exit();
            } else {
                echo "خطأ: " . $stmt->error;
            }
        }
    } else {
        echo "لم يتم اختيار أي صور.";
    }
} else {
    echo "لم يتم تقديم النموذج.";
}

$conn->close();

