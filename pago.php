<?php
session_start(); // Inicio la sesión para poder usar variables de sesión

if (!isset($_SESSION["usuario"])) { // Compruebo si el usuario no ha iniciado sesión
    header("Location: login.php"); // Si no hay sesión, redirijo a la página de login
    exit; // Salgo del script para que no siga ejecutándose
}

unset($_SESSION["cesta"]); // Elimino la cesta de la sesión porque se ha realizado el pago
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8"> <!-- Defino la codificación de caracteres -->
    <title>Pago</title> <!-- Título de la página -->
    <link rel="stylesheet" href="css/estilos.css"> <!-- Enlazo el archivo de estilos CSS -->
</head>

<body>

    <div class="contenedor"> <!-- Div principal con clase contenedor -->
        <h2>Gracias por su compra</h2> <!-- Mensaje de agradecimiento -->
        <p>Su pedido ha sido procesado correctamente.</p> <!-- Confirmación de que el pago se ha realizado -->

        <a href="productos.php" class="boton">Volver al inicio</a> <!-- Enlace para volver a la página de productos -->
    </div> <!-- Fin del contenedor -->

</body>

</html>
