<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Actualización Automática</title>
</head>
<body>

<h1>AutoRefresh 30s</h1>

<?php
    include('/etc/config/variables.php');
    $conexion=mysqli_connect($db_host,$db_user,$db_password,"roomService");

// Especifica que el script PHP generará una salida HTML
header('Content-Type: text/html');

// Indica al navegador que recargue la página cada 30 segundos
header('Refresh: 30');

// Aquí puedes realizar tu consulta SELECT a la base de datos y mostrar los resultados
// Por ejemplo:
echo "<h1>Última actualización: " . date('H:i:s') . "</h1>";

// Comprobar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Consulta SQL para obtener los datos de la tabla roomInfo
$sql = "SELECT * FROM roomInfo";
$result = $conexion->query($sql);

// Si hay resultados, mostrarlos en la página
if ($result->num_rows > 0) {
    echo "<ul>";
    // Mostrar cada fila de resultados
    while($row = $result->fetch_assoc()) {
        echo "<li>Habitación: " . $row["roomNum"]. ", Descripción: " . $row["essay"]. ", DMB: " . $row["DNB"]. ", Size: " . $row["roomSize"].   "</li>";
    }
    echo "</ul>";
} else {
    echo "0 resultados";
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>


<script>
// Función para recargar la página cada 30 segundos
function recargarPagina() {
    location.reload(); // Recarga la página
}

// Llama a la función para recargar la página cada 30 segundos (30000 milisegundos)
setInterval(recargarPagina, 30000);
</script>

</body>
</html>
