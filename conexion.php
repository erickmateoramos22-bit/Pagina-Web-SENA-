<?php
// Configuración de las credenciales de acceso
$host = "localhost";
$user = "root";
$password = "";
$database = "streetwise_db";

// Crear la conexión usando la extensión orientada a objetos MySQLi
$conexion = new mysqli($host, $user, $password, $database);

// Validar si ocurrió un error durante el intento de conexión
if ($conexion->connect_error) {
    die("Error crítico de conexión: " . $conexion->connect_error);
}

// Configurar el mapa de caracteres para admitir tildes y la ñ
$conexion->set_charset("utf8mb4");
?>