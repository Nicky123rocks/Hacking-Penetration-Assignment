<?php
include "config/db.php";
$result = mysqli_query($conn,"SELECT * FROM products");
?>

<h2>TechNovation Solutions Store</h2>

<form method="GET">
    <input type="text" name="search">
    <input type="submit" value="Search">
</form>

<?php
if(isset($_GET['search'])){
    $q = $_GET['search']; // SQLi vulnerable
    $result = mysqli_query($conn,"SELECT * FROM products WHERE name LIKE '%$q%'");
}
?>

<?php while($row=mysqli_fetch_assoc($result)) { ?>
    <p>
        <a href="product.php?id=<?=$row['id']?>"><?=$row['name']?></a>
        - RM <?=$row['price']?>
    </p>
<?php } ?>

<a href="auth/login.php">Login</a>
