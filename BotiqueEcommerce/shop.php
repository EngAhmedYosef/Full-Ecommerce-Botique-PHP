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


<?php
include("include/header.php");
?>

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
                <!-- Link--><a class="nav-link" href="index.php">Home</a>
              </li>
              <li class="nav-item">
                <!-- Link--><a class="nav-link active" href="shop.php">Shop</a>
              </li>
              <!-- <li class="nav-item">
                <a class="nav-link" href="detail.php">Product detail</a>
              </li> -->
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

                    <!-- <form action="" method="post">
                      <input type="text" name="idview" value="<?php echo $row_view['id'] ?>">
                    </form> -->


                    <li class="list-inline-item m-0"><i class="fas fa-star small text-warning"></i></li>
                    <li class="list-inline-item m-0"><i class="fas fa-star small text-warning"></i></li>
                    <li class="list-inline-item m-0"><i class="fas fa-star small text-warning"></i></li>
                    <li class="list-inline-item m-0"><i class="fas fa-star small text-warning"></i></li>
                    <li class="list-inline-item m-0"><i class="fas fa-star small text-warning"></i></li>
                  </ul>
                  <h2 class="h4">red</h2>
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
              <h1 class="h2 text-uppercase mb-0">Shop</h1>
            </div>
            <div class="col-lg-6 text-lg-right">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-lg-end mb-0 px-0">
                  <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Shop</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </section>


      <div class="topnav">
        <a class="active" href="#home">Home</a>
        <div class="search-container">
          <form action="shop.php" method="post">
            <input type="text" placeholder="Search.." name="search">
            <button type="submit"><i class="fa fa-search"></i></button>
          </form>

        </div>
      </div>

      <section class="py-5">
        <div class="container p-0">
          <div class="row">
            <!-- SHOP SIDEBAR-->
            <div class="col-lg-3 order-2 order-lg-1">
              <h5 class="text-uppercase mb-4">Categories</h5>
              <div class="py-2 px-4 bg-dark text-white mb-3"><strong class="small text-uppercase font-weight-bold">Fashion &amp; ACC</strong></div>
              <ul class="list-unstyled small text-muted pl-lg-4 font-weight-normal">
                <?php
                include('admin/conn.php');
                $selectcat = "SELECT * FROM cattigur";
                $querycat = $conn->query($selectcat);
                foreach ($querycat as $cattigur) {

                ?>

                  <li class="mb-2"><a class="reset-anchor" href="?catid=<?= $cattigur['id']  ?>"><?= $cattigur['name']   ?></a></li>
                <?php  } ?>
                <li class="mb-2"><a class="reset-anchor" href="shop.php">ALL</a></li>


              </ul>



            </div>

            <?php
            $query_salles = mysqli_query($conn, 'SELECT * FROM salles') or die('FAILED');
            $sales_count = mysqli_num_rows($query_salles);
            @$cat_id = $_GET['catid'];
            @$query_cat = mysqli_query($conn, "SELECT * FROM salles WHERE cattigur='$cat_id'") or die('FAILED');
            @$cat_count = mysqli_num_rows($query_cat);
            ?>
            <!-- SHOP LISTING-->
            <div class="col-lg-9 order-1 order-lg-2 mb-5 mb-lg-0">
              <div class="row mb-3 align-items-center">
                <div class="col-lg-6 mb-2 mb-lg-0">
                  <p class="text-small text-muted mb-0">Showing 1–12 of <?php

                                                                        if (isset($cat_id)) {
                                                                          echo $cat_count;
                                                                        } else {
                                                                          echo $sales_count;
                                                                        }

                                                                        ?> results</p>
                </div>
                <div class="col-lg-6">
                  <ul class="list-inline d-flex align-items-center justify-content-lg-end mb-0">
                    <li class="list-inline-item text-muted mr-3"><a class="reset-anchor p-0" href="#"><i class="fas fa-th-large"></i></a></li>
                    <li class="list-inline-item text-muted mr-3"><a class="reset-anchor p-0" href="#"><i class="fas fa-th"></i></a></li>
                    <li class="list-inline-item">
                      <form>

                        <select class="selectpicker ml-auto" name="sorting" data-width="200" data-style="bs-select-form-control" data-title="Default sorting" onchange="this.parentElement.parentElement.submit()">
                          <option value="default">Default sorting</option>
                          <option value="popularity">Popularity</option>
                          <option value="low-high">Price: Low to High</option>
                          <option value="high-low">Price: High to Low</option>
                        </select>

                      </form>
                    </li>
                  </ul>
                </div>
              </div>


              <div class="row">
                <?php
                include('admin/conn.php');
                // تحديد عدد العناصر في الصفحة


                $limit = 12;
                $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                $offset = ($page - 1) * $limit;

                // Get category and sorting parameters
                $cat_get = isset($_GET['catid']) ? intval($_GET['catid']) : null;
                $sort = isset($_GET['sorting']) ? $_GET['sorting'] : null;

                // Initialize the base query
                $baseQuery = "SELECT * FROM salles";
                $totalCountQuery = "SELECT COUNT(*) FROM salles";

                // Modify the query based on category
                if ($cat_get) {
                  $baseQuery .= " WHERE cattigur = $cat_get";
                  $totalCountQuery .= " WHERE cattigur = $cat_get";
                }

                // Check if there's a search term
                $searchTerm = '';
                if (isset($_POST['search'])) {
                  $searchTerm = $conn->real_escape_string($_POST['search']);
                  $baseQuery .= empty($cat_get) ? " WHERE name LIKE '%$searchTerm%'" : " AND name LIKE '%$searchTerm%'";
                  $totalCountQuery .= empty($cat_get) ? " WHERE name LIKE '%$searchTerm%'" : " AND name LIKE '%$searchTerm%'";
                } else {
                  // Modify the query based on sorting
                  if ($sort == "low-high") {
                    $baseQuery .= " ORDER BY price ASC";
                  } elseif ($sort == "high-low") {
                    $baseQuery .= " ORDER BY price DESC";
                  }

                  // Add pagination to the query
                  $baseQuery .= " LIMIT $limit OFFSET $offset";
                }

                // Execute the total count query
                $totalResults = $conn->query($totalCountQuery);
                $totalRows = $totalResults->fetch_row()[0];

                // Calculate the total number of pages only if not searching
                $number_of_pages = isset($_POST['search']) ? 1 : ceil($totalRows / $limit);

                // Execute the main query
                $query = $conn->query($baseQuery);
                ?>

                <div id="salles-container" class="row">
                  <?php while ($row = mysqli_fetch_array($query)) {
                    $covers = explode(',', $row['cover']);
                    $image = trim($covers[0]);
                    $image = str_replace(['[', ']', '"'], '', $image);
                  ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                      <div class="product text-center p-3 shadow-sm bg-white rounded">
                        <div class="position-relative mb-3">
                          <a class="d-block" href="detail.php?id=<?php echo $row['id']; ?>">
                            <img class="img-fluid rounded" style="max-width: 100%; height: 250px; object-fit: contain;" src="admin/inc/des/images/<?php echo $image ?>" alt="Product Image">
                          </a>
                          <div class="product-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                            <ul class="mb-0 list-inline d-flex justify-content-center gap-2">
                              <!-- Favorite Form -->
                              <form action="" method="post">
                                <input type="hidden" name="favorite_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="favorite_image" value="<?php echo $image; ?>">
                                <input type="hidden" name="favorite_name" value="<?php echo $row['name']; ?>">
                                <input type="hidden" name="favorite_price" value="<?php echo $row['price']; ?>">
                                <button class="btn btn-sm btn-outline-dark" name="favorite" type="submit">
                                  <i class="far fa-heart"></i>
                                </button>
                              </form>
                              <!-- Cart Form -->
                              <form action="" method="post" class="d-flex align-items-center">
                                <input type="hidden" name="product_image" value="<?php echo $image; ?>">
                                <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
                                <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                                <input type="number" min="1" name="product_quantity" value="1" class="form-control form-control-sm me-2" style="width: 60px;">
                                <input class="btn btn-sm btn-dark" type="submit" value="Add to cart" name="add_to_cart">
                              </form>
                              <li class="list-inline-item">
                                <a class="btn btn-sm btn-outline-dark" href="detail.php?id=<?php echo $row['id']; ?>"><i class="fas fa-expand"></i></a>
                              </li>
                            </ul>
                          </div>
                        </div>
                        <h6 class="mb-2">
                          <a class="reset-anchor text-dark" href="detail.php?id=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a>
                        </h6>
                        <p class="small text-muted mb-0">$<?php echo $row['price']; ?></p>
                      </div>
                    </div>
                  <?php } ?>
                </div>

                <!-- PAGINATION -->
                <?php if (empty($searchTerm)) { ?>
                  <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center justify-content-lg-end">
                      <?php if ($page > 1) { ?>
                        <li class="page-item">
                          <a class="page-link" href="shop.php?catid=<?= $cat_get ?>&sorting=<?= $sort ?>&page=<?= $page - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">«</span>
                          </a>
                        </li>
                      <?php } ?>

                      <?php for ($pageIndex = 1; $pageIndex <= $number_of_pages; $pageIndex++) { ?>
                        <li class="page-item <?= ($page == $pageIndex) ? 'active' : '' ?>">
                          <a class="page-link" href="shop.php?catid=<?= $cat_get ?>&sorting=<?= $sort ?>&page=<?= $pageIndex ?>"><?= $pageIndex ?></a>
                        </li>
                      <?php } ?>

                      <?php if ($page < $number_of_pages) { ?>
                        <li class="page-item">
                          <a class="page-link" href="shop.php?catid=<?= $cat_get ?>&sorting=<?= $sort ?>&page=<?= $page + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">»</span>
                          </a>
                        </li>
                      <?php } ?>
                    </ul>
                  </nav>
                <?php } ?>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                  $(document).ready(function() {
                    $('.pagination').on('click', 'a', function(e) {
                      e.preventDefault(); // Prevent the default behavior of the link
                      var page = $(this).attr('href').split('=').pop(); // Get the page number from the link
                      var catid = <?= json_encode($cat_get) ?>; // Get the category ID
                      var sorting = <?= json_encode($sort) ?>; // Get the sorting type

                      // Send an AJAX request to fetch data
                      $.ajax({
                        url: 'shop.php?catid=' + catid + '&sorting=' + sorting + '&page=' + page,
                        type: 'GET',
                        success: function(data) {
                          // Update the content in the appropriate div
                          $('#salles-container').html($(data).find('#salles-container').html());
                          // Update the active page number
                          $('.pagination').html($(data).find('.pagination').html());
                        },
                        error: function() {
                          alert('Error loading data. Please try again.');
                        }
                      });
                    });
                  });
                </script>

                <div class="row">
                </div>

                <?php
                include('admin/conn.php');
                $selectuser = "SELECT * FROM users";
                $qry = $conn->query($selectuser);


                ?>
                <!-- PAGINATION-->
                </ul>
                </nav>



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
              <p class="small text-muted mb-0">&copy; 2024 All rights reserved.</p>
            </div>
            <div class="col-lg-6 text-lg-right">
              <p class="small text-muted mb-0">Template designed by <a class="text-white reset-anchor" href="https://bootstraptemple.com/p/bootstrap-ecommerce">Bootstrap Temple</a></p>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <?php

    include('include/footer.php');

    ?>
</body>

</html>