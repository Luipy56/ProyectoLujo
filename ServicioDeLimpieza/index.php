<?php 
	$conexion=mysqli_connect('localhost','root','','wordpress');
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
			<td>id</td>
			<td>nombre</td>
			<td>apellido</td>
			<td>email</td>
		</tr>

		<?php 
		$sql="SELECT * from t_persona";
		$result=mysqli_query($conexion,$sql);
		while($mostrar=mysqli_fetch_array($result)){
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