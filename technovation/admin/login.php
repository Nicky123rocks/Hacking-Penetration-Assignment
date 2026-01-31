<?php
session_start();
include "../config/db.php";

if(isset($_POST['login'])){
    $u=$_POST['username'];
    $p=$_POST['password'];

    $q=mysqli_query($conn,"SELECT * FROM users WHERE username='$u' AND password='$p' AND role='admin'");
    if(mysqli_num_rows($q)>0){
        $_SESSION['admin']=$u;
        header("Location: dashboard.php");
    }
}
?>

<form method="POST">
<input name="username">
<input name="password">
<input type="submit" name="login">
</form>
