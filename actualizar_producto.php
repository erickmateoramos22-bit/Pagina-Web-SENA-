<?php
//==================================================
// Conexión con la base de datos
//==================================================
include "conexion.php";
//==================================================
// Recibir los datos enviados desde editar_producto.php
//==================================================
//id del producto a actualizar
$id = $_POST["id"];
// datos del producto a actualizar
$categoria = $_POST["categoria"];
$sku = $_POST["sku"];
$nombre = $_POST["nombre"];
$precio = $_POST["precio"];
$descripcion = $_POST["descripcion"];
// la imagen se sube mediante un formulario de tipo file, por lo que se debe manejar de manera diferente
$imagen = "";
// recibir los datos de la variante del producto
// si el formulario trae Ids de variantes existentes se reciben 
//si no traen ninguno,se utiliza un array(arreglo o varinte que permite guardar muchos valores juntos ) vacío para evitar errores
//los corchetes[] indican que se reciben varios valores en el mismo campo 
// esto es importante porque una variante nueva todavia no tiene un ID asignado en la base de datos
$variant_id = $_POST["variant_id"] ?? [];
// los demas también se reciben como arreglos, ya que pueden haber varias variantes
$variant_sku = $_POST["variant_sku"] ?? [];
$variant_size = $_POST["variant_size"] ?? [];
$variant_color = $_POST["variant_color"] ?? [];
$variant_price = $_POST["variant_price"] ?? [];
$variant_stock = $_POST["variant_stock"] ?? [];
//----consulta para actualizar el producto en la base de datos
//--- actualizar el producto 
$sqlProducto = "UPDATE products 
        SET
            category_id = ?,
            sku = ?,
            name = ?,
            price = ?,
            description = ?
        WHERE product_id = ?";
//---preparar la consulta
$consultaProducto = $conexion->prepare($sqlProducto);
//--asociar los valores recibidos con los signos de interrogación
$consultaProducto->bind_param(
    "issdsi", 
    $categoria, 
    $sku, 
    $nombre, 
    $precio, 
    $descripcion, 
    $id
    );
    ///---ejecutar la consulta
    if (!$consultaProducto->execute()) {
        die("Error al actualizar el producto: " . $consultaProducto->error);
    }
    // se cierra la consulta porque ya se actualizó el producto
    $consultaProducto->close();
    //Recorrer las variantes del producto y actualizar o crear cada una de ellas

    //count() es una función de PHP que devuelve el número de elementos en un array, 
    // en este caso se utiliza para saber cuantas variantes tiene el producto
    for ($i = 0; $i < count($variant_sku); $i++) {
    //obtener los datos de la variante actual
    //si existe un variant_id en esta pocision lo usamos 
    // si no existe sera 0
    $idVariante = isset($variant_id[$i])
     ? (int)$variant_id[$i]
     : 0;
    // datos de la variante
     $skuVariante = $variant_sku[$i];
     $talla = $variant_size[$i];
     $color = $variant_color[$i];
     $precioVariante = $variant_price[$i];
     $stockVariante = $variant_stock[$i];
        // si ya existe la variante 
        if ($idVariante > 0) {
            //Actualizar la variante existente
             $sqlVariante = "UPDATE product_variants 
                    SET
                        sku = ?,
                        size = ?,
                        color = ?,
                        price = ?,
                        stock = ?
                    WHERE variant_id = ?
                    AND product_id = ?";
    //---preparar la consulta
    $consultaVariante = $conexion->prepare($sqlVariante);
    //--asociar los valores recibidos con los signos de interrogación
    $consultaVariante->bind_param(
        "sssdsii", 
        $skuVariante,
        $talla,
        $color,
        $precioVariante,
        $stockVariante,
        $idVariante,
        $id
    );
    //si es una variante nueva 
    } else {
        //crear una nueva variante
        $sqlVariante = "INSERT INTO product_variants 
                        (
                        product_id,
                        sku, size,
                        color,
                        price,
                        stock
                        ) 
                        VALUES (?, ?, ?, ?, ?, ?)";
        //---preparar la consulta
        $consultaVariante = $conexion->prepare($sqlVariante);
        //--asociar los valores recibidos con los signos de interrogación
        $consultaVariante->bind_param(
            "isssdi", 
            $id, 
            $skuVariante, 
            $talla, 
            $color, 
            $precioVariante, 
            $stockVariante
        );
    }
    //ejecutar la actualización de la variante
    if (!$consultaVariante->execute()) {
        die(
            "Error al actualizar/crear la variante: " 
            . $consultaVariante->error
            );
    }
    //cerrar la conslta de la variante
    $consultaVariante->close();
    }
    ////////////////////////////////////////////////////////////////////////
    // calcular el stock total del producto sumando el stock de todas sus variantes
    // suma el stock de todas lsa variantes de cada producto 
    //actualizar el stock total del producto en la tabla products
    $sqlActualizarStock = "UPDATE products 
                            SET stock = (
                            SELECT COALESCE(SUM(stock), 0)
                            FROM product_variants
                            WHERE product_id = ?
                            )
                            WHERE product_id = ?";
     //preparar la consulta
    $consultaActualizarStock = $conexion->prepare($sqlActualizarStock);
    //asociar los valores con los signos de interrogación
    $consultaActualizarStock->bind_param
    (
        "ii",
        $id,
        $id
    );
    //ejecutar 
    if (!$consultaActualizarStock->execute()) {
        die("Error al actualizar el stock total del producto: " .
         $consultaActualizarStock->error);
    }
    //cerrar consulta 
    $consultaActualizarStock->close();
    //terminamos y volvemos al admi istrador de productos
    $conexion->close();
    header("Location: admin_productos.php");
    exit();
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

        <a href="admin.html">
            <img src="iconos/casa.png"
                 alt="Inicio"
                 width="65px"
                 height="65px">
        </a>
    
    </div>
</header>