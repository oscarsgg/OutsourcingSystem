<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Outsourcing1";

// Intentar establecer la conexión
$conexion = mysqli_connect($servername, $username, $password, $dbname);

// Verificar la conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Establecer el conjunto de caracteres a utf8
mysqli_set_charset($conexion, "utf8");
?>