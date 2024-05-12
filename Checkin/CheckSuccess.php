<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheckIn Completado</title>
	<link rel="stylesheet" href="styles.css">
	<style>
        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('multimedia/bg2.jpg');
            background-size: cover;
            background-position: center;
        }
	p{
	text-align: center; padding-top: 10px; padding-bottom:10px;
	}
	</style>
</head>
<body>
    
    <div class="wrapper">

        <?php
        // Obtener el estado del botón desde la URL
        $status = isset($_GET['status']) ? $_GET['status'] : '';

        // Definir el mensaje según el estado del botón
        if ($status === 'checkin') {
            $message = "¡Check-In Completado!";
        } elseif ($status === 'checkout') {
            $message = "¡Check-Out Completado!";
        } else {
            $message = "¡Link Incorrecto, abandonde la página!";
        }
        ?>

        <div class="<?php echo ($status === 'checkout') ? 'out' : 'in'; ?>">
            <h1><?php echo $message; ?></h1>
        <p style="padding-top:50px"><?php echo ($status === 'checkout') ? "Has realizado correctamente el Check-Out." : (($status === 'checkin') ? "Has realizado correctamente el Check-In." : ""); ?></p>

        <?php if ($status === ''): ?>
            <p>Si esperabas encontrar otra cosa, contacta con tu administrador informático.</p>
        <?php else: ?>
            <p>Fecha: <?php echo date("Y-m-d"); ?></p>
            <p>Hora actual: <?php echo date("H:i:s"); ?></p>
        <?php endif; ?>
        </div>

    </div>

</body>
</html>
