<?php
    session_start();
    if(!isset($_SESSION['nia'])){
        header("location:login.php");
        session_destroy();
    }

?>