<?php 

include("../../conn.php");

    $id = $_GET['id'];
    $sql = "DELETE FROM cattigur WHERE id=$id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        header("location: ../../category.php");
    }
    else{
        echo "Error With Delete";
    }
