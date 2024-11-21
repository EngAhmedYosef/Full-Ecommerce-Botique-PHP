<?php
    include("../../conn.php");


    if (isset($_POST['editbrand'])) {
        $id = $_POST['id'];
        $brand = $_POST['brand'];
        $sql = "UPDATE `brand` SET `name`='$brand' WHERE id=$id";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            header("location: ../../brand.php");
        } else {
            echo "Error Category Not Inserted";
        }
    }
    