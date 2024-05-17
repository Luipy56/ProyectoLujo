<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Fichar</title>
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
	.redp{color: red; padding-top:20px; text-align: center;}
    </style>
</head>
<body>
    <div class="wrapper">
        <form action="processLogin.php" method="post">
            <h1>Login Fichar</h1>
            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
                <i class="bx bxs-user"></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class="bx bxs-lock-alt"></i>
            </div>
            <div class="remember-forgot">
                <a href="forgotten.html">¿Contraseña olvidada?</a>
            </div>
            <div class="input-box">
                <input type="text" name="adminpasslogin" placeholder="Contraseña de DEMO" required>
            </div>
            <button type="submit" class="btn">Login</button>
        <?php
                if (isset($_GET['error'])) {
                    echo "<p class='redp'>Contraseña y/o usuario incorrectos</p>";
                }elseif (isset($_GET['noSession'])){
			echo "<p class='redp'>Primero inicie sesión</p>";
		}elseif (isset($_GET['noAdmin'])){
                        echo "<p class='redp'>Contraseña de DEMO incorrecta</p>";
                }
        ?>
        </form>
    </div>
</body>
</html>
