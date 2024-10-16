<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/Outsourcing/config.php');

// Verificar si el usuario está logueado y es una empresa
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'EMP') {
    header("Location: login.php");
    exit();
}

// Obtener el número de la empresa
$query_empresa = "SELECT numero FROM Empresa WHERE usuario = ?";
$stmt_empresa = mysqli_prepare($conexion, $query_empresa);
mysqli_stmt_bind_param($stmt_empresa, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt_empresa);
$resultado_empresa = mysqli_stmt_get_result($stmt_empresa);
$empresa = mysqli_fetch_assoc($resultado_empresa);
$numero_empresa = $empresa['numero'];

// Obtener tipos de contrato
$query_tipos_contrato = "SELECT codigo, nombre FROM Tipo_Contrato";
$resultado_tipos_contrato = mysqli_query($conexion, $query_tipos_contrato);
$tipos_contrato = mysqli_fetch_all($resultado_tipos_contrato, MYSQLI_ASSOC);

// Obtener certificaciones
$query_certificaciones = "SELECT c.codigo, c.nombre, cu.nombre AS curso_nombre FROM Certificacion c JOIN Curso cu ON c.curso = cu.codigo";
$resultado_certificaciones = mysqli_query($conexion, $query_certificaciones);
$certificaciones = mysqli_fetch_all($resultado_certificaciones, MYSQLI_ASSOC);

// Obtener carreras
$query_carreras = "SELECT codigo, nombre FROM Carrera";
$resultado_carreras = mysqli_query($conexion, $query_carreras);
$carreras = mysqli_fetch_all($resultado_carreras, MYSQLI_ASSOC);

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y sanitizar los datos del formulario
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $salario = isset($_POST['salario']) ? intval($_POST['salario']) : null;
    $es_directo = $_POST['es_directo'] == '1' ? 1 : 0;
    $cantEmpleados = intval($_POST['cantEmpleados']);
    $fechaInicio = mysqli_real_escape_string($conexion, $_POST['fechaInicio']);
    $fechaCierre = mysqli_real_escape_string($conexion, $_POST['fechaCierre']);
    $tipo_contrato = mysqli_real_escape_string($conexion, $_POST['tipo_contrato']);
    $certificaciones_requeridas = isset($_POST['certificaciones']) ? $_POST['certificaciones'] : [];
    $carreras_solicitadas = isset($_POST['carreras']) ? $_POST['carreras'] : [];
    $requerimientos = isset($_POST['requerimientos']) ? $_POST['requerimientos'] : [];

    // Validar fechas
    $fecha_actual = new DateTime();
    $fecha_inicio = new DateTime($fechaInicio);
    $fecha_cierre = new DateTime($fechaCierre);

    if ($fecha_inicio < $fecha_actual) {
        $mensaje = "Error: La fecha de inicio debe ser posterior a la fecha actual.";
    } elseif ($fecha_cierre <= $fecha_inicio) {
        $mensaje = "Error: La fecha de cierre debe ser posterior a la fecha de inicio.";
    } else {
        // Calcular días restantes
        $diferencia = $fecha_actual->diff($fecha_cierre);
        $diasRestantes = $diferencia->days;

        // Iniciar transacción
        mysqli_begin_transaction($conexion);

        try {
            // Insertar la vacante
            $query_insertar = "INSERT INTO Vacante (titulo, descripcion, salario, es_directo, cantEmpleados, fechaInicio, fechaCierre, diasRestantes, estado, tipo_contrato, empresa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?)";
            $stmt_insertar = mysqli_prepare($conexion, $query_insertar);
            mysqli_stmt_bind_param($stmt_insertar, "sssiissisi", $titulo, $descripcion, $salario, $es_directo, $cantEmpleados, $fechaInicio, $fechaCierre, $diasRestantes, $tipo_contrato, $numero_empresa);
            mysqli_stmt_execute($stmt_insertar);
            
            $vacante_id = mysqli_insert_id($conexion);

            // Insertar certificaciones requeridas
            if (!empty($certificaciones_requeridas)) {
                $query_cert = "INSERT INTO Certificaciones_requeridas (vacante, certificacion) VALUES (?, ?)";
                $stmt_cert = mysqli_prepare($conexion, $query_cert);
                foreach ($certificaciones_requeridas as $cert) {
                    mysqli_stmt_bind_param($stmt_cert, "is", $vacante_id, $cert);
                    mysqli_stmt_execute($stmt_cert);
                }
            }

            // Insertar carreras solicitadas
            if (!empty($carreras_solicitadas)) {
                $query_carrera = "INSERT INTO Carreras_solicitadas (vacante, carrera) VALUES (?, ?)";
                $stmt_carrera = mysqli_prepare($conexion, $query_carrera);
                foreach ($carreras_solicitadas as $carrera) {
                    mysqli_stmt_bind_param($stmt_carrera, "is", $vacante_id, $carrera);
                    mysqli_stmt_execute($stmt_carrera);
                }
            }

            // Insertar requerimientos
            if (!empty($requerimientos)) {
                $query_req = "INSERT INTO Requerimiento (descripcion, vacante) VALUES (?, ?)";
                $stmt_req = mysqli_prepare($conexion, $query_req);
                foreach ($requerimientos as $req) {
                    if (!empty($req)) {
                        mysqli_stmt_bind_param($stmt_req, "si", $req, $vacante_id);
                        mysqli_stmt_execute($stmt_req);
                    }
                }
            }

            // Confirmar transacción
            mysqli_commit($conexion);
            $mensaje = "Vacante publicada con éxito.";
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            mysqli_rollback($conexion);
            $mensaje = "Error al publicar la vacante: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Vacante - TalentBridge</title>
    <link rel="stylesheet" href="css/publicar_vacante.css">
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>TalentBridge</h2>
            </div>
            <nav class="sidebar-nav">
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
            <header class="main-header">
                <button id="toggleSidebar" class="toggle-sidebar-btn">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>Publicar Nueva Vacante</h1>
            </header>
            <?php if ($mensaje): ?>
                <div class="mensaje <?php echo strpos($mensaje, 'éxito') !== false ? 'exito' : 'error'; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
            <section class="vacante-form">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label for="titulo">Título de la Vacante:</label>
                        <input type="text" id="titulo" name="titulo" required maxlength="30">
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" name="descripcion" required maxlength="250"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="salario">Salario (opcional):</label>
                        <input type="number" id="salario" name="salario">
                    </div>
                    <div class="form-group">
                        <label>¿Es contratación directa?</label>
                        <div class="radio-group">
                            <input type="radio" id="es_directo_si" name="es_directo" value="1" required>
                            <label for="es_directo_si">Sí</label>
                            <input type="radio" id="es_directo_no" name="es_directo" value="0" required>
                            <label for="es_directo_no">No</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cantEmpleados">Cantidad de Empleados:</label>
                        <input type="number" id="cantEmpleados" name="cantEmpleados" required min="1">
                    </div>
                    <div class="form-group">
                        <label for="fechaInicio">Fecha de Inicio:</label>
                        <input type="date" id="fechaInicio" name="fechaInicio" required>
                    </div>
                    <div class="form-group">
                        <label for="fechaCierre">Fecha de Cierre:</label>
                        <input type="date" id="fechaCierre" name="fechaCierre" required>
                    </div>
                    <div class="form-group">
                        <label for="tipo_contrato">Tipo de Contrato:</label>
                        <select id="tipo_contrato" name="tipo_contrato" required>
                            <?php foreach ($tipos_contrato as $tipo): ?>
                                <option value="<?php echo htmlspecialchars($tipo['codigo']); ?>">
                                    <?php echo htmlspecialchars($tipo['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Certificaciones Requeridas:</label>
                        <div class="checkbox-container" id="certificaciones-container">
                            <input type="text" id="certificacion-search" placeholder="Buscar certificación...">
                            <div class="checkbox-scroll">
                                <?php foreach ($certificaciones as $cert): ?>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="cert_<?php echo $cert['codigo']; ?>" name="certificaciones[]" value="<?php echo $cert['codigo']; ?>">
                                        <label for="cert_<?php echo $cert['codigo']; ?>">
                                            <?php echo htmlspecialchars($cert['nombre'] . ' (' . $cert['curso_nombre'] . ')'); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Carreras Solicitadas:</label>
                        <div class="checkbox-container" id="carreras-container">
                            <input type="text" id="carrera-search" placeholder="Buscar carrera...">
                            <div class="checkbox-scroll">
                                <?php foreach ($carreras as $carrera): ?>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="carrera_<?php echo $carrera['codigo']; ?>" name="carreras[]" value="<?php echo $carrera['codigo']; ?>">
                                        <label for="carrera_<?php echo $carrera['codigo']; ?>">
                                            <?php echo htmlspecialchars($carrera['nombre']); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Requerimientos:</label>
                        <div id="requerimientos-container">
                            <div class="requerimiento-item">
                                <input type="text" name="requerimientos[]" placeholder="Ingrese un requerimiento">
                                <button type="button" class="btn-add-requerimiento">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">Publicar Vacante</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');

            toggleBtn.addEventListener('click', function() {
                sidebar.style.display = sidebar.style.display === 'none' ? 'block' : 'none';
                mainContent.style.marginLeft = sidebar.style.display === 'none' ? '0' : '250px';
            });

            // Funcionalidad para agregar requerimientos dinámicamente
            const requerimientosContainer = document.getElementById('requerimientos-container');
            const addRequerimientoBtn = requerimientosContainer.querySelector('.btn-add-requerimiento');

            addRequerimientoBtn.addEventListener('click', function() {
                const newRequerimiento = document.createElement('div');
                newRequerimiento.className = 'requerimiento-item';
                newRequerimiento.innerHTML = `
                    <input type="text" name="requerimientos[]" placeholder="Ingrese un requerimiento">
                    <button type="button" class="btn-remove-requerimiento">-</button>
                `;
                requerimientosContainer.insertBefore(newRequerimiento, this.parentNode);
            });

            requerimientosContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-remove-requerimiento')) {
                    e.target.parentNode.remove();
                }
            });

            // Funcionalidad de búsqueda para certificaciones
            const certificacionSearch = document.getElementById('certificacion-search');
            const certificacionItems = document.querySelectorAll('#certificaciones-container .checkbox-item');

            certificacionSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                certificacionItems.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });

            // Funcionalidad de búsqueda para carreras
            const carreraSearch = document.getElementById('carrera-search');
            const carreraItems = document.querySelectorAll('#carreras-container .checkbox-item');

            carreraSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                carreraItems.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });

            // Validación de fechas
            const fechaInicio = document.getElementById('fechaInicio');
            const fechaCierre = document.getElementById('fechaCierre');

            fechaInicio.addEventListener('change', validateDates);
            fechaCierre.addEventListener('change', validateDates);

            function validateDates() {
                const start = new Date(fechaInicio.value);
                const end = new Date(fechaCierre.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                if (start < today) {
                    alert('La fecha de inicio debe ser posterior a la fecha actual.');
                    fechaInicio.value = '';
                }

                if (end <= start) {
                    alert('La fecha de cierre debe ser posterior a la fecha de inicio.');
                    fechaCierre.value = '';
                }
            }
        });
    </script>
</body>
</html>