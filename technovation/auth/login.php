<?php
session_start();
include "../config/db.php";

if(isset($_POST['login'])){
    $u=$_POST['username'];
    $p=$_POST['password'];

    $q=mysqli_query($conn,"SELECT * FROM users WHERE username='$u' AND password='$p'");
    if(mysqli_num_rows($q)>0){
        $_SESSION['user']=$u;
        header("Location: ../index.php");
    } else {
        echo "Login Failed";
    }
}
?>

<form method="POST">
<input name="username">
<input name="password">
<input type="submit" name="login">
</form>
