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
</style>

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

$user_id = $_SESSION['user_id'] ?? null;


if (isset($_GET['logout'])) {
  unset($admin);
  session_destroy();
  header('location=index.php');
  exit();
}

if (isset($_POST['deletemsg'])) {
  $id = $_POST['deletemsg'];
  mysqli_query($conn, "DELETE FROM messages WHERE id='$id'");
}

// معالجة تحديث الـ view
if (isset($_POST['view_id'])) {
  $view_id = $_POST['view_id'];
  mysqli_query($conn, "UPDATE messages SET view=1 WHERE id='$view_id'");
  echo "Updated"; // هذا السطر يضيف رسالة للتأكد من تحديث القيمة
  exit();
}

include 'inc/des/menue.php';
include 'inc/des/header.php';
?>

<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Messages</h1>
  </div>

  <table id="customers">
    <tr>
      <th>id</th>
      <th>name</th>
      <th>email</th>
      <th>Subject</th>
      <th>view</th>
      <th>controls</th>
    </tr>

    <?php
    $sql = "SELECT * FROM messages";
    $result = $conn->query($sql);
    $rownumber = 1;

    while ($row = $result->fetch_assoc()) {
    ?>
      <form action="#" method="post">
        <tr>
          <td><?php echo $rownumber; ?></td>
          <td><?php echo $row['name']; ?>
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
          </td>
          <td><?php echo $row['email']; ?></td>
          <td><?php echo $row['subject']; ?></td>
          <td id="status_<?= $row['id']; ?>"><?php echo $row['view'] == 0 ? 'unread' : 'read'; ?></td>

          <td>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?= $row['id']; ?>" data-id="<?= $row['id']; ?>" onclick="markAsRead(<?= $row['id']; ?>)">
              View Message
            </button>

            <button type="submit" class="btn btn-danger" name="deletemsg" value="<?= $row['id']; ?>">
              Delete Message
            </button>
          </td>
        </tr>
      </form>

      <!-- Modal -->
      <div class="modal fade" id="modal<?= $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Message Details</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <?php echo $row['message']; ?>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

    <?php
      $rownumber++;
    }
    ?>
  </table>

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

<script>
  function markAsRead(id) {
    $.ajax({
      type: "POST",
      url: "", // إرسال الطلب لنفس الصفحة
      data: {
        view_id: id
      },
      success: function(response) {
        console.log("Message marked as read: " + response);
        // تحديث الحالة إلى "read" مباشرةً في الواجهة
        document.getElementById("status_" + id).innerText = 'read';
      },
      error: function() {
        console.error("Failed to mark message as read.");
      }
    });
  }

  // ربط حدث إغلاق النافذة المنبثقة بكل نافذة بناءً على ID الرسالة
  $(document).ready(function() {
    <?php
    $result->data_seek(0); // إعادة المؤشر إلى بداية النتائج
    while ($row = $result->fetch_assoc()) {
      echo "$('#modal{$row['id']}').on('hidden.bs.modal', function () {
              markAsRead({$row['id']});
            });";
    }
    ?>
  });
</script>


</body>

</html>