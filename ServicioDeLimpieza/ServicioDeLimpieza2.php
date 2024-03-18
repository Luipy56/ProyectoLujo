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
			<td>Piso</td>
			<td>Nº de post</td>
			<td>Protocolo</td>
			<td>Fecha</td>
			<td>Estado</td>
		</tr>
	</table>

		<?php
		$current_date = date("Y-m-d");
                $sql = "SELECT * FROM wp_postmeta WHERE meta_key IN ('mphb_check_in_date', 'mphb_check_out_date') ORDER BY  post_id";
                $result = mysqli_query($conexion,$sql);
		$diccionario = array();

                while($mostrar = mysqli_fetch_array($result)){
			$meta_id = $mostrar['meta_id'];
        		$post_id = $mostrar['post_id'];
        		$meta_key = $mostrar['meta_key'];
        		$meta_value = $mostrar['meta_value'];

			if ($meta_key === 'mphb_check_in_date') {
		            $check_in = strtotime($meta_value);
		        } elseif ($meta_key === 'mphb_check_out_date') {
		            $check_out = strtotime($meta_value);
		            $current_date = time();

		            if ($current_date >= $check_in && $current_date <= $check_out) {
		                $estado = 'V';
		            } else {
		                $estado = 'R';
		            }
		        }
		        ?>
			<table>
		        	<tr>
		        		<td>Piso 1</td>
					<td><?php echo $estado ?></td>
		       		</tr>
			</table>
		<?php
	        }
	        ?>
<br>
	<table border="1">
		<tr>
			<td colspan="4">Pisto 1</td>
		</tr>
                <tr>
                        <td></td>
                        <td>V</td>
                        <td>V</td>
                        <td>V</td>
                </tr>
        </table>





</body>
</html>
