<?php
include("../../conn.php");

if (isset($_POST['editcategory'])) {
    $id = $_POST['id'];
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    $IMAGE = $_FILES['image'];
    $image_location = $_FILES['image']['tmp_name'];
    $image_name = mysqli_real_escape_string($conn, $_FILES['image']['name']); // Escape special characters
    move_uploaded_file($image_location, 'images/' . $image_name);
    $image_up = "images/" . $image_name;

    $sql = "UPDATE `cattigur` SET `name`='$category', `image`='$image_up' WHERE id=$id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        header("location: ../../category.php");
    } else {
        echo "Error: " . mysqli_error($conn); // Display SQL error
    }
}

