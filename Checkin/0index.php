<?php
session_start();

// Definir la lógica para manejar el inicio de sesión y el estado del usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    // Validar las credenciales del usuario (aquí deberías tener tu lógica de validación)
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Por simplicidad, vamos a comparar con credenciales fijas
    $username_correcto = "yonadu";
    $password_correcto = "1234";

    if ($username == $username_correcto && $password == $password_correcto) {
        // Credenciales correctas, establecer una sesión
        $_SESSION["loggedin"] = true;
        // Redirigir a la misma página para mostrar el contenido del usuario autenticado
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        // Credenciales incorrectas, mostrar un mensaje de error
        $error_message = "Usuario o contraseña incorrectos";
    }
}

// Verificar si el usuario está autenticado
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // El usuario está autenticado, mostrar el contenido de fichaje
    include 'CheckInOut.php';
} else {
    // El usuario no está autenticado, mostrar el formulario de inicio de sesión
    include 'login_form.php';
}
?>