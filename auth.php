<?php
    //inicio sesion
    session_start();
    //verifico que el usuario este logueado  y con isset verifico si ese campo esta vacio que es el campo porque el registro campo unico 
    if(!isset($_SESSION['email'])){
        //si no esta logueado lo redirijo al login
        header("Location:login.php");
        //destruye la sesion cuando acabe
        session_destroy();
    }
?>