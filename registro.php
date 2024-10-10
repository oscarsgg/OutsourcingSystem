<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo_registro = $_POST['tipo_registro'];
    
    if ($tipo_registro == 'prospecto') {
        // Procesar registro de prospecto
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $primerApellido = mysqli_real_escape_string($conexion, $_POST['primerApellido']);
        $segundoApellido = mysqli_real_escape_string($conexion, $_POST['segundoApellido']);
        $fechaNacimiento = mysqli_real_escape_string($conexion, $_POST['fechaNacimiento']);
        $resumen = mysqli_real_escape_string($conexion, $_POST['resumen']);
        $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
        $contrasenia = mysqli_real_escape_string($conexion, $_POST['contrasenia']);
        
        // Insertar en la tabla Usuario
        $query_usuario = "INSERT INTO Usuario (correo, contrasenia, rol) VALUES (?, ?, 'PRO')";
        $stmt_usuario = mysqli_prepare($conexion, $query_usuario);
        mysqli_stmt_bind_param($stmt_usuario, "ss", $correo, $contrasenia);
        
        if (mysqli_stmt_execute($stmt_usuario)) {
            $id_usuario = mysqli_insert_id($conexion);
            
            // Insertar en la tabla Prospecto
            $query_prospecto = "INSERT INTO Prospecto (nombre, primerApellido, segundoApellido, resumen, fechaNacimiento, usuario) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_prospecto = mysqli_prepare($conexion, $query_prospecto);
            mysqli_stmt_bind_param($stmt_prospecto, "sssssi", $nombre, $primerApellido, $segundoApellido, $resumen, $fechaNacimiento, $id_usuario);
            
            if (mysqli_stmt_execute($stmt_prospecto)) {
                $success = "Registro de prospecto exitoso";
            } else {
                $error = "Error al registrar el prospecto";
            }
        } else {
            $error = "Error al crear el usuario";
        }
    } elseif ($tipo_registro == 'empresa') {
        // Procesar registro de empresa
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $ciudad = mysqli_real_escape_string($conexion, $_POST['ciudad']);
        $calle = mysqli_real_escape_string($conexion, $_POST['calle']);
        $numeroCalle = mysqli_real_escape_string($conexion, $_POST['numeroCalle']);
        $colonia = mysqli_real_escape_string($conexion, $_POST['colonia']);
        $codigoPostal = mysqli_real_escape_string($conexion, $_POST['codigoPostal']);
        $nombreCont = mysqli_real_escape_string($conexion, $_POST['nombreCont']);
        $primerApellidoCont = mysqli_real_escape_string($conexion, $_POST['primerApellidoCont']);
        $segundoApellidoCont = mysqli_real_escape_string($conexion, $_POST['segundoApellidoCont']);
        $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
        $contrasenia = mysqli_real_escape_string($conexion, $_POST['contrasenia']);
        
        // Insertar en la tabla Usuario
        $query_usuario = "INSERT INTO Usuario (correo, contrasenia, rol) VALUES (?, ?, 'EMP')";
        $stmt_usuario = mysqli_prepare($conexion, $query_usuario);
        mysqli_stmt_bind_param($stmt_usuario, "ss", $correo, $contrasenia);
        
        if (mysqli_stmt_execute($stmt_usuario)) {
            $id_usuario = mysqli_insert_id($conexion);
            
            // Insertar en la tabla Empresa
            $query_empresa = "INSERT INTO Empresa (nombre, ciudad, calle, numeroCalle, colonia, codigoPostal, nombreCont, primerApellidoCont, segundoApellidoCont, usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_empresa = mysqli_prepare($conexion, $query_empresa);
            mysqli_stmt_bind_param($stmt_empresa, "sssisssssi", $nombre, $ciudad, $calle, $numeroCalle, $colonia, $codigoPostal, $nombreCont, $primerApellidoCont, $segundoApellidoCont, $id_usuario);
            
            if (mysqli_stmt_execute($stmt_empresa)) {
                $success = "Registro de empresa exitoso";
            } else {
                $error = "Error al registrar la empresa";
            }
        } else {
            $error = "Error al crear el usuario";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - TalentBridge</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        .success {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registro en TalentBridge</h1>
        
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php else: ?>
        
        <form id="tipoRegistroForm">
            <label for="tipo_registro">Seleccione el tipo de registro:</label>
            <select id="tipo_registro" name="tipo_registro">
                <option value="">Seleccione una opción</option>
                <option value="prospecto">Prospecto</option>
                <option value="empresa">Empresa</option>
            </select>
        </form>

        <form id="registroProspectoForm" style="display:none;" method="POST">
            <input type="hidden" name="tipo_registro" value="prospecto">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="primerApellido">Primer Apellido:</label>
            <input type="text" id="primerApellido" name="primerApellido" required>
            
            <label for="segundoApellido">Segundo Apellido:</label>
            <input type="text" id="segundoApellido" name="segundoApellido">
            
            <label for="fechaNacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fechaNacimiento" name="fechaNacimiento" required>
            
            <label for="resumen">Resumen Profesional:</label>
            <textarea id="resumen" name="resumen" rows="4"></textarea>
            
            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" required>
            
            <label for="contrasenia">Contraseña:</label>
            <input type="password" id="contrasenia" name="contrasenia" required>
            
            <input type="submit" value="Registrarse como Prospecto">
        </form>

        <form id="registroEmpresaForm" style="display:none;" method="POST">
            <input type="hidden" name="tipo_registro" value="empresa">
            <label for="nombre">Nombre de la Empresa:</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="ciudad">Ciudad:</label>
            <input type="text" id="ciudad" name="ciudad" required>
            
            <label for="calle">Calle:</label>
            <input type="text" id="calle" name="calle" required>
            
            <label for="numeroCalle">Número de Calle:</label>
            <input type="number" id="numeroCalle" name="numeroCalle" required>
            
            <label for="colonia">Colonia:</label>
            <input type="text" id="colonia" name="colonia" required>
            
            <label for="codigoPostal">Código Postal:</label>
            <input type="number" id="codigoPostal" name="codigoPostal" required>
            
            <label for="nombreCont">Nombre del Contacto:</label>
            <input type="text" id="nombreCont" name="nombreCont" required>
            
            <label for="primerApellidoCont">Primer Apellido del Contacto:</label>
            <input type="text" id="primerApellidoCont" name="primerApellidoCont" required>
            
            <label for="segundoApellidoCont">Segundo Apellido del Contacto:</label>
            <input type="text" id="segundoApellidoCont" name="segundoApellidoCont">
            
            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" required>
            
            <label for="contrasenia">Contraseña:</label>
            <input type="password" id="contrasenia" name="contrasenia" required>
            
            <input type="submit" value="Registrarse como Empresa">
        </form>
        
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('tipo_registro').addEventListener('change', function() {
            var tipoRegistro = this.value;
            document.getElementById('registroProspectoForm').style.display = 'none';
            document.getElementById('registroEmpresaForm').style.display = 'none';
            if (tipoRegistro === 'prospecto') {
                document.getElementById('registroProspectoForm').style.display = 'block';
            } else if (tipoRegistro === 'empresa') {
                document.getElementById('registroEmpresaForm').style.display = 'block';
            }
        });
    </script>
</body>
</html>