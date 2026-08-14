<?php
include 'conexion.php';

// Iniciar o reanudar la sesión global del navegador
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email      = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password   = isset($_POST['password']) ? $_POST['password'] : '';
    $admin_code = isset($_POST['admin_code']) ? trim($_POST['admin_code']) : '';

    // 1. Buscar al usuario por su correo electrónico
    $consulta = $conexion->prepare("SELECT user_id, password_hash, role_id, first_name FROM users WHERE email = ?");
    $consulta->bind_param("s", $email);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        
        // 2. Verificar si la contraseña es correcta
        if (password_verify($password, $usuario['password_hash'])) {
            
            $user_id = $usuario['user_id'];
            $role_id = $usuario['role_id'];
            $nombre  = $usuario['first_name'];

            // 3. VERIFICACIÓN DEL CÓDIGO ADMINISTRADOR EN EL LOGIN
            // Si pone el código correcto y aún no es administrador, lo ascendemos en la BD
            if ($admin_code === '12345678' && $role_id != 1) {
                $actualizar_rol = $conexion->prepare("UPDATE users SET role_id = 1 WHERE user_id = ?");
                $actualizar_rol->bind_param("i", $user_id);
                $actualizar_rol->execute();
                $actualizar_rol->close();
                
                $role_id = 1; // Cambiamos la variable local para la redirección actual
            }

            // 4. Guardar los datos en la sesión global
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role_id'] = $role_id;
            $_SESSION['name']    = $nombre;

            // 5. Redirección con aviso en pantalla utilizando JavaScript
            if ($role_id == 1) {
                echo "<script>
                    alert('¡Bienvenido al panel de Administración, " . addslashes($nombre) . "!');
                    window.location.href = 'admin.html';
                </script>";
            } else {
                echo "<script>
                    alert('¡Inicio de sesión correcto! Bienvenido de vuelta a Street Wise.');
                    window.location.href = 'index.html';
                </script>";
            }
            exit();
            
        } else {
            echo "<script>
                alert('La contraseña ingresada no es válida.');
                window.history.back();
            </script>";
        }
    } else {
        echo "<script>
            alert('Este correo electrónico no se encuentra registrado.');
            window.history.back();
        </script>";
    }
    $consulta->close();
}
$conexion->close();
?>