<?php
include("admin/conn.php");
session_start();
$user_id = $_SESSION['user_id'];

?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Boutique | Ecommerce bootstrap template</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="all,follow">
  <!-- Bootstrap CSS-->
  <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
  <!-- Lightbox-->
  <link rel="stylesheet" href="vendor/lightbox2/css/lightbox.min.css">
  <!-- Range slider-->
  <link rel="stylesheet" href="vendor/nouislider/nouislider.min.css">
  <!-- Bootstrap select-->
  <link rel="stylesheet" href="vendor/bootstrap-select/css/bootstrap-select.min.css">
  <!-- Owl Carousel-->
  <link rel="stylesheet" href="vendor/owl.carousel2/assets/owl.carousel.min.css">
  <link rel="stylesheet" href="vendor/owl.carousel2/assets/owl.theme.default.css">
  <!-- Google fonts-->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@300;400;700&amp;display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Martel+Sans:wght@300;400;800&amp;display=swap">
  <!-- theme stylesheet-->
  <link rel="stylesheet" href="css/style.default.css" id="theme-stylesheet">
  <!-- Custom stylesheet - for your changes-->
  <link rel="stylesheet" href="css/custom.css">
  <!-- Favicon-->
  <link rel="shortcut icon" href="img/favicon.png">
  <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
</head>

<body>
  <div class="page-holder">
    <!-- navbar-->
    <header class="header bg-white">
      <div class="container px-0 px-lg-3">
        <nav class="navbar navbar-expand-lg navbar-light py-3 px-lg-0"><a class="navbar-brand" href="index.html"><span class="font-weight-bold text-uppercase text-dark">Boutique</span></a>
          <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item">
                <!-- Link--><a class="nav-link" href="index.php">Home</a>
              </li>
              <li class="nav-item">
                <!-- Link--><a class="nav-link" href="shop.php">Shop</a>
              </li>
              <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" id="pagesDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pages</a>
                <div class="dropdown-menu mt-3" aria-labelledby="pagesDropdown"><a class="dropdown-item border-0 transition-link" href="index.html">Homepage</a><a class="dropdown-item border-0 transition-link" href="shop.html">Category</a><a class="dropdown-item border-0 transition-link" href="detail.html">Product detail</a><a class="dropdown-item border-0 transition-link" href="cart.html">Shopping cart</a><a class="dropdown-item border-0 transition-link" href="checkout.html">Checkout</a></div>
              </li>
            </ul>

            <?php

            $query_count = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id'") or die('FAILED');
            $count_products = mysqli_num_rows($query_count);

            $query_count_fav = mysqli_query($conn, "SELECT * FROM favorite WHERE user_id='$user_id'") or die('FAILED');
            $count_fav = mysqli_num_rows($query_count_fav);

            ?>
            <ul class="navbar-nav ml-auto">
              <li class="nav-item"><a class="nav-link" href="cart.php"> <i class="fas fa-dolly-flatbed mr-1 text-gray"></i>Cart<small class="text-gray">(<?php echo $count_products ?>)</small></a></li>
              <li class="nav-item"><a class="nav-link" href="favorite.php"> <i class="far fa-heart mr-1"></i><small class="text-gray"> (<?php echo $count_fav ?>)</small></a></li>

              <?php
              if (isset($_SESSION['user_id'])) {
                $user_name = $_SESSION['user_name']; // استعادة اسم المستخدم من الجلسة
                echo "<div class='dropdown'>
            <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>$user_name
            </button>
            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
            '<a class='dropdown-item' href='index.php?logout=" . $_SESSION['user_id'] . "'><i class='fas fa-user-alt mr-1 text-gray'></i>Logout</a></div></div>";
              } else {
                echo "<a class='dropdown-item' href='login/log.php'><i class='fas fa-user-alt mr-1 text-gray'></i>Login</a>";
              }
              ?>
            </ul>
          </div>
        </nav>
      </div>
    </header>
    <!--  Modal -->
    <div class="modal fade" id="productView" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body p-0">
            <div class="row align-items-stretch">
              <div class="col-lg-6 p-lg-0"><a class="product-view d-block h-100 bg-cover bg-center" style="background: url(img/product-5.jpg)" href="img/product-5.jpg" data-lightbox="productview" title="Red digital smartwatch"></a><a class="d-none" href="img/product-5-alt-1.jpg" title="Red digital smartwatch" data-lightbox="productview"></a><a class="d-none" href="img/product-5-alt-2.jpg" title="Red digital smartwatch" data-lightbox="productview"></a></div>
              <div class="col-lg-6">
                <button class="close p-4" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <div class="p-5 my-md-4">
                  <ul class="list-inline mb-2">
                    <li class="list-inline-item m-0"><i class="fas fa-star small text-warning"></i></li>
                    <li class="list-inline-item m-0"><i class="fas fa-star small text-warning"></i></li>
                    <li class="list-inline-item m-0"><i class="fas fa-star small text-warning"></i></li>
                    <li class="list-inline-item m-0"><i class="fas fa-star small text-warning"></i></li>
                    <li class="list-inline-item m-0"><i class="fas fa-star small text-warning"></i></li>
                  </ul>
                  <h2 class="h4">Red digital smartwatch</h2>
                  <p class="text-muted">$250</p>
                  <p class="text-small mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ut ullamcorper leo, eget euismod orci. Cum sociis natoque penatibus et magnis dis parturient montes nascetur ridiculus mus. Vestibulum ultricies aliquam convallis.</p>
                  <div class="row align-items-stretch mb-4">
                    <div class="col-sm-7 pr-sm-0">
                      <div class="border d-flex align-items-center justify-content-between py-1 px-3"><span class="small text-uppercase text-gray mr-4 no-select">Quantity</span>
                        <div class="quantity">
                          <button class="dec-btn p-0"><i class="fas fa-caret-left"></i></button>
                          <input class="form-control border-0 shadow-0 p-0" type="text" value="1">
                          <button class="inc-btn p-0"><i class="fas fa-caret-right"></i></button>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-5 pl-sm-0"><a class="btn btn-dark btn-sm btn-block h-100 d-flex align-items-center justify-content-center px-0" href="cart.html">Add to cart</a></div>
                  </div><a class="btn btn-link text-dark p-0" href="#"><i class="far fa-heart mr-2"></i>Add to wish list</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <!-- HERO SECTION-->
      <section class="py-5 bg-light">
        <div class="container">
          <div class="row px-4 px-lg-5 py-lg-4 align-items-center">
            <div class="col-lg-6">
              <h1 class="h2 text-uppercase mb-0">Checkout</h1>
            </div>
            <div class="col-lg-6 text-lg-right">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-lg-end mb-0 px-0">
                  <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                  <li class="breadcrumb-item"><a href="cart.php">Cart</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </section>
      <section class="py-5">
        <!-- BILLING ADDRESS-->
        <h2 class="h5 text-uppercase mb-4">Billing details</h2>
        <div class="row">
          <div class="col-lg-8">
            <form action="processOrder.php" method="POST">
              <div class="row">
                <div class="col-lg-6 form-group">
                  <label class="text-small text-uppercase" for="firstName">First name</label>
                  <input class="form-control form-control-lg" id="firstName" name="firstName" type="text" placeholder="Enter your first name">
                </div>
                <div class="col-lg-6 form-group">
                  <label class="text-small text-uppercase" for="lastName">Last name</label>
                  <input class="form-control form-control-lg" id="lastName" name="lastName" type="text" placeholder="Enter your last name">
                </div>
                <div class="col-lg-6 form-group">
                  <label class="text-small text-uppercase" for="email">Email address</label>
                  <input class="form-control form-control-lg" id="email" name="email" type="email" placeholder="e.g. Jason@example.com">
                </div>
                <div class="col-lg-6 form-group">
                  <label class="text-small text-uppercase" for="phone">Phone number</label>
                  <input class="form-control form-control-lg" id="phone" name="phone" type="tel" placeholder="e.g. +02 245354745">
                </div>
                <div class="col-lg-6 form-group">
                  <label class="text-small text-uppercase" for="company">Company name (optional)</label>
                  <input class="form-control form-control-lg" id="company" name="company" type="text" placeholder="Your company name">
                </div>
                <div class="col-lg-6 form-group">
                  <label class="text-small text-uppercase" for="country">Country</label>
                  <select class="selectpicker country" id="country" name="country" data-width="fit" data-style="form-control form-control-lg" data-title="Select your country"></select>
                </div>
                <div class="col-lg-12 form-group">
                  <label class="text-small text-uppercase" for="address">Address line 1</label>
                  <input class="form-control form-control-lg" id="address" name="address" type="text" placeholder="House number and street name">
                </div>
                <div class="col-lg-12 form-group">
                  <label class="text-small text-uppercase" for="address">Address line 2</label>
                  <input class="form-control form-control-lg" id="addressalt" name="addressalt" type="text" placeholder="Apartment, Suite, Unit, etc (optional)">
                </div>
                <div class="col-lg-6 form-group">
                  <label class="text-small text-uppercase" for="city">Town/City</label>
                  <input class="form-control form-control-lg" id="city" name="city" type="text">


                </div>
                <div class="col-lg-6 form-group">
                  <?php

                  $total = $_GET['grandtotal'];


                  ?>
                  <label class="text-small text-uppercase" for="state">State/County</label>
                  <input class="form-control form-control-lg" id="state" name="state" type="text">
                  <input type="hidden" name="grandtotal" value="<?php echo $total; ?>">
                </div>







                <!-- قسم خيارات الدفع -->
                <h5 class="text-uppercase mb-4">Payment Method</h5>
                <div class="form-group">
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" id="cashOnDelivery" type="radio" name="paymentMethod" value="Cash on Delivery" checked>
                    <label class="custom-control-label" for="cashOnDelivery">Cash on Delivery</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" id="creditCard" type="radio" name="paymentMethod" value="Credit Card">
                    <label class="custom-control-label" for="creditCard">Credit Card</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" id="paypal" type="radio" name="paymentMethod" value="PayPal">
                    <label class="custom-control-label" for="paypal">PayPal</label>
                  </div>
                </div>

                <!-- قسم حقول بيانات بطاقة الائتمان -->
                <div id="creditCardFields" class="d-none">
                  <h5 class="text-uppercase mb-4">Credit Card Details</h5>
                  <div class="form-group">
                    <label class="text-small text-uppercase" for="cardNumber">Card Number</label>
                    <input class="form-control form-control-lg" id="cardNumber" name="cardNumber" type="text" placeholder="Enter your card number">
                  </div>
                  <div class="form-group">
                    <label class="text-small text-uppercase" for="cardExpiry">Expiration Date</label>
                    <input class="form-control form-control-lg" id="cardExpiry" name="cardExpiry" type="text" placeholder="MM/YY">
                  </div>
                  <div class="form-group">
                    <label class="text-small text-uppercase" for="cardCVC">CVC</label>
                    <input class="form-control form-control-lg" id="cardCVC" name="cardCVC" type="text" placeholder="CVC">
                  </div>
                </div>

                <!-- قسم بيانات PayPal -->
                <div id="paypalFields" class="d-none">
                  <h5 class="text-uppercase mb-4">PayPal Details</h5>

                  <!-- حقل إدخال البريد الإلكتروني الخاص بحساب PayPal -->
                  <div class="form-group">
                    <label for="paypalEmail">PayPal Email</label>
                    <input type="email" class="form-control" id="paypalEmail" name="paypalEmail" placeholder="Enter your PayPal email">
                  </div>

                  <!-- حقل إدخال آخر إن احتجت إلى جمع تفاصيل إضافية -->
                  <div class="form-group">
                    <label for="paypalNote">Additional Note (optional)</label>
                    <input type="text" class="form-control" id="paypalNote" name="paypalNote" placeholder="Any additional note">
                  </div>
                </div>



                <div class="col-lg-12 form-group">
                  <button class="btn btn-dark" type="submit">Place order</button>
                </div>
              </div>
            </form>
          </div>
          <!-- ORDER SUMMARY-->
          <div class="col-lg-4">
            <div class="card border-0 rounded-0 p-lg-4 bg-light">
              <div class="card-body">
                <h5 class="text-uppercase mb-4">Your order</h5>
                <ul class="list-unstyled mb-0">
                  <?php

                  $sql = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id'") or die("Failed");
                  if (mysqli_num_rows($sql) > 0) {
                    while ($row = mysqli_fetch_array($sql)) {

                  ?>
                      <li class="d-flex align-items-center justify-content-between"><strong class="small font-weight-bold"><?php echo $row['name'] ?></strong><span class="text-muted small"><?php echo $row['price'] . "$" ?></span></li>
                      <li class="border-bottom my-2"></li>
                  <?php
                    }
                  }

                  $total = $_GET['grandtotal'];
                  ?>
                  <li class="d-flex align-items-center justify-content-between"><strong class="text-uppercase small font-weight-bold">Total</strong><span><?php echo $total . "$" ?></span></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    <footer class="bg-dark text-white">
      <div class="container py-4">
        <div class="row py-5">
          <div class="col-md-4 mb-3 mb-md-0">
            <h6 class="text-uppercase mb-3">Customer services</h6>
            <ul class="list-unstyled mb-0">
              <li><a class="footer-link" href="#">Help &amp; Contact Us</a></li>
              <li><a class="footer-link" href="#">Returns &amp; Refunds</a></li>
              <li><a class="footer-link" href="#">Online Stores</a></li>
              <li><a class="footer-link" href="#">Terms &amp; Conditions</a></li>
            </ul>
          </div>
          <div class="col-md-4 mb-3 mb-md-0">
            <h6 class="text-uppercase mb-3">Company</h6>
            <ul class="list-unstyled mb-0">
              <li><a class="footer-link" href="#">What We Do</a></li>
              <li><a class="footer-link" href="#">Available Services</a></li>
              <li><a class="footer-link" href="#">Latest Posts</a></li>
              <li><a class="footer-link" href="#">FAQs</a></li>
            </ul>
          </div>
          <div class="col-md-4">
            <h6 class="text-uppercase mb-3">Social media</h6>
            <ul class="list-unstyled mb-0">
              <li><a class="footer-link" href="#">Twitter</a></li>
              <li><a class="footer-link" href="#">Instagram</a></li>
              <li><a class="footer-link" href="#">Tumblr</a></li>
              <li><a class="footer-link" href="#">Pinterest</a></li>
            </ul>
          </div>
        </div>
        <div class="border-top pt-4" style="border-color: #1d1d1d !important">
          <div class="row">
            <div class="col-lg-6">
              <p class="small text-muted mb-0">&copy; 2020 All rights reserved.</p>
            </div>
            <div class="col-lg-6 text-lg-right">
              <p class="small text-muted mb-0">Template designed by <a class="text-white reset-anchor" href="https://bootstraptemple.com/p/bootstrap-ecommerce">Bootstrap Temple</a></p>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <!-- JavaScript files-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/lightbox2/js/lightbox.min.js"></script>
    <script src="vendor/nouislider/nouislider.min.js"></script>
    <script src="vendor/bootstrap-select/js/bootstrap-select.min.js"></script>
    <script src="vendor/owl.carousel2/owl.carousel.min.js"></script>
    <script src="vendor/owl.carousel2.thumbs/owl.carousel2.thumbs.min.js"></script>
    <script src="js/front.js"></script>
    <script>
      // ------------------------------------------------------- //
      //   Inject SVG Sprite - 
      //   see more here 
      //   https://css-tricks.com/ajaxing-svg-sprite/
      // ------------------------------------------------------ //
      function injectSvgSprite(path) {

        var ajax = new XMLHttpRequest();
        ajax.open("GET", path, true);
        ajax.send();
        ajax.onload = function(e) {
          var div = document.createElement("div");
          div.className = 'd-none';
          div.innerHTML = ajax.responseText;
          document.body.insertBefore(div, document.body.childNodes[0]);
        }
      }
      // this is set to BootstrapTemple website as you cannot 
      // inject local SVG sprite (using only 'icons/orion-svg-sprite.svg' path)
      // while using file:// protocol
      // pls don't forget to change to your domain :)
      injectSvgSprite('https://bootstraptemple.com/files/icons/orion-svg-sprite.svg');
    </script>
    <!-- FontAwesome CSS - loading as last, so it doesn't block rendering-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  </div>


  <script>
    // عند تغيير طريقة الدفع، نقوم بإظهار أو إخفاء الحقول بناءً على الاختيار
    document.addEventListener('DOMContentLoaded', function() {
      const creditCardOption = document.getElementById('creditCard');
      const paypalOption = document.getElementById('paypal');
      const creditCardFields = document.getElementById('creditCardFields');
      const paypalFields = document.getElementById('paypalFields');

      // تحديث الحقول عند اختيار المستخدم طريقة دفع
      document.querySelectorAll('input[name="paymentMethod"]').forEach(function(input) {
        input.addEventListener('change', function() {
          if (creditCardOption.checked) {
            creditCardFields.classList.remove('d-none');
            paypalFields.classList.add('d-none');
          } else if (paypalOption.checked) {
            paypalFields.classList.remove('d-none');
            creditCardFields.classList.add('d-none');
          } else {
            creditCardFields.classList.add('d-none');
            paypalFields.classList.add('d-none');
          }
        });
      });
    });
  </script>
</body>

</html>