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

    <h1 class="titulo-catalogo">AGREGAR NUEVO PRODUCTO</h1>
    <div class="formulario-agregar-producto">
        <form action="guardar_producto.php" method="POST" enctype="multipart/form-data">
            <label>NOMBRE DEL PRODUCTO:</label>
            <br>
            <input type="text" name="nombre" required>
            <br><br>
            <label>Precio:</label>
            <br>
            <input type="number" name="precio"  required>
            <br><br>
            <label>Descripción:</label>
            <br>
            <input type="text" name="descripcion" required>
            <br><br>
            <label>Imagen (URL):</label>
            <br>
            <input type="file" name="imagen"  accept="image/*" required>
            <br><br>
            <label>Stock:</label>
            <br>
            <input type="number" name="stock" required>
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
            <input type="text" name="sku" required>
            <br><br>
                <button type="submit">
                Guardar Producto
            </button>
        </form>
    </div> 
    </main>
    
        