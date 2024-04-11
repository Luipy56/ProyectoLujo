<?php

    include('/etc/config/variables.php');
    $conn = mysqli_connect($db_host, $db_user, $db_password,'roomService');
//Recuerdo: Al poner DNB la puerta no se podrá abrir desde fuera escepto en caso de incendio o shutdown
//Filtrar por cuna: SELECT post_id FROM wp_postmeta WHERE meta_key = '_mphb_booking_price_breakdown' AND meta_value LIKE '%Cuna%';
//die();
//        if (isset($_POST['numHab'])) {
	  if (isset($_POST['numHab']) && isset($_POST['DNB'])) {
                $numHab = $_POST['numHab'];
		$DNBstate = $_POST['DNB'];
		$numHab = (int)$numHab;
		$DNBstate = (int)$DNBstate;
		echo $DNBstate;
		//die();
                $sql = "UPDATE roomInfo SET DNB = $DNBstate WHERE roomNum = $numHab;";

		if ($conn->query($sql) === TRUE) {
		    echo "Actualización exitosa";
		} else {
		   echo $numHab;
		    echo "Error al actualizar: " . $conn->error;
		}
//                $result = mysqli_query($conexion, $query);
//                $array = mysqli_fetch_row($result);
//                echo "Estado: " . $array[0] . "molestar";
        }
?>
