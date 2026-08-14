<?php
//---conexion a la base de datos
include 'conexion.php';
//---consulta para obtener los productos de la base de datos ordenados por id
$sql = "SELECT user_id,role_id,first_name,last_name,email,phone
        FROM users
        ORDER BY user_id";
//---ejecutar la consulta y guardar el resultado en una variable
$resultado = $conexion->query($sql);
?>
 <!----html para mostrar la tabla de productos--->
<!DOCTYPE html>
<html lang="es">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">

<head>
    <title>Administrar Usuarios - Street Wise</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

<header class="encabezado">

    <img class="logo" src="Imagenes/logo Street Wise.png"
         alt="Street Wise"
         width="140px"
         height="140px">

    <div class="buscador"></div>

    <input type="text" name="busqueda" placeholder="Buscar...">

    <div class="menu-movil">
        ☰
    </div>

    <div class="Iconos">

        <a href="carrito compras.html">
            <img src="iconos/carrito-de-compras.png"
                 alt="Carrito de Compras">
        </a>

        <a href="admin.html">
            <img src="iconos/casa.png"
                 alt="Inicio"
                 width="65px"
                 height="65px">
        </a>

    </div>

</header>

<main>

    <h1 class="titulo-catalogo">
        Administración de Usuarios
    </h1>

    <div class="contenedor-producto">

        <table class="tabla-producto">

            <tr>
                <th>Id Uusario</th>
                <th>Rol ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Telefono</th>
            </tr>
        <!---bucle para mostrar los productos en la tabla--->
            <?php while($usuario= $resultado->fetch_assoc()) { ?>

            <tr>
                <td>
                    <?php echo $usuario['user_id']; ?>
                </td>

                <td>
                    <?php echo $usuario['role_id']; ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($usuario['first_name']); ?>
                </td>

                <<td>
                    <?php echo htmlspecialchars($usuario['last_name']); ?>
                </td>

                <<td>
                    <?php echo htmlspecialchars($usuario['email']); ?>
                </td>
                
                <td>
                    <?php echo htmlspecialchars($usuario['phone']); ?>

                </td>
                
                <td>

                    <a href="eliminar_usuarios.php?id=<?php echo $usuario['user_id']; ?>"
                       onclick="return confirm('¿Desea eliminar este usuario?')">
                        Eliminar
                    </a>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</main>

<footer>
    <p class="footer">
        © 2024 Street Wise. Todos los derechos reservados.
    </p>
</footer>

<script src="script.js"></script>

</body>
</html>