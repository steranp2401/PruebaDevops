<?php
// Inicio la sesión 
session_start();

// conexion
require_once "conexion.php";

// Compruebo si el formulario ha sido enviado 
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Conectamos a la base de datos 
    $pdo = conectar();

    // Recogemos los datos del formulario de manera segura 
    $usuario = $_POST["usuario"] ?? '';
    $password = $_POST["password"] ?? '';

    // Verifico que los campos tengan contenido, que no se manden vacios
    if (!empty($usuario) && !empty($password)) {

        // Consulta preparada 
        $sql = "SELECT * FROM usuarios WHERE usuario = ?";

        // Preparo la consulta
        $preparar = $pdo->prepare($sql);

        // se ejecuta la consulta con el nombre de usuario que hayamos puesto
        $preparar->execute([$usuario]);

        // cogemos el registro del usuario como array asociativo
        $user = $preparar->fetch(PDO::FETCH_ASSOC);

        /* 
         * Esto verificara:
         * 1. Que el usuario existe en la base de datos
         * 2. Que la contraseña introducida coincide con la contraseña hash (encriptada) almacenada 
         */
        if ($user && isset($user["contrasena"]) && password_verify($password, $user["contrasena"])) {

            // Guardamos el usuario en la sesión para mantenerlo logueado
            $_SESSION["usuario"] = $user["usuario"];

            // Se redirige a la página de productos
            header("Location: productos.php");
            exit;

        } else {
            // Si no coincide usuario o contraseña, muestro error
            $error = "Usuario o contraseña incorrectos";
        }

    } else {
        // Si algún campo esta vacio
        $error = "Por favor, rellena todos los campos";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>
<div class="contenedor">
    <h1>Iniciar sesión</h1>

    <!-- Pondra mensaje de error si existe -->
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST" action="">
        <label>Usuario</label>
        <input type="text" name="usuario" required><br><br>

        <label>Contraseña</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Entrar</button>
    </form>
</div>

</body>
</html>
