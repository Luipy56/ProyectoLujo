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
	<table border="1" >
		<tr>
                        <td>Meta ID</td>
                        <td>Nº</td>
                        <td>Protocolo</td>
                        <td>Info</td>
                </tr>

		<?php
                $sql = "SELECT * FROM wp_postmeta WHERE meta_key IN ('mphb_check_in_date', 'mphb_check_out_date','_mphb_booking_price_breakdown','mphb_email','mphb_first_name') ORDER BY post_id";
                $result = mysqli_query($conexion,$sql);
                while($mostrar = mysqli_fetch_array($result)){
                 ?>
		<tr>
                       <td><?php echo $mostrar['meta_id'] ?></td>
                       <td><?php echo $mostrar['post_id'] ?></td>
                       <td><?php echo $mostrar['meta_key'] ?></td>
                       <td><?php echo $mostrar['meta_value'] ?></td>
		</tr>
	<?php 
	}
	 ?>

	</table>
<br>
	<?php
	$numeroHabitacion = "100";
	$comandoPeticionHabitacion = "SELECT post_id FROM wp_postmeta WHERE meta_key = '_mphb_booking_price_breakdown' AND meta_value LIKE '%$numeroHabitacion%';";
	$conexionPeticionHabitacion = mysqli_query($conexion,$comandoPeticionHabitacion);
	while($respuestaPeticionHabitacion = mysqli_fetch_array($conexionPeticionHabitacion)){
	?>
	<div>
	<p>Id de la habitación <?php echo $numeroHabitacion?>: <?php echo $respuestaPeticionHabitacion['post_id'] ?></p>

	<?php
	$post_id = $respuestaPeticionHabitacion['post_id'];
	$comandoFechas = "SELECT meta_value FROM wp_postmeta WHERE post_id = $post_id AND (meta_key = 'mphb_check_in_date' OR meta_key = 'mphb_check_out_date');";
	$conexionFechas = mysqli_query($conexion, $comandoFechas);

	$fechas = array();
	while ($filaFecha = mysqli_fetch_array($conexionFechas)) {
    		$fechas[] = $filaFecha['meta_value'];}
	?>
	Check-in: <?php echo $fechas[0]; ?><br>
        Check-out: <?php echo $fechas[1]; ?><br>
	<?php
	$fechaActual=date('Y-m-d');
	//$fechaActual=strtotime('2024-03-25');
	if ($fechaActual >= $fechas[0] && $fechaActual <= $fechas[1]) {
        echo "<strong>La fecha actual está dentro del rango de reserva.</strong>";
    	} else {
        echo "<strong>La fecha actual está fuera del rango de reserva.</strong>";
    	}
	echo $fechaActual

	?>
	</div>
	<?php
        }
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

<br>
	<table border="1" >
                <tr>
                        <td>Meta ID</td>
                        <td>Nº</td>
                        <td>Protocolo</td>
                        <td>Fecha</td>
                </tr>

                <?php
                $sql = "SELECT * FROM wp_postmeta ORDER BY post_id";
                $result = mysqli_query($conexion,$sql);
                while($mostrar = mysqli_fetch_array($result)){
                 ?>
                <tr>
                       <td><?php echo $mostrar['meta_id'] ?></td>
                       <td><?php echo $mostrar['post_id'] ?></td>
                       <td><?php echo $mostrar['meta_key'] ?></td>
                       <td><?php echo $mostrar['meta_value'] ?></td>
               </tr>
        <?php 
        }
         ?>

        </table>
</body>
</html>
