<?php

include("../admin/conn.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

if (isset($_POST['check'])) {
    $email = $_POST['email'];
    $verfiycode = rand(10000, 99999);

    $sql = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($sql) > 0) {
        $update = mysqli_query($conn, "UPDATE users SET code='$verfiycode' WHERE email='$email'");

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ay7362775@gmail.com';
        $mail->Password = 'nhqwjvlrfvcubtut';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom("ay7362775@gmail.com", "Ahmed Yosef");
        $mail->addAddress($email);
        $mail->addReplyTo($_POST["email"], $_POST["name"]);

        $mail->isHTML(true);
        $mail->Subject = "VervicationCode";
        $mail->Body = "VervicationCode For Botiqe Ecommerce Is " . $verfiycode;

        $mail->send();
        echo "<script>
                alert('Message was sent successfully!');
                document.location.href = 'index.php';
              </script>";

        header("location:vervicationcode.php?email=$email");
    } else {
        $message[] = 'Email does not exist!';
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
        }

        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .btn-primary {
            background-color: #5a67d8;
            border: none;
            padding: 10px;
            border-radius: 5px;
            width: 100%;
            font-size: 16px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #4c51bf;
        }

        .alert {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        label {
            font-weight: 600;
            color: #333;
        }
    </style>
</head>

<body>

    <form action="" method="post">
        <div class="form-group first">
            <?php
            if (isset($message)) {
                foreach ($message as $message) {
                    echo '<div class="alert alert-danger" onclick="this.remove();">' . $message . '</div>';
                }
            }
            ?>

            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" required>

            <input type="submit" value="Check" name="check" class="btn btn-primary">
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
