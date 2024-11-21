<?php
// الاتصال بقاعدة البيانات
include('admin/conn.php');
session_start();
$user_id = $_SESSION['user_id'];

// التحقق من أن الطلب تم إرساله عبر POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // استقبال البيانات من النموذج
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $company = !empty($_POST['company']) ? mysqli_real_escape_string($conn, $_POST['company']) : "NULL";
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $address2 = mysqli_real_escape_string($conn, $_POST['addressalt']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);

    // استقبال طريقة الدفع
    $paymentMethod = mysqli_real_escape_string($conn, $_POST['paymentMethod']);

    // إذا كانت بطاقة ائتمان، استقبال بيانات البطاقة
    if ($paymentMethod == 'Credit Card') {
        $cardNumber = mysqli_real_escape_string($conn, $_POST['cardNumber']);
        $cardExpiry = mysqli_real_escape_string($conn, $_POST['cardExpiry']);
        $cardCVC = mysqli_real_escape_string($conn, $_POST['cardCVC']);

        // يجب هنا إضافة الكود لمعالجة بيانات البطاقة عبر خدمة دفع (مثل Stripe أو غيره)
    }

    // إذا كانت PayPal، يمكنك توجيه المستخدم إلى صفحة PayPal لإتمام الدفع

    // حساب المجموع النهائي
    $total = $_POST['grandtotal'];  // أو قم بجلب القيمة من قاعدة البيانات كما هو في الكود السابق

    // إدخال الطلب في جدول الطلبات (orders)
    $insertOrder = "INSERT INTO orders (user_id, firstName, lastName, email, phone, company, country, address, address2, city, state, total, paymentMethod)
                    VALUES ('$user_id', '$firstName', '$lastName', '$email', '$phone', '$company', '$country', '$address', '$address2', '$city', '$state', '$total', '$paymentMethod')";

    if (mysqli_query($conn, $insertOrder)) {

        // الحصول على ID آخر إدخال في جدول orders
        $last_order_id = mysqli_insert_id($conn);

        // تحديث عناصر cart باستخدام order_id
        mysqli_query($conn, "UPDATE cart SET order_id = '$last_order_id' WHERE user_id = '$user_id' AND order_id IS NULL") or die('query failed');
        mysqli_query($conn, "UPDATE cart_orders SET order_id = '$last_order_id' WHERE user_id = '$user_id' AND order_id IS NULL") or die('query failed');


        // بعد نجاح الطلب، مسح العربة أو توجيه المستخدم إلى صفحة تأكيد
        $deleteCart = "DELETE FROM cart WHERE user_id='$user_id'";
        mysqli_query($conn, $deleteCart);

        // توجيه المستخدم إلى صفحة تأكيد الطلب
        header("Location: orderConfirmation.php?orderSuccess=true");
    } else {
        echo "Error: " . $insertOrder . "<br>" . mysqli_error($conn);
    }
}
