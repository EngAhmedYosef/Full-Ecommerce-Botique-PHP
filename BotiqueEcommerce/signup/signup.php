<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Registration</title>
	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<!-- Font-->
	<link rel="stylesheet" type="text/css" href="css/nunito-font.css">
	<!-- Main Style Css -->
	<link rel="stylesheet" href="css/style.css" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="form-v9">



	<?php

	include('../admin/conn.php');
	include('../include/functions.php');

	//Import PHPMailer classes into the global namespace
	//These must be at the top of your script, not inside a function
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	//required files
	require '../phpmailer/src/Exception.php';
	require '../phpmailer/src/PHPMailer.php';
	require '../phpmailer/src/SMTP.php';



	if (isset($_POST['register'])) {
		$name = mysqli_real_escape_string($conn, $_POST['name']);
		$email = mysqli_real_escape_string($conn, $_POST['email']);
		$pass = mysqli_real_escape_string($conn, md5($_POST['password']));
		$cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
		$verfiycode = rand(10000, 99999);


		if ($pass == $cpass) {

			$select = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');

			if (mysqli_num_rows($select) > 0) {
				$message[] = 'user already exist!';
			} else {
				mysqli_query($conn, "INSERT INTO `users`(name, email, password,code,permission) VALUES('$name', '$email', '$pass','$verfiycode',3)") or die('query failed');
				$message[] = 'registered successfully!';
				// sendEmail($email, "Verfiy Code Ecommerce", "Verfiy Code $verfiycode");




				$mail = new PHPMailer(true);

				//Server settings
				$mail->isSMTP();                              //Send using SMTP
				$mail->Host       = 'smtp.gmail.com';       //Set the SMTP server to send through
				$mail->SMTPAuth   = true;             //Enable SMTP authentication
				$mail->Username   = 'ay7362775@gmail.com';   //SMTP write your email
				$mail->Password   = 'nhqwjvlrfvcubtut';      //SMTP password
				$mail->SMTPSecure = 'ssl';            //Enable implicit SSL encryption
				$mail->Port       = 465;

				//Recipients
				$mail->setFrom("ay7362775@gmail.com", "Ahmed Yosef"); // Sender Email and name
				$mail->addAddress($email);     //Add a recipient email  
				$mail->addReplyTo($_POST["email"], $_POST["name"]); // reply to sender email

				//Content
				$mail->isHTML(true);               //Set email format to HTML
				$mail->Subject = "VervicationCode";   // email subject headings
				$mail->Body    = "VervicationCode For Botiqe Ecommerce Is  ".$verfiycode; //email message

				// Success sent message alert
				$mail->send();
				echo
				" 
				<script> 
				 alert('Message was sent successfully!');
				 document.location.href = 'index.php';
				</script>
				";





				header("location:vervicationcode.php?email=$email");
			}
		} else {
			$message[] = "Password Not Matches";
		}
	}
	?>


	<?php
	if (isset($message)) {
		foreach ($message as $message) {
			echo '<div class="alert alert-danger" onclick="this.remove();">' . $message . '</div>';
		}
	}
	?>

	<div class="page-content">
		<div class="form-v9-content" style="background-image: url('images/form-v9.jpg')">
			<form class="form-detail" action="#" method="post">
				<h2>Registration Form</h2>
				<div class="form-row-total">
					<div class="form-row">
						<input type="text" name="name" class="input-text" placeholder="Your Name" required>
					</div>
					<div class="form-row">
						<input type="email" name="email" class="input-text" placeholder="Your Email" required>
					</div>
				</div>
				<div class="form-row-total">
					<div class="form-row">
						<input type="password" name="password" class="input-text" placeholder="Your Password" required>
					</div>
					<div class="form-row">
						<input type="password" name="cpassword" class="input-text" placeholder="Your Password" required>
					</div>
				</div>
				<div class="form-row-last">
					<input type="submit" name="register" class="register" value="تسجيل حساب">
					<p>هل لديك حساب؟ <a href="../login/log.php"> تسجيل دخول</a></p>


				</div>
			</form>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>