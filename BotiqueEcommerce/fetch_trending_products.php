<?php
include('admin/conn.php');

// التحقق من الصفحة المطلوبة
$page_get = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$items_per_page = 4;
$off = ($page_get - 1) * $items_per_page;

// استعلام المنتجات بناءً على الصفحة
$query_trend = mysqli_query($conn, "SELECT * FROM salles WHERE sold>40 LIMIT $items_per_page OFFSET $off") or die('Failed');


// عرض المنتجات
while ($row = mysqli_fetch_assoc($query_trend)) {
    if (mysqli_num_rows($query_trend) > 0) {
?>
        <!-- PRODUCT -->
        <div class="col-md-3 mb-3">
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
                                <input type="hidden" name="favorite_image" value="<?php echo $image; ?>">
                                <input type="hidden" name="favorite_name" value="<?php echo $row['name']; ?>">
                                <input type="hidden" name="favorite_price" value="<?php echo $row['price']; ?>">
                                <button class="btn btn-sm btn-outline-dark" name="favorite" type="submit">
                                    <i class="far fa-heart"></i>
                                </button>
                            </form>

                            <!-- نموذج السلة -->
                            <form action="" method="post" class="d-flex align-items-center add-to-cart-form">
                                <input type="hidden" name="product_image" value="<?php echo $image; ?>">
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
}


// حساب إجمالي عدد الصفحات
$query_total = mysqli_query($conn, "SELECT COUNT(*) AS total FROM salles WHERE sold > 40");
$row_total = mysqli_fetch_assoc($query_total);
$total_pages = ceil($row_total['total'] / $items_per_page);
?>

<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center justify-content-lg-end">
        <?php if ($page_get > 1) { ?>
            <li class="page-item">
                <a class="page-link" href="#" data-page="<?= $page_get - 1 ?>">«</a>
            </li>
        <?php } ?>
        <?php for ($page = 1; $page <= $total_pages; $page++) { ?>
            <li class="page-item <?php if ($page_get == $page) echo 'active'; ?>">
                <a class="page-link" href="#" data-page="<?= $page ?>"><?= $page ?></a>
            </li>
        <?php } ?>
        <?php if ($page_get < $total_pages) { ?>
            <li class="page-item">
                <a class="page-link" href="#" data-page="<?= $page_get + 1 ?>">»</a>
            </li>
        <?php } ?>
    </ul>
</nav>

<?php
?>

