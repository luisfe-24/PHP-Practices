# API REST

Es la manera de conectar dos softwares entre sí, lo que permite combinar las utilidades y contenido que puedan tener cada uno en un proyecto. Se maneja mediante solicitudes de los datos a utilizar.

## Métodos HTTP - API RESTful

- **GET**: Extraer datos.
- **POST**: Enviar datos.
- **PUT**: Modificar datos.
- **DELETE**: Borrar datos.

## Códigos de estado

- **200 – OK**: Solicitud con éxito.
- **404 – Not Found**: No se encuentra el contenido.
- **500 – Internal Server Error**: Error en el servidor.

## Tipos de autorizaciones para conectarse a una API REST

- **OAuth**: Protocolo de autorización, no es tan seguro.
- **OAuth 2.0**: Otorga acceso limitado, genera un token de autenticación por lo que es más seguro.
- **API key**: Identificador para autenticar a un usuario para usar servicios de una API.

En este proyecto se utilizará PHP y usaré Thunder Client para manejar las solicitudes con la API que necesite.

## Rutas de la API

### Cursos

- **POST /cursos**: Crear un nuevo curso.
- **GET /cursos**: Obtener la lista de cursos.
- **GET /cursos/{id}**: Obtener los detalles de un curso específico.
- **PUT /cursos/{id}**: Actualizar un curso específico.
- **DELETE /cursos/{id}**: Eliminar un curso específico.

### Registro

- **POST /registro**: Crear un nuevo registro de cliente.

## Controladores

### ControladorCursos

- **index()**: Maneja la solicitud GET para obtener la lista de cursos.
- **create()**: Maneja la solicitud POST para crear un nuevo curso.
- **show($id)**: Maneja la solicitud GET para obtener los detalles de un curso específico.
- **update($id)**: Maneja la solicitud PUT para actualizar un curso específico.
- **delete($id)**: Maneja la solicitud DELETE para eliminar un curso específico.

### ControladorClientes

- **create()**: Maneja la solicitud POST para crear un nuevo registro de cliente.

## Modelos

### ModeloCursos

- **index($tabla)**: Obtiene la lista de cursos desde la base de datos.
- **create($tabla, $datos)**: Inserta un nuevo curso en la base de datos.
- **show($tabla1, $tabla2, $id)**: Obtiene los detalles de un curso específico, incluyendo información del creador, uniendo las tablas `cursos` y `clientes`.
- **update($tabla, $datos)**: Actualiza los detalles de un curso específico en la base de datos.

### ModeloClientes

- **index($tabla)**: Obtiene la lista de clientes desde la base de datos.
- **create($tabla, $datos)**: Inserta un nuevo cliente en la base de datos.

## Explicación del Proceso

### Manejo de Solicitudes PUT

Para manejar las solicitudes PUT, se agregó una condición en `rutas.php` que captura y procesa los datos para actualizar los cursos. Los datos se capturan del cuerpo de la solicitud utilizando `file_get_contents('php://input')` y `parse_str`. Luego, se llama al método `update` del `ControladorCursos` con los datos capturados.

### Método `update` en `ControladorCursos`

El método `update` en `ControladorCursos` realiza las siguientes acciones:
1. Valida las credenciales del cliente utilizando autenticación básica.
2. Valida los datos de entrada para asegurarse de que contienen solo caracteres permitidos.
3. Obtiene los detalles del curso utilizando el método `show` de `ModeloCursos`.
4. Verifica si el cliente está autorizado para actualizar el curso.
5. Prepara los datos para actualizar el curso y llama al método `update` de `ModeloCursos`.
6. Devuelve respuestas JSON adecuadas basadas en el éxito o fracaso de la operación de actualización.

### Método `show` en `ModeloCursos`

El método `show` en `ModeloCursos` se ha mejorado para recuperar los detalles del curso junto con la información del creador uniendo las tablas `cursos` y `clientes`. La consulta SQL selecciona columnas específicas de ambas tablas y utiliza una cláusula `INNER JOIN` para unir las tablas basándose en la columna `id_creador`.

### Método `update` en `ModeloCursos`

El método `update` en `ModeloCursos` prepara una consulta SQL para actualizar los detalles del curso en la tabla `cursos`. Los parámetros se enlazan a la consulta para prevenir inyecciones SQL. La consulta se ejecuta y se devuelve el resultado. Se agregó manejo de errores para imprimir información de error de la base de datos si la ejecución de la consulta falla.

### Verificación de Solicitudes GET

Se verificó que las solicitudes GET para recuperar los detalles del curso se manejen correctamente y devuelvan los detalles del curso junto con la información del creador.

Con estos cambios, se implementa la funcionalidad para manejar solicitudes PUT para actualizar los detalles del curso, recuperar los detalles del curso con el método `show`, y actualizar los detalles del curso con el método `update`. Se incluye validación adecuada, enlace de parámetros y manejo de errores para asegurar la robustez de la implementación.