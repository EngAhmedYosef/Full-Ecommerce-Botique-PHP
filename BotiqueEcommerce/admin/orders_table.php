<style>
  img {
    width: 100px;
    height: auto;
  }

  #customers {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
  }

  #customers td,
  #customers th {
    border: 1px solid #ddd;
    padding: 8px;
  }

  #customers tr:nth-child(even) {
    background-color: #f2f2f2;
  }

  #customers tr:hover {
    background-color: #ddd;
  }

  #customers th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #04AA6D;
    color: white;
  }

  .form-control {
    width: auto;
    min-width: 150px;
  }

  .btn {
    margin-top: 10px;
  }

  /* جعل الجدول يظهر بشكل عمودي على الشاشات الصغيرة */
  @media (max-width: 768px) {
    #customers thead {
      display: none;
      /* إخفاء العناوين الرأسية */
    }

    #customers tr {
      display: block;
      margin-bottom: 15px;
      background-color: #f9f9f9;
      padding: 8px;
      border: 1px solid #ddd;
    }

    #customers td {
      display: flex;
      justify-content: space-between;
      padding: 10px 8px;
      border: none;
      border-bottom: 1px solid #ddd;
      position: relative;
      text-align: left;
    }

    #customers td::before {
      content: attr(data-label);
      font-weight: bold;
      flex: 0 0 45%;
      text-align: left;
      color: #333;
    }
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
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Orders</h1>
  </div>

  <div class="table-responsive"> <!-- إضافة فئة table-responsive -->

    <table id="customers" class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>User ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Phone</th>
          <th>Country</th>
          <th>Address</th>
          <th>Address 2</th>
          <th>City</th>
          <th>State</th>
          <th>Product Name</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Total Price</th>
          <th>Status</th>
          <th>Order Date</th>
          <th>Order Status</th>
          <th>Payment Method</th>
          <th>Controls</th>
        </tr>
      </thead>
      <tbody>
        <?php

        $sql = "SELECT orders.user_id, orders.phone, orders.country, orders.address, orders.address2, orders.city, orders.state, 
              orders.firstName, orders.lastName, orders.total, cart.name AS product_name, cart.quantity, cart.price AS price, 
              cart.status, cart.id, orders.date, orders.paymentMethod  
              FROM orders 
              JOIN users ON orders.user_id = users.id
              JOIN cart ON orders.user_id = cart.user_id";

        $result = $conn->query($sql);
        $rownumber = 1;

        while ($row = $result->fetch_assoc()) {
        ?>
          <tr>
            <td data-label="#"> <?php echo $rownumber ?> </td>
            <td data-label="User ID"> <?php echo $row['user_id'] ?> </td>
            <td data-label="First Name"> <?php echo $row['firstName'] ?> </td>
            <td data-label="Last Name"> <?php echo $row['lastName'] ?> </td>
            <td data-label="Phone"> <?php echo $row['phone'] ?> </td>
            <td data-label="Country"> <?php echo $row['country'] ?> </td>
            <td data-label="Address"> <?php echo $row['address'] ?> </td>
            <td data-label="Address 2"> <?php echo $row['address2'] ?> </td>
            <td data-label="City"> <?php echo $row['city'] ?> </td>
            <td data-label="State"> <?php echo $row['state'] ?> </td>
            <td data-label="Product Name"> <?php echo $row['product_name'] ?> </td>
            <td data-label="Quantity"> <?php echo $row['quantity'] ?> </td>
            <td data-label="Price"> <?php echo $row['price'] ?> </td>
            <td data-label="Total Price"> <?php echo $row['total'] ?> </td>
            <td data-label="Status"> <?php echo $row['status'] ?> </td>
            <td data-label="Order Date"> <?php echo $row['date'] ?> </td>
            <td data-label="Order Status">
              <form action="#" method="POST">
                <select name="status" class="form-control" required>
                  <option value="Pending" <?php if ($row['status'] == 1) echo 'selected'; ?>>1-Pending</option>
                  <option value="Processing" <?php if ($row['status'] == 2) echo 'selected'; ?>>2-Processing</option>
                  <option value="Delivered" <?php if ($row['status'] == 3) echo 'selected'; ?>>3-Delivered</option>
                </select>
                <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                <button type="submit" name="update" class="btn btn-primary">Update</button>
              </form>
            </td>
            <td data-label="Payment Method"> <?php echo $row['paymentMethod'] ?> </td>
            <td data-label="Controls">
              <a href="deleteorder.php?id=<?php echo $row['user_id'] ?>">
                <button type="submit" name="delete" class="btn btn-danger">delete</button>
              </a>
            </td>
          </tr>


        <?php
          $rownumber++;
        }
        ?>
      </tbody>
    </table>

    <script src="js/jquery-3.6.0.min.js"></script>
    <br>

    <?php include 'inc/des/footer.php'; ?>
  </div>
  <!-- End of Content Wrapper -->
</div>
<!-- End of Page Wrapper -->

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