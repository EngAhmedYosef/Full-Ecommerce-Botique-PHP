<?php
include("../admin/conn.php");
session_start();

if (isset($_POST['change'])) {
    $email = $_GET['email'];
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['cpassword']);
    if ($password == $cpassword) {
        $update = mysqli_query($conn, "UPDATE users SET password='$password' WHERE email='$email'") or die(mysqli_error($conn));
        $_SESSION['message'] = "Password Is Changed Successfully";
        header("location: log.php");
        exit();
    } else {
        $message[] = "Password Not Matches";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #f093fb, #f5576c);
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
            position: relative;
        }

        .form-control {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .btn-primary {
            background-color: #28a745;
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
            background-color: #218838;
        }

        .alert {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            font-size: 14px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        label {
            font-weight: 600;
            color: #333;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
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

            <label for="password">New Password</label>
            <div class="form-group">
                <input type="password" class="form-control" name="password" id="password" required>
                <span class="toggle-password" onclick="togglePassword('password', 'password-icon1')">
                    <i id="password-icon1" class="fas fa-eye"></i>
                </span>
            </div>

            <label for="cpassword">Confirm New Password</label>
            <div class="form-group">
                <input type="password" class="form-control" name="cpassword" id="cpassword" required>
                <span class="toggle-password" onclick="togglePassword('cpassword', 'password-icon2')">
                    <i id="password-icon2" class="fas fa-eye"></i>
                </span>
            </div>

            <input type="submit" value="Change Password" name="change" class="btn btn-primary">
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId, iconId) {
            var passwordField = document.getElementById(inputId);
            var passwordIcon = document.getElementById(iconId);
            if (passwordField.type === "password") {
                passwordField.type = "text";
                passwordIcon.classList.remove("fa-eye");
                passwordIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                passwordIcon.classList.remove("fa-eye-slash");
                passwordIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>

</html>
