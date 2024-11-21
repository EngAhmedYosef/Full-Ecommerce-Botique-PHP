<?php
// الاتصال بقاعدة البيانات
include('admin/conn.php');

// التحقق مما إذا كان الطلب قد تم بنجاح
if (isset($_GET['orderSuccess']) && $_GET['orderSuccess'] == 'true') {
    // جلب تفاصيل الطلب من قاعدة البيانات
    $orderQuery = "SELECT * FROM orders WHERE id = (SELECT MAX(id) FROM orders)"; // افتراض أن الطلب الأخير هو الذي تم تأكيده للتو
    $orderResult = mysqli_query($conn, $orderQuery);
    
    if ($orderResult && mysqli_num_rows($orderResult) > 0) {
        $orderDetails = mysqli_fetch_assoc($orderResult);
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Order Confirmation</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 800px;
                    margin: 50px auto;
                    background-color: #fff;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                h1, h2, h3 {
                    color: #333;
                }
                p {
                    color: #555;
                    line-height: 1.6;
                }
                ul {
                    list-style: none;
                    padding: 0;
                }
                li {
                    margin: 10px 0;
                }
                strong {
                    color: #000;
                }
                .btn {
                    display: inline-block;
                    background-color: #000;
                    color: #fff;
                    padding: 10px 20px;
                    text-decoration: none;
                    border-radius: 5px;
                    margin-top: 20px;
                }
                .btn:hover {
                    background-color: #444;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Order Confirmation</h1>
                <p>Thank you, <strong><?php echo $orderDetails['firstName']; ?></strong>! Your order has been placed successfully.</p>
                
                <h2>Order Details</h2>
                <ul>
                    <li><strong>First Name:</strong> <?php echo $orderDetails['firstName']; ?></li>
                    <li><strong>Last Name:</strong> <?php echo $orderDetails['lastName']; ?></li>
                    <li><strong>Email:</strong> <?php echo $orderDetails['email']; ?></li>
                    <li><strong>Phone:</strong> <?php echo $orderDetails['phone']; ?></li>
                    <li><strong>Company (if provided):</strong> <?php echo $orderDetails['company']; ?></li>
                    <li><strong>Address:</strong> <?php echo $orderDetails['address'] . ", " . $orderDetails['address2']; ?></li>
                    <li><strong>City:</strong> <?php echo $orderDetails['city']; ?></li>
                    <li><strong>State:</strong> <?php echo $orderDetails['state']; ?></li>
                    <li><strong>Country:</strong> <?php echo $orderDetails['country']; ?></li>
                    <li><strong>Total:</strong> $<?php echo number_format($orderDetails['total'], 2); ?></li>
                    <li><strong>Payment Method:</strong> <?php echo $orderDetails['paymentMethod']; ?></li>
                </ul>

                <h3>Next Steps</h3>
                <p>Your order will be processed and shipped to the provided address soon.</p>
                <p>If you have any questions, please contact us at <a href="contact.php">Help & Contact</a>.</p>

                <a href="index.php" class="btn">Continue Shopping</a>
            </div>
        </body>
        </html>

        <?php
    } else {
        echo "<p>There was an issue retrieving your order details. Please try again later.</p>";
    }
} else {
    echo "<p>No order to confirm.</p>";
}
?>
