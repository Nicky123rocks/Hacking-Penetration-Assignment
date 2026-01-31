<?php
session_start();
$_SESSION['product']=$_POST['product'];
$_SESSION['price']=$_POST['price'];
header("Location: ../checkout.php");
?>
