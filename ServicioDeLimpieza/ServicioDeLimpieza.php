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
