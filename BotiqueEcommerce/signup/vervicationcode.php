<?php
include("../admin/conn.php");
session_start();
if (isset($_POST['check'])) {
    $email = $_GET['email'];
    $verfiy = $_POST['vervicationcode'];

    $sql = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND code='$verfiy'");
    if (mysqli_num_rows($sql) > 0) {
        $update = mysqli_query($conn, "UPDATE users SET users_approve='1' WHERE email='$email'");
        $row = mysqli_fetch_assoc($sql);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['name'];
        header("location: ../index.php");
    } else {
        $message[] = 'Verification Code Is Not Correct!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            font-family: 'Arial', sans-serif;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 400px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2 class="text-center">Verification Code</h2>
        <form action="" method="post">
            <div class="form-group">
                <?php
                if (isset($message)) {
                    foreach ($message as $msg) {
                        echo '<div class="alert alert-danger" onclick="this.remove();">' . $msg . '</div>';
                    }
                }
                ?>
                <label for="number">Verification Code</label>
                <input type="number" class="form-control" name="vervicationcode" required>
            </div>
            <input type="submit" value="Check" name="check" class="btn btn-primary btn-block">
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
