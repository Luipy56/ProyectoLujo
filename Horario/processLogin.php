<?php
        session_start();
        include('/etc/config/variables.php');
	include('/etc/config/cifrar.php');
        $connection=mysqli_connect($db_host,$db_user,$db_password,'plantillaPersonal');


//Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

//Get values from the form
$username = $_POST['username'];
$password = $_POST['password'];
$adminPassLogin= $_POST['adminpasslogin'];

if ($adminPassLogin != "@"){
	header("Location: https://horario.lujohotel.es/?noAdmin");
	exit();
}

$passCifrada = cifrarContraseña($username,$password,$clave);
echo $passCifrada;
//die();
//Query to check login
$sql = "SELECT * FROM Personal WHERE Username='$username' AND Password='$passCifrada'";
$result = mysqli_query($connection, $sql); //Función

if ($result && mysqli_num_rows($result) > 0) {
    echo "Inicio de sesión exitoso.";
} else {
    // Credenciales incorrectas, redirigir
    header("Location: https://horario.lujohotel.es/?error=1");
    exit();
}


//Comprobar si es un usuario administrador
$sql = "SELECT COUNT(*) AS count FROM Personal WHERE Username = '$username' AND Role = 'Administrator'";
$result = $connection->query($sql); //Método del objeto

if ($result) {
    // Obtener el resultado de la consulta
    $row = $result->fetch_assoc();

    // Verificar si el resultado es mayor que cero (es decir, al menos una fila coincide)
    if ($row["count"] > 0) {
        //tiene el rol de 'Administrador'
	    $_SESSION['username'] = $username;
        header("Location:horarioAdmin.php");
	exit();
    }
} else {
    echo "Error al ejecutar la consulta: " . $connection->error;
}


//Check if a result was found (contraseña y usuario correctos)
if (mysqli_num_rows($result) == 1) {
        $_SESSION['username'] = $username;
        header("Location:horario.php");
        exit();
} else {
    //Show error message and stay on the same page
        //echo "Incorrect username or password";
        header("Location: https://horario.lujohotel.es/?error=1");
        exit();
}

//Close connection
mysqli_close($connection);
