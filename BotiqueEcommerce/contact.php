<?php
include("admin/conn.php");
session_start();
@$user_id = $_SESSION['user_id'];

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//required files
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

//Create an instance; passing `true` enables exceptions
if (isset($_POST["send"])) {

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
    $mail->setFrom($_POST["email"], $_POST["name"]); // Sender Email and name
    $mail->addAddress('ay7362775@gmail.com');     //Add a recipient email  
    $mail->addReplyTo($_POST["email"], $_POST["name"]); // reply to sender email

    //Content
    $mail->isHTML(true);               //Set email format to HTML
    $mail->Subject = $_POST["subject"];   // email subject headings
    $mail->Body    = $_POST["message"]; //email message

    // Success sent message alert
    $mail->send();
    $name = $_POST["name"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];
    mysqli_query($conn,"INSERT INTO messages (name,user_id,email,subject,message) VALUES ('$name',$user_id,'$email','$subject','$message')");
    echo
    " 
    <script> 
     alert('Message was sent successfully!');
     document.location.href = 'index.php';
    </script>
    ";
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        @import url(https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600);

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-weight: 300;
            font-size: 12px;
            line-height: 30px;
            color: #272727;
            background: rgb(25, 199, 155);
        }

        .container {
            max-width: 400px;
            width: 100%;
            margin: 0 auto;
            position: relative;
        }

        #contact input {
            font: 400 12px/16px;
            width: 100%;
            border: 1px solid #CCC;
            background: #FFF;
            margin: 10 5px;
            padding: 10px;
        }

        h1 {
            margin-bottom: 30px;
            font-size: 30px;
        }

        #contact {
            background: #F9F9F9;
            padding: 25px;
            margin: 50px 0;
        }


        fieldset {
            border: medium none !important;
            margin: 0 0 10px;
            min-width: 100%;
            padding: 0;
            width: 100%;
        }

        textarea {
            height: 100px;
            max-width: 100%;
            resize: none;
            width: 100%;
        }

        button {
            cursor: pointer;
            width: 100%;
            border: none;
            background: rgb(17, 146, 60);
            color: #FFF;
            margin: 0 0 5px;
            padding: 10px;
            font-size: 20px;
        }

        button:hover {
            background-color: rgb(15, 95, 42);
        }
    </style>
</head>

<body>
    <div class="container">
        <form id="contact" action="" method="post">
            <h1>Contact Form</h1>

            <fieldset>
                <input placeholder="Your name" name="name" type="text" tabindex="1" autofocus>
            </fieldset>
            <fieldset>
                <input placeholder="Your Email Address" name="email" type="email" tabindex="2">
            </fieldset>
            <fieldset>
                <input placeholder="Type your subject line" type="text" name="subject" tabindex="4">
            </fieldset>
            <fieldset>
                <textarea name="message" placeholder="Type your Message Details Here..." tabindex="5"></textarea>
            </fieldset>

            <fieldset>
                <button type="submit" name="send" id="contact-submit">Submit Now</button>
                <br>
                <a href="index.php">
                    <button type="submit" name="back" id="contact-submit">Back To Shop</button>
                </a>
            </fieldset>
        </form>

    </div>
</body>

</html>