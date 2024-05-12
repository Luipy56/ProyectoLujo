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
//echo $dni;
//Ahora se  comprobará si hay hay una checkIN o no
$sql = "SELECT COUNT(*) as count FROM CheckInOut WHERE DNI = '$dni' AND CheckOut IS NULL ORDER BY ID DESC LIMIT 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc(); // obtiene la próxima fila del resultado como un array asociativo.
$variable = ($row['count'] > 0) ? 0 : 1; // 1 Cuando hay que hacer un IN 0 cuando hay que hacer un OUT. Para especificar, si hay una entrada que contenga SÓLO un IN entonces 0
//echo $variable;



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
        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('multimedia/bg2.jpg');
            background-size: cover;
            background-position: center;
        }
        .wrapper {
        margin-top: 50px; /* Ajusta el margen superior según tus necesidades */
        }

        h1 {
            margin-bottom: 20px; /* Ajusta el margen inferior del h1 según tus necesidades */
        }

        .button-container {
            position: relative; /* Agregamos position relative */
            width: 100px; /* ajusta el tamaño según tus necesidades */
            height: 100px; /* ajusta el tamaño según tus necesidades */
            background-size: contain;
            background-repeat: no-repeat;
            cursor: pointer;
        }
        button {
            width: 100%;
            height: 100%;
            border: none;
            background: transparent; /* quitamos el fondo del botón */
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <?php
    // Mostrar mensaje según el valor de la variable
    if ($variable == 1) {
        echo "<h1>Puedes hacer Check-In</h1>";
    } else {
        echo "<h1>Puedes hacer Check-Out</h1>";
    }
    ?>
    <form method="post" style="display: flex; justify-content: center; margin-top:50px;">
        <div class="button-container" style="background-image: url('<?php echo ($variable == 1) ? 'multimedia/CheckInVerde.png' : 'multimedia/CheckInNegro.png'; ?>');">
            <button type="submit" name="checkin" <?php if ($variable != 1) echo "disabled"; ?>></button>
        </div>
        <div class="button-container" style="background-image: url('<?php echo ($variable == 0) ? 'multimedia/CheckOutVerde.png' : 'multimedia/CheckOutNegro.png'; ?>');position: relative; left: 25px;">
            <button type="submit" name="checkout" <?php if ($variable != 0) echo "disabled"; ?>></button>
        </div>
    </form>
    <div style="display:flex;justify-content:center">
    	<div style="margin-top:20px; margin-right:70px;"><p>CheckIn</p></div>
    	<div style="margin-top:20px;"><p>CheckOut</p></div>
    </div>
</div>
</body>
</html>
