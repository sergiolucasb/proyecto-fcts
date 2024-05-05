<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <title>Listado empresas</title>

    <?php
    $host='localhost';
    $dbname='gestion_fct';
    $user='root';
    $pass='';
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }
        catch(PDOException $e) {
        echo "Se ha producido un error al intentar conectar al servidor MySQL: ".$e->getMessage();
    }     
    
    $nombre_empresa = $_POST['nombre_empresa'] ?? null;

    $sql = "SELECT nombre, telefono FROM empresa where true";
    $datos=[];

    if(!empty($nombre_empresa)) {
        $sql.= " and nombre like :nombre";
        $datos[':nombre'] = '%' . $nombre_empresa . '%';
    }

    $sql.=" limit 0, 9";
    $consulta = $pdo->prepare($sql);
    $consulta->execute($datos);

    
    
    ?>
</head>
<body>
    <?php
    ?>
    <header>
        <nav>
            <div>
                <a href="index.php">
                    <img src="img/logo-el-campico.png" alt="Logo EFA El Campico">
                </a>
                <p>GESTION FCTs EFA EL CAMPICO</p>
            </div>
            <div>
                <a href="mis_empresas.php">Mis empresas</a>
                <a href="#">Editar perfil</a>
                <a href="#">Salir</a>
            </div>
        </nav>
    </header>
    <section>
        <h2>Listado de empresas</h2>
        <form action="index.php" method="post">
            <input type="text" placeholder="Buscar por nombre" name="nombre_empresa">
            <input type="submit" value="Buscar">
        </form>
        <article>
            <div>
                <p>Nombre de la empresa</p>
                <p>Contacto</p>
                <p>Comentarios</p>
                <p>Preferencia</p>
            </div>
            <?php
                while ($row = $consulta->fetch()) {
                    echo "<div>";
                    echo "<p>".$row['nombre']."</p>";
                    echo "<p>".$row['telefono']."</p>";
                    echo "<a href='comterios.php'><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill='#000000' d='M64 0C28.7 0 0 28.7 0 64V352c0 35.3 28.7 64 64 64h96v80c0 6.1 3.4 11.6 8.8 14.3s11.9 2.1 16.8-1.5L309.3 416H448c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64H64z'/></svg></a>";
                    echo "<p>Preferencia</p>";
                    echo "</div>";
                }
            ?>
        </article>
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