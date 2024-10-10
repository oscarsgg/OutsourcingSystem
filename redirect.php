<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header("Location: login.php");
    exit();
}

switch ($_SESSION['user_role']) {
    case 'ADM':
        header("Location: user/admin/admin_dashboard.php");
        break;
    case 'EMP':
        header("Location: empresa_dashboard.php");
        break;
    case 'PRO':
        header("Location: user/prospecto/prospecto_dashboard.php");
        break;
    default:
        // Si por alguna razón el rol no coincide, redirigir al login
        session_destroy();
        header("Location: login.php");
        break;
}
exit();