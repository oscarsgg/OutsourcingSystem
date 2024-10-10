<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/Outsourcing/config.php');

// Verificar si el usuario está logueado y es un prospecto
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'PRO') {
    header("Location: login.php");
    exit();
}

// Obtener información del prospecto
$query_prospecto = "SELECT * FROM Prospecto WHERE usuario = ?";
$stmt_prospecto = mysqli_prepare($conexion, $query_prospecto);
mysqli_stmt_bind_param($stmt_prospecto, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt_prospecto);
$resultado_prospecto = mysqli_stmt_get_result($stmt_prospecto);
$prospecto = mysqli_fetch_assoc($resultado_prospecto);

// Obtener vacantes destacadas
$query_vacantes = "SELECT v.*, e.nombre as nombre_empresa 
                   FROM Vacante v 
                   JOIN Empresa e ON v.empresa = e.numero 
                   ORDER BY v.fechaInicio DESC LIMIT 5";
$resultado_vacantes = mysqli_query($conexion, $query_vacantes);

// Obtener opciones para los filtros
$query_empresas = "SELECT DISTINCT nombre FROM Empresa";
$resultado_empresas = mysqli_query($conexion, $query_empresas);

$query_ubicaciones = "SELECT DISTINCT ciudad FROM Empresa";
$resultado_ubicaciones = mysqli_query($conexion, $query_ubicaciones);

$query_tipos_contrato = "SELECT DISTINCT tipo_contrato FROM Vacante";
$resultado_tipos_contrato = mysqli_query($conexion, $query_tipos_contrato);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard del Prospecto - TalentBridge</title>
    <link rel="stylesheet" href="css/css.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="/Outsourcing/index.php">
                    <h2>TalentBridge</h2>
                </a>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="perfil_prospecto.php"><i class="fas fa-user"></i> Ver y Gestionar Perfil</a></li>
                    <li><a href="mis_aplicaciones.php"><i class="fas fa-file-alt"></i> Mis Aplicaciones</a></li>
                    <li><a href="mis_cursos.php"><i class="fas fa-graduation-cap"></i> Mis Cursos</a></li>
                    <li><a href="generar_contrato.php"><i class="fas fa-file-contract"></i> Mis Contratos</a></li>
                    <li><a href="/Outsourcing/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <header class="main-header">
                <h1>Bienvenido, <?php echo htmlspecialchars($prospecto['nombre'] . ' ' . $prospecto['primerApellido']); ?></h1>
            </header>
            <section class="search-section">
                <h2>Buscar Vacantes</h2>
                <form class="search-form" action="buscar_vacantes.php" method="GET">
                    <input type="text" name="keyword" placeholder="Palabra clave">
                    <select name="empresa">
                        <option value="">Todas las empresas</option>
                        <?php while ($empresa = mysqli_fetch_assoc($resultado_empresas)): ?>
                            <option value="<?php echo htmlspecialchars($empresa['nombre']); ?>">
                                <?php echo htmlspecialchars($empresa['nombre']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <select name="ubicacion">
                        <option value="">Todas las ubicaciones</option>
                        <?php while ($ubicacion = mysqli_fetch_assoc($resultado_ubicaciones)): ?>
                            <option value="<?php echo htmlspecialchars($ubicacion['ubicacion']); ?>">
                                <?php echo htmlspecialchars($ubicacion['ubicacion']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <select name="tipo_contrato">
                        <option value="">Todos los tipos de contrato</option>
                        <?php while ($tipo_contrato = mysqli_fetch_assoc($resultado_tipos_contrato)): ?>
                            <option value="<?php echo htmlspecialchars($tipo_contrato['tipo_contrato']); ?>">
                                <?php echo htmlspecialchars($tipo_contrato['tipo_contrato']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <button type="submit">Buscar</button>
                </form>
            </section>
            <section class="featured-vacancies">
                <h2>Vacantes Destacadas</h2>
                <div class="vacancy-grid">
                    <?php while ($vacante = mysqli_fetch_assoc($resultado_vacantes)): ?>
                        <div class="vacancy-card">
                            <h3><?php echo htmlspecialchars($vacante['titulo']); ?></h3>
                            <p class="company"><?php echo htmlspecialchars($vacante['nombre_empresa']); ?></p>
                            <p class="description"><?php echo htmlspecialchars(substr($vacante['descripcion'], 0, 100)) . '...'; ?></p>
                            <a href="detalle_vacante.php?id=<?php echo $vacante['numero']; ?>" class="btn-detail">Ver Detalle</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>
        </main>
    </div>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</body>
</html>