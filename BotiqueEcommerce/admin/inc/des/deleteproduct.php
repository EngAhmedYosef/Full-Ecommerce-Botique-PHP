<?php

include('../../conn.php');
$ID = $_GET['id'];
mysqli_query($conn, "DELETE FROM salles WHERE id=$ID");
header('location: http://localhost/my_site3/admin/all_pro.php');
