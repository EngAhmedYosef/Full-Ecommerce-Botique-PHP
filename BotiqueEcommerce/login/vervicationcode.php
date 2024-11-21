<?php
include("../admin/conn.php");
if (isset($_POST['verfiy'])) {
    $email = $_GET['email'];
    $verfiy = $_POST['vervicationcode'];

    $sql = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND code='$verfiy'");
    if (mysqli_num_rows($sql) > 0) {
        header("location: resetpassword.php?email=$email");
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* تصميم الجسم */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #6dd5ed, #2193b0); /* خلفية متدرجة جميلة */
        }

        /* تصميم الفورم */
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
            background-color: #28a745; /* لون زر أخضر */
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

            <label for="text">Verification Code</label>
            <input type="text" class="form-control" name="vervicationcode" required>

            <input type="submit" value="Verify" name="verfiy" class="btn btn-primary">
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
