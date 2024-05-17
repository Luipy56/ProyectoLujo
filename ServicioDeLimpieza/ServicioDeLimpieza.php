<?php
  /*Se incluye un archivo llamado variables que contiene información sobre la base de datos */
	include('/etc/config/variables.php');
  /*Se establece la conexión con dos base de datos*/
	$conexion=mysqli_connect($db_host,$db_user,$db_password,$db_name);
	$conexionDNB=mysqli_connect($db_host,$db_user,$db_password,'roomService');
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Servicio de limpieza</title>
	<meta charset="UTF-8">
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
  .lightblueDot {
   background-color: cyan;
  }
  .yellowDot {
   background-color: yellow;
  }
  .blackDot {
   background-color: black;
  }
  .dotText{
   font-weight:bold;
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
  }
  .Vip{
    background-image: url('multimedia/planoPaintHabitacionesVip.png');
    margin-top: 50px;
  }
  .colspan{
    display: flex;
    justify-content: space-between;

    width: 100%;
    height: 90px;
  }
  .celda{ flex: 0 0 calc((100% / 13) - 2px);

    display: flex; flex-direction: column; align-items: center; justify-content: center;
  }
	body{
            background: url('multimedia/bg2.jpg');
            background-size: cover;
            background-position: center;
        }
	p{color:#fff}
</style>
</head>
<body>
<br>
<?php

function DNBstate($conexion,$numHab){
  /**Consulta a la base de datos para saber si la habitacion especificada por parámetro
   * ha solicitado "No Molestar"
   * Devuelve TRUE si la habitacion está en modo NoMolestar
   */
  $query="SELECT DNB FROM roomInfo WHERE roomNum=$numHab;";
  $result = mysqli_query($conexion, $query);
  $array = mysqli_fetch_row($result);
  $state = $array[0];
  if ($state == 1){return true;}else{return false;}
  }

function comprobarCoincidencia($numHab,$conn,$fechasCheckIn, $fechasCheckOut, $Mod) {
  /**Esta funciónd debería de habnerla separado
   * Se mapean las fechas para hacer los IFs en bucle por todas las posibles fechas registradas en las reservas de  una habitacion
   * Con el MOD 0
   *  Se comprueba si la fecha de checkin es igual al día de hoy para saber si el huepes entra hoy
   *  si es  así se devuelve el número 2!
   *  Si el In no es hoy se asegura de que hoy esté dentro de los check
   *  y se comprueba si el huesped quiere que no se le moleste, devolviendo 3 si NoMolestar
   *  o devolviendo 1 si le da igual que le molesen
   * Con el MOD 1
   *  Se consulta el índice de la fecha que deseamos de todas las fechas registradas en una habitación
   *  es decir, se obtiene la posición de la fecha que nos interesa dentro del array de fechas
   * Si después de todo no ha habido ningún return, se devuelve False
   *
   * Resumen:
   * Si al huesped no le importa que le molesten = 1
   * Si el huesped entra hoy = 2
   * Si NoMolestar al hueped = 3
   * Si hay error o no coincide nada = false
   */
    //misma longitud
    if (count($fechasCheckIn) !== count($fechasCheckOut)) {
        return false;
    }

    $fechaActual = strtotime(date('Y-m-d'));

    //mapear
    for ($i = 0; $i < count($fechasCheckIn); $i++) {
        $checkIn = strtotime($fechasCheckIn[$i]);
        $checkOut = strtotime($fechasCheckOut[$i]);

	if ($Mod == '0'){/*Se consulta si hay coincidencia*/
	        //Si concidencias
	        if ($fechaActual == $checkIn) { //El huesped entra hoy
	            return 2;
	        }
	        elseif ($fechaActual >= $checkIn && $fechaActual <= $checkOut) { //El huesped lleva 1 o más días en el hotel
	        	if (DNBstate($conn,$numHab)){return 3; //El huesped pide que NO se le moleste
            }
            else{return 1;} //Al huesped no le importa que le molesten
	        }
	} elseif ($Mod == '1'){/*Se consulta el indice en el array*/
		if ($fechaActual >= $checkIn && $fechaActual <= $checkOut) {
            		return $i;
        	}
	}
    }
    //Si no concidencias
    return false;
  }
function consultaEstandarFechas($numHab, $alias, $meta_keyF, $conexion){
  /**Consulta para saber las fechas de check in y out de una habitación en concreto
   * Toma como parámetro el nombre de la meta_key (que define si está pidiendo el in o el out)
   * el número de la habitacion y un alias para la variable
   * Devuelve el resultado de la consulta, el meta_value como un array
   */
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
  /**Función estandar para consultar información de una habitación en particular
   * Se pasa como parámetros,
   *  el número de la hab
   *  un alias para el resultado
   *  el nombre de la meta_key (el nombre que tiene en DB una entrada)
   *  la conexión
   *  una fecha ($beacon) como referencia para buscar la reserva de la que queremos saber info
   * Con esto podemos solicitar por ejemplo la información de la "Nota" que ha dejado el cliente o su nombre
   */
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

//Especificar número de habitaciones joder no se ve na con el azul oscuro del cmd
$habitaciones = array();
$num_pisos = 4;
$num_habitaciones_por_piso = 12;
$numHabitacionesVip =2;
$arrayNumHabitacionesVip = array();
for ($i = 1; $i <= $numHabitacionesVip; $i++) {
    $arrayNumHabitacionesVip[] = 400 + $i;
}
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
echo '<h1 style="text-align:center;color:#fff;">Hotel Lujo <br> Servicio de limpieza </h1>';
echo '<h3 style="text-align:center;color:#fff;">Clicke en los circulos de colores para más información</h3>';

//Empieza el código de verdad
echo '<div class="tablaPuntos Vip"><div class="colspan">';
$counter = 0;
foreach ($habitaciones as $numeroHabitacion) {
	/*Zona de vacio*/
	if ($numeroHabitacion[0] != 4 or in_array($numeroHabitacion, $arrayNumHabitacionesVip) or $numeroHabitacion == end($arrayNumHabitacionesVip)+1) {

		if (($counter % 12 == 0 && $counter != 0) || $numeroHabitacion == end($arrayNumHabitacionesVip)+1){/*Zona de vacio*/
			echo '</div>';
			echo '</div></div>';
			echo '<div class="tablaPuntos"><div class="colspan">';
			if ($numeroHabitacion == 403){
				$counter=$counter-$numHabitacionesVip;
				continue;}
		}elseif ($counter % 6 == 0 && $counter != 0 && $counter % 12 != 0) {
			echo '<div class="celda"></div>';
		}
        $counter++;


        /**Funciones para solicitar las fechas de entrada y salida de una habitación */
/*FechaIn*/	$conexionCheckInsPorHabitacion = consultaEstandarFechas($numeroHabitacion, 'check_in_date', 'mphb_check_in_date', $conexion);
/*FechaOut*/	$conexionCheckOutsPorHabitacion = consultaEstandarFechas($numeroHabitacion, 'check_out_date', 'mphb_check_out_date', $conexion);
    /**Proceso las peticiones anteriores para dejar el resultado en un array
     * Se nota que lo hice muy al principio
     */
	$fechasCheckIns = array();
	while ($fila = mysqli_fetch_assoc($conexionCheckInsPorHabitacion)) {
 	   $fechasCheckIns[] = $fila['check_in_date'];
	}
	$fechasCheckOuts = array();
	while ($fila = mysqli_fetch_assoc($conexionCheckOutsPorHabitacion)) {
           $fechasCheckOuts[] = $fila['check_out_date'];
        }
        /**Se comprobará en qué estado de coincidencia respecto a la fecha o al no molestar se encuentra la habitación
         * Y se solicita el indice dentro del array de fechas está la fecha que nos interesa
         */
	$coincidenciaEnFechas = comprobarCoincidencia($numeroHabitacion,$conexionDNB,$fechasCheckIns, $fechasCheckOuts, '0');
        $indiceFechasInOut= comprobarCoincidencia($numeroHabitacion,$conexionDNB,$fechasCheckIns, $fechasCheckOuts, '1');

        /**Ahora guardaremos en variables la información relevante que mostraremos de cada habitacion, como el nombre del cliente */
/*Nombre*/      $nameCustomer = consultaEstandar($numeroHabitacion, 'nameCustomer', 'mphb_first_name', $conexion, $fechasCheckIns[$indiceFechasInOut]);
/*Mail*/        $mailCustomer = consultaEstandar($numeroHabitacion, 'mailCustomer', 'mphb_email', $conexion, $fechasCheckIns[$indiceFechasInOut]);
/*NºTel*/       $numCustomer = consultaEstandar($numeroHabitacion, 'numCustomer', 'mphb_phone', $conexion, $fechasCheckIns[$indiceFechasInOut]);
/*Nota*/        $notaCustomer = consultaEstandar($numeroHabitacion, 'notaCustomer', 'mphb_note', $conexion, $fechasCheckIns[$indiceFechasInOut]);

	//zona de creación de celdas
//	echo '<div class="celda"><div class="dot ' . ($coincidenciaEnFechas == 1 ? 'redDot' : ($coincidenciaEnFechas == 2 ? 'yellowDot' : 'greenDot')) . '"';
  
  /**Gracias a el valor del estado de la habitación que hemos conseguido antes gracias a la función comprobarCoincidencia y que hemos guardado en $coincidenciaEnFechas
   * Definimos la calse que tendrá el div, cosa que cambiará el color del circulo que se mostrará:
   *  Si es 1 (Se puede molestar) = Verde
   *  Si es 2 (Entra hoy huesped)= Amarillo
   *  Si es 3 (Se puede molestar)= Rojo
   *  De lo contrario (No hay huesped)= Azul claro
   */
	echo '<div class="celda"><div class="dot ' . ($coincidenciaEnFechas == 1 ? 'greenDot' : ($coincidenciaEnFechas == 2 ? 'yellowDot' : ($coincidenciaEnFechas == 3 ? 'redDot' : 'lightblueDot'))) . '"';
  
  /** Si la coincidencia es en número, es decir, hay un huesped en la habitación
   * Se hará del circulo de color botón el cual mostrará un alert con información relevante
   * La alerta se procesará en js
   * Esta cacho frase hay que tratarla un poco como si fuera una función a la que se le pasan parámetros
   */
	if ($coincidenciaEnFechas) {
    	echo ' onclick="mostrarAlerta(\'' . $fechasCheckIns[$indiceFechasInOut] . '\', \'' . $fechasCheckOuts[$indiceFechasInOut] . '\', \'' . $nameCustomer . '\', \'' . $numCustomer . '\', \'' . $mailCustomer . '\', \'' . $notaCustomer . '\')"';}
	/**Se cierra el div especificando el número de la hab */
  echo '></div><div class="dotText">' . $numeroHabitacion . '</div></div>';
}}

echo '</div></div>';
mysqli_close($conexion);
?>
  <!-- Leyenda -->
<div class="left">
  <div class="dot2 redDot"><p>No molestar</p></div>
  <div class="dot2 greenDot"><p>Sí molestar</p></div>
  <div class="dot2 lightblueDot"><p>Habitación vacía</p></div>
  <!-- <div class="dot2 blueDot"><p> Pide limpieza antes de la noche</p></div> Eliminar -->
  <div class="dot2 yellowDot"><p>¡Ha de limpiarse para mediodía!</p></div>
  <div class="dot2 blackDot"><p>No sé, quizá hay un cadáver</p></div>
</div>
<?php echo '<h2><a href="https://github.com/Luipy56/ProyectoLujo/tree/main/ServicioDeLimpieza">Enlace al Código en GitHub</a></h2>';?>
<script>
  /** La siguiente función procesa la Alerta dado los parámetros anteriormente
   * y se estructurará las frases que aparecen en la alerta
   */
function mostrarAlerta(In, Out, Name, Num, Mail, Nota) {
    if (In !== '' || Out !== '') {
        alert("Fecha de entrada: " + In + "\nFecha de salida: " + Out + "\nNombre del cliente: " + Name + "\nContacto del cliente: " + Num + "\nMail: " + Mail + "\nNota: " + Nota)
    }
}
</script>
</body>
</html>
