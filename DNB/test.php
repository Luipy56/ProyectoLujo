<?php
include('/etc/config/variables.php');
$conexion=mysqli_connect($db_host,$db_user,$db_password,$db_name);

if(isset($_POST['dato'])){
    $dato = $_POST['dato'];
    // Aquí puedes hacer lo que necesites con el dato, como incluirlo en tu query
    // Ejemplo:
	$query="SELECT post_id
        FROM wp_postmeta
        WHERE meta_key = '_mphb_booking_price_breakdown'
                AND meta_value LIKE '%$dato %'
                AND post_id IN (
                        SELECT post_id
                        FROM wp_postmeta
                        WHERE meta_key = 'mphb_check_in_date'
                                AND meta_value = '2024-04-16'
        );";
    // Luego puedes ejecutar la consulta y manejar los resultados
	$result = mysqli_query($conexion, $query);
	$array = mysqli_fetch_row($result);

	echo "Dato procesado: " . $array[0];
} else {
    echo "No se recibió ningún dato.";
}
?>
