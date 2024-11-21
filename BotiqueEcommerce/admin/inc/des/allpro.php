<?php

if (!isset($_SESSION['admin_name'])) {
    // إعادة توجيه المستخدم إلى صفحة تسجيل الدخول إذا لم تكن الجلسة متاحة
    header("Location: index.php");
    exit();
}

$admin_name = $_SESSION['admin_name'];
?>


<style>
  /* تحسين تنسيق الصورة */
  img {
    width: 100px;
    height: auto;
    margin: 5px;
    border-radius: 5px;
  }

  /* تنسيق جدول العملاء */
  #customers {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    margin-top: 20px;
    /* تباعد من الأعلى لترك مساحة للزر */
  }

  #customers td,
  #customers th {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: center;
    vertical-align: middle;
  }

  #customers tr:nth-child(even) {
    background-color: #f9f9f9;
  }

  #customers tr:hover {
    background-color: #f1f1f1;
  }

  #customers th {
    padding-top: 14px;
    padding-bottom: 14px;
    text-align: center;
    background-color: #04AA6D;
    color: white;
    font-weight: bold;
  }

  /* تحسين تنسيق أزرار العمليات */
  .btn {
    padding: 8px 16px;
    border-radius: 4px;
    color: white;
    text-decoration: none;
    margin: 2px;
  }

  .btn-info {
    background-color: #17a2b8;
  }

  .btn-danger {
    background-color: #dc3545;
  }

  .btn-primary {
    background-color: #007bff;
  }

  /* تنسيق الزر ليكون أعلى يمين الجدول */
  .table-header {
    display: flex;
    justify-content: flex-end;
    margin-bottom: -10px;
    /* لإزالة المسافة بين الزر والجدول */
  }
</style>

<!-- Topbar Search -->
<form action="all_pro.php" method="post" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
  <div class="input-group">
    <input name="search" type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
    <div class="input-group-append">
      <button class="btn btn-primary" type="submit">
        <i class="fas fa-search fa-sm"></i>
      </button>
    </div>
  </div>
</form>

<div class="table-header">
  <a href="?do=add" class="btn btn-primary">Add New Product</a>
</div>


<?php
if (isset($_POST['search'])) {
  $name = $_POST['search'];

  $stmt = $conn->prepare("SELECT * FROM salles WHERE name LIKE ?");
  $like = "%$name%";
  $stmt->bind_param("s", $like);
  $stmt->execute();
  $result_search = $stmt->get_result();
} else {
  $stmt = $conn->prepare("SELECT * FROM salles");
  $stmt->execute();
  $result_search = $stmt->get_result();
}
?>

<table id="customers">
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Price</th>
    <th>Category</th>
    <th>Brand</th>
    <th>Count</th>
    <th>Sold</th>
    <th>Cover</th>
    <th>Process</th>
  </tr>

  <?php
  $rownumber = 1;

  while ($row = $result_search->fetch_assoc()) {
  ?>
    <tr>
      <td><?php echo $rownumber; ?></td>
      <td><?php echo htmlspecialchars($row['name']); ?></td>
      <td><?php echo htmlspecialchars($row['price']); ?></td>
      <td>
        <?php
        $catid = $row['cattigur'];
        $sqlcat = "SELECT name FROM cattigur WHERE id = ?";
        $stmt_cat = $conn->prepare($sqlcat);
        $stmt_cat->bind_param("i", $catid);
        $stmt_cat->execute();
        $resultcat = $stmt_cat->get_result();
        if ($rowcat = $resultcat->fetch_assoc()) {
          echo htmlspecialchars($rowcat['name']);
        }
        ?>
      </td>
      <td>
        <?php
        $brandid = $row['brand'];
        $sqlbrand = "SELECT name FROM brand WHERE id = ?";
        $stmt_brand = $conn->prepare($sqlbrand);
        $stmt_brand->bind_param("i", $brandid);
        $stmt_brand->execute();
        $resultbrand = $stmt_brand->get_result();
        if ($rowbrand = $resultbrand->fetch_assoc()) {
          echo htmlspecialchars($rowbrand['name']);
        }
        ?>
      </td>
      <td><?php echo htmlspecialchars($row['count']); ?></td>
      <td><?php echo htmlspecialchars($row['sold']); ?></td>
      <td>
        <?php
        // فك ترميز JSON إلى مصفوفة
        $covers = json_decode($row['cover'], true);

        // التأكد من أن عملية فك الترميز نجحت وأن المصفوفة ليست فارغة
        if (is_array($covers) && !empty($covers)) {
          foreach ($covers as $image) {
            echo "<img src='inc/des/images/" . htmlspecialchars($image) . "' alt='cover image'>";
          }
        } else {
          echo "لا توجد صور متاحة.";
        }
        ?>

      </td>
      <td>
        <a href="inc/des/editproduct.php?id=<?php echo $row['id']; ?>" class="btn btn-info">Edit Product</a>
        <a href="inc/des/deleteproduct.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete Product</a>
      </td>
    </tr>

  <?php
    $rownumber++;
  }
  ?>
</table>

<?php
$stmt->close();
?>