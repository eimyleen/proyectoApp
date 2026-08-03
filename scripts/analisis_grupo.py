import sys
import os
import json
import pymysql

def print_fallback(error_msg=""):
    print(json.dumps({
        "promedio_grupo_proyectado": 0.0,
        "distribucion_riesgo": {
            "Alto": 0,
            "Medio": 0,
            "Bajo": 0
        },
        "alumnos_riesgo": []
    }))

if len(sys.argv) < 2:
    print_fallback("No grupo_id")
    sys.exit(0)

grupo_id = sys.argv[1]

try:
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
        # Obtener alumnos del grupo
        cursor.execute("SELECT alumno_id FROM alumnos_grupos WHERE grupo_id = %s", (grupo_id,))
        alumnos = [r['alumno_id'] for r in cursor.fetchall()]
        
        if not alumnos:
            print_fallback("No alumnos")
            sys.exit(0)

        # Obtener el último periodo registrado para estos alumnos
        format_strings = ','.join(['%s'] * len(alumnos))
        cursor.execute(f"SELECT periodo FROM calificaciones WHERE alumno_id IN ({format_strings}) ORDER BY periodo DESC LIMIT 1", tuple(alumnos))
        periodo_row = cursor.fetchone()
        
        # Si no hay periodo específico, tomamos todas las calificaciones por defecto
        periodo_actual = periodo_row['periodo'] if periodo_row else None

        # Analizar alumnos
        alumnos_riesgo = []
        promedios_grupo = []
        
        # Contador para la gráfica de dona[cite: 2]
        distribucion_riesgo = {
            "Alto": 0,
            "Medio": 0,
            "Bajo": 0
        }
        
        for alumno_id in alumnos:
            if periodo_actual:
                cursor.execute(
                    "SELECT calificacion FROM calificaciones WHERE alumno_id = %s AND periodo = %s", 
                    (alumno_id, periodo_actual)
                )
            else:
                cursor.execute("SELECT calificacion FROM calificaciones WHERE alumno_id = %s", (alumno_id,))

            cals = [float(r['calificacion']) for r in cursor.fetchall()]
            
            promedio = sum(cals)/len(cals) if cals else 0.0
            promedios_grupo.append(promedio)
            
            # Clasificación alineada[cite: 2]
            if promedio < 8.0:
                riesgo = "Alto"
            elif promedio < 8.8:
                riesgo = "Medio"
            else:
                riesgo = "Bajo"
            
            # Incrementar el contador correspondiente
            distribucion_riesgo[riesgo] += 1
            
            alumnos_riesgo.append({
                "alumno_id": alumno_id,
                "riesgo": riesgo,
                "promedio_actual": round(promedio, 1)
            })
            
        # Proyección del grupo[cite: 2]
        promedio_proyectado = sum(promedios_grupo) / len(promedios_grupo) if promedios_grupo else 0.0

        print(json.dumps({
            "promedio_grupo_proyectado": round(promedio_proyectado, 1),
            "distribucion_riesgo": distribucion_riesgo,
            "alumnos_riesgo": alumnos_riesgo
        }))

except Exception as e:
    print_fallback(str(e))
finally:
    connection.close()