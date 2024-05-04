<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    // Si el usuario no está autenticado, redirigirlo al inicio de sesión
    header('Location: login.php');
    exit();
}

// Mostrar errores de PHP en la página web
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir el archivo de utilidades para la conexión y otras funciones
require_once __DIR__.'/config.php';

// Función para obtener y mostrar las clases disponibles
function mostrarClasesDisponibles() {
    $conn = conexionBD();

    // Consulta para obtener las clases disponibles
    $query = "SELECT * FROM Clases";

    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        echo '<h1>Clases Disponibles</h1>';
        echo '<ul>';

        while ($row = $result->fetch_assoc()) {
            echo '<li>';
            echo '<strong>Nombre: </strong>' . htmlspecialchars($row['Nombre']) . '<br>';
            echo '<strong>Descripción: </strong>' . htmlspecialchars($row['Descripcion']) . '<br>';
            echo '<a href="inscribirClase.php?id=' . $row['ClaseID'] . '">Inscribirse</a>';
            echo '</li>';
        }

        echo '</ul>';
    } else {
        echo '<p>No hay clases disponibles en este momento.</p>';
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Clases Disponibles</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.jpg" type="image/png">
    <link rel="stylesheet" href="CSS/login_registro.css">
</head>
<body>
    <!-- topbar -->
    <div class="topbar">
        <div class="topbar-name">
            <a class="topbar-name">gymOrg</a>
        </div>
        
        <!-- topbar items -->
        <div>
            <a href="index.php" class="topbar-item">Inicio</a>
            <a href="login.php" class="topbar-item">Log In</a>
            <a href="registro.php" class="topbar-item">Registro</a>
            <?php
            // Verificar si el usuario no está autenticado
            if (isset($_SESSION["login"]) || $_SESSION["login"] == true) {
                echo "<a href='clases.php' class='topbar-item'>Clases</a>";
            }
            ?>
        </div>

        <?php
        // Función para mostrar el saludo dependiendo del estado de sesión del usuario
        function mostrarSaludo() {
            if (isset($_SESSION['login']) && ($_SESSION['login'] === true)) {
                return "Bienvenido, {$_SESSION['nombre']} <a href='logout.php' class='salir'>(salir)</a>";   
            } else {
                return "Usuario desconocido.";
            }
        }
        ?>
        <!-- Mostrar el saludo -->
        <div class="saludo"><?= mostrarSaludo(); ?></div>
    </div>

    <main>
        <article>
            <?php
            // Mostrar las clases disponibles solo si el usuario está autenticado
            mostrarClasesDisponibles();
            ?>
        </article>
    </main>
</body>
</html>
