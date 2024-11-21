<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Form-v9 by Colorlib</title>
	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<!-- Font-->
	<link rel="stylesheet" type="text/css" href="css/nunito-font.css">
	<!-- Main Style Css -->
    <link rel="stylesheet" href="css/style.css"/>
</head>
<body class="form-v9">



	<?php 

include ('../admin/conn.php');




// if ($_SERVER['REQUEST_METHOD']=="POST") {
// $username = $_POST['username'];
// $email = $_POST['email'];
// $password = $_POST['password'];




// $insertUser = "INSERT INTO users (name , email , password , permission) VALUES ('$username' , '$email' , '$password',3)";


// $conn -> query($insertUser);






	
// 	}
?>



	<div class="page-content">
		<div class="form-v9-content" style="background-image: url('images/form-v9.jpg')">
			<form class="form-detail" action="../admin/users.php" method="post">
				<h2>AddUser Form</h2>
				<div class="form-row-total">
					<div class="form-row">
						<input type="text" class="username" placeholder="Your Name" required>
						<br>
					</div>

					<div class="form-row">
						<input type="text" class="email" placeholder="Your Email" required>
					</div>

				</div>
				<div class="form-row-total">
					<div class="form-row">
						<input type="password" class="password" placeholder="Your Password" required>
					</div>
					
				</div>
				<div class="form-row-last">
					<input type="submit" name="register" class="register" value="Add">

				</div>

			</form>
		</div>
	</div>

	<script src="js/jquery-3.6.0.min.js"></script>
<script>
	
	$('form').submit(function(event){

       event.preventDefault();
       

       // get form values 
       var username = $('.username').val();
       var email = $('.email').val();
       var password = $('.password').val();

       //post req server.php insert
       $.post('server.php' , {
        
        'username' : username ,
        'email' : email ,
        'password' : password ,

       } , function(res){

            console.log(res);

       });


       

	})


</script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>