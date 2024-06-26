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

   $error = "";

    try {
        $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //si pulsa
        if (!empty($_POST["btnacceder"])) {
            //si los campos están vacíos
            if (empty($_POST["email"]) || empty($_POST["pasword"])) {
                $error = 'Los campos están vacíos';

            //si los campos están llenos    
            } else {
                //recoger datos del formulario
                $email = $_POST["email"];
                $password = $_POST["pasword"];

                //comprobar que es alumno
                $sql = "SELECT * FROM alumno WHERE email='$email' AND pasword='$password'";
                $consulta = $conexion->prepare($sql);
                $consulta->execute();
                if ($datos = $consulta->fetch(PDO::FETCH_ASSOC)) {
                    session_start();
                    $_SESSION['email'] = $email;
                    header("Location: listado_empresas.php");
                    exit();
                } else {
                    //comprobar que es tutor
                    $sql = "SELECT * FROM tutor WHERE email='$email' AND pasword='$password'";
                    $consulta = $conexion->prepare($sql);
                    $consulta->execute();
                    if ($datos = $consulta->fetch(PDO::FETCH_ASSOC)) {
                        session_start();
                        $_SESSION['email'] = $email;
                        header("Location: gestion_empresas.php");
                        exit();
                    } else {
                        $error = 'Usuario o contraseña incorrectos';
                    }
                }
            }
        }

    } catch (PDOException $e) {
        echo $e->getMessage();
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
            <input type="text" id="email" name="email" placeholder="Introduce tu correo...">
            <input type="password" id="pasword" name="pasword" placeholder="Introduce tu contraseña...">
            <input type="submit" name="btnacceder" value="Entrar">
            <?php
            echo $error;
            ?>
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