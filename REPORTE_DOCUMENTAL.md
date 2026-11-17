# Reporte Documental - U3: Desarrollo de Aplicación Web MVC con ORM, API RESTful y Despliegue en OCI

**Estudiante:** Zaid  
**Materia:** Programación Backend / Cloud Computing  
**Fecha:** 16 de Noviembre de 2026  

---

## 1. Datos Generales y Enlaces de Entrega

* **Dirección IP Pública OCI:** `40.233.1.94`
* **URL Base de Consumo de la API (Puerto 80 HTTP):**  
  `http://40.233.1.94/api/v1/productos`
* **Repositorio de Código Fuente en GitHub:**  
  [https://github.com/zaidmr25/laravel-api-productos](https://github.com/zaidmr25/laravel-api-productos)
* **Archivo de Pruebas Postman:**  
  Incluido en el repositorio como [`postman_collection.json`](https://github.com/zaidmr25/laravel-api-productos/blob/main/postman_collection.json) (formato Postman Collection v2.1).

---

## 2. Arquitectura Backend: Patrón MVC y ORM

### 2.1 Patrón Modelo-Vista-Controlador (MVC) por Capas
La aplicación fue desarrollada utilizando el framework backend **Laravel 11 (PHP 8.2)** implementando una separación estricta de responsabilidades por capas:

1. **Capa Modelo (`app/Models/Producto.php`):** Representa la entidad de negocio `Producto`. Gestiona las reglas de persistencia, tipado de atributos y mapeo relacional a través de Eloquent ORM. No ejecuta consultas SQL crudas.
2. **Capa Vista (API RESTful JSON):** Al ser un servicio RESTful para clientes desacoplados (Postman, aplicaciones móviles o Single Page Applications), la capa de presentación entrega respuestas estructuradas en formato estandarizado **JSON**, acompañadas de los encabezados HTTP apropiados y cabeceras **CORS (`Access-Control-Allow-Origin: *`)** configuradas en `config/cors.php`.
3. **Capa Controlador (`app/Http/Controllers/ProductoController.php`):** Orquesta el flujo de la aplicación. Recibe las peticiones HTTP entrantes, ejecuta las validaciones de datos (`Validator`), invoca los métodos del ORM y retorna la respuesta con los códigos de estado HTTP semánticos (200, 201, 400, 404, 500).

```
[ Cliente HTTP: Postman / Navegador ]
                 |
                 v (HTTP Request en puerto 80)
   +-------------------------------+
   |      Rutas (routes/api.php)   |  -> Prefijo: /api/v1/productos
   +-------------------------------+
                 |
                 v
   +-------------------------------+
   |    Controlador (Controller)   |  -> Validación de entrada y lógica de API
   |    (ProductoController.php)   |
   +-------------------------------+
                 |
                 v (Operaciones ORM: all(), find(), create(), update(), delete())
   +-------------------------------+
   |         Modelo (Model)        |  -> Abstracción relacional sin SQL crudo
   |       (app/Models/Producto)   |
   +-------------------------------+
                 |
                 v (Driver Relacional)
   +-------------------------------+
   |         Base de Datos         |  -> Tabla 'productos'
   +-------------------------------+
```

### 2.2 Explicación del ORM (Eloquent)
Se utilizó **Eloquent ORM** nativo de Laravel. El uso de sentencias SQL directas (como `SELECT * FROM...` o `INSERT INTO...`) quedó estrictamente eliminado y prohibido. 

La entidad `Producto` cuenta con 5 campos descriptivos principales:
* `nombre` (string): Nombre comercial del producto.
* `descripcion` (text): Especificaciones y detalles técnicos.
* `precio` (decimal 10,2): Costo unitario monetario.
* `stock` (integer): Cantidad de existencias en almacén.
* `categoria` (string): Clasificación del catálogo.

La creación y mantenimiento del esquema se administró mediante migraciones relacionales (`database/migrations/...create_productos_table.php`), asegurando reproducibilidad tanto en local como en la nube.

---

## 3. Tabla Resumen de Endpoints RESTful

| Método HTTP | Endpoint / Ruta | Descripción de la Acción | Éxito HTTP | Errores Posibles |
| :--- | :--- | :--- | :---: | :---: |
| **GET** | `/api/v1/productos` | Obtener la lista completa de registros mediante `Producto::all()` | `200 OK` | `500 Internal Error` |
| **GET** | `/api/v1/productos/{id}` | Obtener un registro individual filtrado por su ID con `Producto::find($id)` | `200 OK` | `404 Not Found` |
| **POST** | `/api/v1/productos` | Crear un nuevo registro recibido en JSON Body procesado por `Producto::create()` | `201 Created` | `400 Bad Request` |
| **PUT** | `/api/v1/productos/{id}` | Actualizar registro existente mediante JSON Body e ID en URL con `$producto->update()` | `200 OK` | `400 Bad Request` / `404 Not Found` |
| **DELETE** | `/api/v1/productos/{id}` | Eliminar un registro específico por ID utilizando `$producto->delete()` | `200 OK` | `404 Not Found` |

---

## 4. Despliegue en Oracle Cloud Infrastructure (OCI)

1. **Instancia VM Compute:** Servidor virtual ejecutado en OCI (Instancia `7-B` con IP Pública `40.233.1.94`).
2. **Entorno de Red (VCN):** Se configuraron las *Ingress Rules* en la *Security List* de la subred para admitir tráfico entrante en el puerto estándar `80 (HTTP)` desde cualquier origen `0.0.0.0/0`.
3. **Servidor y Contenerización (Docker):** La aplicación Laravel 11 con PHP 8.2 se empaquetó y ejecuta en segundo plano con política de reinicio automático (`--restart always`) mediante Docker sobre el puerto HTTP 80, garantizando alta disponibilidad y aislamiento continuo del entorno.
