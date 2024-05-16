<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
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
    <header>
        <nav>
            <div>
                <a href="lisatdo_empresas.php">
                    <img src="img/logo-el-campico.png" alt="Logo EFA El Campico">
                </a>
                <p>GESTION FCTs EFA EL CAMPICO</p>
            </div>
        </nav>
    </header>
    <section>
        <h2>Iniciar sesión</h2>
        <form action="login.php" method="POST">
            <input type="text" id="nia" name="nia" placeholder="Introduce tu NIA...">
            <input type="password" id="password" name="password" placeholder="Introduce tu contraseña...">
            <input type="submit" name="btnacceder" value="Entrar">
        </form> 
    </section>
    <footer>
        <p>FCTs EFA El Campico</p>
        <div>
            <a href="#">Contacto</a>
            <a href="https://www.elcampico.org/">Mi centro</a>
        </div>
        <img src="img/logo-el-campico.png" alt="Logo EFA El Campico">
    </footer>



</body>
</html>
