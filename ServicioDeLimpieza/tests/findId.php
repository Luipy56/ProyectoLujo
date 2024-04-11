<?php
    include('/etc/config/variables.php');
    $conexion = mysqli_connect($db_host, $db_user, $db_password, $db_name);




	if (isset($_POST['room']) && isset($_POST['dateCheckIn'])) {
	        $room = $_POST['room'];
		$dateCheckIn = $_POST['dateCheckIn'];

//		echo "Habitación: " . $habitacion . ", Check-In: " . $checkIn;


	        $query = "SELECT post_id
	        FROM wp_postmeta
	        WHERE meta_key = '_mphb_booking_price_breakdown'
	            AND meta_value LIKE '%$room %'
	            AND post_id IN (
	                SELECT post_id
	                FROM wp_postmeta
	                WHERE meta_key = 'mphb_check_in_date'
	                    AND meta_value = '$dateCheckIn'
	            );";

	        $result = mysqli_query($conexion, $query);
        	$array = mysqli_fetch_row($result);
        	echo "Dato procesado: " . $array[0];
	}
?>
