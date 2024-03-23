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
</style>

</head>
<body>
<br>

<?php
$num_pisos = 3;
$num_habitaciones_por_piso = 12;

// Crear arrays para almacenar los números de habitaciones por piso
$habitaciones_por_piso = array();

for ($piso = 0; $piso < $num_pisos; $piso++) {
    $habitaciones_piso_actual = array();
    $inicio_habitacion = ($piso * $num_habitaciones_por_piso) + 101;
    $fin_habitacion = ($piso * $num_habitaciones_por_piso) + 112;

    for ($habitacion = $inicio_habitacion; $habitacion <= $fin_habitacion; $habitacion++) {
        $habitaciones_piso_actual[] = str_pad($habitacion, 3, "0", STR_PAD_LEFT);
    }

    $habitaciones_por_piso[] = $habitaciones_piso_actual;
}

if (mysqli_connect_errno()) {
    echo "Error al conectar con MySQL: " . mysqli_connect_error();
    exit();
}

// Imprimir la tabla tres veces con los números de habitaciones de cada piso
for ($iteracion = 0; $iteracion < $num_pisos; $iteracion++) {
    echo '<div class="puntos">';
    echo '<table><tr>';

    foreach ($habitaciones_por_piso[$iteracion] as $numeroHabitacion) {
        error_log("Número de habitación: $numeroHabitacion"); // Debug

        $comandoPeticionHabitacion = "SELECT post_id FROM wp_postmeta WHERE meta_key = '_mphb_booking_price_breakdown' AND meta_value LIKE '%$numeroHabitacion %';";
        error_log("Comando de petición de habitación: $comandoPeticionHabitacion"); // Debug

        $conexionPeticionHabitacion = mysqli_query($conexion, $comandoPeticionHabitacion);

        if (!$conexionPeticionHabitacion) {
            error_log("Error al ejecutar la consulta: " . mysqli_error($conexion));
            exit();
        }

        $post_id = null;
        $fechas = array();

        while ($respuestaPeticionHabitacion = mysqli_fetch_array($conexionPeticionHabitacion)) {
            $post_id = $respuestaPeticionHabitacion['post_id'];

            $comandoFechas = "SELECT meta_value FROM wp_postmeta WHERE post_id = $post_id AND (meta_key = 'mphb_check_in_date' OR meta_key = 'mphb_check_out_date');";
            error_log("Comando de fechas: $comandoFechas"); // Debug

            $conexionFechas = mysqli_query($conexion, $comandoFechas);

            if (!$conexionFechas) {
                error_log("Error al ejecutar la consulta de fechas: " . mysqli_error($conexion));
                exit();
            }

            while ($filaFecha = mysqli_fetch_array($conexionFechas)) {
                $fechas[] = $filaFecha['meta_value'];
            }
        }

        $fechaActual = date('Y-m-d');
        if ($post_id && $fechaActual >= $fechas[0] && $fechaActual <= $fechas[1]) {
            echo '<td><div class="red-dot"></div></td>';
        } else {
            echo '<td><div class="green-dot"></div></td>';
        }
    }
    echo '<br>';
    echo '</tr></table>';
    echo '</div>';
}
?>
