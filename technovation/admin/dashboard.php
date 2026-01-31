<?php
include "../config/db.php";
$res=mysqli_query($conn,"SELECT * FROM products");
?>

<h2>Admin Dashboard</h2>

<?php while($r=mysqli_fetch_assoc($res)){ ?>
<p><?=$r['name']?> 
<a href="delete_product.php?id=<?=$r['id']?>">Delete</a></p>
<?php } ?>
