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

    $sql = "insert into empresa values (:nombre_empresa, :cif, :nombre_fiscal, :email, :direccion, :localidad, :provincia, :num_plazas, :telefono, :persona_contacto)";

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

    $error = false;
    if (!empty($anyadir)) {
        if (!empty($nombre_empresa)) {
            $consulta = $pdo->prepare($sql);
            $consulta->execute($datos);
        } else {
            $error = true;
        }

    }
    

    ?>
</head>
<body>
    <form action="nueva_empresa.php" method="post">
        <h2>Nueva empresa</h2>
        <?php
        if ($error == true) {
            echo "<p class='error'>Error: El nombre de la empresa no puede estar vacío</p>";
        }
        ?>
        <input type="text" placeholder="Nombre de la empresa..." name="nombre_empresa">
        <input type="text" placeholder="CIF..." name="cif">
        <input type="text" name="nombre_fiscal" placeholder="Nombre fiscal...">
        <input type="text" name="email" placeholder="Correo electrónico...">
        <input type="text" name="direccion" placeholder="Dirección...">
        <input type="text" name="localidad" placeholder="Localidad...">
        <input type="text" name="provincia" placeholder="Provincia...">
        <input type="number" name="num_plazas" placeholder="Número de plazas...">
        <input type="number" name="telefono" placeholder="Teléfono...">
        <input type="text" name="persona_contacto" placeholder="Persona de contacto...">
        <div>
            <input type="submit" value="Añadir" name="anyadir">
            <a href="gestion_empresas.php">Volver</a>
        </div>

    </form>
</body>
</html>