<?php
        session_start();
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
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        header("Location: https://fichar.lujohotel.es/borrar/CheckInOut.php");
        exit();
} else {
    //Show error message and stay on the same page
        //echo "Incorrect username or password";
        header("Location: login_form.php?error=1");
        exit();
}

//Close connection
mysqli_close($connection);