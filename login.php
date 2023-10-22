<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["password"])) {
        $userPassword = $_POST["password"];
        $storedPassword = "9b1a6de4b68e83d527b539f85ccfce7c00571eb6bd384020c4a7a5a83b1ec784"; 
        $salt = "saltQwoptyu@123";
        $hashedPassword = hash('sha256', $salt . $userPassword);
        echo  $hashedPassword;
        if ($hashedPassword === $storedPassword) {
            session_start();
            $_SESSION['usuario'] = 'Javier'; 
            header('Location: index.php');
            exit;
        } else {
            echo '<div class="alert alert-danger">Contraseña incorrecta</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        .content {
            margin-top: 80px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <h2>Iniciar sesión</h2>
        <form method="post" action="login.php">
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" name="password" id="password" class="form-control" style="width: 300px">
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
