<?php

include("conn.php");

$id = $_GET['id'];
$sql = "DELETE FROM orders WHERE id='$id'";

// $sql = "DELETE cart_orders, orders 
//     FROM cart 
//     INNER JOIN orders ON cart_orders.user_id = orders.user_id 
//     WHERE cart_orders.user_id = '$id'";

$result = mysqli_query($conn, $sql);
if ($result) {
    header("location: orders.php");
} else {
    echo "Error With Delete";
}
