<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="fonts/icomoon/style.css">
  <link rel="stylesheet" href="css/owl.carousel.min.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
  <title>Login To Boutique Ecommerce</title>
</head>

<body>
  <?php
  include('../admin/conn.php');
  session_start();

  if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

    $select = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');
    $row = mysqli_fetch_assoc($select);

    if (mysqli_num_rows($select) > 0 && $row['users_approve'] == 1) {
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['user_name'] = $row['name'];
      @$user_id = $_SESSION['user_id'];

      if (isset($_POST['remember_me'])) {
        $token = bin2hex(random_bytes(16));
        $expiry_time = time() + (86400 * 7);
        $token_expiry = date('Y-m-d H:i:s', $expiry_time);

        $query = mysqli_query($conn, "UPDATE users SET remember_token = '$token', token_expiry = '$token_expiry' WHERE id = " . $row['id']);
        setcookie('remember_me', $token, $expiry_time, "/", "", true, true);
        setcookie("user_id", $user_id, time() + (86400 * 30), "/");
      }
      @$_SESSION['user_id']=$user_id;
      header('location:../index.php');
    }
    if (mysqli_num_rows($select) > 0 && $row['users_approve'] != 1) {
      header("location:../signup/vervicationcode.php?email=$email");
    } else {
      $message[] = 'incorrect password or email!';
    }
  }
  ?>

  <div class="content">
    <div class="container">
      <?php
      if (isset($message)) {
        foreach ($message as $message) {
          echo '<div class="alert alert-danger" onclick="this.remove();">' . $message . '</div>';
        }
      }

      if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-success" onclick="this.remove();">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
      }
      ?>

      <div class="row">
        <div class="col-md-6">
          <img src="images/undraw_remotely_2j6y.svg" alt="Image" class="img-fluid">
        </div>
        <div class="col-md-6 contents">
          <div class="row justify-content-center">
            <div class="col-md-8">
              <div class="mb-4">
                <div class="text-center">
                  <h1 class="h4 text-gray-900 mb-4">Welcome To Botique Shop Website!</h1>
                </div>
                <h3>Sign In</h3>
              </div>
              <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="form-group first">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" name="email">
                </div>
                <div class="form-group last mb-4">
                  <label for="password">Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" name="password" id="password">
                    <div class="input-group-append">
                      <span class="input-group-text" onclick="togglePassword()" style="cursor: pointer;">
                        <i id="password-icon" class="fas fa-eye"></i>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="d-flex mb-5 align-items-center">
                  <label class="control control--checkbox mb-0"><span class="caption">Remember me</span>
                    <input type="checkbox" name="remember_me" checked="checked" />
                    <div class="control__indicator"></div>
                  </label>
                  <span class="ml-auto"><a href="checkemail.php" class="forgot-pass">Forgot Password</a></span>
                </div>

                <input type="submit" value="Login" name="login" class="btn btn-block btn-primary">
                <p> don't have an account<a class="btn btn-sm" href="../signup/signup.php">Sign Up</a></p>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>
  <script>
    function togglePassword() {
      var passwordField = document.getElementById("password");
      var passwordIcon = document.getElementById("password-icon");
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
