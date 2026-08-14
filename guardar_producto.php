<?php

include "conexion.php";
//-datos que se envian desde el formulario
$nombre = $_POST["nombre"];
$categoria = $_POST["categoria"];
$sku = $_POST["sku"];
$precio = $_POST["precio"];
$descripcion = $_POST["descripcion"];
$stock = $_POST["stock"];
//---procesar la imagen que se sube desde el formulario
//----variable donde se guarda la ruta de la imagen que se sube desde el formulario
$imagen="";
//-----verificar si se subio una imagen desde el formulario
if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) 
    {
    //----obtener el nombre original del archivo subido
    $nombreImagen = $_FILES["imagen"]["name"];
    //----definir la ruta donde se guardara la imagen
    $imagen= "Imagenes/Productos/" . $nombreImagen;
    //---copiar la imagen desde la carpeta temporal a la carpeta de destino
    move_uploaded_file($_FILES["imagen"]["tmp_name"], $imagen);
    }
/*verificar si llegan los datos
echo "<h2>Datos recibidos</h2>";

echo "Nombre: " . $nombre;
echo "<br>";

echo "Categoria: " . $categoria;
echo "<br>";

echo "SKU: " . $sku;
echo "<br>";

echo "Precio: " . $precio;
echo "<br>";

echo "Descripción: " . $descripcion;
echo "<br>";

echo "Stock: " . $stock;
*/
//---consulta para insertar el producto en la base de datos
$sql = "INSERT INTO products
 (category_id ,sku,name,price, description, stock, main_image) 
 VALUES(?, ?, ?, ?, ?, ?, ?)";
 //-preparar la consulta
 $consulta = $conexion->prepare($sql);
 //--vinvula los valores a los parametros de la consulta i=entero, s=texto, d=decimal
 //--se entregan los datos en el mismo orden que se definieron los parametros en la consulta
 $consulta->bind_param(
"issdsis", 
$categoria, 
$sku, 
$nombre, 
$precio,
$descripcion,
$stock, 
$imagen,
);
//---se ejecuta la consulta y se define las condidicones de exito o fracaso de la misma
if ($consulta->execute()) {
    echo "Producto agregado correctamente";
} else {
    echo "Error:" . $conexion->error;
}
//---cerrar la conexion a la base de datos
$conexion->close();
$consulta->close();