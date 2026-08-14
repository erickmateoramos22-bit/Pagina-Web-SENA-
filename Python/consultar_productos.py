#consultrar productos
#este script permite consultar los productos de la base de datos
# Importamos la librería que permite a Python conectarse a MySQL
from conexion import conexion
# Creamos un cursor para ejecutar consultas SQL
cursor = conexion.cursor()
#consulta SQL para obtener todos los productos
sql ="""
SELECT
    product_id,
    name,
    price,
    stock
FROM PRODUCTS
ORDER BY NAME   
"""
# Ejecutamos la consulta SQL
cursor.execute(sql)
# Obtenemos todos los resultados de la consulta
resultado = cursor.fetchall()
# Mostramos los resultados en la consola
print("lista de productos disponibles:")

for producto in resultado:
    print(producto)
    # Cerramos el cursor y la conexión a la base de datos
cursor.close()
conexion.close()