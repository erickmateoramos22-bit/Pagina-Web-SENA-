
// ==========================================
// 1. MENU MOVIL
// ==========================================
const menuMovil = document.querySelector('.menu-movil');
const iconos = document.querySelector('.Iconos');
if(menuMovil && iconos){
    menuMovil.addEventListener('click',() => {
        iconos.classList.toggle('mostrar');
    });
}

// ==========================================
// 2. REDIRECCIÓN DE CATÁLOGOS
// ==========================================
const selectorHombre = document.getElementById("hombre");
if (selectorHombre) {
    selectorHombre.addEventListener("change", function() {
        if(this.value !== "") {
            window.location.href = this.value;
        }
    }); 
}

const selectorMujer = document.getElementById("mujer");
if (selectorMujer) {
    selectorMujer.addEventListener("change", function() {
        if(this.value !== "") {
            window.location.href = this.value;
        }
    });
}

// ==========================================
// 3. AGREGAR AL CARRITO 
// ==========================================
const botonesAgregar = document.querySelectorAll(".boton-comprar");

if (botonesAgregar.length > 0) {
    let carrito = JSON.parse(localStorage.getItem("carritoStreetWise")) || [];

    botonesAgregar.forEach(boton => {
        boton.addEventListener("click", (e) => {
            e.preventDefault();
            
            // CORRECCIÓN CLAVE: Usamos e.currentTarget para asegurar que lea los datos del <button>
            const nombre = e.currentTarget.getAttribute("data-nombre");
            const precio = parseInt(e.currentTarget.getAttribute("data-precio"));
            const imagen = e.currentTarget.getAttribute("data-imagen") || "Imagenes/logo Street Wise.png";

            const productoSeleccionado = {
                nombre: nombre,
                precio: precio,
                imagen: imagen,
                cantidad: 1
            };

            carrito.push(productoSeleccionado);
            localStorage.setItem("carritoStreetWise", JSON.stringify(carrito));
            
            // Alerta confirmando que funcionó
            alert(`¡${nombre} ha sido agregado al carrito!`);
        });
    });
}

/// ==========================================
// 4. MOSTRAR ITEMS Y CALCULAR TOTAL EN EL CARRITO
// ==========================================
const contenedorItems = document.getElementById("contenedor-items-producto");
const contenedorTotal = document.getElementById("contenedor-total-carrito"); // Atrapar la nueva caja del total

if (contenedorItems) {
    const carritoGuardado = JSON.parse(localStorage.getItem("carritoStreetWise")) || [];
    
    if (carritoGuardado.length === 0) {
        contenedorItems.innerHTML = "<p style='color: black; font-family: \"Street Wise\";'>Tu carrito está vacío. ¡Ve a buscar el mejor estilo!</p>";
        
        // Si está vacío, el total es $0
        if (contenedorTotal) {
            contenedorTotal.innerHTML = "<h3 style='color: black; font-family: \"Street Wise\"; margin: 0;'>Total: $0</h3>";
        }
    } else {
        contenedorItems.innerHTML = ""; // Limpiamos la caja de productos
        
        let totalCompra = 0; // variable para acumular la suma total

        carritoGuardado.forEach(producto => {
            // 1. Sumamos el precio de este producto al total acumulado
            // Multiplicamos precio por cantidad por si el usuario agrega más de uno del mismo
            totalCompra += producto.precio * producto.cantidad;

            // Validamos la imagen de respaldo
            const rutaImagen = (producto.imagen && producto.imagen !== "null") ? producto.imagen : "Imagenes/logo Street Wise.png";

            // Creamos la estructura visual para cada producto
            const filaProducto = `
                <div class="item-carrito" style="color: black; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid black; padding-bottom: 15px; width: 100%;">
                    <div class="detalle-pedido"> 
                        <h3 style="margin: 0; color: white; font-family: 'Street Wise';">${producto.nombre}</h3>
                        <p style="margin: 5px 0; color: white; font-family: 'Street Wise';">Cantidad: ${producto.cantidad}</p>
                        <p style="margin: 5px 0; color: white; font-family: 'Street Wise';">Precio: $${producto.precio.toLocaleString()}</p>
                    </div>
                    <img src="${rutaImagen}" alt="${producto.nombre}" style="width: 80px; height: 80px; border-radius: 10px; object-fit: cover; border: 1px solid black;">
                </div>
            `;   
            contenedorItems.innerHTML += filaProducto;
        });

        // 2. ¡Pintamos el total calculado en su respectiva caja al terminar el ciclo!
        // .toLocaleString() le añade los puntos de miles automáticamente (ej: 190000 -> 190.000)
        if (contenedorTotal) {
            contenedorTotal.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                    <h2 style="color: white; font-family: 'Street Wise'; margin: 0;">TOTAL:</h2>
                    <h2 style="color: white; font-family: 'Street Wise'; margin: 0;">$${totalCompra.toLocaleString()}</h2>
                </div>
            `;
        }
    }
}
// ==========================================
// 5. REINICIAR / VACIAR EL CARRITO COMPLETAMENTE
// ==========================================
const botonVaciar = document.querySelector(".boton-eliminar");

if (botonVaciar) {
    botonVaciar.addEventListener("click", (e) => {
        e.preventDefault(); // Evitamos cualquier acción por defecto del botón
        
        // Preguntamos al usuario si de verdad quiere borrar todo por seguridad
        const confirmar = confirm("¿Estás seguro de que deseas vaciar todo tu carrito de compras?");
        
        if (confirmar) {
            // Eliminamos la caja "carritoStreetWise" de la memoria del navegador
            localStorage.removeItem("carritoStreetWise");
            
            // Alternativa: Si quisieras borrar ABSOLUTAMENTE TODO lo guardado usarías: localStorage.clear();
            
            // Recargamos la página automáticamente para que se vea reflejado que ya está vacío
            window.location.reload();
        }
    });
}
// Bscador en tiempo real
//obtenemos el cuadro de busqieda de html
const buscador = document.getElementById("buscar");
// Obtenemos el contenedor donde aparecerán las sugerencias
const sugerencias = document.getElementById("sugerencias");

// Verificamos que ambos elementos existan
if (buscador && sugerencias) {

    // Detecta cada vez que el usuario escribe una letra
    buscador.addEventListener("input", function () {

        // Guardamos el texto escrito
        const texto = buscador.value;

        // Si el usuario no escribe nada
        if (texto.length == 0) {
            sugerencias.innerHTML = "";
            return;
        }

        // Enviamos el texto escrito a Flask
        fetch("http://127.0.0.1:5000/sugerencias?buscar=" + texto)
            .then(respuesta => respuesta.json())
            .then(datos => {

                console.log(datos);

            });

    });

}