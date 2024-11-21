<?php
include("conn.php");
session_start();

if (!isset($_SESSION['admin_name'])) {
  // إعادة توجيه المستخدم إلى صفحة تسجيل الدخول إذا لم تكن الجلسة متاحة
  header("Location: index.php");
  exit();
}

$admin_name = $_SESSION['admin_name'];
$admin = $_SESSION['admin'];



if (isset($_GET['logout'])) {
  unset($admin);
  session_destroy();
  header('location:index.php');
  exit();
}

if (isset($_POST['update'])) {
  echo $order_id = $_POST['order_id'];

  if ($_POST['status'] == "Pending") {
    $sql = mysqli_query($conn, "UPDATE orders SET status=1 WHERE id='$order_id'");
  }

  if ($_POST['status'] == "Processing") {
    $sql = mysqli_query($conn, "UPDATE orders SET status=2 WHERE id='$order_id'");
  }

  if ($_POST['status'] == "Delivered") {
    $sql = mysqli_query($conn, "UPDATE orders SET status=3 WHERE id='$order_id'");
  }
}

include 'inc/des/menue.php';
include 'inc/des/header.php';
?>

<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Orders</h1>
  </div>

  <div class="row"> <!-- حاوية الصفوف للبطاقات -->
    <?php
    // استعلام لجلب جميع الطلبات لكل عميل
    $sql = "SELECT orders.id AS order_id, orders.user_id, orders.phone, orders.country, orders.address, 
    orders.address2, orders.city, orders.state, orders.status, orders.firstName, orders.lastName, 
    orders.total, cart_orders.name AS product_name, cart_orders.quantity, cart_orders.price AS price, 
    orders.date, orders.paymentMethod 
    FROM orders 
    JOIN users ON orders.user_id = users.id
    JOIN cart_orders ON orders.id = cart_orders.order_id"; // ربط الطلبات بجدول cart_orders باستخدام order_id

    $result = $conn->query($sql);

    // مصفوفة لتخزين الطلبات
    $orders = [];
    while ($row = $result->fetch_assoc()) {
      $order_id = $row['order_id'];

      // إذا كانت البيانات موجودة بالفعل، أضف المنتج إلى المصفوفة
      if (!isset($orders[$order_id])) {
        $orders[$order_id] = [
          'order_id' => $row['order_id'],
          'user_id' => $row['user_id'],
          'firstName' => $row['firstName'],
          'lastName' => $row['lastName'],
          'phone' => $row['phone'],
          'country' => $row['country'],
          'address' => $row['address'],
          'address2' => $row['address2'],
          'city' => $row['city'],
          'state' => $row['state'],
          'status' => $row['status'],
          'total' => $row['total'],
          'date' => $row['date'],
          'paymentMethod' => $row['paymentMethod'],
          'products' => []
        ];
      }

      // أضف تفاصيل المنتج إلى المصفوفة
      $orders[$order_id]['products'][] = [
        'product_name' => $row['product_name'],
        'quantity' => $row['quantity'],
        'price' => $row['price']
      ];
    }

    // عرض الطلبات
    foreach ($orders as $order) {
    ?>
      <div class="col-md-6 col-lg-4"> <!-- تصميم أفضل للأعمدة على الشاشات المختلفة -->
        <div class="card shadow-sm mb-4 border-0"> <!-- إضافة ظل وإزالة الحدود للحصول على مظهر أنظف -->
          <div class="card-header bg-primary text-white text-center">
            <h5 class="card-title m-0">Order ID: <?php echo $order['order_id']; ?></h5>
            <small>User ID: <?php echo $order['user_id']; ?></small>
          </div>
          <div class="card-body">
            <h6 class="text-primary">User Information</h6>
            <p><strong>First Name:</strong> <?php echo $order['firstName']; ?></p>
            <p><strong>Last Name:</strong> <?php echo $order['lastName']; ?></p>
            <p><strong>Phone:</strong> <?php echo $order['phone']; ?></p>
            <hr>
            <h6 class="text-primary">Order Details</h6>
            <p><strong>Country:</strong> <?php echo $order['country']; ?></p>
            <p><strong>City:</strong> <?php echo $order['city']; ?></p>
            <p><strong>State:</strong> <?php echo $order['state']; ?></p>
            <p><strong>Status:</strong> <?php echo $order['status']; ?></p>
            <p><strong>Total:</strong> <?php echo $order['total']; ?> USD</p>
            <p><strong>Date:</strong> <?php echo $order['date']; ?></p>
            <p><strong>Payment Method:</strong> <?php echo $order['paymentMethod']; ?></p>
            <hr>
            <h6 class="text-primary">Products:</h6>
            <?php foreach ($order['products'] as $product) { ?>
              <p><strong>Product Name:</strong> <?php echo $product['product_name']; ?></p>
              <p><strong>Quantity:</strong> <?php echo $product['quantity']; ?></p>
              <p><strong>Price:</strong> <?php echo $product['price']; ?> USD</p>
              <hr>
            <?php } ?>
          </div>
          <div class="card-footer">
            <form action="#" method="POST" class="d-flex justify-content-between">
              <select name="status" class="form-control" required>
                <option value="Pending" <?php if ($order['status'] == 1) echo 'selected'; ?>>1-Pending</option>
                <option value="Processing" <?php if ($order['status'] == 2) echo 'selected'; ?>>2-Processing</option>
                <option value="Delivered" <?php if ($order['status'] == 3) echo 'selected'; ?>>3-Delivered</option>
              </select>
              <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
              <button type="submit" name="update" class="btn btn-sm btn-primary ml-2">Update</button>
            </form>
            <a href="deleteorder.php?id=<?php echo $order['order_id']; ?>" class="btn btn-sm btn-danger mt-2 w-100">Delete</a>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>


<script src="js/jquery-3.6.0.min.js"></script>
<br>

<?php include 'inc/des/footer.php'; ?>

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        <?php echo "<a class='btn btn-primary' href='all_pro.php?logout=" . $admin . "'>Logout</a>"; ?>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>
<!-- Page level plugins -->
<script src="vendor/chart.js/Chart.min.js"></script>
<!-- Page level custom scripts -->
<script src="js/demo/chart-area-demo.js"></script>
<script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>
