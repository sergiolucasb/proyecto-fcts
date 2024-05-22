<?php
session_start();
if(!isset($_SESSION['nia'])) {
    header("location: http://localhost/proyecto-fcts/login.php");
    exit(); 
    session_destroy();
}


?>