<?php
	include('/etc/config/variables.php');
	$conexion=mysqli_connect($db_host,$db_user,$db_password,$db_name);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Servicio de limpieza</title>

<style>
   .dot2 {
    height: 20px;
    width: 20px;
    border-radius: 50%;
    font-weight:bold;
    margin-bottom:10px;
  }
  .dot {
    height: 20px;
    width: 20px;

    border-radius: 50%;
    font-weight: bold;

    display: flex;
    align-items: center;
    justify-content: center;
  }
  .greenDot {
    background-color: lime;
   }
  .redDot {
   background-color: red;
  }
  .blueDot {
   background-color: blue;
  }
  .yellowDot {
   background-color: yellow;
  }
  .blackDot {
   background-color: black;
  }
  .left{
   display: left;
   padding:100px;
   justify-content:left;
  }
  .left p{
    margin-left:40px;
    width: 500px;
  }
  .tablaPuntos{
    background-image: url('multimedia/planoPaintHabitaciones.png');
    background-size: contain;
    background-repeat: no-repeat;
    image-rendering: pixelated;

    width: 964px;
    height: 210px;

    display: flex;
    align-items: flex-end;
    margin: 0 auto;
    margin-top: 100px;
    margin-bottom: 100px;
  }
  .colspan{
    display: flex;
    justify-content: space-between;

    width: 100%;
    height: 90px;
  }
  .celda{
    flex: 0 0 calc((100% / 13) - 2px);

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }
  body{
   background-color:#f7e6b2;
  }
</style>

</head>
<body>
<br>
<?php
function comprobarCoincidencia($fechasCheckIn, $fechasCheckOut) {
    //misma longitud
    if (count($fechasCheckIn) !== count($fechasCheckOut)) {
        return false;
    }

    $fechaActual = strtotime(date('Y-m-d'));

    //mapear
    for ($i = 0; $i < count($fechasCheckIn); $i++) {
        $checkIn = strtotime($fechasCheckIn[$i]);
        $checkOut = strtotime($fechasCheckOut[$i]);

        //Si concidencias
	if ($fechaActual == $checkIn) {
            return 2;
	}
        elseif ($fechaActual >= $checkIn && $fechaActual <= $checkOut) {
            return 1;
        }
    }
    //Si no concidencias
    return false;
}
function fechasInOut($fechasCheckIn, $fechasCheckOut) {
    //misma longitud
    if (count($fechasCheckIn) !== count($fechasCheckOut)) {
        return false;
    }

    $fechaActual = strtotime(date('Y-m-d'));

    //mapear
    for ($i = 0; $i < count($fechasCheckIn); $i++) {
        $checkIn = strtotime($fechasCheckIn[$i]);
        $checkOut = strtotime($fechasCheckOut[$i]);

        //Si concidencias
        if ($fechaActual >= $checkIn && $fechaActual <= $checkOut) {
            return $i;
	}
    }
    //Si no concidencias
    return false;
}
function consultaEstandarFechas($numHab, $alias, $meta_keyF, $conexion){
	$query = "SELECT meta_value AS $alias
        	      FROM wp_postmeta
              	WHERE meta_key = '$meta_keyF'
              	AND post_id IN (
                	  SELECT post_id
                  	FROM wp_postmeta
                  	WHERE meta_key = '_mphb_booking_price_breakdown'
                  	AND meta_value LIKE '%$numHab %'
              	)";

	$result = mysqli_query($conexion, $query);
	return $result;
}
function consultaEstandar($numHab, $alias, $meta_keyF, $conexion, $beacon){
	$query = "SELECT meta_value AS $alias
          FROM wp_postmeta
          WHERE meta_key = '$meta_keyF'
          AND post_id IN (
              SELECT post_id
              FROM wp_postmeta
              WHERE meta_key = '_mphb_booking_price_breakdown'
              AND meta_value LIKE '%$numHab %'
          )
          AND post_id IN (
              SELECT post_id
              FROM wp_postmeta
              WHERE meta_key = 'mphb_check_in_date'
              AND meta_value = '$beacon'
          )";

	$result = mysqli_query($conexion, $query);
	$array = mysqli_fetch_row($result);
	$XCustomer = $array[0];
	return $XCustomer;
}

//Especificar número de habitaciones joder no se ve na con el azul oscuro del nano
$habitaciones = array();
$num_pisos = 3;
$num_habitaciones_por_piso = 12;

for ($piso = $num_pisos; $piso >= 1; $piso--) {
    for ($habitacion = 1; $habitacion <= $num_habitaciones_por_piso; $habitacion++) {
        $habitaciones[] = str_pad($piso, 1, "0", STR_PAD_LEFT) . str_pad($habitacion, 2, "0", STR_PAD_LEFT);
    }
}

if (mysqli_connect_errno()) {
    echo "Error al conectar con MySQL: " . mysqli_connect_error();
    exit();
}
?>

<?php
// Mostrar la tabla de la centena 300 primero
echo '<div class="tablaPuntos"><div class="colspan">';

$counter = 0;
foreach ($habitaciones as $numeroHabitacion) {
    if ($counter % 12 == 0 && $counter != 0) {
	echo '</div>';
	echo '</div></div>';
	echo '<div class="tablaPuntos"><div class="colspan">';
	}

	if ($counter % 6 == 0 && $counter != 0 && $counter % 12 != 0) {
	echo '<div class="celda"></div>';
	}

        $counter++;

/*FechaIn*/	$conexionCheckInsPorHabitacion = consultaEstandarFechas($numeroHabitacion, 'check_in_date', 'mphb_check_in_date', $conexion);
/*FechaOut*/	$conexionCheckOutsPorHabitacion = consultaEstandarFechas($numeroHabitacion, 'check_out_date', 'mphb_check_out_date', $conexion);

	$fechasCheckIns = array();
	while ($fila = mysqli_fetch_assoc($conexionCheckInsPorHabitacion)) {
 	   $fechasCheckIns[] = $fila['check_in_date'];
	}
	$fechasCheckOuts = array();
	while ($fila = mysqli_fetch_assoc($conexionCheckOutsPorHabitacion)) {
           $fechasCheckOuts[] = $fila['check_out_date'];
        }
	$coincidenciaEnFechas = comprobarCoincidencia($fechasCheckIns, $fechasCheckOuts);
        $indiceFechasInOut= fechasInOut($fechasCheckIns, $fechasCheckOuts);

/*Nombre*/      $nameCustomer = consultaEstandar($numeroHabitacion, 'nameCustomer', 'mphb_first_name', $conexion, $fechasCheckIns[$indiceFechasInOut]);
/*Mail*/        $mailCustomer = consultaEstandar($numeroHabitacion, 'mailCustomer', 'mphb_email', $conexion, $fechasCheckIns[$indiceFechasInOut]);
/*NºTel*/       $numCustomer = consultaEstandar($numeroHabitacion, 'numCustomer', 'mphb_phone', $conexion, $fechasCheckIns[$indiceFechasInOut]);
/*Nota*/        $notaCustomer = consultaEstandar($numeroHabitacion, 'notaCustomer', 'mphb_note', $conexion, $fechasCheckIns[$indiceFechasInOut]);

	$coincidenciaEnFechas = comprobarCoincidencia($fechasCheckIns, $fechasCheckOuts);
	$indiceFechasInOut= fechasInOut($fechasCheckIns, $fechasCheckOuts);
	echo '<div class="celda"><div class="dot ' . ($coincidenciaEnFechas == 1 ? 'redDot' : ($coincidenciaEnFechas == 2 ? 'yellowDot' : 'greenDot')) . '"';
	if ($coincidenciaEnFechas) {
    	echo ' onclick="mostrarAlerta(\'' . $fechasCheckIns[$indiceFechasInOut] . '\', \'' . $fechasCheckOuts[$indiceFechasInOut] . '\', \'' . $nameCustomer . '\', \'' . $numCustomer . '\', \'' . $mailCustomer . '\', \'' . $notaCustomer . '\')"';}
	echo '></div><div class="dotText">' . $numeroHabitacion . '</div></div>';
}
echo '</div></div>';
mysqli_close($conexion);
?>
<div class="left">
  <div class="dot2 redDot"><p>No molestar</p></div>
  <div class="dot2 greenDot"><p>Habitación vacia</p></div>
  <div class="dot2 blueDot"><p> Pide limpieza antes de la noche</p></div>
  <div class="dot2 yellowDot"><p>¡Ha de limpiarse para mañana!</p></div>
  <div class="dot2 blackDot"><p>No sé</p></div>
</div>

<?php foreach ($info_dots as $info_dot): ?>
        <div class="dot" data-color="<?php echo $info_dot['color']; ?>"></div>
    <?php endforeach; ?>
<script>
function mostrarAlerta(In, Out, Name, Num, Mail, Nota) {
    if (In !== '' || Out !== '') {
        alert("Fecha de entrada: " + In + "\nFecha de salida: " + Out + "\nNombre del cliente: " + Name + "\nContacto del cliente: " + Num + "\nMail: " + Mail + "\nNota: " + Nota)
    }
}
</script>
</script>
</body>
</html>
