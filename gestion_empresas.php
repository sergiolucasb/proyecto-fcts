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
    include 'auth.php';
    $host = 'localhost';
    $dbname = 'proyect-fcts';
    $user = 'root';
    $pass = '';

    //crear conexión
    try {
        //crear variable pdo para conexión
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        //función setattribute sobre variable pdo
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
    $pagina_deseada = $_POST['pagina_desada'] ?? $pagina_actual;
    $paginador_submit = $_POST['paginador_submit'] ?? null;

    //botón de salir
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


    if (isset($_POST['buscar_submit'])) {
        // El formulario de búsqueda se envió
        if (!empty($terminos_de_busqueda)) {
            // Si se proporciona un término de búsqueda, puedes utilizarlo para buscar tanto por nombre de empresa como por CIF.
            $sql .= " AND (nombre LIKE :nombre OR cif LIKE :cif)";
            $datos[':nombre'] = '%' . $terminos_de_busqueda . '%';
            $datos[':cif'] = '%' . $terminos_de_busqueda . '%';
        }
    }

    $id = $_POST['id'] ?? null;

    //ELIMINAR
    if (isset($_POST['eliminar'])) {
        //consulta delete
        $sql_delete = "DELETE FROM empresa WHERE nombre = :id"; 
       
        $consulta_delete = $pdo->prepare($sql_delete);
        //se utiliza paras vincular la variable $id con el :id de la consulta
        $datos[':id'] = $id;
        try{
            //validamos si la consulrta esta preparada para ejecutarla
            if ($consulta_delete->execute($datos)) {
                echo "Usuario eliminado con éxito";
                echo '<script>window.location.href = "gestion_empresas.php";</script>';
            }
            //capturamos la excepcion
        }catch(PDOException $e){
            //Utilizo el codigo del error 2300 ya que es esa la excepcion que manda
        if ($e->errorInfo[0] === '23000') {
            //aqui muestro el mensaje que yo quiero es decir si no se puede elimnar por tema de foreing key
            echo "No se puede eliminar";
        } else {
            //le muestro otro error alternativo en caso de que sea otra 
            echo "Error al eliminar la empresa";
        }
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
                <a href="gestion_empresas.php">
                    <img src="img/logo-el-campico.png" alt="Logo EFA El Campico">
                </a>
                <p>GESTION FCTs EFA EL CAMPICO</p>
            </div>
            <div>
                <p>GESTIÓN DE EMPRESAS</p>
                <form action="gestion_empresas.php" method="post">
                    <input type="submit" name="logout" id="logout" value="Salir">
                </form>
            </div>
        </nav>
    </header>
    <section>
        <h2>Gestión de empresas</h2>
        <form action="gestion_empresas.php" method="post">
            <article id="filtros_busqueda">
                <input type="text" placeholder="Buscar empresa" name="buscar">
                <input type="submit" value="buscar" name="buscar_submit">

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
                    echo "<a href='nueva_empresa.php?id=".$row['nombre']."'>Modificar</a>";
                    // Agregar un formulario para cada botón de eliminar
                    echo "<form action='gestion_empresas.php' method='POST'>";
                    echo "<input type='hidden' name='id' value='" . $row['nombre'] . "'>";
                    echo "<input type='submit' name='eliminar' value='Eliminar'>";
                    echo "</form>";
                    echo "</div>";
                }

                ?>

            </article>
        </form>
        <form action="gestion_empresas.php" method="POST">


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