<?php 



if ($_SERVER['REQUEST_METHOD'] == "POST"){
	
	$username = $_POST['username'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$message = $_POST['message'];

	
	$conn = new mysqli('localhost','root','', 'shop');

	if($conn -> query("INSERT INTO messages (name , email , phone , message) VALUES ('$username' ,'$email' , '$phone' , '$message')")){

			echo "<div class='alert alert-success'>message sent successfully<div>";

	} else {
		echo $conn -> error ;
	}

	







} else {

	echo " page not found";
}