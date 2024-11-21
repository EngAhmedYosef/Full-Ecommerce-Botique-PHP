<style>
  .card {
    border: 1px solid #ddd;
    /* إطار البطاقة */
  }

  .card-header {
    background-color: #04AA6D;
    /* لون خلفية رأس البطاقة */
    color: white;
    /* لون نص الرأس */
  }

  .card-body {
    background-color: #f9f9f9;
    /* لون خلفية جسم البطاقة */
  }

  .card-footer {
    background-color: #f2f2f2;
    /* لون خلفية تذييل البطاقة */
  }
</style>







<?php
include("conn.php");
session_start();
$admin = $_SESSION['admin'];
$admin_name = $_SESSION['admin_name'];

if (!isset($admin)) {
  header('location:index.php');
  exit();
}

if (isset($_GET['logout'])) {
  unset($admin);
  session_destroy();
  header('location:index.php');
  exit();
};

if (isset($_POST['update'])) {
  $id = $_POST['id'];

  if ($_POST['status'] == "Pending") {
    $sql = mysqli_query($conn, "UPDATE cart SET status=1 WHERE id='$id'");
  }

  if ($_POST['status'] == "Processing") {
    $sql = mysqli_query($conn, "UPDATE cart SET status=2 WHERE id='$id'");
  }

  if ($_POST['status'] == "Delivered") {
    $sql = mysqli_query($conn, "UPDATE cart SET status=3 WHERE id='$id'");
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
    $sql = "SELECT orders.user_id, orders.phone, orders.country, orders.address, orders.address2, orders.city, orders.state, 
        orders.firstName, orders.lastName, orders.total, cart.name AS product_name, cart.quantity, cart.price AS price, 
        cart.status, cart.id, orders.date, orders.paymentMethod  
        FROM orders 
        JOIN users ON orders.user_id = users.id
        JOIN cart ON orders.user_id = cart.user_id";

    $result = $conn->query($sql);

    $order_nums = 1;
    while ($row = $result->fetch_assoc()) {

    ?>
      <div class="col-md-4"> <!-- عمود لكل بطاقة -->
        <div class="card mb-4"> <!-- بطاقة -->
          <div class="card-header">
            <h5 class="card-title" style="color:black">Order #<?php echo $order_nums ?></h5>
          </div>
          <div class="card-body">
            <p><strong>User ID:</strong> <?php echo $row['user_id']; ?></p>
            <p><strong>First Name:</strong> <?php echo $row['firstName']; ?></p>
            <p><strong>Last Name:</strong> <?php echo $row['lastName']; ?></p>
            <p><strong>Phone:</strong> <?php echo $row['phone']; ?></p>
            <p><strong>Country:</strong> <?php echo $row['country']; ?></p>
            <p><strong>Address:</strong> <?php echo $row['address']; ?></p>
            <p><strong>Address2:</strong> <?php echo $row['address2']; ?></p>
            <p><strong>City:</strong> <?php echo $row['city']; ?></p>
            <p><strong>State:</strong> <?php echo $row['state']; ?></p>
            <p><strong>Product Name:</strong> <?php echo $row['product_name']; ?></p>
            <p><strong>Quantity:</strong> <?php echo $row['quantity']; ?></p>
            <p><strong>Price:</strong> <?php echo $row['price']; ?></p>
            <p><strong>Total Price:</strong> <?php echo $row['total']; ?></p>
            <p><strong>Status:</strong> <?php echo $row['status']; ?></p>
            <p><strong>Order Date:</strong> <?php echo $row['date']; ?></p>
            <p><strong>Payment Method:</strong> <?php echo $row['paymentMethod']; ?></p>
          </div>
          <div class="card-footer">
            <form action="#" method="POST">
              <select name="status" class="form-control" required>
                <option value="Pending" <?php if ($row['status'] == 1) echo 'selected'; ?>>1-Pending</option>
                <option value="Processing" <?php if ($row['status'] == 2) echo 'selected'; ?>>2-Processing</option>
                <option value="Delivered" <?php if ($row['status'] == 3) echo 'selected'; ?>>3-Delivered</option>
              </select>
              <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
              <button type="submit" name="update" class="btn btn-primary mt-2">Update</button>
            </form>
            <a href="deleteorder.php?id=<?php echo $row['user_id']; ?>" class="btn btn-danger mt-2">Delete</a>
          </div>
        </div>
      </div>
    <?php
      $order_nums++;
    } ?>
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