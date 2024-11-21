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



if (isset($_GET['logout'])) {
  unset($admin);
  session_destroy();
  header('location:index.php');
};
if (isset($_POST['update'])) {
  $permission_status = $_POST['permission_status'];
  $id = $_POST['id'];

  if ($_POST['permission_status'] == "Admin") {
    $sql = mysqli_query($conn, "UPDATE users SET permission=1 WHERE id='$id'") or die(mysqli_error($con));
  }

  if ($_POST['permission_status'] == "Manager") {
    $sql = mysqli_query($conn, "UPDATE users SET permission=2 WHERE id='$id'") or die(mysqli_error($con));
  }

  if ($_POST['permission_status'] == "User") {
    $sql = mysqli_query($conn, "UPDATE users SET permission=3 WHERE id='$id'") or die(mysqli_error($con));
  }
}

?>


<?php

include 'inc/des/menue.php'

?>

<?php

include 'inc/des/header.php';

?>

<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Users</h1>

  </div>





  <table id="customers">
    <thead>
      <th>id</th>
      <th>name</th>
      <th>email</th>
      <th>Permission</th>
      <th>controls</th>

    </thead>



    <tbody>
      <?php

      include('conn.php');

      $sql = "SELECT * FROM users";
      $result = $conn->query($sql);
      $rownumber = 1;

      while ($row = $result->fetch_assoc()) {
      ?>
        <tr>
          <td><?php echo $rownumber   ?></td>
          <td><?php echo $row['name']    ?></td>
          <td><?php echo $row['email']    ?></td>
          <td>
            <form action="#" method="POST">
              <select name="permission_status" class="form-control" required>
                <option value="Admin" <?php if ($row['permission'] == 1) echo 'selected'; ?>>1-Admin</option>
                <option value="Manager" <?php if ($row['permission'] == 2) echo 'selected'; ?>>2-Manager</option>
                <option value="User" <?php if ($row['permission'] == 3) echo 'selected'; ?>>3-User</option>
              </select>
              <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
              <button type="submit" name="update" class="btn btn-primary">Update</button>
            </form>
          </td>

          <td> <!-- Button trigger modal -->
            <a href="deleteuser.php?id=<?php echo $row['id'] ?>">
              <button type="submit" name="delete" class="btn btn-danger">
                delete
              </button>
            </a>

            <!-- Modal -->
          </td>


        </tr>







      <?php


        $rownumber++;
      }


      ?>




    </tbody>






  </table>

  <script src="js/jquery-3.6.0.min.js"></script>
  <!-- <script src="get_id_to_delete.js"></script> -->
  <br>

  <!-- Button trigger modal -->
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    Add User
  </button>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-detail" action="adduser.php" method="post">
            <h2>AddUser Form</h2>
            <div class="form-row-total">
              <div class="form-row">
                <input type="text" name="username" class="username" placeholder="Your Name" required>
                <br>
              </div>

              <div class="form-row">
                <input type="text" name="useremail" class="email" placeholder="Your Email" required>
              </div>

            </div>
            <div class="form-row-total">
              <div class="form-row">
                <input type="password" name="userpassword" class="password" placeholder="Your Password" required>
              </div>
            </div>


            <div class="form-row-total">
              <div class="form-row">
                <select name="permission" class="form-select" aria-label="Default select example">
                  <option selected>Permission</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                </select>
              </div>
            </div>



            <div class="form-row-total">
              <div class="form-row">
                <button name="add" type="submit" class="btn btn-primary add">Add</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

              </div>
            </div>






          </form>


        </div>
        <div class="modal-footer">


          <script src="js/jquery-3.6.0.min.js"></script>
          <!-- <script>
    
    $('.add').click(function(event){
       event.preventDefault();
       

       // get form values 
       var username = $('.username').val();
       var email = $('.email').val();
       var password = $('.password').val();

       $('tbody').append(`<tr><td></td> <td>${username}</td> <td>${email}</td>  </tr>`);

       //post req server.php insert
       $.post('server.php' , {
        
        'username' : username ,
        'email' : email ,
        'password' : password ,


        

       } , function(res){

            
            console.log(res);
       });

    
       

    })


    




</script> -->


        </div>
      </div>
    </div>
  </div>







  <!--  <script src="append.js"></script> -->

  <?php


  include 'inc/des/footer.php';

  ?>





</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->








<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
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
        <?php

        echo "<a class='btn btn-primary' href='all_pro.php?logout=" . $admin . "'>Logout</a>";

        ?>
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