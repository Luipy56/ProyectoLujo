<?php
	include('/etc/config/variables.php');
	$conexion=mysqli_connect($db_host,$db_user,$db_password,$db_name);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Servicio de limpieza</title>
<style>
   .green-dot {
    height: 20px;
    width: 20px;
    background-color: green;
    border-radius: 50%;
    font-weight:bold;
    margin: 0 auto;
   }

  .red-dot {
   height: 20px;
   width: 20px;
   background-color: red;
   border-radius: 50%;
   margin: 0 auto;
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

<div class="puntos">
    <table>
        <tr>
            <?php
            $counter = 0;
            foreach ($habitaciones as $numeroHabitacion) {
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
			echo '<td><div class="red-dot"></div>' . $numeroHabitacion . '</td>';
                } else {
                    echo '<td><div class="green-dot"></div>' . $numeroHabitacion . '</td>';
                }
            }
            ?>
        </tr>
    </table>
</div>
<?php
// Cerrar conexión
mysqli_close($conexion);
?>


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
