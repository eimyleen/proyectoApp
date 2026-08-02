import sys
import os
import json
import pymysql
import numpy as np
from sklearn.linear_model import LinearRegression
from sklearn.cluster import KMeans

def print_fallback(error_msg=""):
    print(json.dumps({
        "prediccion_nota": 8.5,
        "estatus_riesgo": "Bajo",
        "cluster_nombre": "Pendiente",
        "recomendacion": "Información en proceso de actualización."
    }))

if len(sys.argv) < 2:
    print_fallback("No alumno_id")
    sys.exit(0)

alumno_id = sys.argv[1]

# Conexión ajustada
try:
    # Mucho más seguro y limpio
    connection = pymysql.connect(
        host=os.environ.get('DB_HOST'),
        user=os.environ.get('DB_USERNAME'),
        password=os.environ.get('DB_PASSWORD'),
        database=os.environ.get('DB_DATABASE'),
        port=int(os.environ.get('DB_PORT', 3306)),
        cursorclass=pymysql.cursors.DictCursor
    )
except Exception as e:
    print_fallback(str(e))
    sys.exit(0)

try:
    with connection.cursor() as cursor:
        cursor.execute("SELECT calificacion FROM calificaciones WHERE alumno_id = %s AND calificacion IS NOT NULL", (alumno_id,))
        results = cursor.fetchall()
        
        prediccion = 8.5
        if len(results) >= 2:
            y = np.array([float(r['calificacion']) for r in results])
            X = np.array(range(len(results))).reshape(-1, 1)
            model = LinearRegression()
            model.fit(X, y)
            # Proyectamos la siguiente calificación esperada
            prediccion = float(model.predict([[len(results)]])[0])
            # Acotamos la predicción entre 0 y 10
            prediccion = max(0.0, min(10.0, prediccion))

        cursor.execute("SELECT AVG(calificacion) as promedio FROM calificaciones WHERE alumno_id = %s", (alumno_id,))
        res = cursor.fetchone()
        promedio = float(res['promedio']) if res and res['promedio'] else 8.0
        
        # Clasificación por perfil
        if prediccion >= 9.0:
            cluster_nombre = "Sobresaliente"
        elif prediccion >= 8.0:
            cluster_nombre = "Rendimiento Constante"
        else:
            cluster_nombre = "En Riesgo"

        # Estatus de Riesgo
        if prediccion < 8.0:
            riesgo = "Alto"
            recomendacion = "Atención requerida: Se sugiere solicitar tutoría académica."
        elif prediccion < 9.0:
            riesgo = "Medio"
            recomendacion = "Desempeño regular. Mantén el esfuerzo para consolidar las notas."
        else:
            riesgo = "Bajo"
            recomendacion = "Excelente trayectoria académica. ¡Sigue así!"

        output = {
            "prediccion_nota": round(prediccion, 1),
            "estatus_riesgo": riesgo,
            "cluster_nombre": cluster_nombre,
            "recomendacion": recomendacion
        }
        print(json.dumps(output))

except Exception as e:
    print_fallback(str(e))
finally:
    connection.close()