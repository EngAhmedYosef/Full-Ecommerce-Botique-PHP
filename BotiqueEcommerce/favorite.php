<?php
include("admin/conn.php");
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login/log.php');
}


if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:login/log.php');
};



?>


<?php
include("include/header.php");
?>

<style>
    .card {
        border-radius: 10px;
        /* زوايا دائرية */
        overflow: hidden;
        /* لقص المحتوى الزائد */
        transition: transform 0.2s;
        /* تأثير التحول */
    }

    .card:hover {
        transform: translateY(-5px);
        /* رفع البطاقة عند التحويم */
    }

    .product-image {
        width: 100%;
        /* عرض الصورة 100% من البطاقة */
        height: 200px;
        /* ارتفاع موحد للصورة */
        object-fit: contain;
        /* عرض الصورة بالكامل مع الحفاظ على النسبة */
        border-bottom: 1px solid #e0e0e0;
        /* فصل الصورة عن المحتوى */
    }


    .card-title {
        font-size: 1.25rem;
        /* حجم النص */
        font-weight: bold;
        /* سمك النص */
    }

    .card-text {
        font-size: 1rem;
        /* حجم نص السعر */
    }

    .custom-alert {
        background-color: #fff3cd; /* لون خلفية */
        color: #856404; /* لون النص */
        border: 1px solid #ffeeba; /* لون الحدود */
        padding: 15px; /* مسافة داخلية */
        border-radius: 5px; /* زوايا دائرية */
        font-size: 1.5rem; /* حجم النص */
        text-align: center; /* مركزية النص داخل البطاقة */
    }
</style>

<body>



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
                            <!-- <li class="nav-item">
                               <a class="nav-link" href="detail.php">Product detail</a>
                            </li> -->
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



        <div class="container mt-5">
            <h1 class="text-center mb-4">العناصر المفضلة</h1>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <!-- PAGINATION-->
                <?php
                $sql_favourite_counts = mysqli_query($conn, "SELECT * FROM favorite WHERE user_id='$user_id'") or die("FAILED");

                $number_of_results = mysqli_num_rows($sql_favourite_counts);

                // تحديد عدد الصفحات (6 منتجات لكل صفحة)
                $results_per_page = 6;
                $number_of_pages = ceil($number_of_results / $results_per_page);

                // تحديد الصفحة الحالية والـ OFFSET
                if (!isset($_GET['page'])) {
                    $page_get = 1;
                } else {
                    $page_get = $_GET['page'];
                }

                $off = ($page_get - 1) * $results_per_page;

                // جلب المنتجات المفضلة مع تحديد العدد والـ OFFSET
                $sql_favourite = mysqli_query($conn, "SELECT * FROM favorite WHERE user_id='$user_id' LIMIT $results_per_page OFFSET $off") or die("FAILED");
                if (mysqli_num_rows($sql_favourite) > 0) {
                    while ($row = mysqli_fetch_assoc($sql_favourite)) {
                ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-light">
                                <!-- صورة المنتج -->
                                <?php
                                $covers = explode(',', $row['image']);
                                $image = trim($covers[0]);
                                $image = str_replace(['[', ']', '"'], '', $image); // إزالة الأقواس المربعة وعلامات الاقتباس
                                ?>
                                <img class="img-fluid product-image" src="admin/inc/des/images/<?php echo $image ?>" alt="...">

                                <!-- محتوى البطاقة -->
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo $row['name'] ?></h5>
                                    <p class="card-text text-muted">$<?php echo $row['price'] ?></p>
                                    <!-- زر الحذف -->
                                    <a href="deletefavorite.php?id=<?php echo $row['id'] ?>" class="btn btn-outline-danger mt-auto align-self-start">
                                        <i class="bi bi-trash3"></i> حذف
                                    </a>
                                </div>
                            </div>
                        </div>


                <?php }
                }
                else{
                    echo "
                    <div class='col-12 text-center mt-5'>
                        <div class='custom-alert'>
                            <strong>لا يوجد عناصر مفضلة!</strong> أضف بعض العناصر إلى المفضلة لديك.
                        </div>
                    </div>";
                }
                ?>
            </div>

            <!-- PAGINATION-->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center justify-content-lg-end">
                    <!-- الزر السابق -->
                    <?php if ($page_get > 1) { ?>
                        <li class="page-item">
                            <a class="page-link" href="favorite.php?page=<?= $page_get - 1 ?>" aria-label="Previous">
                                <span aria-hidden="true">«</span>
                            </a>
                        </li>
                    <?php } ?>

                    <!-- عرض أرقام الصفحات -->
                    <?php for ($page = 1; $page <= $number_of_pages; $page++) { ?>
                        <li class="page-item <?php if ($page_get == $page) echo 'active'; ?>">
                            <a class="page-link" href="favorite.php?page=<?= $page ?>"><?= $page ?></a>
                        </li>
                    <?php } ?>

                    <!-- الزر التالي -->
                    <?php if ($page_get < $number_of_pages) { ?>
                        <li class="page-item">
                            <a class="page-link" href="favorite.php?page=<?= $page_get + 1 ?>" aria-label="Next">
                                <span aria-hidden="true">»</span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>








        <?php

        include('include/footer.php');

        ?>
</body>

</html>