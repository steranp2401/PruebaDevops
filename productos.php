<?php
session_start(); // Inicio la sesión para poder usar variables de sesión

if (!isset($_SESSION["usuario"])) { // Compruebo si el usuario no ha iniciado sesión
    header("Location: login.php"); // Si no hay sesión, redirijo a la página de login
    exit; // Salgo del script para que no siga ejecutándose
}

require_once "conexion.php"; // Incluyo el archivo de conexión a la base de datos
$pdo = conectar(); // Creo la conexión a la base de datos usando la función conectar

// Añadir producto a la cesta
if (isset($_POST["id_producto"])) { // Compruebo si se ha enviado un producto desde el formulario
    $id = $_POST["id_producto"]; // Guardo el código del producto (VARCHAR)
    $_SESSION["cesta"][] = $id; // Añado el producto a la cesta en la sesión
}

// Obtener todos los productos de la BD
$stmt = $pdo->query("SELECT cod, nombre_corto, PVP FROM producto"); // Hago la consulta para obtener todos los productos
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC); // Guardo los resultados en un array asociativo
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8"> <!-- Defino la codificación de caracteres -->
    <title>Productos</title> <!-- Título de la página -->
    <link rel="stylesheet" href="css/estilos.css"> <!-- Enlazo el archivo de estilos CSS -->

</head>

<body>

    <div class="contenedor"> <!-- Div principal con clase contenedor -->
        <h2>Bienvenido, <?= $_SESSION["usuario"] ?></h2> <!-- Saludo al usuario con su nombre -->
        <h3>Listado de productos</h3> <!-- Subtítulo para la lista de productos -->

        <table> <!-- Inicio de la tabla de productos -->
            <tr>
                <th>Nombre</th>
                <th>Precio</th>
                <th></th>
            </tr> <!-- Cabecera de la tabla -->
            <?php foreach ($productos as $p): ?> <!-- Recorro cada producto -->
                <tr> <!-- Fila del producto -->
                    <td><?= htmlspecialchars($p["nombre_corto"]) ?></td>
                    <!-- Nombre del producto con protección contra HTML -->
                    <td><?= number_format($p["PVP"], 2) ?> €</td> <!-- Precio del producto con 2 decimales -->
                    <td>
                        <form method="POST"> <!-- Formulario para añadir el producto a la cesta -->
                            <input type="hidden" name="id_producto" value="<?= htmlspecialchars($p["cod"]) ?>">
                            <!-- Campo oculto con el código del producto -->
                            <button type="submit">Añadir a la cesta</button> <!-- Botón para enviar el formulario -->
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?> <!-- Fin del bucle foreach -->
        </table> <!-- Fin de la tabla -->

        <br>
        <a href="cesta.php" class="boton">Ir a la cesta</a><br> <!-- Enlace para ir a la cesta -->
        <a href="logoff.php" class="boton">Cerrar sesión</a> <!-- Enlace para cerrar sesión -->
    </div> <!-- Fin del contenedor -->


</body>

</html>