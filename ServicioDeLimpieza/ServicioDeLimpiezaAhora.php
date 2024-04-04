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
echo '<tr><td colspan="12">"Hola"</td></tr>';

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

        while ($respuestaPeticionHabitacion = mysqli_fetch_array($conexionPeticionHabitacion)) {
            $post_id = $respuestaPeticionHabitacion['post_id'];

            $comandoFechas = "SELECT meta_value FROM wp_postmeta WHERE post_id = $post_id AND (meta_key = 'mphb_check_in_date' OR meta_key = 'mphb_check_out_date');";
            $conexionFechas = mysqli_query($conexion, $comandoFechas);

            while ($filaFecha = mysqli_fetch_array($conexionFechas)) {
                $fechas[] = $filaFecha['meta_value'];
            }
        }

		$fechaActual = date('Y-m-d');
        if ($post_id && $fechaActual >= $fechas[0] && $fechaActual <= $fechas[1]) {
			echo '<td><div class="dot red-dot"></div>' . $numeroHabitacion . '</td>';
        } else {
            echo '<td><div class="dot green-dot"></div>' . $numeroHabitacion . '</td>';
        }
    }
}

echo '</tr></table></div>';
echo '<div style="    height: 100px;"></div>';
// Mostrar la tabla de la centena 200 luego
echo '<div class="puntos"><table><tr>';

$counter = 0;
foreach ($habitaciones as $numeroHabitacion) {
    if (strpos($numeroHabitacion, '2') === 0) { // Filtrar por la centena 200
        if ($counter % 12 == 0 && $counter != 0) {
            echo '</tr></table><br><table><tr>';
        }
        $counter++;

        $comandoPeticionHabitacion = "SELECT post_id FROM wp_postmeta WHERE meta_key = '_mphb_booking_price_breakdown' AND meta_value LIKE '%$numeroHabitacion %';";
        $conexionPeticionHabitacion = mysqli_query($conexion, $comandoPeticionHabitacion);

        $post_id = null;
        $fechas = array();

        while ($respuestaPeticionHabitacion = mysqli_fetch_array($conexionPeticionHabitacion)) {
            $post_id = $respuestaPeticionHabitacion['post_id'];

            $comandoFechas = "SELECT meta_value FROM wp_postmeta WHERE post_id = $post_id AND (meta_key = 'mphb_check_in_date' OR meta_key = 'mphb_check_out_date');";
            $conexionFechas = mysqli_query($conexion, $comandoFechas);

            while ($filaFecha = mysqli_fetch_array($conexionFechas)) {
                $fechas[] = $filaFecha['meta_value'];
            }
        }

		$fechaActual = date('Y-m-d');
        if ($post_id && $fechaActual >= $fechas[0] && $fechaActual <= $fechas[1]) {
			echo '<td><div class="dot red-dot"></div>' . $numeroHabitacion . '</td>';
        } else {
            echo '<td><div class="dot green-dot"></div>' . $numeroHabitacion . '</td>';
        }
    }
}

echo '</tr></table></div>';
echo '<div style="    height: 100px;"></div>';
// Mostrar la tabla de la centena 100 por último
echo '<div class="puntos"><table><tr>';

$counter = 0;
foreach ($habitaciones as $numeroHabitacion) {
    if (strpos($numeroHabitacion, '1') === 0) { // Filtrar por la centena 100
        if ($counter % 12 == 0 && $counter != 0) {
            echo '</tr></table><br><table><tr>';
        }
        $counter++;

        $comandoPeticionHabitacion = "SELECT post_id FROM wp_postmeta WHERE meta_key = '_mphb_booking_price_breakdown' AND meta_value LIKE '%$numeroHabitacion %';";
        $conexionPeticionHabitacion = mysqli_query($conexion, $comandoPeticionHabitacion);

        $post_id = null;
        $fechas = array();

        while ($respuestaPeticionHabitacion = mysqli_fetch_array($conexionPeticionHabitacion)) {
            $post_id = $respuestaPeticionHabitacion['post_id'];

            $comandoFechas = "SELECT meta_value FROM wp_postmeta WHERE post_id = $post_id AND (meta_key = 'mphb_check_in_date' OR meta_key = 'mphb_check_out_date');";
            $conexionFechas = mysqli_query($conexion, $comandoFechas);

            while ($filaFecha = mysqli_fetch_array($conexionFechas)) {
                $fechas[] = $filaFecha['meta_value'];
            }
        }

		$fechaActual = date('Y-m-d');
        if ($post_id && $fechaActual >= $fechas[0] && $fechaActual <= $fechas[1]) {
			echo '<td><div class="dot red-dot"></div>' . $numeroHabitacion . '</td>';
        } else {
            echo '<td><div class="dot green-dot"></div>' . $numeroHabitacion . '</td>';
        }
    }
}

echo '</tr></table></div>';

// Cerrar conexión
mysqli_close($conexion);
?>
<div class="left">
  <div class="dot2 red-dot"><p>No molestar</p></div>
  <div class="dot2 green-dot"><p>Habitación vacia</p></div>
  <div class="dot2 blue-dot"><p> Pide limpieza antes de la noche</p></div>
  <div class="dot2 yellow-dot"><p>¡Ha de limpiarse para mañana!</p></div>
  <div class="dot2 black-dot"><p>No sé</p></div>
</div>
<br>
<table class="puntos">
  <tr>
    <th colspan="24">Primer Piso</th>
  </tr>
  <tr>
    <th colspan="12">Sección Uno</th>
    <th colspan="12">Sección Dos</th>
  </tr>
  <tr>
    <td colspan="2"><div class="green-dot"></div></td>
    <td colspan="2"><div class="green-dot"></div></td>
    <td colspan="2"><div class="green-dot"></div></td>
    <td colspan="2"><div class="green-dot"></div></td>
    <td colspan="2"><div class="green-dot"></div></td>
    <td colspan="2"><div class="green-dot"></div></td>

    <td colspan="2"><div class="green-dot"></div></td>
    <td colspan="2"><div class="green-dot"></div></td>
    <td colspan="2"><div class="green-dot"></div></td>
    <td colspan="2"><div class="green-dot"></div></td>
    <td colspan="2"><div class="green-dot"></div></td>
    <td colspan="2"><div class="green-dot"></div></td>

  </tr>
</table>
</body>
</html>
