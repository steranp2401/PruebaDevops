<?php
session_start(); // Inicio la sesión para poder usar variables de sesión

if (!isset($_SESSION["usuario"])) { // Compruebo si el usuario no ha iniciado sesión
    header("Location: login.php"); // Si no hay sesión, redirijo a la página de login
    exit; // Salgo del script para que no siga ejecutándose
}

require_once "conexion.php"; // Incluyo el archivo de conexión a la base de datos
$pdo = conectar(); // Creo la conexión a la base de datos usando la función conectar

$cesta = $_SESSION["cesta"] ?? []; // Obtengo la cesta de la sesión o un array vacío si no existe
$productos = []; // Inicializo un array para los productos

if ($cesta) { // Si hay productos en la cesta

    $ids = "'" . implode("','", array_map("addslashes", $cesta)) . "'"; // Convierto los códigos de la cesta en una lista segura para SQL
    $stmt = $pdo->query("SELECT cod, nombre_corto, PVP FROM producto WHERE cod IN ($ids)"); // Hago la consulta para obtener los datos de los productos
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC); // Guardo los resultados en un array asociativo
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8"> <!-- Defino la codificación de caracteres -->
    <title>Cesta</title> <!-- Título de la página -->
    <link rel="stylesheet" href="css/estilos.css"> <!-- Enlazo el archivo de estilos CSS -->
</head>

<body>

    <div class="contenedor"> <!-- Div principal con clase contenedor -->
        <h2>Cesta de la compra</h2> <!-- Título de la sección -->

        <?php if (!$productos): ?> <!-- Compruebo si no hay productos en el array -->
            <p class="error">No hay productos en la cesta.</p> <!-- Mensaje de error si la cesta está vacía -->
        <?php else: ?> <!-- Si hay productos -->

            <table> <!-- Inicio de la tabla -->
                <tr> <!-- Fila de cabecera -->
                    <th>Producto</th> <!-- Columna Producto -->
                    <th>Precio</th> <!-- Columna Precio -->
                </tr>

                <?php
                $total = 0; // Inicializo la variable total a 0
                foreach ($productos as $p): // Recorro cada producto en el array
                    $total += $p["PVP"]; // Sumo el precio del producto al total
                    ?>
                    <tr> <!-- Fila del producto -->
                        <td><?= htmlspecialchars($p["nombre_corto"]) ?></td>
                        <!-- Nombre corto del producto con protección contra HTML -->
                        <td><?= number_format($p["PVP"], 2) ?> €</td> <!-- Precio del producto con 2 decimales -->
                    </tr>
                <?php endforeach; ?> <!-- Fin del bucle foreach -->

                <tr> <!-- Fila del total -->
                    <td><strong>Total</strong></td> <!-- Texto Total -->
                    <td><strong><?= number_format($total, 2) ?> €</strong></td> <!-- Total con formato de 2 decimales -->
                </tr>
            </table> <!-- Fin de la tabla -->

        <?php endif; ?> <!-- Fin del else -->

        <br>
        <a href="productos.php" class="boton">Seguir comprando</a><br><br> <!-- Enlace para volver a productos -->
        <a href="pago.php" class="boton">Proceder al pago</a> <!-- Enlace para ir al pago -->
    </div> <!-- Fin del contenedor -->

</body>

</html>