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

$passCifrada = cifrarContraseña($username,$password,$clave);


$sql = "SELECT Username FROM Personal";
$result = $conn->query($sql);

$arrayTrabajadores = array();

//Verificar si la consulta devolvió resultados
if ($result->num_rows > 0) {
    //Recorrer cada fila del resultado y agregar el nombre de usuario al array
    while($row = $result->fetch_assoc()) {
        $arrayTrabajadores[] = $row['Username'];
    }
} else {
    echo "Error al buscar trabajadores.";
	die();
}

$sql = "SELECT DNI FROM Personal";
$result = $conn->query($sql);

$arrayDNIs = array();

//Verificar si la consulta devolvió resultados
if ($result->num_rows > 0) {
    //Recorrer cada fila del resultado y agregar el nombre de usuario al array
    while($row = $result->fetch_assoc()) {
        $arrayDNIs[] = $row['DNI'];
    }
} else {
    echo "Error al buscar DNIs.";
        die();
}


if ($password != $rpassword){
        header("Location:addUser.php?passNoRep");
	exit();

}elseif(in_array($password, $arrayTrabajadores)){
        header("Location:addUser.php?userRep");
        exit();

}elseif($username=="Bernat" OR $username=="bernat" OR $name == "Bernat" OR $name == "bernat"){
        header("Location:addUser.php?userBan");
        exit();

}elseif(in_array($password, $arrayTrabajadores)){
        header("Location:addUser.php?dniRep");
        exit();

}elseif($adminPass!="@"){
        header("Location:addUser.php?noAdmin");
        exit();

}

$sql = "INSERT INTO Personal (DNI, Name, LName, Username, Password, Profesto, Role, EntryTime) VALUES ('$dni', '$name', '$apellido', '$username', '$passCifrada', '$profesto', '$role', '$entrytime')";

if ($conn->query($sql) === TRUE) {
    echo "Nuevo registro creado exitosamente";
	header("Location:hechoAddUser.html");
        exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
	header("Location:addUser.php");
	exit();
}

/*
$sql = "INSERT INTO Personal (DNI, Name, LName, Username, Password, Profesto, Role, EntryTime) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

// Preparar la declaración
$stmt = $conn->prepare($sql);

// Verificar si la preparación fue exitosa
if ($stmt) {
    // Vincular los parámetros
    $stmt->bind_param("ssssssss", $dni, $name, $apellido, $username, $passCifrada, $profesto, $role, $entryTime);

    // Ejecutar la declaración
    if ($stmt->execute()) {
        echo "Nuevo registro creado exitosamente.";
        header("Location:hechoAddUser.html")
        exit();
    } else {
        echo "Error: " . $stmt->error;
        header("Location:addUser.php?inyect");
        exit();
    }

    // Cerrar la declaración
    $stmt->close();
} else {
    echo "Error en la preparación de la consulta: " . $conn->error;
        header("Location:addUser.php?inyect");
        exit();
}
*/








?>
