import sys
import os
import json
import pymysql
import numpy as np
from sklearn.linear_model import LinearRegression
from sklearn.cluster import KMeans

# 1. Get arguments
if len(sys.argv) < 2:
    print(json.dumps({"error": "No alumno_id provided"}))
    sys.exit(1)

alumno_id = sys.argv[1]

# 2. Database Connection
try:
    connection = pymysql.connect(
        host=os.environ.get('DB_HOST', 'localhost'),
        user=os.environ.get('DB_USERNAME', 'root'),
        password=os.environ.get('DB_PASSWORD', ''),
        database=os.environ.get('DB_DATABASE', 'laravel'),
        cursorclass=pymysql.cursors.DictCursor
    )
except Exception as e:
    print(json.dumps({"error": str(e)}))
    sys.exit(1)

# 3. Data Retrieval & Modeling (Simplified logic as per instructions)
# Note: Real implementation would query historical califications
try:
    with connection.cursor() as cursor:
        # Example: Get historical grades for prediction
        cursor.execute("SELECT cf FROM calificaciones WHERE alumno_id = %s AND cf IS NOT NULL", (alumno_id,))
        results = cursor.fetchall()
        
        # Linear Regression - Dummy training data based on historical grades
        # In a real scenario, use more features like (period_id, parical_id)
        X = np.array(range(len(results))).reshape(-1, 1)
        y = np.array([r['cf'] for r in results])
        
        prediccion = 8.0 # Default
        if len(X) > 1:
            model = LinearRegression()
            model.fit(X, y)
            prediccion = model.predict([[len(X)]])[0]

        # Clustering - Dummy KMeans
        # Aggregating data for clustering
        cursor.execute("SELECT AVG(cf) as promedio FROM calificaciones WHERE alumno_id = %s", (alumno_id,))
        promedio = cursor.fetchone()['promedio'] or 7.0
        
        # Dummy data for other alumnos to cluster against
        # Real implementation: select average grades of all students
        data_clustering = np.array([[promedio], [6.0], [9.0]]) 
        kmeans = KMeans(n_clusters=min(3, len(data_clustering)), n_init=10).fit(data_clustering)
        cluster_id = kmeans.predict([[promedio]])[0]
        
        cluster_nombres = ["En Riesgo", "Rendimiento Constante", "Sobresaliente"]
        cluster_nombre = cluster_nombres[cluster_id]

        # Risk Calculation
        riesgo = "Bajo"
        if prediccion < 6:
            riesgo = "Alto"
        elif prediccion < 8:
            riesgo = "Medio"

        # Output JSON
        output = {
            "prediccion_nota": round(float(prediccion), 1),
            "estatus_riesgo": f"Riesgo {riesgo}",
            "cluster_nombre": cluster_nombre,
            "cluster_id": int(cluster_id),
            "recomendacion": "Mantén tu esfuerzo para mejorar el promedio."
        }
        print(json.dumps(output))

finally:
    connection.close()
