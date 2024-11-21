<?php 

include("../../conn.php");

    $id = $_GET['id'];
    $sql = "DELETE FROM brand WHERE id=$id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        header("location: ../../brand.php");
    }
    else{
        echo "Error With Delete";
    }
