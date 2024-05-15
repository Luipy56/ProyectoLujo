<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }
        body{
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('multimedia/bg2.jpg');
            background-size: cover;
            background-position: center;
        }
	.error{color: red; padding-top:20px; text-align: center;}
    </style>
</head>
<body>
    <div class="wrapper">
        <form action="processAddUser.php" method="post">
            <h1>Registrar Nuevo Usuario</h1>
            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
                <i class="bx bxs-user"></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class="bx bxs-lock-alt"></i>
            </div>
	    <div class="input-box">
                <input type="password" name="rpassword" placeholder="Repetir password" required>
                <i class="bx bxs-lock-alt"></i>
            </div>
            <div class="input-box">
                <input type="text" name="dni" placeholder="NIF" required>
            </div>
            <div class="input-box">
                <input type="text" name="name" placeholder="Nombre" required>
            </div>
            <div class="input-box">
                <input type="text" name="apellido" placeholder="Apellido" required>
            </div>
	    <div class="input-box">
                <input type="number" name="profesto" placeholder="Jornada en horas" required min="0" max="8">
            </div>
            <div class="input-box">
                <input type="time" name="entrytime" placeholder="Entra a trabajar (hh:mm:ss)" required>
            </div>
	    <div class="input-box">
		    <select name="role" required>
		        <option value="" disabled selected>Selecciona un rol</option>
		        <option value="Fiambre">Fiambre</option>
		        <option value="Recepción">Recepción</option>
			<option value="Piscina">Piscina</option>
		        <option value="Otros">Otros</option>
		    </select>
       	    </div>

	    <div class="input-box">
                <input type="password" name="adminpass" placeholder="Contraseña de administrador" required>
            </div>

            <button type="submit" class="btn">Login</button>

            <?php
                if (isset($_GET['userRep'])) {
                    echo "<p class='error'>El nombre de usario no está disponible</p>";
		}elseif (isset($_GET['passNoRep'])){
		    echo "<p class='error'>La repetición de la contraseña no es idéntica</p>";
		}elseif (isset($_GET['userBan'])){
                    echo "<p class='error'>El nombre de usuario BERNAT está prohibido</p>";
                }elseif (isset($_GET['dniRep'])){
                    echo "<p class='error'>El NIF no está disponible</p>";
                }elseif (isset($_GET['noAdmin'])){
                    echo "<p class='error'>Contraseña de administrador incorrecta</p>";
                }
            ?>
        </form>
    </div>
</body>
</html>
