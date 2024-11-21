<?php
include("admin/conn.php");
session_start();
@$user_id = $_SESSION['user_id'];


if (isset($_GET['logout'])) {
  unset($user_id);
  session_destroy();
  header('location:login/log.php');
};

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
    header('Location: ' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
    exit();
  } else {
    header('location:login/log.php');
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

  <style>
    .owl-thumb-item {
      transition: transform 0.2s;
      /* تأثير تحريك عند التمرير */
    }

    .owl-thumb-item:hover {
      transform: scale(1.05);
      /* تكبير الصورة قليلاً عند التمرير عليها */
    }

    .product-slider img {
      border-radius: 5px;
      /* إضافة زوايا مستديرة للصور */
    }

    .owl-thumbs img {
      border: 2px solid transparent;
      /* حدود شفافة */
      border-radius: 5px;
      /* إضافة زوايا مستديرة للصورة المصغرة */
    }

    .owl-thumbs img:hover {
      border: 2px solid #007bff;
      /* تغيير لون الحد عند التمرير */
    }
  </style>
</head>

<body>

  <?php

  if (isset($_SESSION['message'])) {

    echo '<div class="alert alert-success" onclick="this.remove();">' . $_SESSION['message'] . '</div>';

    unset($_SESSION['message']);
  }

  ?>
  <div class="page-holder bg-light">
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
              <li class="nav-item">
                <!-- Link--><a class="nav-link active" href="detail.php">Product detail</a>
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
              <li class="nav-item"><a class="nav-link" href="#"> <i class="fas fa-user-alt mr-1 text-gray"></i></a></li>
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

                  <?php

                  $pro_id = $_GET['id'];
                  include('admin/conn.php');
                  $sellpro = "SELECT * FROM salles WHERE id = $pro_id";
                  $query = mysqli_query($conn, $sellpro);
                  while ($row = mysqli_fetch_array($query)) {

                  ?>
                    <h2 class="h4"><?= $row['name']   ?></h2>
                    <p class="text-muted">$<?= $row['price']     ?></p>
                    <p class="text-muted"><?= $row['content']     ?></p>
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


                      <div class="col-sm-5 pl-sm-0"><a class="btn btn-dark btn-sm btn-block h-100 d-flex align-items-center justify-content-center px-0" href="index.php">Add to cart</a></div>
                    </div><a class="btn btn-link text-dark p-0" href="#"><i class="far fa-heart mr-2"></i>Add to wish list</a>

                  <?php } ?>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <section class="py-5">
      <div class="container">
        <div class="row mb-5">
          <div class="col-lg-6">

            <?php

            $pro_id = $_GET['id'];
            include('admin/conn.php');
            $sellpro = "SELECT * FROM salles WHERE id = $pro_id";
            $query = mysqli_query($conn, $sellpro);
            while ($row = mysqli_fetch_array($query)) {


            ?>
              <!-- PRODUCT SLIDER-->
              <div class="row m-0">
                <div class="col-sm-2 p-0 order-2 order-sm-1 mt-2 mt-sm-0">
                  <div class="owl-thumbs d-flex flex-row flex-sm-column" data-slider-id="1">
                    <?php
                    // تحويل النص المفصول بفواصل إلى مصفوفة
                    $covers = explode(',', $row['cover']);

                    // حلقة لعرض الصور
                    foreach ($covers as $image) {
                      // إزالة المسافات الزائدة وعلامات الاقتباس أو الأقواس الزائدة
                      $image = trim($image);
                      $image = str_replace(['[', ']', '"'], '', $image); // إزالة الأقواس المربعة وعلامات الاقتباس

                      // التأكد من أن الصورة ليست فارغة
                      if (!empty($image)) {
                    ?>
                        <div class="owl-thumb-item flex-fill mb-2">
                          <img class="w-100" src="admin/inc/des/images/<?php echo $image; ?>" alt="Cover Image">
                        </div>
                    <?php
                      }
                    }
                    ?>
                  </div>
                </div>
                <div class="col-sm-10 order-1 order-sm-2">
                  <div class="owl-carousel product-slider" data-slider-id="1">
                    <?php
                    // إعادة استخدام نفس مصفوفة الصور للعرض في السلايدر
                    foreach ($covers as $image) {
                      $image = trim($image);
                      $image = str_replace(['[', ']', '"'], '', $image); // إزالة الأقواس المربعة وعلامات الاقتباس
                      if (!empty($image)) {
                    ?>
                        <a class="d-block" href="admin/inc/des/images/<?php echo $image; ?>" data-lightbox="product" title="Product item">
                          <img class="img-fluid" src="admin/inc/des/images/<?php echo $image; ?>" alt="Product Image">
                        </a>
                    <?php
                      }
                    }
                    ?>
                  </div>
                </div>
              </div>

          </div>
        <?php } ?>
        <!-- PRODUCT DETAILS-->
        <div class="col-lg-6">
          <!-- <ul class="list-inline mb-2">
            <li class="list-inline-item m-0"><i class="fas fa-star small text-warning"></i></li>
            <li class="list-inline-item m-0"><i class="fas fa-star small text-warning"></i></li>
            <li class="list-inline-item m-0"><i class="fas fa-star small text-warning"></i></li>
            <li class="list-inline-item m-0"><i class="fas fa-star small text-warning"></i></li>
            <li class="list-inline-item m-0"><i class="fas fa-star small text-warning"></i></li>
          </ul> -->
          <?php

          $pro_id = $_GET['id'];
          include('admin/conn.php');
          $sellpro = "SELECT * FROM salles WHERE id = $pro_id";
          $query = $conn->query($sellpro);
          $salles = $query->fetch_assoc();

          ?>

          <h1><?= $salles['name']   ?></h1>
          <p class="text-muted lead">$<?= $salles['price']     ?></p>
          <p class="text-small mb-4"><?= $salles['content']     ?></p>
          <div class="row align-items-stretch mb-4">
            <div class="col-sm-5 pr-sm-0">
              <!-- نموذج السلة -->
              <form action="" method="post" class="form-inline">
                <input type="hidden" name="product_image" value="<?php echo $image; ?>">
                <input type="hidden" name="product_name" value="<?php echo $salles['name']; ?>">
                <input type="hidden" name="product_price" value="<?php echo $salles['price']; ?>">

                <div class="form-group mb-2">
                  <label for="quantity" class="sr-only">Quantity</label>
                  <input type="number" min="1" name="product_quantity" value="1" class="form-control" id="quantity">
                </div>

                <button type="submit" name="add_to_cart" class="btn btn-dark btn-sm ml-2">Add to Cart</button>
              </form>


              <?php

              $pro_id = $_GET['id'];
              $sellcat = "SELECT cattigur.name 
              FROM cattigur 
              INNER JOIN salles ON cattigur.id = salles.cattigur 
              WHERE salles.id = $pro_id";
              $query = $conn->query($sellcat);
              $cat = $query->fetch_assoc();

              $sellbrand = "SELECT brand.name 
              FROM brand 
              INNER JOIN salles ON brand.id = salles.brand 
              WHERE salles.id = $pro_id";
              $query_brand = $conn->query($sellbrand);
              $brand = $query_brand->fetch_assoc();

              ?>

              <!-- نموذج المفضلة -->
              <form action="" method="post" class="form-inline mt-3">
                <input type="hidden" name="favorite_id" value="<?php echo $salles['id']; ?>">
                <input type="hidden" name="favorite_image" value="<?php echo $image; ?>">
                <input type="hidden" name="favorite_name" value="<?php echo $salles['name']; ?>">
                <input type="hidden" name="favorite_price" value="<?php echo $salles['price']; ?>">

                <button class="btn btn-outline-dark btn-sm ml-2" name="favorite" type="submit">
                  <i class="far fa-heart"></i>
                </button>
              </form>



              <br>
              <ul class="list-unstyled small d-inline-block">
                <!-- <li class="px-3 py-2 mb-1 bg-white"><strong class="text-uppercase">SKU:</strong><span class="ml-2 text-muted">039</span></li> -->
                <li class="px-3 py-2 mb-1 bg-white text-muted"><strong class="text-uppercase text-dark">Category:</strong><a class="reset-anchor ml-2" href="#"><?php echo $cat['name']; ?></a></li>
                <li class="px-3 py-2 mb-1 bg-white text-muted"><strong class="text-uppercase text-dark">Brand:</strong><a class="reset-anchor ml-2" href="#"><?php echo $brand['name']; ?></a></li>
              </ul>

            </div>
          </div>
        </div>
        </div>
      </div>
      <!-- DETAILS TABS-->
      <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
        <li class="nav-item"><a class="nav-link active" id="description-tab" data-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="true">Description</a></li>
        <!-- <li class="nav-item"><a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">Reviews</a></li> -->
      </ul>
      <div class="tab-content mb-5" id="myTabContent">
        <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
          <div class="p-4 p-lg-5 bg-white">
            <h6 class="text-uppercase">Product description </h6>
            <p class="text-muted text-small mb-0"><?php echo $salles['content'] ?></p>
          </div>
        </div>
        <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
          <div class="p-4 p-lg-5 bg-white">
            <div class="row">
              <div class="col-lg-8">
                <div class="media mb-3"><img class="rounded-circle" src="img/customer-1.png" alt="" width="50">
                  <div class="media-body ml-3">
                    <h6 class="mb-0 text-uppercase">Jason Doe</h6>
                    <p class="small text-muted mb-0 text-uppercase">20 May 2020</p>
                    <ul class="list-inline mb-1 text-xs">
                      <li class="list-inline-item m-0"><i class="fas fa-star text-warning"></i></li>
                      <li class="list-inline-item m-0"><i class="fas fa-star text-warning"></i></li>
                      <li class="list-inline-item m-0"><i class="fas fa-star text-warning"></i></li>
                      <li class="list-inline-item m-0"><i class="fas fa-star text-warning"></i></li>
                      <li class="list-inline-item m-0"><i class="fas fa-star-half-alt text-warning"></i></li>
                    </ul>
                    <p class="text-small mb-0 text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                  </div>
                </div>
                <div class="media"><img class="rounded-circle" src="img/customer-2.png" alt="" width="50">
                  <div class="media-body ml-3">
                    <h6 class="mb-0 text-uppercase">Jason Doe</h6>
                    <p class="small text-muted mb-0 text-uppercase">20 May 2020</p>
                    <ul class="list-inline mb-1 text-xs">
                      <li class="list-inline-item m-0"><i class="fas fa-star text-warning"></i></li>
                      <li class="list-inline-item m-0"><i class="fas fa-star text-warning"></i></li>
                      <li class="list-inline-item m-0"><i class="fas fa-star text-warning"></i></li>
                      <li class="list-inline-item m-0"><i class="fas fa-star text-warning"></i></li>
                      <li class="list-inline-item m-0"><i class="fas fa-star-half-alt text-warning"></i></li>
                    </ul>
                    <p class="text-small mb-0 text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- RELATED PRODUCTS-->
      <h2 class="h5 text-uppercase mb-4">Related products</h2>
      <div class="row">
        <?php
        // الحصول على ID المنتج من الرابط
        $pro_id = $_GET['id'];
        $sellrelatedcat = "SELECT * FROM salles WHERE id = $pro_id";
        $query = $conn->query($sellrelatedcat);
        $sell_rel_cat = $query->fetch_assoc();

        // حساب إجمالي عدد المنتجات في نفس الفئة
        $query_trend_counts = mysqli_query($conn, "SELECT * FROM salles WHERE cattigur=$sell_rel_cat[cattigur]") or die('Failed');
        $number_of_results = mysqli_num_rows($query_trend_counts);
        $number_of_pages = ceil($number_of_results / 4); // 4 منتجات لكل صفحة

        // الصفحة الحالية
        $page_get = isset($_GET['page']) ? $_GET['page'] : 1;
        $off = ($page_get - 1) * 4;

        // جلب المنتجات بناءً على الصفحة الحالية والفئة
        $query_trend = mysqli_query($conn, "SELECT * FROM salles WHERE cattigur=$sell_rel_cat[cattigur] LIMIT $off, 4") or die('Failed');

        // عرض المنتجات إذا كانت موجودة
        if (mysqli_num_rows($query_trend) > 0) {
          while ($row = mysqli_fetch_assoc($query_trend)) {
        ?>
            <!-- PRODUCT -->
            <div class="col-auto mb-3">
              <div class="product text-center">
                <div class="position-relative mb-3">
                  <div class="badge text-white badge-"></div>
                  <a class="d-block" href="detail.php?id=<?php echo $row['id']; ?>">
                    <?php

                    $covers = explode(',', $row['cover']);
                    $image = trim($covers[0]);
                    $image = str_replace(['[', ']', '"'], '', $image); // إزالة الأقواس المربعة وعلامات الاقتباس


                    ?>
                    <img class="img-fluid" style="max-width: 100%; height: auto;" src="admin/inc/des/images/<?php echo $image ?>" alt="...">

                  </a>
                  <div class="product-overlay">
                    <ul class="mb-0 list-inline d-flex justify-content-center gap-2"> <!-- إضافة d-flex و gap-2 -->
                      <!-- نموذج المفضلة -->
                      <form action="" method="post">
                        <input type="hidden" name="favorite_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="favorite_image" value="<?php echo $row['cover']; ?>">
                        <input type="hidden" name="favorite_name" value="<?php echo $row['name']; ?>">
                        <input type="hidden" name="favorite_price" value="<?php echo $row['price']; ?>">
                        <button class="btn btn-sm btn-outline-dark" name="favorite" type="submit">
                          <i class="far fa-heart"></i>
                        </button>
                      </form>

                      <!-- نموذج السلة -->
                      <form action="" method="post" class="d-flex align-items-center">
                        <input type="hidden" name="product_image" value="<?php echo $row['cover']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
                        <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                        <input type="number" min="1" name="product_quantity" value="1" style="width: 60px;" class="me-2">
                        <input class="btn btn-sm btn-dark" type="submit" value="Add to cart" name="add_to_cart" class="btn">
                      </form>

                      <li class="list-inline-item mr-0">
                        <a class="btn btn-sm btn-outline-dark" href="detail.php?id=<?php echo $row['id']; ?>"><i class="fas fa-expand"></i></a>
                      </li>
                    </ul>
                  </div>
                </div>

                <h6>
                  <a class="reset-anchor" href="detail.php?id=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a>
                </h6>
                <p class="small text-muted">$<?php echo $row['price']; ?></p>
              </div>
            </div>
        <?php
          }
        } else {
          echo "<p>No products found</p>";
        }
        ?>


      </div>

      <!-- PAGINATION -->
      <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center justify-content-lg-end">
          <!-- الزر السابق -->
          <?php if ($page_get > 1) { ?>
            <li class="page-item">
              <a class="page-link" href="detail.php?page=<?= $page_get - 1 ?>&id=<?= $pro_id ?>" aria-label="Previous">
                <span aria-hidden="true">«</span>
              </a>
            </li>
          <?php } ?>

          <!-- عرض أرقام الصفحات -->
          <?php
          for ($page = 1; $page <= $number_of_pages; $page++) {
          ?>
            <li class="page-item <?php if ($page_get == $page) echo 'active'; ?>">
              <a class="page-link" href="detail.php?page=<?= $page ?>&id=<?= $pro_id ?>"><?= $page ?></a>
            </li>
          <?php } ?>

          <!-- الزر التالي -->
          <?php if ($page_get < $number_of_pages) { ?>
            <li class="page-item">
              <a class="page-link" href="detail.php?page=<?= $page_get + 1 ?>&id=<?= $pro_id ?>" aria-label="Next">
                <span aria-hidden="true">»</span>
              </a>
            </li>
          <?php } ?>
        </ul>
      </nav>

  </div>


  </section>
  <footer class="bg-dark text-white">
    <div class="container py-4">
      <div class="row py-5">
        <div class="col-md-4 mb-3 mb-md-0">
          <h6 class="text-uppercase mb-3">Customer services</h6>
          <ul class="list-unstyled mb-0">
            <li><a class="footer-link" href="contact.php">Help &amp; Contact Us</a></li>
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