<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/Outsourcing/config.php');

// Verificar si el usuario está logueado y es una empresa
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'EMP') {
    header("Location: login.php");
    exit();
}

// Obtener información de la empresa
$query_empresa = "SELECT * FROM Empresa WHERE usuario = ?";
$stmt_empresa = mysqli_prepare($conexion, $query_empresa);
mysqli_stmt_bind_param($stmt_empresa, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt_empresa);
$resultado_empresa = mysqli_stmt_get_result($stmt_empresa);
$empresa = mysqli_fetch_assoc($resultado_empresa);

// Obtener vacantes recientes de la empresa
$query_vacantes = "SELECT * FROM Vacante WHERE empresa = ? ORDER BY fechaInicio DESC LIMIT 5";
$stmt_vacantes = mysqli_prepare($conexion, $query_vacantes);
mysqli_stmt_bind_param($stmt_vacantes, "i", $empresa['numero']);
mysqli_stmt_execute($stmt_vacantes);
$resultado_vacantes = mysqli_stmt_get_result($stmt_vacantes);

// // Obtener estadísticas
// $query_stats = "SELECT 
//                 (SELECT COUNT(*) FROM Vacante WHERE empresa = ?) as total_vacantes,
//                 (SELECT COUNT(*) FROM Aplicacion a JOIN Vacante v ON a.vacante = v.numero WHERE v.empresa = ?) as total_aplicaciones,
//                 (SELECT COUNT(*) FROM Contrato c JOIN Vacante v ON c.vacante = v.numero WHERE v.empresa = ?) as total_contratos";
// $stmt_stats = mysqli_prepare($conexion, $query_stats);
// mysqli_stmt_bind_param($stmt_stats, "iii", $empresa['numero'], $empresa['numero'], $empresa['numero']);
// mysqli_stmt_execute($stmt_stats);
// $resultado_stats = mysqli_stmt_get_result($stmt_stats);
// $stats = mysqli_fetch_assoc($resultado_stats);
// ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Empresa - TalentBridge</title>
    <link rel="stylesheet" href="css/css.css">
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
                <h1>Bienvenido, <?php echo htmlspecialchars($empresa['nombre']); ?></h1>
            </header>
            <!-- <section class="dashboard-summary">
                <div class="summary-card">
                    <i class="fas fa-briefcase"></i>
                    <h3>Vacantes Activas</h3>
                    <p><?php echo $stats['total_vacantes']; ?></p>
                </div>
                <div class="summary-card">
                    <i class="fas fa-users"></i>
                    <h3>Aplicaciones Recibidas</h3>
                    <p><?php echo $stats['total_aplicaciones']; ?></p>
                </div>
                <div class="summary-card">
                    <i class="fas fa-handshake"></i>
                    <h3>Contratos Firmados</h3>
                    <p><?php echo $stats['total_contratos']; ?></p>
                </div>
            </section> -->
            <section class="recent-vacancies">
                <h2>Vacantes Recientes</h2>
                <div class="vacancy-list">
                    <?php while ($vacante = mysqli_fetch_assoc($resultado_vacantes)): ?>
                        <div class="vacancy-item">
                            <h3><?php echo htmlspecialchars($vacante['titulo']); ?></h3>
                            <!-- <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($vacante['ubicacion']); ?></p>
                            <p><i class="fas fa-clock"></i> Publicada el <?php echo date('d/m/Y', strtotime($vacante['fechaPublicacion'])); ?></p> -->
                            <a href="detalle_vacante.php?id=<?php echo $vacante['numero']; ?>" class="btn-detail">Ver Detalles</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>
            <section class="quick-actions">
                <h2>Acciones Rápidas</h2>
                <div class="action-buttons">
                    <a href="publicar_vacante.php" class="btn-action"><i class="fas fa-plus-circle"></i> Nueva Vacante</a>
                    <a href="ver_aplicaciones.php" class="btn-action"><i class="fas fa-envelope-open-text"></i> Ver Aplicaciones</a>
                    <a href="reportes.php" class="btn-action"><i class="fas fa-chart-bar"></i> Generar Reportes</a>
                </div>
            </section>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');

            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            });
        });
    </script>
</body>
</html>