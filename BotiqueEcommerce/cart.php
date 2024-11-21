<?php

include("admin/conn.php");
session_start();
@$user_id = $_SESSION['user_id'];




if (isset($_POST['update_cart'])) {
  $update_quantity = $_POST['cart_quantity'];
  $update_id = $_POST['cart_id'];
  mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed');
  $message[] = 'تم تحديث كمية سلة التسوق بنجاح!';
}

if (isset($_GET['remove'])) {
  $remove_id = mysqli_real_escape_string($conn, $_GET['remove']);

  // ابدأ المعاملة
  mysqli_begin_transaction($conn);

  try {
    // حذف من جدول cart
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or throw new Exception('Failed to delete from cart');

    // حذف من جدول cart_orders
    mysqli_query($conn, "DELETE FROM `cart_orders` WHERE id = '$remove_id'") or throw new Exception('Failed to delete from cart_orders');

    // تأكيد المعاملة
    mysqli_commit($conn);
    header('Location: cart.php');
    exit();
  } catch (Exception $e) {
    // إلغاء المعاملة في حالة حدوث خطأ
    mysqli_rollback($conn);
    die('Error: ' . $e->getMessage());
  }
}

if (isset($_GET['delete_all'])) {
  $user_id = mysqli_real_escape_string($conn, $user_id);

  // ابدأ المعاملة
  mysqli_begin_transaction($conn);

  try {
    // حذف من جدول cart
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or throw new Exception('Failed to delete from cart');

    // حذف من جدول cart_orders بناءً على user_id
    mysqli_query($conn, "DELETE FROM `cart_orders` WHERE user_id = '$user_id'") or throw new Exception('Failed to delete from cart_orders');

    // تأكيد المعاملة
    mysqli_commit($conn);
    header('Location: cart.php');
    exit();
  } catch (Exception $e) {
    // إلغاء المعاملة في حالة حدوث خطأ
    mysqli_rollback($conn);
    die('Error: ' . $e->getMessage());
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

  include('admin/conn.php');




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
                <!-- Link--><a class="nav-link" href="index.php">Home</a>
              </li>
              <li class="nav-item">
                <!-- Link--><a class="nav-link" href="shop.php">Shop</a>
              </li>
              <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" id="pagesDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pages</a>
                <div class="dropdown-menu mt-3" aria-labelledby="pagesDropdown"><a class="dropdown-item border-0 transition-link" href="index.php">Homepage</a><a class="dropdown-item border-0 transition-link" href="shop.php">Category</a><a class="dropdown-item border-0 transition-link" href="cart.php">Shopping cart</a></div>
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
              <h1 class="h2 text-uppercase mb-0">Cart</h1>
            </div>
            <div class="col-lg-6 text-lg-right">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-lg-end mb-0 px-0">
                  <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Cart</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </section>
      <section class="py-5">
        <h2 class="h5 text-uppercase mb-4">Shopping cart</h2>
        <div class="row">
          <div class="col-lg-8 mb-4 mb-lg-0">
            <!-- CART TABLE-->
            <div class="table-responsive mb-4">
              <table class="table">
                <thead class="bg-light">
                  <tr>
                    <th class="border-0" scope="col"> <strong class="text-small text-uppercase">Product</strong></th>
                    <th class="border-0" scope="col"> <strong class="text-small text-uppercase">name</strong></th>
                    <th class="border-0" scope="col"> <strong class="text-small text-uppercase">Price</strong></th>
                    <th class="border-0" scope="col"> <strong class="text-small text-uppercase">Quantity</strong></th>
                    <th class="border-0" scope="col"> <strong class="text-small text-uppercase">Total</strong></th>
                    <th class="border-0" scope="col"> <strong class="text-small text-uppercase">Opearation</strong></th>
                    <th class="border-0" scope="col"> </th>
                  </tr>
                </thead>

                <?php
                include('admin/conn.php');
                $selectimg = "SELECT * FROM cart WHERE user_id = '$user_id'";

                $queryimg = $conn->query($selectimg);
                $grand_total = 0;

                if (mysqli_num_rows($queryimg) > 0) {

                  while ($salles = mysqli_fetch_array($queryimg)) {

                ?>


                    <tbody>



                      <tr>
                        <th class="pl-0 border-0" scope="row">
                          <div class="media align-items-center">
                            <a class="reset-anchor d-block animsition-link" href="detail.php">
                              <?php

                              $covers = explode(',', $salles['image']);
                              $image = trim($covers[0]);
                              $image = str_replace(['[', ']', '"'], '', $image); // إزالة الأقواس المربعة وعلامات الاقتباس


                              ?>
                              <img class="img-fluid" style="max-width: 100%; height: auto;" src="admin/inc/des/images/<?php echo $image ?>" alt="...">

                            </a>
                          </div>
                        </th>
                        <th>
                          <div class="media-body ml-3"><strong class="h6"><a class="reset-anchor animsition-link" href="detail.php"><?php echo $salles['name']  ?></a></strong></div>

                        </th>
                        <th class="align-middle border-0">
                          <p class="mb-0 small">$<?php echo $salles['price']  ?></p>
                        </th>
                        <th class="align-middle border-0">
                          <!-- <div class="border d-flex align-items-center justify-content-between px-3"><span class="small text-uppercase text-gray headings-font-family">Quantity</span>
                          <div class="quantity">
                            <button class="dec-btn p-0"><i class="fas fa-caret-left"></i></button>
                            <input class="form-control form-control-sm border-0 shadow-0 p-0" type="text" value="1"/>
                            <button class="inc-btn p-0"><i class="fas fa-caret-right"></i></button>
                          </div>
                        </div> -->


                          <form action="" method="post">
                            <input type="hidden" name="cart_id" value="<?php echo $salles['id']; ?>">
                            <input type="number" min="1" name="cart_quantity" value="<?php echo $salles['quantity']; ?>">
                            <input type="submit" name="update_cart" value="تعديل" class="option-btn">

                          </form>
                        </th>

                        <th><?php echo $sub_total = ($salles['price'] * $salles['quantity']); ?>$</th>
                        <td><a href="cart.php?remove=<?php echo $salles['id']; ?>" class="delete-btn" onclick="return confirm('إزالة العنصر من سلة التسوق؟');">حذف</a></td>

                      </tr>



                  <?php
                    $grand_total += $sub_total;
                  }
                } else {
                  echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">العربة فارغة</td></tr>';
                }
                  ?>




                  <tr class="table-bottom">
                    <td colspan="4">المبلغ الإجمالي :</td>
                    <td><?php echo $grand_total; ?>$</td>
                    <td><a href="cart.php?delete_all" onclick="return confirm('حذف كل المنتجات من العربة?');" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">حذف الكل</a></td>
                  </tr>
                    </tbody>
              </table>
            </div>


            <!-- CART NAV-->
            <div class="bg-light px-4 py-3">
              <div class="row align-items-center text-center">
                <div class="col-md-6 mb-3 mb-md-0 text-md-left"><a class="btn btn-link p-0 text-dark btn-sm" href="shop.php"><i class="fas fa-long-arrow-alt-left mr-2"> </i>Continue shopping</a></div>
                <?php

                if (isset($_SESSION['user_id'])) {
                  $user_id = $_SESSION['user_id'];
                  $sql = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id'");

                  if (mysqli_num_rows($sql) > 0) {
                    // إذا كانت العربة تحتوي على عناصر، يتم التوجيه إلى صفحة معالجة الطلبات أو صفحة الدفع
                    echo "<div class='col-md-6 text-md-right'><a class='btn btn-outline-dark btn-sm' href='checkout.php?grandtotal=$grand_total'>Procceed to checkout<i class='fas fa-long-arrow-alt-right ml-2'></i></a></div>";
                  } 
                } else {
                  // إذا لم يكن المستخدم مسجلاً الدخول، يتم التوجيه إلى صفحة تسجيل الدخول
                  echo "<div class='col-md-6 text-md-right'><a class='btn btn-outline-dark btn-sm' href='login/log.php'>Procceed to checkout<i class='fas fa-long-arrow-alt-right ml-2'></i></a></div>";
                }
                ?>

              </div>
            </div>
          </div>
          <!-- ORDER TOTAL-->
          <div class="col-lg-4">
            <div class="card border-0 rounded-0 p-lg-4 bg-light">
              <div class="card-body">
                <h5 class="text-uppercase mb-4">Cart total</h5>
                <ul class="list-unstyled mb-0">
                  <li class="border-bottom my-2"></li>
                  <li class="d-flex align-items-center justify-content-between mb-4"><strong class="text-uppercase small font-weight-bold">Total</strong><span>$<?php echo $grand_total  ?></span></li>
                  <li>
                    <!-- <form action="#">
                      <div class="form-group mb-0">
                        <input class="form-control" type="text" placeholder="Enter your coupon">
                        <button class="btn btn-dark btn-sm btn-block" type="submit"> <i class="fas fa-gift mr-2"></i>Apply coupon</button>
                      </div>
                    </form> -->
                  </li>
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
</body>

</html>