

<?php 

$id = $_POST['id'];
require '../../conn.php';

$delete ="DELETE FROM users WHERE id = $id";
$query = $conn -> query($delete);

if ($query) {
	echo "user deleted";
} else {
	echo $conn -> error ;
}