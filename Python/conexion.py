# conexion.py
# Archivo encargado de conectarse a la base de datos MySQL

# Importamos la librería que permite a Python conectarse a MySQL
import mysql.connector

try:
    # Crear la conexión con la base de datos
    conexion = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="streetwise_db"
    )

    # Verificar si la conexión fue exitosa
    if conexion.is_connected():
        print("Conexión realizada correctamente con MySQL.")
    # si existe un error muestre cual fue mediante el print
except mysql.connector.Error as error:
    print("Error al conectar con MySQL:")
    print(error)