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

?>

<?php

include 'inc/des/menue.php'

?>

<?php

include 'inc/des/header.php';

?>


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
<table class="table">
    <thead>
        <tr>
            <th scope="col">Brand_Id</th>
            <th scope="col">Brand_name</th>
            <th scope="col">Operations</th>

        </tr>
    </thead>
    <tbody>
        <?php
        include("conn.php");
        $sql = "SELECT * FROM brand";
        $result = mysqli_query($conn, $sql);
        $rownumber = 1;
        while ($row = mysqli_fetch_assoc($result)) {

        ?>
            <tr>
                <th scope="row"><?php echo $rownumber ?></th>
                <th scope="row"><?php echo $row['name'] ?></th>
                <th scope="row">
                    <a href="inc/des/deletebrand.php?id=<?php echo $row['id'] ?>">
                        <button type="submit" name="delete" class="btn btn-danger">
                            delete
                        </button>
                    </a>

                    <a href="inc/des/editbrand.php?id=<?php echo $row['id'] ?>">
                        <button type="submit" name="edit" class="btn btn-info">
                            Edit
                        </button>
                    </a>
                </th>


            </tr>
        <?php
            $rownumber++;
        }

        ?>

    </tbody>
</table>

<a href="inc/des/addbrand.php">
    <button name="addbrand" type="submit" class="btn btn-primary">Add Brand</button>

</a>
<br>
<br>



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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>