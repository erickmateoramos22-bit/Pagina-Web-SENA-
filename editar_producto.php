<?php
// Conexión a la base de datos
include 'conexion.php';
/// Obtener el ID del producto a editar desde la URL ejemplo: editar_producto.php?id=1
$id = $_GET['id'];
//--Consulta para obtener los datos del producto busca solo el producto con el id recibido
$sql = "SELECT * FROM products 
WHERE product_id = ?";
//--Preparar la consulta
$consulta = $conexion->prepare($sql);
//--asociar el id recibido con el signo de interrogación
$consulta->bind_param("i", $id);
//--ejecutar la consulta
$consulta->execute();
//--obtener el resultado
$resultado = $consulta->get_result();
$producto = $resultado->fetch_assoc();
// oetener las variantes del producto
// las variantes son una tabla diferente que se relaciona con el producto por el id del producto
// se busca la variante que pertenece al producto con el id recibido
$sqlVariantes = "SELECT *
                FROM product_variants
                WHERE product_id = ?";
                //preparar consulta 
                $consultaVariantes = $conexion->prepare($sqlVariantes);
                //asociar el id del producto con el signo de interrogación
                $consultaVariantes->bind_param("i", $id);
                //ejecutar la consulta
                $consultaVariantes->execute();
                //obtener el resultado
                $resultadoVariantes = $consultaVariantes->get_result();
?>
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

        <a href="admin_productos.php">
            <img src="iconos/casa.png"
                 alt="Inicio"
                 width="65px"
                 height="65px">
        </a>
    
    </div>
</header>
<main>
    
    <h1 class="titulo-catalogo">EDITAR PRODUCTO</h1>
    <div class="formulario-agregar-producto">
        <form action="actualizar_producto.php" method="POST" enctype="multipart/form-data">
           
        <!--enviar el ID del producto el usuario no lo ve pero php lo recibe -->
            <input type="hidden" name="id" value="<?php echo $producto['product_id']; ?>">    
            
            <label>NOMBRE DEL PRODUCTO:</label>
            <br>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['name']); ?>" required>
            <br><br>
            <label>Precio:</label>
            <br>
            <input type="number" name="precio" value="<?php echo htmlspecialchars($producto['price']); ?>" required>
            <br><br>
            <label>Descripción:</label>
            <br>
            <input type="text" name="descripcion" value="<?php echo htmlspecialchars($producto['description']); ?>" required>
            <br><br>
            <label>Imagen (URL):</label>
            <br>
            <input type="file" name="imagen"  accept="image/*">
            <br><br>
            <label>Stock:</label>
            <br>
            <input type="number" name="stock" value="<?php echo htmlspecialchars($producto['stock']); ?>" required>
            <br><br>
            <label>Categoria:</label>
            <br>
            <select name="categoria" required>
                <option value="">Seleccione una categoría</option>
                <option value="3">Accesorios Hombre</option>
                <option value="4">Camisas Hombre</option>
                <option value="5">Pantalones Hombre</option>
                <option value="6">Chaquetas Hombre</option>
                <option value="7">Busos Hombre</option>
                <option value="8">Sudaderas Hombre</option>
                <option value="9">Accesorios Mujer</option>
                <option value="10">Camisas Mujer</option>
                <option value="11">Pantalones Mujer</option>
                <option value="12">Chaquetas Mujer</option>
                <option value="13">Busos Mujer</option>
                <option value="14">Sudaderas Mujer</option>
            </select>    
            <label>SKU:</label>
            <br>
            <input type="text" name="sku" value="<?php echo htmlspecialchars($producto['sku']); ?>" required>
            <br><br>
            <!--variante de cada producto-->
            <h2>VARIANTES DEL PRODUCTO</h2>
            <div id="contenedor-variantes">
                <!---se muestran las variantes del producto si existen-->
            <?php 
            if ($resultadoVariantes->num_rows > 0) { ?>
    <?php while ($variante = $resultadoVariantes->fetch_assoc()) { ?>
        <div class="variante">
            <!-- ID de la variante -->
            <input
                type="hidden"
                name="variant_id[]"
                value="<?php echo $variante['variant_id']; ?>"
            >
            <label>SKU de la variante:</label>
            <input
                type="text"
                name="variant_sku[]"
                value="<?php echo htmlspecialchars($variante['sku']); ?>"
                required
            >
            <br><br>
            <label>Talla:</label>
            <input
                type="text"
                name="variant_size[]"
                value="<?php echo htmlspecialchars($variante['size']); ?>"
                required
            >
            <br><br>
            <label>Color:</label>
            <input
                type="text"
                name="variant_color[]"
                value="<?php echo htmlspecialchars($variante['color']); ?>"
                required
            >
            <br><br>
            <label>Precio:</label>
            <input
                type="number"
                step="0.01"
                name="variant_price[]"
                value="<?php echo htmlspecialchars($variante['price']); ?>"
                required
            >
            <br><br>
            <label>Stock:</label>
            <input
                type="number"
                name="variant_stock[]"
                value="<?php echo htmlspecialchars($variante['stock']); ?>"
                required
            >
            <hr>
    </div>
            <br>
        
    <?php 
     } 
     }else { 
        ?>
    <p>
        Este producto todavía no tiene variantes.
    </p>
<?php } ?>
</div>
<br>
    <button type="button" id="agregar-variante">
        Agregar Variante
    </button>

                <button type="submit">
            Actualizar Producto
            </button>
        </form>
    </div> 
    </main>
    <script>
        //agregar una nueva variante al formulario
       // buscar contenedor de variantes
        const contenedor = document.getElementById('contenedor-variantes');
        // buscar el boton de agregar variantdes
        const botonAgregar = document.getElementById('agregar-variante');
        // Cuando el administrador haga clic en "Agregar Variante",
        // se ejecutará esta función.
        botonAgregar.addEventListener('click', function() {
            // Crear un nuevo div para la variante
            const nuevaVariante = document.createElement('div');
            nuevaVariante.className = 'variante';
            // Agregar los campos de la variante al nuevo div
            nuevaVariante.innerHTML = `
                <label>SKU de la variante:</label>
                <input type="text" name="variant_sku[]" required>
                <br><br>
                <label>Talla:</label>
                <input type="text" name="variant_size[]" required>
                <br><br>
                <label>Color:</label>
                <input type="text" name="variant_color[]" required>
                <br><br>
                <label>Precio:</label>
                <input type="number" step="0.01" name="variant_price[]" required>
                <br><br>
                <label>Stock:</label>
                <input type="number" name="variant_stock[]" required>
                <hr>
            `;
    // Agregar la nueva variante al formulario
    contenedor.appendChild(nuevaVariante)
});
    </script>
</body>
</html>