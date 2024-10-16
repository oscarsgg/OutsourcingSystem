<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/Outsourcing/config.php');

// Verificar si el usuario está logueado y es una empresa
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'EMP') {
    header("Location: login.php");
    exit();
}

// Obtener el ID de la vacante de la URL
$vacante_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($vacante_id === 0) {
    header("Location: gestionar_vacantes.php");
    exit();
}

// Obtener los detalles de la vacante
$query_vacante = "SELECT v.*, tc.nombre AS tipo_contrato_nombre, tc.descripcion AS tipo_contrato_descripcion, 
                         e.nombre AS nombre_empresa
                  FROM Vacante v
                  JOIN Tipo_Contrato tc ON v.tipo_contrato = tc.codigo
                  JOIN Empresa e ON v.empresa = e.numero
                  WHERE v.numero = ?";
$stmt_vacante = mysqli_prepare($conexion, $query_vacante);
mysqli_stmt_bind_param($stmt_vacante, "i", $vacante_id);
mysqli_stmt_execute($stmt_vacante);
$resultado_vacante = mysqli_stmt_get_result($stmt_vacante);
$vacante = mysqli_fetch_assoc($resultado_vacante);

if (!$vacante) {
    header("Location: gestionar_vacantes.php");
    exit();
}

// Obtener certificaciones requeridas
$query_certificaciones = "SELECT c.nombre, c.descripcion, cu.nombre AS curso_nombre
                         FROM Certificaciones_requeridas cr
                         JOIN Certificacion c ON cr.certificacion = c.codigo
                         JOIN Curso cu ON c.curso = cu.codigo
                         WHERE cr.vacante = ?";
$stmt_certificaciones = mysqli_prepare($conexion, $query_certificaciones);
mysqli_stmt_bind_param($stmt_certificaciones, "i", $vacante_id);
mysqli_stmt_execute($stmt_certificaciones);
$resultado_certificaciones = mysqli_stmt_get_result($stmt_certificaciones);
$certificaciones = mysqli_fetch_all($resultado_certificaciones, MYSQLI_ASSOC);

// Obtener carreras solicitadas
$query_carreras = "SELECT c.nombre
                   FROM Carreras_solicitadas cs
                   JOIN Carrera c ON cs.carrera = c.codigo
                   WHERE cs.vacante = ?";
$stmt_carreras = mysqli_prepare($conexion, $query_carreras);
mysqli_stmt_bind_param($stmt_carreras, "i", $vacante_id);
mysqli_stmt_execute($stmt_carreras);
$resultado_carreras = mysqli_stmt_get_result($stmt_carreras);
$carreras = mysqli_fetch_all($resultado_carreras, MYSQLI_ASSOC);

// Obtener requerimientos
$query_requerimientos = "SELECT descripcion
                         FROM Requerimiento
                         WHERE vacante = ?";
$stmt_requerimientos = mysqli_prepare($conexion, $query_requerimientos);
mysqli_stmt_bind_param($stmt_requerimientos, "i", $vacante_id);
mysqli_stmt_execute($stmt_requerimientos);
$resultado_requerimientos = mysqli_stmt_get_result($stmt_requerimientos);
$requerimientos = mysqli_fetch_all($resultado_requerimientos, MYSQLI_ASSOC);

// Procesar la actualización de la vacante si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $salario = is_numeric($_POST['salario']) ? $_POST['salario'] : 0;
    $es_directo = isset($_POST['es_directo']) ? 1 : 0;
    $cantEmpleados = intval($_POST['cantEmpleados']);
    $fechaInicio = mysqli_real_escape_string($conexion, $_POST['fechaInicio']);
    $fechaCierre = mysqli_real_escape_string($conexion, $_POST['fechaCierre']);
    $estado = isset($_POST['estado']) ? 1 : 0;
    $tipo_contrato = mysqli_real_escape_string($conexion, $_POST['tipo_contrato']);

    $query_update = "UPDATE Vacante SET 
                     titulo = ?, descripcion = ?, salario = ?, es_directo = ?, 
                     cantEmpleados = ?, fechaInicio = ?, fechaCierre = ?, 
                     estado = ?, tipo_contrato = ?
                     WHERE numero = ?";
    $stmt_update = mysqli_prepare($conexion, $query_update);
    mysqli_stmt_bind_param($stmt_update, "ssdiisssssi", $titulo, $descripcion, $salario, $es_directo, 
                           $cantEmpleados, $fechaInicio, $fechaCierre, $estado, $tipo_contrato, $vacante_id);
    
    if (mysqli_stmt_execute($stmt_update)) {
        // Actualización exitosa, recargar los datos de la vacante
        header("Location: detalle_vacante.php?id=" . $vacante_id);
        exit();
    } else {
        $error_message = "Error al actualizar la vacante: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Vacante - TalentBridge</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/detalle_vacante.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <h2>TalentBridge</h2>
            <nav>
                <ul>
                    <li><a href="empresa_dashboard.php"><i class="fas fa-home"></i> Inicio</a></li>
                    <li><a href="perfil_empresa.php"><i class="fas fa-building"></i> Perfil de la Empresa</a></li>
                    <li><a href="publicar_vacante.php"><i class="fas fa-plus-circle"></i> Publicar Vacante</a></li>
                    <li><a href="gestionar_vacantes.php"><i class="fas fa-list-ul"></i> Gestionar Vacantes</a></li>
                    <li><a href="contratos.php"><i class="fas fa-file-contract"></i> Contratos Existentes</a></li>
                    <li><a href="membresia.php"><i class="fas fa-id-card"></i> Membresía</a></li>
                    <li><a href="/Outsourcing/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <div class="vacante-details">
                <div class="vacante-header">
                    <h1 class="vacante-title"><?php echo htmlspecialchars($vacante['titulo']); ?></h1>
                    <button class="btn-edit" onclick="toggleEditForm()">Editar Vacante</button>
                </div>
                <div class="vacante-info">
                    <div class="info-item">
                        <div class="info-label">Descripción:</div>
                        <div class="info-value"><?php echo nl2br(htmlspecialchars($vacante['descripcion'])); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Salario:</div>
                        <div class="info-value">
                            <?php echo $vacante['salario'] ? '$' . number_format($vacante['salario'], 2) : 'No especificado'; ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tipo de Contratación:</div>
                        <div class="info-value"><?php echo $vacante['es_directo'] ? 'Directa' : 'Indirecta'; ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Cantidad de Empleados:</div>
                        <div class="info-value"><?php echo $vacante['cantEmpleados']; ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Fecha de Inicio:</div>
                        <div class="info-value"><?php echo date('d/m/Y', strtotime($vacante['fechaInicio'])); ?></div>
                    </div>
                
                    <div class="info-item">
                        <div class="info-label">Fecha de Cierre:</div>
                        <div class="info-value"><?php echo date('d/m/Y', strtotime($vacante['fechaCierre'])); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Días Restantes:</div>
                        <div class="info-value"><?php echo $vacante['diasRestantes']; ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Estado:</div>
                        <div class="info-value"><?php echo $vacante['estado'] ? 'Activa' : 'Inactiva'; ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tipo de Contrato:</div>
                        <div class="info-value"><?php echo htmlspecialchars($vacante['tipo_contrato_nombre']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Descripción del Contrato:</div>
                        <div class="info-value"><?php echo htmlspecialchars($vacante['tipo_contrato_descripcion']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Empresa:</div>
                        <div class="info-value"><?php echo htmlspecialchars($vacante['nombre_empresa']); ?></div>
                    </div>
                </div>

                <?php if (!empty($certificaciones)): ?>
                    <h2 class="section-title">Certificaciones Requeridas</h2>
                    <ul>
                        <?php foreach ($certificaciones as $cert): ?>
                            <li class="list-item">
                                <?php echo htmlspecialchars($cert['nombre']); ?> 
                                (<?php echo htmlspecialchars($cert['curso_nombre']); ?>)
                                <br>
                                <small><?php echo htmlspecialchars($cert['descripcion']); ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if (!empty($carreras)): ?>
                    <h2 class="section-title">Carreras Solicitadas</h2>
                    <ul>
                        <?php foreach ($carreras as $carrera): ?>
                            <li class="list-item"><?php echo htmlspecialchars($carrera['nombre']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if (!empty($requerimientos)): ?>
                    <h2 class="section-title">Requerimientos Adicionales</h2>
                    <ul>
                        <?php foreach ($requerimientos as $req): ?>
                            <li class="list-item"><?php echo htmlspecialchars($req['descripcion']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <div id="editForm" class="edit-form">
                    <h2>Editar Vacante</h2>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="titulo">Título:</label>
                            <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($vacante['titulo']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción:</label>
                            <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($vacante['descripcion']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="salario">Salario:</label>
                            <input type="number" id="salario" name="salario" value="<?php echo $vacante['salario']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="es_directo">Tipo de Contratación:</label>
                            <select id="es_directo" name="es_directo">
                                <option value="1" <?php echo $vacante['es_directo'] ? 'selected' : ''; ?>>Directa</option>
                                <option value="0" <?php echo !$vacante['es_directo'] ? 'selected' : ''; ?>>Indirecta</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cantEmpleados">Cantidad de Empleados:</label>
                            <input type="number" id="cantEmpleados" name="cantEmpleados" value="<?php echo $vacante['cantEmpleados']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="fechaInicio">Fecha de Inicio:</label>
                            <input type="date" id="fechaInicio" name="fechaInicio" value="<?php echo $vacante['fechaInicio']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="fechaCierre">Fecha de Cierre:</label>
                            <input type="date" id="fechaCierre" name="fechaCierre" value="<?php echo $vacante['fechaCierre']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado:</label>
                            <select id="estado" name="estado">
                                <option value="1" <?php echo $vacante['estado'] ? 'selected' : ''; ?>>Activa</option>
                                <option value="0" <?php echo !$vacante['estado'] ? 'selected' : ''; ?>>Inactiva</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tipo_contrato">Tipo de Contrato:</label>
                            <input type="text" id="tipo_contrato" name="tipo_contrato" value="<?php echo htmlspecialchars($vacante['tipo_contrato']); ?>" required>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn-cancel" onclick="toggleEditForm()">Cancelar</button>
                            <button type="submit" class="btn-save">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleEditForm() {
            var form = document.getElementById('editForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>