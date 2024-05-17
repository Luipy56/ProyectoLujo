<?php
  /*Se incluye un archivo llamado variables que contiene información sobre la base de datos */
        include('/etc/config/variables.php');
  /*Se establece la conexión con dos base de datos*/
        $conn=mysqli_connect($db_host,$db_user,$db_password,"plantillaPersonal");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horario Hotel Lujo</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="stylesHorarios.css">
</head>
<body>
<?php
session_start();
// Verificar si las variables de sesión están establecidas
if (isset($_SESSION['username'])) {
      $username = $_SESSION['username'];

  } else {
      header("Location: login_form.php?noSession");
      exit();
  }
//Verifica si se ha hecho clic en el botón de cerrar sesión
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login_form.php");
    exit;
}

?>
    <div class="container">
        <div class="content">
            <h1>Bienvenid@ <?php echo $username; ?></h1>
        
        <div class="button-container">
            <form method="post">
                <button type="submit" name="logout">Cerrar sesión</button>
            </form>
        

	<form method="post">
	    <label for="worker" style="color: #fff;">Selecciona un trabajador:</label>
	    <select name="worker" id="worker">
<?php
	        $sql = "SELECT Username FROM Personal";
	        $result = $conn->query($sql);
	        // Si hay resultados, mostrar cada nombre como una opción en el select
	        if ($result->num_rows > 0) {
	            while($row = $result->fetch_assoc()) {
	                echo "<option value='" . $row["Username"] . "'>" . $row["Username"] . "</option>";
	            }
		}
?>

	    </select>
	    <button type="submit" name="submit">Enviar</button>
	</form>
	</div>
	<?php
	// Procesar el formulario cuando se envíe
	$persona = $username;
	if (isset($_POST['submit'])) {
	    if (isset($_POST['worker'])) {
	        $persona = $_POST['worker'];
	    }
	}
	?>
	<h2>Actualmente viendo el horario de <?php echo $persona; ?></h2>
    </div></div>
<?php

//Sacar DNI (ya que se almacena por DNI en el CheckInOut)
$sql = "select DNI FROM Personal where Username = '$persona';";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	$row = $result->fetch_assoc();
	$dni = $row['DNI'];
  } else {
	echo "Seleccione un usuario valido";
      die();
  }

//Cuantos días tiene este mes?
$year = date('Y');
$month = date('m');
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

//Preparo $d para mapear un mes entero
for ($d = 1; $d <= $daysInMonth; $d++){
      //Abro div principal diario
      echo "<div class='diaDelPadre'>";
      echo "<div class='dia'>";
      echo "<h2 style='width:70px;margin-right:20px;position:relative;top:0;margin-top: 0;':>Día $d</h2>";
            //Básicamente coge el in y el out y calcula la diferencia redondeandola, si no hay OUT pilla la hora actual.
	    //Filtramos por el mes actual
            //Además pilla la hora del CheckIn
	$sql = "SELECT HOUR(CheckIn) AS HoraCheckIn,
	                ROUND(HOUR(TIMEDIFF(IFNULL(CheckOut, NOW()), CheckIn)) + (MINUTE(TIMEDIFF(IFNULL(CheckOut, NOW()), CheckIn)) / 60)) AS DiferenciaHorasRedondeada 
	        FROM CheckInOut
	        WHERE DAY(CheckIn) = '$d'
	            AND MONTH(CheckIn) = '$month'
	            AND DNI = '$dni'";

                  /*Esta consulta selecciona la diferencia de horas entre el momento de CheckIn y el momento de CheckOut en la tabla CheckInOut.
                  Pero, si el valor de CheckOut es nulo (es decir, si el usuario aún no ha realizado el CheckOut),
                  se reemplaza con la fecha y hora actuales utilizando la función NOW().
                  Luego, calcula la diferencia de tiempo utilizando TIMEDIFF() entre el valor de CheckOut (o la fecha y hora actuales si CheckOut es nulo) y
                  CheckIn. A continuación, extrae la parte de la hora de esta diferencia utilizando HOUR(), y la parte de los minutos utilizando MINUTE().
                  Después, divide los minutos entre 60 para obtener la fracción de hora y los suma a las horas. Finalmente, redondea este valor utilizando ROUND()
                  y lo devuelve como DiferenciaHorasRedondeada.*/

	//Limpiar variables
	$resultsArray = [];
	$row = null;

	$result = $conn->query($sql);
	//Comprobar los resultados y almacenar en array
	if ($result->num_rows > 0) {
		//Obtener todos los resultados en forma de array asociativo (dickionario)
		$resultsArray = $result->fetch_all(MYSQLI_ASSOC);
	}
        for ($h = 1; $h <= 24; $h++){

		//Procesar cada fila
        	foreach ($resultsArray as $row) {
			//Definimos la hora del checkin y las horas trabajadas hasta el out
                	$checkIn = $row['HoraCheckIn'];
                	$diferenciaHoras = $row['DiferenciaHorasRedondeada'];
			/*Si usuario no CheckOut Muchas horas poner como máximo hasta las 00:00*/
			if(($checkIn + $diferenciaHoras) > 24){
			$diferenciaHoras = 24-$checkIn;
			}
			//Si la hora procesada está dentro de las horas trabajada...
			if($h >= $checkIn && $h <= ($checkIn+$diferenciaHoras)){

				//Dibujar Verde tantas horas trabajadas
				for($verde = 1; $verde<=$diferenciaHoras; $verde++){
					//Redondear las esquinas
					echo "<div class='hora blanco";
						if ($h == 1) {
						    echo " redondeoIzq";
						} elseif ($h == 24) {
						    echo " redondeoDer";
						}
					echo "'>$h:00";
					$h++;
					echo "<div class='hora verde'></div>";
					echo "</div>";
				}
				//Respetar las horas pintadas

			}

		}
		if($h<=24){ /*asegurar que no se imprima un 25º div*/
		echo "<div class='hora blanco";
                        if ($h == 1) {
                            echo " redondeoIzq";
                        } elseif ($h == 24) {
                            echo " redondeoDer";
                        }
                echo "'>$h:00";
		echo "</div>";
		}
        }

      echo "</div></div>";
      echo "<div class='espacio'></div>";
}
?>
</body>
</html>
