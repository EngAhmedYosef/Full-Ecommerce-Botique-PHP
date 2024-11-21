<?php 



if ($_SERVER['REQUEST_METHOD'] == "POST"){
	
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	

	
	$conn = new mysqli('localhost','root','', 'shop');

	if($conn -> query("INSERT INTO users (name , email , password , permission) VALUES ('$username' ,'$email' , '$password' , 2)")){

			echo "<div class='alert alert-success'>message sent successfully<div>";
			

	} else {
		echo $conn -> error ;
	}

	







} else {

	echo " page not found";
}