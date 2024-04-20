<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No Molestar</title>
</head>
<body>
<?php
include('/etc/config/variables.php');
$conexion=mysqli_connect($db_host,$db_user,$db_password,$db_name);
?>

<input type="text" id="miInput" placeholder="Introduce información aquí">;

<script>
// Función para enviar la información a PHP
function enviarInformacion() {
    // Obtenemos el valor del input
    let informacion = document.getElementById('miInput').value;

    // Creamos una nueva instancia de XMLHttpRequest
    let xhr = new XMLHttpRequest();

    // Definimos la URL del archivo PHP (este mismo archivo)
    let url = '<?php echo $_SERVER["PHP_SELF"]; ?>';

    // Creamos los parámetros que se enviarán al PHP
    let params = 'informacion=' + encodeURIComponent(informacion);

    // Configuramos la solicitud
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    // Definimos la función a ejecutar cuando la solicitud se complete
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log('Respuesta del servidor:', xhr.responseText);
        }
    }

    // Enviamos la solicitud con los parámetros
    xhr.send(params);
}

// Evento para enviar la información cuando se escriba en el input
document.getElementById('miInput').addEventListener('input', enviarInformacion);
</script>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['informacion'])) {
    // Obtenemos la información enviada desde JavaScript
    $informacion = $_POST['informacion'];
}
$query="SELECT post_id
	FROM wp_postmeta
	WHERE meta_key = '_mphb_booking_price_breakdown'
		AND meta_value LIKE '%informacion %'
		AND post_id IN (
			SELECT post_id
			FROM wp_postmeta
			WHERE meta_key = 'mphb_check_in_date'
        			AND meta_value = '2024-04-16'
  	);";
$result = mysqli_query($conexion, $query);
$reply = mysqli_fetch_row($result);
echo $reply[0];

?>




</body>
</html>
