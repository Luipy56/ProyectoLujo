<?php
	include('/etc/config/variables.php');
        $connection=mysqli_connect($db_host,$db_user,$db_password,'plantillaPersonal');


//Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

//Get values from the form
$username = $_POST['username'];
$password = $_POST['password'];

//Query to check login
$sql = "SELECT * FROM Personal WHERE Username='$username' AND Password='$password'";
$result = mysqli_query($connection, $sql);

//Check if a result was found
if (mysqli_num_rows($result) == 1) {
    //Redirect to Wikipedia page
	header("Location: CheckInOut.php");
	exit();
} else {
    //Show error message and stay on the same page
    echo "Incorrect username or password";
}

//Close connection
mysqli_close($connection);

//Limpiar el búfer de salida y eliminar los comentarios






























/*
$clave='hola';
$password='123';
$email = 'pau@pauperis.com';
$first_two = substr($email, 0, 2);
$last_three = substr($email, -3);
$salted_password=$password.'{'.$first_two.$last_three.$clave.'}';
echo base64_encode(hash('sha512', $salted_password, true));
echo '<br>';
echo base64_encode(hash('sha512', $salted_password, true));
echo strlen(base64_encode(hash('sha512', $salted_password, true)));
*/
ob_end_clean();
?>

