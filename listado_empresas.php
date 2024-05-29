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
    include 'auth.php';
    $host = 'localhost';
    $dbname = 'proyect-fcts';
    $user = 'root';
    $pass = '';

    //conexión a base de datos
    try {
        //nuevo objeto pdo
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        //función setattribute sobre pdo
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Se ha producido un error al intentar conectar al servidor MySQL: " . $e->getMessage();
    }

    //obtener filtros de búsqueda
    $terminos_de_busqueda = $_POST['buscar'] ?? null;

    //mostrar empresas
    $sql = "SELECT nombre, telefono, cif FROM empresa where true";
    $datos = []; //iniciar array de datos

    
    //total empresas / resultados por página

    $pagina_actual = $_POST['pagina_deseada'] ?? 1;
    //sql para calcular el total de empresas
    $total_resultados = $pdo->prepare("SELECT COUNT(*) FROM empresa");
    $total_resultados->execute();
    //total de empresas en variable
    $total_empresas = $total_resultados->fetchColumn();
    $resultados_por_pagina = 10;

    $total_paginas = ceil($total_empresas / $resultados_por_pagina);

    //recoger botones previo / siguiente
    $pagina_primera = $_POST['pagina_primera'] ?? null;
    $pagina_anterior = $_POST['pagina_anterior'] ?? null;
    $pagina_siguiente = $_POST['pagina_siguiente'] ?? null;
    $pagina_ultima = $_POST['pagina_ultima'] ?? null;
    $pagina_deseada = $_POST['pagina_deseada'] ?? $pagina_actual;
    $paginador_submit = $_POST['paginador_submit'] ?? null;

    $logout = $_POST['logout'] ?? null;
    //cerrar sesión
    if (!empty($logout)) {
        header('Location: login.php');
        session_destroy();
    }

    if (!empty($pagina_primera)) {
        $pagina_actual = 1;
    }

    if (!empty($pagina_anterior)) {
        //comprobar que no sea la primera página
        if ($pagina_actual != 1) {
            $pagina_actual = $pagina_actual - 1;
        }
    }

    if (!empty($pagina_siguiente)) {
        //comprobar que no sea la última página
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

    //se ha pulsado en buscar
    if (isset($_POST['buscar_submit'])) {
        //si tiene algo escrito
        if (!empty($terminos_de_busqueda)) {
            //compara nombre o cif
            $sql .= " AND (nombre LIKE :nombre OR cif LIKE :cif)";
            $datos[':nombre'] = '%' . $terminos_de_busqueda . '%';
            $datos[':cif'] = '%' . $terminos_de_busqueda . '%';
        }
    }

    //desde variable páginas, resultados por página
    $variable_paginas = ($pagina_actual - 1) * $resultados_por_pagina;
    $sql .= " LIMIT $variable_paginas, $resultados_por_pagina";

    //ejecutar consulta select
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
                <a href="mis_empresas.php">Mis empresas</a>
                <a href="#">Editar perfil</a>
                <form action="listado_empresas.php" method="post">
                    <input type="submit" name="logout" id="logout" value="Salir">
                </form>
            </div>
        </nav>
    </header>
    <section>
        <h2>Listado de empresas</h2>
        <form action="listado_empresas.php" method="post">
            <article id="filtros_busqueda">
                <input type="text" placeholder="Buscar empresa" name="buscar">
                <input type="submit" value="bucar" name="buscar_submit">

            </article>

            <article id="listado">
                <div>
                    <p>Nombre de la empresa</p>
                    <p>Contacto</p>
                    <p>Comentarios</p>
                    <p>Preferencia</p>
                </div>
                <?php
                //hacemos el bucle while ejecuta hasta que traiga consultas
                while ($row = $consulta->fetch()) {
                    echo "<div>";
                    //listamos nombre y telefono recogido por la consulta del select
                    echo "<p>" . $row['nombre'] . "</p>";
                    echo "<p>" . $row['telefono'] . "</p>";
                    echo "<a href='#'><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill='#000000' d='M64 0C28.7 0 0 28.7 0 64V352c0 35.3 28.7 64 64 64h96v80c0 6.1 3.4 11.6 8.8 14.3s11.9 2.1 16.8-1.5L309.3 416H448c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64H64z'/></svg></a>";
                    //TODO funcionalidad preferencia
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