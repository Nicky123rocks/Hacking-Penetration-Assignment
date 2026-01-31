<?php
session_start();
include "config/db.php";

if(isset($_POST['checkout'])){
    $user = $_SESSION['user'];
    mysqli_query($conn,"INSERT INTO orders VALUES(NULL,'$user','{$_SESSION['product']}','{$_SESSION['price']}')");
    echo "Order Successful";
}
?>

<form method="POST">
    <input type="submit" name="checkout" value="Checkout">
</form>
