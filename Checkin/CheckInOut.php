<?php
include('/etc/config/variables.php');
$conn = mysqli_connect($db_host, $db_user, $db_password, 'plantillaPersonal');

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT COUNT(*) as count FROM CheckInOut WHERE DNI = '16335775W' AND CheckOut IS NULL ORDER BY ID DESC LIMIT 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$variable = ($row['count'] > 0) ? 0 : 1; // Si hay al menos una entrada sin CheckOut, $variable será 1, de lo contrario será 0

// Si se envió algún formulario, procesar la acción
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["checkin"])) {
        // Acción para CheckIn
        $sql = "INSERT INTO CheckInOut (DNI, CheckIn) VALUES ('16335775W', NOW())";
        if ($conn->query($sql) === TRUE) {
            header("Location: CheckSuccess.php?status=checkin"); // Redirigir a una página de éxito para CheckIn
            exit(); // Terminar la ejecución del script después de redirigir
        } else {
            echo "Error al registrar CheckIn: " . $conn->error;
        }
    } elseif (isset($_POST["checkout"])) {
        // Acción para CheckOut
        $sql = "UPDATE CheckInOut SET CheckOut = NOW() WHERE DNI = '16335775W' AND CheckOut IS NULL";
        if ($conn->query($sql) === TRUE) {
            header("Location: CheckSuccess.php?status=checkout"); // Redirigir a una página de éxito para CheckOut
            exit(); // Terminar la ejecución del script después de redirigir
        } else {
            echo "Error al registrar CheckOut: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheckInOut</title>
</head>
<body>
    <?php
    // Mostrar mensaje según el valor de la variable
    if ($variable == 1) {
        echo "<p>Puedes hacer CheckIn</p>";
    } else {
        echo "<p>Puedes hacer CheckOut</p>";
    }
    ?>

    <form method="post">
        <button type="submit" name="checkin" <?php if ($variable != 1) echo "disabled"; ?>>CheckIn</button>
        <button type="submit" name="checkout" <?php if ($variable != 0) echo "disabled"; ?>>CheckOut</button>
    </form>
</body>
</html>
