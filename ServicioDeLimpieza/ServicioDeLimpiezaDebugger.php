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
    font-weight:bold;
    margin:0 auto;
  }
  .green-dot {
    background-color: green;
   }
  .red-dot {
   background-color: red;
  }
  .blue-dot {
   background-color: blue;
  }
  .yellow-dot {
   background-color: yellow;
  }
  .black-dot {
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
  .puntos table {
    border-collapse: collapse;
    width: 100%;
  }
  .puntos table, .puntos th, .puntos td {
    border: 1px solid black;
  }
  .puntos th, .puntos td {
    padding: 20px;
    text-align: center;
  }
  body{
   background-color:#f7e6b2;
  }
  .tablaPiso1{
    background-image: url('Piso1.png');
    background-size: auto 100%;
    background-repeat: no-repeat;
  }
</style>

</head>
<body>
<br>
<?php
function fechaMasCercana($fechas) {
    if (count($fechas) == 0) {
	echo 'null';
        return null; // Si el array está vacío, retornar null
    } elseif (count($fechas) == 1) {
	echo 'solo 1 entrada';
        return $fechas[0]; // Si solo hay una fecha, retornar esa fecha
    } else {
	echo 'Más de 1 entrada';
        // Obtener la fecha actual
        $fechaActual = strtotime(date('Y-m-d'));
        
        // Ordenar el array de fechas de menor a mayor
        sort($fechas);
        
        // Iterar sobre las fechas y encontrar la primera mayor o igual a la fecha actual
        foreach ($fechas as $fecha) {
            // Convertir la fecha del array a un timestamp
            $timestampFecha = strtotime($fecha);
            if ($timestampFecha >= $fechaActual) {
                return $fecha; // Si la fecha es mayor o igual a la actual, retornarla
            }
        }
        
        // Si no se encontró ninguna fecha mayor o igual a la actual, retornar la última fecha del array
        return end($fechas);
    }
}
//Especificar número de habitaciones joder no se ve na con el azul oscuro del nano
$habitaciones = array();
$num_pisos = 3;
$num_habitaciones_por_piso = 12;

for ($piso = 1; $piso <= $num_pisos; $piso++) {
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
echo '<div class="puntos"><table class="tablaPiso1"><tr>';

$counter = 0;
foreach ($habitaciones as $numeroHabitacion) {
    if (strpos($numeroHabitacion, '3') === 0) { // Filtrar por la centena 300
        if ($counter % 12 == 0 && $counter != 0) {
            echo '</tr></table><br><table><tr>';
        }
        $counter++;

        $comandoPeticionHabitacion = "SELECT post_id FROM wp_postmeta WHERE meta_key = '_mphb_booking_price_breakdown' AND meta_value LIKE '%$numeroHabitacion %';";
        $conexionPeticionHabitacion = mysqli_query($conexion, $comandoPeticionHabitacion);

        $post_id = null;
        $fechas = array();

//Prueba
	while ($respuestaPeticionHabitacion = mysqli_fetch_array($conexionPeticionHabitacion)) {
            $post_id = $respuestaPeticionHabitacion['post_id'];
            $comandoFechas = "SELECT meta_value FROM wp_postmeta WHERE post_id = $post_id AND (meta_key = 'mphb_check_in_date');";
            $conexionFechas = mysqli_query($conexion, $comandoFechas);

            while ($filaFecha = mysqli_fetch_array($conexionFechas)) {
                $fechas[] = $filaFecha['meta_value'];
		echo '<br>';
                print_r($filaFecha);
                echo '<br>';
                print_r($respuestaPeticionHabitacion);
                echo '<br>';
                print($numeroHabitacion);
                echo '<br>';
                print_r($fechas);
                echo '<br>';
                echo '<br>';

	    }
	echo 'Salgo del bucle';
        echo '<br>';
        print_r($fechas);;
        echo '<br>';
        echo'aaaaa';
        echo '<br>';
        echo '<br>';

	$fechaMasCercana = fechaMasCercana($fechas);
	echo 'voy a imprimir';
	echo $fechaMasCercana;
	echo '<br>';
	echo '<br>';
	}
//Prueba

        while ($respuestaPeticionHabitacion = mysqli_fetch_array($conexionPeticionHabitacion)) {
            $post_id = $respuestaPeticionHabitacion['post_id'];
            $comandoFechas = "SELECT meta_value FROM wp_postmeta WHERE post_id = $post_id AND (meta_key = 'mphb_check_in_date' OR meta_key = 'mphb_check_out_date');";
            $conexionFechas = mysqli_query($conexion, $comandoFechas);

            while ($filaFecha = mysqli_fetch_array($conexionFechas)) {
                $fechas[] = $filaFecha['meta_value'];
		echo '<br>';
		print_r($filaFecha);
		echo '<br>';
		print_r($respuestaPeticionHabitacion);
		echo '<br>';
		print($numeroHabitacion);
		echo '<br>';
		print_r($fechas);
		echo '<br>';
		echo '<br>';
            }
	echo 'Salgo del bucle';
	echo '<br>';
	print_r($fechas);;
	echo '<br>';
	echo'aaaaa';
	echo '<br>';
	echo '<br>';
        }

    }
}

echo '</tr></table></div>';
echo '<div style="    height: 100px;"></div>';
// Mostrar la tabla de la centena 200 luego
