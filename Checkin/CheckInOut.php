<?php
session_start();
include('/etc/config/variables.php');
$conn = mysqli_connect($db_host, $db_user, $db_password, 'plantillaPersonal');

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$username = $_SESSION['username']; //Obtener el name de la sesión

//A raiz de las credenciales sacaré el DNI del usuario para hacer correctamente el insert/update
$sql = "Select DNI From Personal Where Username = '$username'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$dni = $row['DNI'];
echo $dni;
//Ahora se  comprobará si hay hay una checkIN o no
$sql = "SELECT COUNT(*) as count FROM CheckInOut WHERE DNI = '$dni' AND CheckOut IS NULL ORDER BY ID DESC LIMIT 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc(); // obtiene la próxima fila del resultado como un array asociativo.
$variable = ($row['count'] > 0) ? 0 : 1; // 1 Cuando hay que hacer un IN 0 cuando hay que hacer un OUT. Para especificar, si hay una entrada que contenga SÓLO un IN entonces 0
echo $variable;



// Si se envió algún formulario, procesar la acción
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["checkin"])) {
        // Acción para CheckIn
        $sql = "INSERT INTO CheckInOut (DNI, CheckIn) VALUES ('$dni', NOW())";
        if ($conn->query($sql) === TRUE) {
            header("Location: CheckSuccess.php?status=checkin"); // Redirigir a una página de éxito para CheckIn
            exit(); // Terminar la ejecución del script después de redirigir
        } else {
            echo "Error al registrar CheckIn: " . $conn->error;
        }
    } elseif (isset($_POST["checkout"])) {
        // Acción para CheckOut
        $sql = "UPDATE CheckInOut SET CheckOut = NOW() WHERE DNI = '$dni' AND CheckOut IS NULL";
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
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
            font-size: 175%;
        }
    </style>
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