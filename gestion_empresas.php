<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="gestion_empresas.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <title>Gestión de empresas</title>
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
    /*
    if (!isset($_SESSION['nia'])) {
        header("Location: login.php");
        exit(); 
    }
    */
    $nombre_empresa = $_POST['nombre_empresa'] ?? null;

    $sql = "SELECT nombre, telefono FROM empresa where true";
    $datos = [];

    if (!empty($nombre_empresa)) {
        $sql .= " and nombre like :nombre";
        $datos[':nombre'] = '%' . $nombre_empresa . '%';
    }

    $pagina_actual = $_POST['pagina_deseada'] ?? 1;
    //para calcular el total de paginas
    $total_resultados = $pdo->prepare("SELECT COUNT(*) FROM empresa");
    $total_resultados->execute();
    $total_empresas = $total_resultados->fetchColumn();
    $resultados_por_pagina = 10;

    $total_paginas = ceil($total_empresas / $resultados_por_pagina);

    $pagina_primera = $_POST['pagina_primera'] ?? null;
    $pagina_anterior = $_POST['pagina_anterior'] ?? null;
    $pagina_siguiente = $_POST['pagina_siguiente'] ?? null;
    $pagina_ultima = $_POST['pagina_ultima'] ?? null;
    $pagina_deseada = $_POST['pagina_desada'] ?? $pagina_actual;
    $paginador_submit = $_POST['paginador_submit'] ?? null;

    $logout = $_POST['logout'] ?? null;

    if (!empty($logout)) {
        header('location: login.php');
        session_destroy();
    }

    if (!empty($pagina_primera)) {
        $pagina_actual = 1;
    }

    if (!empty($pagina_anterior)) {
        if ($pagina_actual != 1) {
            $pagina_actual = $pagina_actual - 1;
        }
    }

    if (!empty($pagina_siguiente)) {
        if ($pagina_actual != $total_paginas) {
            $pagina_actual = $pagina_actual + 1;
        } else {
            $pagina_actual = 1;
        }
    }

    if (!empty($pagina_ultima)) {
        $pagina_actual = $total_paginas;
    }

    if (!empty($paginador_submit)) {
        $pagina_actual = $pagina_deseada;
    }

    $variable_paginas = ($pagina_actual - 1) * $resultados_por_pagina;

    $sql .= " LIMIT $variable_paginas, $resultados_por_pagina";

    $consulta = $pdo->prepare($sql);
    $consulta->execute($datos);
    ?>
</head>
<body>
    <header>
        <nav>
            <div>
                <a href="lisatdo_empresas.php">
                    <img src="img/logo-el-campico.png" alt="Logo EFA El Campico">
                </a>
                <p>GESTION FCTs EFA EL CAMPICO</p>
            </div>
            <div>
                <p>GESTIÓN DE EMPRESAS</p>
                <form action="listado_empresas.php" method="post">
                    <input type="submit" name="logout" id="logout" value="Salir">
                </form>
            </div>
        </nav>
    </header>
    <section>
        <h2>Gestión de empresas</h2>
        <form action="gestion_empresas.php" method="post">
            <article id="filtros_busqueda">
                <input type="text" placeholder="Buscar por nombre" name="nombre_empresa">
                <input type="submit" value="Buscar" name="filtros_submit">

            </article>
            <a href="nueva_empresa.php">Añadir empresa</a>
            <article id="listado">
                <div>
                    <p>Nombre de la empresa</p>
                    <p>Contacto</p>
                    <p>Modificar</p>
                    <p>Eliminar</p>
                </div>
                <?php
                while ($row = $consulta->fetch()) {
                    echo "<div>";
                    echo "<p>" . $row['nombre'] . "</p>";
                    echo "<p>" . $row['telefono'] . "</p>";
                    echo "<a href='nueva_empresa.php'>Modificar</a>";
                    echo "<select name='preferencia'>";
                    echo "<option value='1'>1</option>";
                    echo "<option value='2'>2</option>";
                    echo "<option value='3'>3</option>";
                    echo "<option value='4'>4</option>";
                    echo "<option value='5'>5</option>";
                    echo "<option value='6'>6</option>";
                    echo "<option value='7'>7</option>";
                    echo "<option value='8'>8</option>";
                    echo "<option value='8'>8</option>";
                    echo "<option value='9'>9</option>";
                    echo "<option value='10'>10</option>";
                    echo "</select>";
                    echo "</div>";
                }
                ?>

            </article>

            <article id="paginador">
                <div>
                    <input type="submit" name="pagina_primera" value="<<">
                    <input type="submit" name="pagina_anterior" value="<">
                    <input type="text" name="pagina_deseada" value="<?php echo $pagina_actual ?>" pattern="[1-9]|<?php echo $total_paginas ?>">
                    <input type="submit" name="pagina_siguiente" value=">">
                    <input type="submit" name="pagina_ultima" value=">>">
                </div>
                <div>
                    <input type="submit" name="paginador_submit" id="paginador_submit" value="Ir">
                </div>
            </article>
            <input type="submit" name="preferencia_submit" id="preferencia_submit" value="Confirmar selección">
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