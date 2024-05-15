<?php

include('/etc/config/variables.php');
include('/etc/config/cifrar.php');

$conn = mysqli_connect($db_host, $db_user, $db_password, 'plantillaPersonal');
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $rpassword = $_POST["rpassword"];
    $dni = $_POST["dni"];
    $name = $_POST["name"];
    $apellido = $_POST["apellido"];
    $profesto = $_POST["profesto"];
    $entrytime = $_POST["entrytime"];
    $role = $_POST["role"];
    $adminPass = $_POST["adminpass"];
}

$resultado = cifrarContraseña($usernameEj,$passEj,$clave);

echo $profesto;

if ($password != $rpassword){
        header("Location:addUser.php?passNoRep");
	exit();

}elseif($username=="yondu"){
        header("Location:addUser.php?userRep");
        exit();

}elseif($username=="Bernat" OR $username=="bernat"){
        header("Location:addUser.php?userBan");
        exit();

}elseif($dni=="yondu"){
        header("Location:addUser.php?dniRep");
        exit();

}elseif($adminPass!="@"){
        header("Location:addUser.php?noAdmin");
        exit();

}else{

//$sql = ;




}



?>
