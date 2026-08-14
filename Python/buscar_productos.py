#buscar productos por nombre
#conexion a la base de datos
from conexion import conexion
#crear un cursor para ejecutar consultas SQL
cursor= conexion.cursor()
#se pide al usuario que ingrese el nombre del producto a buscar
busqueda = input("Ingrese el nombre del producto a buscar: ")
#consulta SQL para buscar productos por nombre
sql ="""
SELECT
    product_id,
    name,
    price,
    stock
FROM PRODUCTS
WHERE NAME LIKE %s
ORDER BY NAME
"""
# Agregamos el comodín % ANTES O DESPUES del nombre del producto para permitir coincidencias parciales
valor = "%" + busqueda + "%"
# Ejecutamos la consulta SQL con el parámetro de búsqueda
cursor.execute(sql, (valor,))
# Obtenemos todos los resultados de la consulta
resultados = cursor.fetchall()
# Mostramos los resultados en la consola
print("\nResultados de la búsqueda:\n")
#se comprueba si existe un producto con el nombre ingresado y se muestran los resultados
if len(resultados) > 0:
    for producto in resultados:
        print(f"ID: {producto[0]}")
        print(f"Nombre: {producto[1]}")
        print(f"Precio: ${producto[2]}")
        print(f"Stock: {producto[3]}")
        #si no exite se muestra mensaje que no existe el producto
else:
    print("No se encontraron productos con ese nombre.")
#cerramos el cursor y la conexión a la base de datos
cursor.close()
conexion.close()