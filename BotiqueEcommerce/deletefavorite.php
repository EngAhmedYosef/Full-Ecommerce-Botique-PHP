<?php 

include("admin/conn.php");
session_start();
$user_id = $_SESSION['user_id'];
$id = $_GET['id'];

$fav_del = mysqli_query($conn,"DELETE FROM favorite WHERE id='$id' AND user_id='$user_id'");
header("location: favorite.php");
