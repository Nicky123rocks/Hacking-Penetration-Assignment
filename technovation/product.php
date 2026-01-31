<?php
include "config/db.php";
$id = $_GET['id'];
$q = mysqli_query($conn,"SELECT * FROM products WHERE id='$id'");
$p = mysqli_fetch_assoc($q);
?>

<h3><?=$p['name']?></h3>
<p><?=$p['description']?></p>

<form action="cart/add_to_cart.php" method="POST">
    <input type="hidden" name="product" value="<?=$p['name']?>">
    <input type="hidden" name="price" value="<?=$p['price']?>">
    <input type="submit" value="Add to Cart">
</form>
