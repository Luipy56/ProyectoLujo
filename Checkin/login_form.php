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
    </style>
</head>
<body>
    <div class="wrapper">
        <form action="processLogin.php" method="post">
            <h1>Login</h1>
            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
                <i class="bx bxs-user"></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class="bx bxs-lock-alt"></i>
            </div>
            <div class="remember-forgot">
                <!-- <label><input type="checkbox"> Remember me</label> -->
                <a href="forgotten.html">¿Contraseña olvidada?</a>
            </div>
            <button type="submit" class="btn">Login</button>
        <?php
                if (isset($_GET['error'])) {
                    echo "<p style='color: red; padding-top:20px; text-align: center;'>Contraseña y/o usuario incorrectos</p>";
                }
        ?>
        </form>
    </div>
</body>
</html>
