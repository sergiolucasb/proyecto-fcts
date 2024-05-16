<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<?php
   $host = "localhost";
   $dbname = "proyect-fcts";
   $user = 'root';
   $pass='';


   try {
       $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
       $conexion->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

       }

       catch(PDOException $e) {
            echo $e->getMessage();
       }
       if (!empty($_POST["btnacceder"])){

        if (empty($_POST["nia"]) and empty($_POST["password"])){
                echo 'Los campos estan vacios';

        } else {
    
            $nia=$_POST["nia"];
            $password=$_POST["password"];

            $sql=$conexion->query(" select * from alumno where nia='$nia' and password='$password' ");

            if ($datos=$sql->FETCH()){
                session_start();

                $_SESSION['nia']=$nia;
                $_SESSION['nombre']=$query['nombre'];

                header("location:listado_empresas.php");
                exit();
                }else{
                    echo 'Usuario o contraseña incorrectos';
                }
            }
        }
?>

    <h2>Iniciar sesión</h2>
    <form action="login.php" method="POST">
        <label for="username">Numero NIA:</label><br>
        <input type="text" id="nia" name="nia"><br>
        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password"><br><br>
        <input type="submit" name="btnacceder"   value="Iniciar sesión">
    </form> 


</body>
</html>
