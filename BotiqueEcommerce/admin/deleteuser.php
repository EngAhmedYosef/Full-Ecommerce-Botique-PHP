<?php 

include("conn.php");

    $id = $_GET['id'];
    $sql = "DELETE FROM users WHERE id=$id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        header("location: users.php");
    }
    else{
        echo "Error With Delete";
    }
