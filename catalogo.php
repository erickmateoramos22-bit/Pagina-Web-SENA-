<?php
// ============================================================
// catalogo.php
// Archivo REUTILIZABLE para mostrar productos de UNA categoría.
// Cada página de catálogo (camisas-hombre.php, etc.) le pasa
// el category_id por la URL, así: catalogo.php?cat=6
// ============================================================
 
error_reporting(E_ALL);
ini_set('display_errors', 1);
 
include 'conexion.php';
 
// LEER LOS PARAMETROS DE LA URL
// Si existe una categoria,ñaguardamos si no existe seria 0
$category_id = isset($_GET['cat']) ? (int) $_GET['cat'] : 0;
// si existe la busqieda la guardamos si no existe seria vacio
// trim() elimina espacios al principio y al final 
$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : "";
// Consultar productos
// si el usuario escribio algo en el buscador ...
if (!empty($buscar)){
    //consulta por nombre del producto 
    $sql ="SELECT
            product_id,
            name,
            description,
            price,
            stock,
            main_image
            FROM products
            WHERE name LIKE ?
            AND is_active = 1
            ORDER BY name ASC";

    $consulta = $conexion->prepare($sql);
    // agregamos los % para buscar coincidencias parciales
     $textoBusqueda ="%" . $buscar . "%";
    
     $consulta->bind_param("s",$textoBusqueda);
}else{
//consulta por categoria
    $sql = "SELECT
                product_id,
                name,
                description,
                price,
                stock,
                main_image
            FROM products
            WHERE category_id = ?
            AND is_active = 1
            ORDER BY name ASC";

    $consulta = $conexion->prepare($sql);

    $consulta->bind_param("i", $category_id);
    }
    //ejecutar la consulta 
    $consulta->execute();
    $resultado = $consulta->get_result();

// 3.TITULO DE LA PAGINA 
// Si se realizó una búsqueda...
if (!empty($buscar)) {

    $nombreCategoria = "Resultados para: " . htmlspecialchars($buscar);

} else {

    // Obtener el nombre de la categoría
    $sqlCategoria = "SELECT name
                     FROM categories
                     WHERE category_id = ?";

    $consultaCategoria = $conexion->prepare($sqlCategoria);

    $consultaCategoria->bind_param("i", $category_id);

    $consultaCategoria->execute();

    $resultadoCategoria = $consultaCategoria->get_result();

    $nombreCategoria = "Catálogo";

    if ($filaCategoria = $resultadoCategoria->fetch_assoc()) {

        $nombreCategoria = $filaCategoria["name"];

    }
}
?>
<!DOCTYPE html>
<html lang="es">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">
    <head>
        <title>Street Wise - <?php echo htmlspecialchars($nombreCategoria); ?></title>
        <link rel="stylesheet" href="estilos.css">
    </head>
    <body>
        <header class="encabezado">
            <img class="logo" src="Imagenes/logo Street Wise.png" alt="Street Wise" width="140px" height="140px">
          <form class="buscador" action="catalogo.php" method="GET">

    <input
        id="buscar"
        class="input_buscador"
        type="text"
        name="buscar"
        placeholder="Buscar..."
        autocomplete="off"
        value="<?php echo htmlspecialchars($buscar); ?>"
        required>

    <button
        class="boton_busqueda"
        type="submit">
        Buscar
    </button>

    <div id="sugerencias"></div>

</form>
            <div class="menu-movil">
                ☰
            </div>
            <div class="Iconos">
                <a href="carrito compras.html">
                    <img src="iconos/carrito-de-compras.png" alt="Carrito de Compras">
                </a>
                <a href="index.html">
                    <img src="iconos/casa.png" alt="inicio" width="65px" height="65px">
                </a>
            </div>
        </header>
 
        <main>
            <h1 class="titulo-catalogo"><?php echo htmlspecialchars($nombreCategoria); ?></h1>
             <!------Menu Hombre---------->
    <div class="contenedor-menus">
    <select name="Hombre" id="hombre">
        <option value="">HOMBRE</option>
        <option value="catalogo.php?cat=3">ACCESORIOS</option>
        <option value="catalogo.php?cat=4">Camisas</option>
        <option value="catalogo.php?cat=5">Pantalones</option>
        <option value="catalogo.php?cat=6">Chaquetas</option>
        <option value="catalogo.php?cat=7"> Busos</option>
        <option value="catalogo.php?cat=8">Sudaderas</option>
    </select>
    <!------------Menu Mujer ----------->
    <select name="Mujer" id="mujer">
        <option value="">MUJER</option>
        <option value="catalogo.php?cat=9">Accesorios</option>
        <option value="catalogo.php?cat=10">Camisas</option>
        <option value="catalogo.php?cat=11">Pantalones</option>
        <option value="catalogo.php?cat=12">Chaquetas</option>
        <option value="catalogo.php?cat=13"> Busos</option>
        <option value="catalogo.php?cat=14">Sudaderas</option>
    </select>
    </div>
 
            <div class="contenedor-productos">
                <?php
                // 4. Recorrer cada producto encontrado y pintar su tarjeta HTML
                if ($resultado->num_rows > 0) {
                    while ($producto = $resultado->fetch_assoc()) {
                        // obtener variantes del producto 
                        // cada productopuede tenr variantes de colo y talla 
                        // La relación se hace mediante:
                        // products.product_id = product_variants.product_id
                        $sqlVariantes = "SELECT
                                        variant_id,
                                        size,
                                        color,
                                        stock
                                        FROM product_variants
                                        WHERE product_id = ?
                                        ORDER BY size ASC";
                                        // preparar la consulta de variantes
                        $consultaVariantes = $conexion->prepare($sqlVariantes);
                        // enviar el product_id del producto actual
                        $consultaVariantes->bind_param("i", $producto['product_id']);
                        // ejecutar la consulta de variantes
                        $consultaVariantes->execute();
                        // obtener los resultados de variantes
                        $resultadoVariantes = $consultaVariantes->get_result();
                        // Verificar si el producto tiene una imagen asociada en la base de datos
                        //---- Si no tiene imagen, usar una imagen por defecto
                        if (!empty($producto['main_image'])) {
                            $imagen =$producto['main_image'];
                        } else {
                            $imagen = 'Imagenes/logo Street Wise.png';
                        }
                           
                        // Texto de stock dinámico: si hay stock, muestra cuántas unidades quedan. Si no, muestra "Agotado"
                        $stockTexto = $producto['stock'] > 0 
                            ? "Disponible: {$producto['stock']} unidades" 
                            : "Agotado";
                ?>
                    <div class="producto">
                        <img src="<?php echo htmlspecialchars($imagen); ?>" alt="<?php echo htmlspecialchars($producto['name']); ?>">
                        <div class="Contenedor-caracterizticas">
                            <h3><?php echo htmlspecialchars($producto['name']); ?></h3>
                            <p class="descripcion"><?php echo htmlspecialchars($producto['description']); ?></p>
                            <p class="precio">$<?php echo number_format($producto['price'], 0, ',', '.'); ?></p>
                            <p class="stock"><?php echo $stockTexto; ?></p>
                            <!-----selector de variantes de color y talla---->
                            <!---cada value es el variant_id de la variante correspondiente-->
                            <?php if ($resultadoVariantes->num_rows > 0) { ?>
                                <label for="variante-<?php echo $producto['product_id']; ?>">
                                    Selecciona una variante:
                                </label>
                                <select
                                    class="selector-variante"
                                    id="variante-<?php echo $producto['product_id']; ?>"
                                    >
                                <?php while ($variante = $resultadoVariantes->fetch_assoc()) { ?>
                                    <option
                                        value="<?php echo $variante['variant_id']; ?>"
                                        data-precio="<?php echo $variante['price'] ?? $producto['price']; ?>"
                                        data-stock="<?php echo $variante['stock']; ?>"
                                        <?php echo $variante['stock'] <= 0 ? 'disabled' : ''; ?>
                                    >
                                    <?php 
                                    // crear texto que ve el usuario
                                    echo "Talla:" . htmlspecialchars($variante['size']);
                                    //mostrara color si existe 
                                    if (!empty($variante['color'])) {       
                                     echo " | Color:" . htmlspecialchars($variante['color']);
                                     echo " | Color: "
                                        . htmlspecialchars(
                                            $variante['color']
                                        );
                                }
                                // mensaje si no hay stock de la variante
                                if ($variante['stock'] <= 0) {
                                    echo " | AGOTADO";
                                }
                                ?>
                            </option>
                        <?php } ?>
                    </select>
                <?php } else { ?>
                <!----mensaje si no hay variantes disponibles para el producto---->
                    <p class="no-variantes">
                        No hay variantes disponibles.
                    </p>
                <?php } ?>
                <button
                    class="boton-comprar"
                    data-id="<?php echo $producto['product_id']; ?>"
                    data-nombre="<?php echo htmlspecialchars($producto['name']); ?>"
                    data-precio="<?php echo (int) $producto['price']; ?>"
                    data-imagen="<?php echo htmlspecialchars($imagen); ?>"
                    <?php echo $producto['stock'] <= 0 ? 'disabled' : ''; ?>
                >
                                <?php echo $producto['stock'] > 0 ? 
                                'AGREGAR AL CARRITO' : 'AGOTADO'; ?>
                            </button>
                        </div>
                    </div>
                <?php
                /// cerrar consulta de variantes para liberar recursos
                $consultaVariantes->close();
                    }
                } else {
                    echo "<p style='text-align:center; font-family:\"Street Wise\";'>No hay productos disponibles en esta categoría todavía.</p>";
                }
                ?>
            </div>
        </main>
 
        <footer>
            <p class="footer">© 2024 Street Wise. Todos los derechos reservados.</p>
        </footer>
        <script src="script.js"></script>
    </body>
</html>
<?php
$consulta->close();
$conexion->close();
?>