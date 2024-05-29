<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="nueva_empresa.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <title>Añadir empresa</title>
    <?php
    $host = 'localhost';
    $dbname = 'proyect-fcts';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Se ha producido un error al intentar conectar al servidor MySQL: " . $e->getMessage();
    }
    
    $error = false;

    //RECOGER VARIABLES
    $nombre_empresa = $_POST['nombre_empresa'] ?? null;
    $cif = $_POST['cif'] ?? null;
    $nombre_fiscal = $_POST['nombre_fiscal'] ?? null;
    $email = $_POST['email'] ?? null;
    $direccion = $_POST['direccion'] ?? null;
    $localidad = $_POST['localidad'] ?? null;
    $provincia = $_POST['provincia'] ?? null;
    $num_plazas = $_POST['num_plazas'] ?? null;
    $telefono = $_POST['telefono'] ?? null;
    $persona_contacto = $_POST['persona_contacto'] ?? null;
    $anyadir = $_POST['anyadir'] ?? null;

    $nombre_empresa_modificar = $_GET['id'] ?? null;
    $nombre_empresa_old = $_POST['nombre_empresa_old'] ?? null;

    //print_r($_POST);
    //echo $nombre_empresa_modificar;
    //exit;

    $sql = "insert into empresa values (:nombre_empresa, :cif, :nombre_fiscal, :email, :direccion, :localidad, :provincia, :num_plazas, :telefono, :persona_contacto);";

    $sql_update = "update empresa set nombre = :nombre_empresa, cif = :cif, nombre_fiscal = :nombre_fiscal, email = :email, direccion = :direccion, localidad = :localidad, provincia = :provincia, numero_plazas = :num_plazas, telefono = :telefono, persona_contacto = :persona_contacto where nombre= '".$nombre_empresa_old . "'";

    $sql_select;
    //si ha pulsado modificar, consulta select sí se hace pero no se asigna la variable?¿?? 
    if (!empty($nombre_empresa_modificar)) {
        $sql_select = "select nombre, cif, nombre_fiscal, email, direccion, localidad, provincia, numero_plazas, telefono, persona_contacto from empresa where nombre='". $nombre_empresa_modificar . "';";
        $consulta_select = $pdo->prepare($sql_select);
        $consulta_select->execute();
        if ($row = $consulta_select->fetch()) {
            $nombre_empresa = $row['nombre'];
            $cif = $row['cif'];
            $nombre_fiscal = $row['nombre_fiscal'];
            $email = $row['email'];
            $direccion = $row['direccion'];
            $localidad = $row['localidad'];
            $provincia = $row['provincia'];
            $num_plazas = $row['numero_plazas'];
            $telefono = $row['telefono'];
            $persona_contacto = $row['persona_contacto'];
        }
    }    

    $datos = [];
    $datos[':nombre_empresa'] = $nombre_empresa;
    $datos[':cif'] = $cif;
    $datos[':nombre_fiscal'] = $nombre_fiscal;
    $datos[':email'] = $email;
    $datos[':direccion'] = $direccion;
    $datos[':localidad'] = $localidad;
    $datos[':provincia'] = $provincia;
    $datos[':num_plazas'] = $num_plazas;
    $datos[':telefono'] = $telefono;
    $datos[':persona_contacto'] = $persona_contacto;


    //si pulsa en el botón
    if (!empty($anyadir)) {

        //AÑADIR
        if (empty($nombre_empresa_old)) {
            if (!empty($nombre_empresa)) {
                echo $sql;
                echo "<br>";
                echo $nombre_empresa;
                $consulta = $pdo->prepare($sql);
                $consulta->execute($datos);
                header("Location: gestion_empresas.php");
                exit; 
            } else {
                $error = true;
            }
        }

        //MODIFICAR
        if (!empty($nombre_empresa_old)) {
            if (!empty($nombre_empresa)) {
                echo $sql_update;
                echo "<br>";
                echo $nombre_empresa;
                $consulta_update = $pdo->prepare($sql_update);
                $consulta_update->execute($datos);
                header("Location: gestion_empresas.php");
                exit;
            } else {
                $error = true;
            }
        }
    }

    



    ?>
</head>
<body>
    <form action="nueva_empresa.php" method="post">
        <h2>Empresa</h2>
        <?php
        if ($error == true) {
            echo "<p class='error'>Error: El nombre de la empresa no puede estar vacío</p>";
        }
        echo $nombre_empresa_modificar;
        echo "<br>";

        
        ?>
        <input type="text" placeholder="Nombre de la empresa..." name="nombre_empresa" value="<?php echo $nombre_empresa?>" required>
        <input type="text" placeholder="CIF..." name="cif" value="<?php echo $cif?>">
        <input type="text" name="nombre_fiscal" placeholder="Nombre fiscal..." value="<?php echo $nombre_fiscal ?>">
        <input type="text" name="email" placeholder="Correo electrónico..." value="<?php echo $email ?>">
        <input type="text" name="direccion" placeholder="Dirección..." value="<?php echo $direccion ?>">
        <input type="text" name="localidad" placeholder="Localidad..." value="<?php echo $localidad ?>">
        <input type="text" name="provincia" placeholder="Provincia..." value="<?php echo $provincia ?>">
        <input type="number" name="num_plazas" placeholder="Número de plazas..." value="<?php echo $num_plazas ?>">
        <input type="text" name="telefono" placeholder="Teléfono..." value="<?php echo $telefono ?>">
        <input type="text" name="persona_contacto" placeholder="Persona de contacto..." value="<?php echo $persona_contacto ?>">
        <div>
            <input type="submit" value="Enviar" name="anyadir">
            <input type="hidden" value = "<?php echo $nombre_empresa_modificar ?>" name='nombre_empresa_old'>
            <a href="gestion_empresas.php">Volver</a>
        </div>

    </form>
</body>
</html>