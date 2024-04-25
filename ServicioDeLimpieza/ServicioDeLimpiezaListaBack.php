$counter = 0;
foreach ($habitaciones as $numeroHabitacion) {
        if ($numeroHabitacion[0] != 4 or in_array($numeroHabitacion, $arrayNumHabitacionesVip) or end($arrayNumHabitacionesVip)+1) {

                if (($counter % 12 == 0 && $counter != 0) || $numeroHabitacion == 403){
                        echo '</div>';
                        echo '</div></div>';
                        echo '<div class="tablaPuntos"><div class="colspan">';
                }elseif ($counter % 6 == 0 && $counter != 0 && $counter % 12 != 0) {
                        echo '<div class="celda"></div>';
                }
        $counter++;

/*FechaIn*/     $conexionCheckInsPorHabitacion = consultaEstandarFechas($numeroHabitacion, 'check_in_date', 'mphb_check_in_date', $conexion);
/*FechaOut*/    $conexionCheckOutsPorHabitacion = consultaEstandarFechas($numeroHabitacion, 'check_out_date', 'mphb_check_out_date', $conexion);

        $fechasCheckIns = array();
        while ($fila = mysqli_fetch_assoc($conexionCheckInsPorHabitacion)) {
           $fechasCheckIns[] = $fila['check_in_date'];
        }
        $fechasCheckOuts = array();
        while ($fila = mysqli_fetch_assoc($conexionCheckOutsPorHabitacion)) {
           $fechasCheckOuts[] = $fila['check_out_date'];
        }
        $coincidenciaEnFechas = comprobarCoincidencia($fechasCheckIns, $fechasCheckOuts, '0');
        $indiceFechasInOut= comprobarCoincidencia($fechasCheckIns, $fechasCheckOuts, '1');

/*Nombre*/      $nameCustomer = consultaEstandar($numeroHabitacion, 'nameCustomer', 'mphb_first_name', $conexion, $fechasCheckIns[$indiceFechasInOut]);
/*Mail*/        $mailCustomer = consultaEstandar($numeroHabitacion, 'mailCustomer', 'mphb_email', $conexion, $fechasCheckIns[$indiceFechasInOut]);
/*NºTel*/       $numCustomer = consultaEstandar($numeroHabitacion, 'numCustomer', 'mphb_phone', $conexion, $fechasCheckIns[$indiceFechasInOut]);
/*Nota*/        $notaCustomer = consultaEstandar($numeroHabitacion, 'notaCustomer', 'mphb_note', $conexion, $fechasCheckIns[$indiceFechasInOut]);

        //zona de creación de celdas
        echo '<div class="celda"><div class="dot ' . ($coincidenciaEnFechas == 1 ? 'redDot' : ($coincidenciaEnFechas == 2 ? 'yellowDot' : 'greenDot')) . '"';
        if ($coincidenciaEnFechas) {
        echo ' onclick="mostrarAlerta(\'' . $fechasCheckIns[$indiceFechasInOut] . '\', \'' . $fechasCheckOuts[$indiceFechasInOut] . '\', \'' . $nameCustomer . '\', \'' . $numCustomer . '\', \'' . $mailCustomer . '\', \'' . $notaCustomer . '\')"';}
        echo '></div><div class="dotText">' . $numeroHabitacion . '</div></div>';


}}