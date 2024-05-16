<?php
  /*Se incluye un archivo llamado variables que contiene información sobre la base de datos */
        include('/etc/config/variables.php');
  /*Se establece la conexión con dos base de datos*/
        $conn=mysqli_connect($db_host,$db_user,$db_password,"plantillaPersonal");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horario Hotel Lujo</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php
session_start();
// Verificar si las variables de sesión están establecidas
if (isset($_SESSION['username'])) {
      $username = $_SESSION['username'];
      
  } else {

      header("Location: login_form.php?noSession");
      exit();
  }
//Sacar DNI (ya que se almacena por DNI en el CheckInOut)
$sql = "select DNI FROM Personal where Username = 'yondu';";
$result = $conn->query($sql);
if ($result->rowCount() > 0) {
      $dni = $result->fetchColumn();
  } else {
      die();
  }


//Preparo $d para mapear un mes entero
for ($d = 1; $d <= 31; $d++){
      //Abro div principal diario
      echo "<h2>Día $d</h2>";
      echo "<div class='dia'>";

            //Básicamente coge el in y el out y calcula la diferencia redondeandola, si no hay OUT pilla la hora actual.
            //Además pilla la hora del CheckIn
            $sql ="SELECT HOUR(CheckIn) AS HoraCheckIn,
                  ROUND(HOUR(TIMEDIFF(IFNULL(CheckOut, NOW()), CheckIn)) + (MINUTE(TIMEDIFF(IFNULL(CheckOut, NOW()), CheckIn)) / 60)) AS DiferenciaHorasRedondeada
                  FROM CheckInOut
                  WHERE DAY(CheckIn) = '$d' AND DNI = '$dni'";
                  /*Esta consulta selecciona la diferencia de horas entre el momento de CheckIn y el momento de CheckOut en la tabla CheckInOut.
                  Pero, si el valor de CheckOut es nulo (es decir, si el usuario aún no ha realizado el CheckOut),
                  se reemplaza con la fecha y hora actuales utilizando la función NOW().
                  Luego, calcula la diferencia de tiempo utilizando TIMEDIFF() entre el valor de CheckOut (o la fecha y hora actuales si CheckOut es nulo) y
                  CheckIn. A continuación, extrae la parte de la hora de esta diferencia utilizando HOUR(), y la parte de los minutos utilizando MINUTE().
                  Después, divide los minutos entre 60 para obtener la fracción de hora y los suma a las horas. Finalmente, redondea este valor utilizando ROUND()
                  y lo devuelve como DiferenciaHorasRedondeada.*/


            $result = $conn->query($sql);
            //Comprobar los resultados y almacenar en array
            if ($result->rowCount() > 0) {
                  //Obtener todos los resultados en forma de array asociativo (dickionario)
                  $resultsArray = $result->fetchAll(PDO::FETCH_ASSOC);
                  
                  //Procesar cada fila
                  foreach ($resultsArray as $row) {
                      $checkIn = $row['HoraCheckIn'];
                      $diferenciaHoras = $row['DiferenciaHorasRedondeada'];
                      
                  }
            }
            //Creamos los divs simulando las horas del día
            for ($i = 1; $i <= 24; $i++){
                  foreach ($resultsArray as $row) {

                        if ($row['HoraCheckIn'] == $i){
                              
                        }

                  }



                  if(
                        foreach ($resultsArray as $row) {
                              $row['HoraCheckIn'] == $i;
                              }
                  ){
                        for ($i = 1; $i <= 24; $i++){
                        echo "<div class='hora'>H$i</div>";
                        }

                  }else{echo "<div class='hora'>H$i</div>";}
            }
      echo "</div>";
      echo "<div class='espacio'>Espacio</div>";

}
?>

</body>
</html>
