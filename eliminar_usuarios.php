<?php
//---conexion a la base de datos
include 'conexion.php';
//----obtener el id del usuario a eliminar desde la URL ejemplo: eliminar_producto.php?id=1
$id = $_GET['id'];
//----consulta para eliminar el usuario de la base de datos
$sql = "DELETE FROM users WHERE user_id = ?";
//--preparar la consulta
$consulta = $conexion->prepare($sql);
//--asociar el id recibido con el signo de interrogación
$consulta->bind_param("i", $id);
//--ejecutar la consulta
if ($consulta->execute()) {
    //--si todo sale bien, redirigir a la página de administración de usuarios
    echo "<h2>Usuario eliminado correctamente.</h2>";
    header("Location: admin_usuarios.php");
    exit();
} else {
    echo "Error al eliminar el usuario: " . $conexion->error;
}
//--cerrar la consulta y la conexión
$consulta->close();
$conexion->close();
?>