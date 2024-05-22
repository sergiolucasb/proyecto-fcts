<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    if (!empty($anyadir)) {
        $consulta = $pdo->prepare($sql);
        $consulta->execute($datos);
    }
    

    ?>
</head>
<body>
    <form action="nueva_empresa.php" method="post">
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
        <input type="submit" value="Añadir" name="anyadir">








    </form>
</body>
</html>