<?php 

include("conn.php");

if (isset($_POST['add'])) {
    $username = $_POST['username'];
    $useremail = $_POST['useremail'];
    $userpassword = md5($_POST['userpassword']);
    $userpermission = $_POST['permission'];

    $sql = "INSERT INTO users(name,email,password,permission) VALUES('$username','$useremail','$userpassword','$userpermission')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        header("location: users.php");
    }
    else {
        echo "Error User Not Inserted";
    }
}