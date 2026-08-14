#-----app.py servidor pricipal utilizando flask
#importar libreria flask
from flask import Flask, request, jsonify
from conexion import conexion
#crear la aplicacion
app = Flask(__name__)
#ruta principal
@app.route("/sugerencias")
def sugerencias():

    # Obtener el texto que escribió el usuario
    buscar = request.args.get("buscar")

    # Crear un cursor para ejecutar consultas SQL
    cursor = conexion.cursor(dictionary=True)

    # Consulta SQL
    sql = """
        SELECT
            product_id,
            name
        FROM products
        WHERE name LIKE %s
        ORDER BY name
        LIMIT 5
    """

    # Ejecutar la consulta
    cursor.execute(sql, ("%" + buscar + "%",))

    # Obtener todos los resultados
    resultados = cursor.fetchall()

    # Cerrar el cursor
    cursor.close()

    # Devolver los resultados en formato JSON(JSON es un formato de intercambio de datos muy utilizado entre el frontend y el backend.)
    return jsonify(resultados)

if __name__ == '__main__':
    app.run(debug=True)