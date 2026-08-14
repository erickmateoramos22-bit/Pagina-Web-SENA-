<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// 1. INCLUIR CONEXIÓN OBJETUAL (Como la tienes en conexion.php)
include("conexion.php"); 

// 2. CAPTURAR DATOS DE FORMA SEGURA
$first_name   = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
$last_name    = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
$email        = isset($_POST['email']) ? trim($_POST['email']) : '';
$password     = isset($_POST['password']) ? $_POST['password'] : ''; 
$phone_number = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$admin_code   = isset($_POST['admin_code']) ? trim($_POST['admin_code']) : ''; 

// 3. VALIDACIÓN BÁSICA DE CAMPOS VACÍOS
if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
    echo "<script>
        alert('Por favor, rellena todos los campos obligatorios.');
        window.history.back();
    </script>";
    exit();
}

// 4. ENCRIPTAR CONTRASEÑA (Para que funcione con password_verify en tu login)
$password_hash = password_hash($password, PASSWORD_BCRYPT);

// 5. EVALUACIÓN DEL CÓDIGO DE ROL
// Si el código es correcto, asigna Rol 1 (Admin) y mandará a admin.html. Si no, Rol 2 (Cliente) e index.html
if ($admin_code === '12345678') {
    $role_id = 1; 
    $redireccion = 'admin.html';
    $mensaje = '¡Registro completado con éxito como Administrador!';
} else {
    $role_id = 2; 
    $redireccion = 'index.html';
    $mensaje = '¡Registro completado con éxito! Bienvenido a Street Wise.';
}

// 6. CONSULTA PREPARADA UTILIZANDO LAS COLUMNAS EXACTAS DE TU TABLA 'users'
// IMPORTANTE: la columna en la base de datos se llama "phone", no "phone_number"
$sql = "INSERT INTO `users` (`first_name`, `last_name`, `email`, `password_hash`, `phone`, `role_id`) 
        VALUES (?, ?, ?, ?, ?, ?)";

$consulta = $conexion->prepare($sql);

// Validar que la preparación de la consulta no haya fallado (por ejemplo, si el nombre de columna estuviera mal)
if ($consulta === false) {
    echo "<script>
        alert('Error preparando la consulta: " . addslashes($conexion->error) . "');
        window.history.back();
    </script>";
    exit();
}

// "sssssi" = 5 strings (s) y 1 entero (i), en el mismo orden de las columnas de arriba
$consulta->bind_param("sssssi", $first_name, $last_name, $email, $password_hash, $phone_number, $role_id);

// 7. EJECUTAR USANDO LA CONSULTA PREPARADA
if ($consulta->execute()) {
    echo "<script>
        alert('$mensaje');
        window.location.href = '$redireccion';
    </script>";
    exit();
} else {
    // En caso de que falle por otra cosa (como un correo repetido, ya que email es UNIQUE)
    $error_sistema = $consulta->error;
    echo "<script>
        alert('Error en el sistema al guardar: " . addslashes($error_sistema) . "');
        window.history.back();
    </script>";
    exit();
}

$consulta->close();
$conexion->close();
?>