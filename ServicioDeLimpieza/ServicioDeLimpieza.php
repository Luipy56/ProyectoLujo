<?php
	include('/etc/config/variables.php');
	$conexion=mysqli_connect($db_host,$db_user,$db_password,$db_name);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Servicio de limpieza</title>
</head>
<body>
<br>
	<table border="1" >
		<tr>
                        <td>Meta ID</td>
                        <td>Nº</td>
                        <td>Protocolo</td>
                        <td>Fecha</td>
                </tr>

		<?php
                $sql = "SELECT * FROM wp_postmeta";
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
