<?php
//---conexion a la base de datos
include 'conexion.php';
//---consulta para obtener los productos de la base de datos ordenados por id
$sql = "SELECT product_id, name, price, stock
        FROM products
        ORDER BY product_id";
//---ejecutar la consulta y guardar el resultado en una variable
$resultado = $conexion->query($sql);
?>
 <!----html para mostrar la tabla de productos--->
<!DOCTYPE html>
<html lang="es">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">

<head>
    <title>Administrar Productos - Street Wise</title>
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
        Administración de Productos
    </h1>

    <div style="text-align:center; margin:20px;">

        <a href="agregar_producto.php">
            <button class="boton-agregar-producto">
                + NUEVO PRODUCTO
            </button>
        </a>

    </div>

    <div class="contenedor-productos">

        <table class="tabla-productos">

            <tr>
                <th>ID</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        <!---bucle para mostrar los productos en la tabla--->
            <?php while($producto = $resultado->fetch_assoc()) { ?>

            <tr>

                <td>
                    <?php echo $producto['product_id']; ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($producto['name']); ?>
                </td>

                <td>
                    $<?php echo number_format($producto['price'],0,',','.'); ?>
                </td>

                <td>
                    <?php echo $producto['stock']; ?>
                </td>

                <td>

                    <a href="editar_producto.php?id=<?php echo $producto['product_id']; ?>">
                        Editar
                    </a>

                    |

                    <a href="eliminar_producto.php?id=<?php echo $producto['product_id']; ?>"
                       onclick="return confirm('¿Desea eliminar este producto?')">
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