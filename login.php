<?php
session_start();

// Si el usuario ya está logueado, redirigir al dashboard correspondiente
if (isset($_SESSION['user_id'])) {
    switch ($_SESSION['user_role']) {
        case 'PRO':
            header("Location: user/prospecto/prospecto_dashboard.php");
            break;
        case 'EMP':
            header("Location: user/empresa/empresa_dashboard.php");
            break;
        case 'ADM':
            header("Location: user/admin/admin_dashboard.php");
            break;
    }
    exit();
}

require_once 'config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $contrasenia = mysqli_real_escape_string($conexion, $_POST['contrasenia']);
    
    $query = "SELECT numero, rol FROM Usuario WHERE correo = ? AND contrasenia = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "ss", $correo, $contrasenia);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    
    if ($usuario = mysqli_fetch_assoc($resultado)) {
        $_SESSION['user_id'] = $usuario['numero'];
        $_SESSION['user_role'] = $usuario['rol'];
        
        // Establecer una cookie de sesión con tiempo de vida limitado (por ejemplo, 30 minutos)
        session_set_cookie_params(1800); // 30 minutos en segundos
        session_regenerate_id(true); // Regenerar el ID de sesión por seguridad
        
        switch ($usuario['rol']) {
            case 'PRO':
                header("Location: user/prospecto/prospecto_dashboard.php");
                break;
            case 'EMP':
                header("Location: user/empresa/empresa_dashboard.php");
                break;
            case 'ADM':
                header("Location: user/admin/admin_dashboard.php");
                break;
        }
        exit();
    } else {
        $error = "Correo o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - TalentBridge</title>
    <link rel="stylesheet" href="css/login.css">
    <style>
        
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" required>
            
            <label for="contrasenia">Contraseña:</label>
            <input type="password" id="contrasenia" name="contrasenia" required>
            
            <input type="submit" value="Iniciar Sesión">
        </form>
    </div>
</body>
</html>