<?php
include("admin/conn.php");

session_start();
@$user_id = $_SESSION['user_id'];
@$user_name = $_SESSION['user_name'];


if (isset($_POST['add_to_cart'])) {

  if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    // إعداد مصفوفة الصور
    $imageNames = [];
    $imageNames[] = $product_image;  // إضافة اسم الصورة إلى المصفوفة
    $jsonImageNames = json_encode($imageNames);  // تحويل المصفوفة إلى JSON لتخزينها في قاعدة البيانات

    // تحقق مما إذا كان المنتج موجودًا في سلة التسوق
    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id' AND order_id IS NULL") or die('query failed');

    if (mysqli_num_rows($select_cart) > 0) {
      $_SESSION['message'] = 'المنتج أضيف بالفعل إلى عربة التسوق!';
    } else {
      // إدخال البيانات في جدول cart
      $cart_query = mysqli_query($conn, "INSERT INTO `cart` (user_id, name, price, image, quantity, order_id) VALUES ('$user_id', '$product_name', '$product_price', '$jsonImageNames', '$product_quantity', NULL)") or die('query failed');

      if ($cart_query) {
        // الحصول على ID آخر إدخال في جدول cart
        $cart_id = mysqli_insert_id($conn);

        $cart_order_query = mysqli_query($conn, "INSERT INTO `cart_orders` (user_id, name, price, image, quantity, order_id) VALUES ('$user_id', '$product_name', '$product_price', '$jsonImageNames', '$product_quantity', NULL)") or die('query failed');

        if ($cart_order_query) {
          $_SESSION['message'] = 'المنتج يضاف الى عربة التسوق!';
        } else {
          $_SESSION['message'] = 'حدث خطأ أثناء إضافة الطلب!';
        }
      } else {
        $_SESSION['message'] = 'حدث خطأ أثناء إضافة المنتج إلى عربة التسوق!';
      }
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
    exit();
  } else {
    header('location:login/log.php');
  }
}

if (isset($_POST['favorite'])) {

  if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $image = $_POST['favorite_image'];
    $name = $_POST['favorite_name'];
    $price = $_POST['favorite_price'];

    $insert_fav = mysqli_query($conn, "SELECT * FROM favorite WHERE name='$name' AND user_id='$user_id'") or die("FAILED");

    if (mysqli_num_rows($insert_fav) > 0) {
      $_SESSION['message'] = 'المنتج أضيف بالفعل إلى قائمة المفضلة!';
    } else {
      mysqli_query($conn, "INSERT INTO favorite (user_id,name,price,image) VALUES('$user_id','$name','$price','$image')") or die('query failed');
      $_SESSION['message'] = 'المنتج يضاف الى القائمة المفضلة!';
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
  } else {
    header('location:login/log.php');
  }
}



// إذا لم يكن المستخدم مسجلاً دخوله مسبقًا
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
  $token = $_COOKIE['remember_me'];

  // تحقق من الرمز في قاعدة البيانات
  $query = mysqli_query($conn, "SELECT * FROM users WHERE remember_token = '$token' AND token_expiry > NOW()") or die('failed');

  $user = mysqli_fetch_assoc($query);

  if ($user) {
    // تسجيل دخول المستخدم تلقائيًا
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name']; // تعيين اسم المستخدم للجلسة



  }
}

if (isset($_GET['logout'])) {
  if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    session_unset();
    session_destroy();

    // حذف الكوكي
    if (isset($_COOKIE['remember_me'])) {
      setcookie('remember_me', '', time() - 3600, "/", "", true, true); // تعيين مدة انتهاء الكوكي إلى وقت ماضي
    }
    if (isset($_COOKIE['user_id'])) {
      setcookie('user_id', '', time() - 3600, "/", "", true, true); // تعيين مدة انتهاء الكوكي إلى وقت ماضي
    }


    // يمكنك أيضًا حذف رمز "remember_token" من قاعدة البيانات هنا إذا أردت.
    $query = mysqli_query($conn, "UPDATE users SET remember_token = NULL, token_expiry = NULL WHERE id='$user_id'");

    header('location:login/log.php');
    exit();
  }
}




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

  <?php
  if (isset($_SESSION['message'])) {

    echo '<div class="alert alert-success" onclick="this.remove();">' . $_SESSION['message'] . '</div>';

    unset($_SESSION['message']);
  }
  ?>
  <div class="page-holder">
    <!-- navbar-->
    <header class="header bg-white">
      <div class="container px-0 px-lg-3">
        <nav class="navbar navbar-expand-lg navbar-light py-3 px-lg-0"><a class="navbar-brand" href="index.php"><span class="font-weight-bold text-uppercase text-dark">Boutique</span></a>
          <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item">
                <!-- Link--><a class="nav-link active" href="index.php">Home</a>
              </li>
              <li class="nav-item">
                <!-- Link--><a class="nav-link" href="shop.php">Shop</a>
              </li>
              <!-- <li class="nav-item">
                <a class="nav-link" href="detail.php">Product detail</a>
              </li> -->
              <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" id="pagesDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pages</a>
                <div class="dropdown-menu mt-3" aria-labelledby="pagesDropdown"><a class="dropdown-item border-0 transition-link" href="index.php">Homepage</a><a class="dropdown-item border-0 transition-link" href="shop.php">Category</a><a class="dropdown-item border-0 transition-link" href="cart.php">Shopping cart</a></div>
              </li>
            </ul>

            <?php

            $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : (isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : null);

            // عد المنتجات في السلة
            if ($user_id) {
              $cart_query = mysqli_query($conn, "SELECT COUNT(*) AS count FROM cart WHERE user_id = '$user_id'");
              $row = mysqli_fetch_assoc($cart_query);
              $count_products = $row['count'];

              // عد المنتجات المفضلة
              $fav_query = mysqli_query($conn, "SELECT COUNT(*) AS count FROM favorite WHERE user_id = '$user_id'");
              $row = mysqli_fetch_assoc($fav_query);
              $count_fav = $row['count'];
            } else {
              $count_products = 0;
              $count_fav = 0;
            }

            ?>

            <ul class="navbar-nav ml-auto">
              <li class="nav-item">
                <a class="nav-link" href="cart.php">
                  <i class="fas fa-dolly-flatbed mr-1 text-gray"></i>Cart<small class="text-gray">(<?php echo $count_products; ?>)</small>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="favorite.php">
                  <i class="far fa-heart mr-1"></i><small class="text-gray"> (<?php echo $count_fav; ?>)</small>
                </a>
              </li>

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
    <!-- HERO SECTION-->
    <div class="container">
      <section class="hero pb-3 bg-cover bg-center d-flex align-items-center" style="background: url(img/hero-banner-alt.jpg)">
        <div class="container py-5">
          <div class="row px-4 px-lg-5">
            <div class="col-lg-6">
              <p class="text-muted small text-uppercase mb-2">New Inspiration 2024</p>
              <h1 class="h2 text-uppercase mb-3">20% off on new season</h1><a class="btn btn-dark" href="shop.php">Browse collections</a>
            </div>
          </div>
        </div>
      </section>
      <!-- CATEGORIES SECTION-->
      <section class="pt-5">
        <header class="text-center">
          <p class="small text-muted small text-uppercase mb-1">Carefully created collections</p>
          <h2 class="h5 text-uppercase mb-4">Browse our categories</h2>
        </header>



        <div class="container mt-5">
          <div class="row justify-content-center">
            <?php
            $querycat = "SELECT * FROM cattigur";
            $resultquerycat = $conn->query($querycat);
            while ($cat = mysqli_fetch_assoc($resultquerycat)) {
            ?>
              <div class="col-auto mb-3">
                <div class="card">
                  <a class="category-item" href="shop.php?catid=<?php echo $cat['id'] ?>">
                    <img class="card-img-top" style="max-width: 100%; height: auto; object-fit: cover;" src="admin/inc/des/<?php echo $cat['image'] ?>" alt="">
                    <strong class="category-item-title"><?php echo $cat['name'] ?></strong>
                  </a>
                  <div class="card-body">
                  </div>
                </div>
              </div>
            <?php
            }
            ?>
          </div>
        </div>



      </section>




      <!-- TRENDING PRODUCTS-->
      <section class="py-5">

        <header>
          <p class="small text-muted small text-uppercase mb-1">Made the hard way</p>
          <h2 class="h5 text-uppercase mb-4">Top trending products</h2>
        </header>
        <div class="row justify-content-center" id="trending-products-container">
          <!-- سيتم تحميل المنتجات هنا باستخدام AJAX -->
        </div>

        <!-- JavaScript لتحميل المنتجات عبر AJAX -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
          $(document).ready(function() {
            // تحميل الصفحة الأولى عند تحميل الصفحة
            loadTrendingProducts(1);

            // التعامل مع الضغط على الروابط في الـ pagination
            $(document).on('click', '.pagination a', function(e) {
              e.preventDefault();
              var page = $(this).data('page');
              loadTrendingProducts(page);
            });

            // دالة لتحميل المنتجات
            function loadTrendingProducts(page) {
              $.ajax({
                url: 'fetch_trending_products.php',
                type: 'POST',
                data: {
                  page: page
                },
                success: function(response) {
                  $('#trending-products-container').html(response);
                }
              });
            }
          });
        </script>



      </section>

      <!-- SERVICES-->
      <section class="py-5 bg-light">
        <div class="container">
          <div class="row text-center">
            <div class="col-lg-4 mb-3 mb-lg-0">
              <div class="d-inline-block">
                <div class="media align-items-end">
                  <svg class="svg-icon svg-icon-big svg-icon-light">
                    <use xlink:href="#delivery-time-1"> </use>
                  </svg>
                  <div class="media-body text-left ml-3">
                    <h6 class="text-uppercase mb-1">Free shipping</h6>
                    <p class="text-small mb-0 text-muted">Free shipping worlwide</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 mb-3 mb-lg-0">
              <div class="d-inline-block">
                <div class="media align-items-end">
                  <svg class="svg-icon svg-icon-big svg-icon-light">
                    <use xlink:href="#helpline-24h-1"> </use>
                  </svg>
                  <div class="media-body text-left ml-3">
                    <h6 class="text-uppercase mb-1">24 x 7 service</h6>
                    <p class="text-small mb-0 text-muted">Free shipping worlwide</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="d-inline-block">
                <div class="media align-items-end">
                  <svg class="svg-icon svg-icon-big svg-icon-light">
                    <use xlink:href="#label-tag-1"> </use>
                  </svg>
                  <div class="media-body text-left ml-3">
                    <h6 class="text-uppercase mb-1">Festival offer</h6>
                    <p class="text-small mb-0 text-muted">Free shipping worlwide</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- NEWSLETTER-->
      <section class="py-5">
        <div class="container p-0">
          <div class="row">
            <!-- <div class="col-lg-6 mb-3 mb-lg-0">
              <h5 class="text-uppercase">Let's be friends!</h5>
              <p class="text-small text-muted mb-0">Nisi nisi tempor consequat laboris nisi.</p>
            </div> -->
            <!-- <div class="col-lg-6">
              <form action="#">
                <div class="input-group flex-column flex-sm-row mb-3">
                  <input class="form-control form-control-lg py-3" type="email" placeholder="Enter your email address" aria-describedby="button-addon2">
                  <div class="input-group-append">
                    <button class="btn btn-dark btn-block" id="button-addon2" type="submit">Subscribe</button>
                  </div>
                </div>
              </form>
            </div> -->
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
              <li><a class="footer-link" href="<?php if ($user_id) {
                                                  echo "contact.php";
                                                } else {
                                                  echo "login/log.php";
                                                } ?>">Help &amp; Contact Us</a></li>
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
              <p class="small text-muted mb-0">&copy; 2024 All rights reserved.</p>
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
</body>

</html>