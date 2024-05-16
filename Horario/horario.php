<?php
  /*Se incluye un archivo llamado variables que contiene información sobre la base de datos */
        include('/etc/config/variables.php');
  /*Se establece la conexión con dos base de datos*/
        $conexion=mysqli_connect($db_host,$db_user,$db_password,"plantillaPersonal");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horario Hotel Lujo</title>
</head>
<body>

<?php
//Abro div principal diario
echo "<div>";
	//Creamos los divs simulando las horas del día
	for ($i = 1; $ <= 24; $i++){
		echo "<div></div>";
	}


echo "</div>";




?>

</body>
</html>
