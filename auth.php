<?php
session_start();
if(!isset($_SESSION['nia'])) {
    header("location:login.php");
    exit();
}

if(isset($_GET['logout'])) {
    session_destroy();
    header("location:login.php");
    exit();
}
?>