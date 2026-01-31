<?php
include "../config/db.php";
$res=mysqli_query($conn,"SELECT * FROM products");
echo json_encode(mysqli_fetch_all($res,MYSQLI_ASSOC));
?>
