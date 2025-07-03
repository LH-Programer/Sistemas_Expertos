# Sistemas_Expertos

# Documentación del Proyecto Web - Módulo de Bodegas

Se desarrolló un **Proyecto Web** para cubrir el requerimiento de un nuevo módulo que permite **administrar las bodegas existentes** en la empresa.

## Tecnologías Utilizadas

- **HTML**: Para la estructura del sitio.
- **CSS**: Para los estilos visuales.
- **JavaScript**: Para funcionalidades dinámicas en la interfaz.
- **jQuery**: Librería utilizada para facilitar el manejo del DOM, junto con **AJAX** para la comunicación asíncrona con el servidor.
- **DataTables**: Complemento utilizado para generar **tablas dinámicas**, con funcionalidades como **paginación**, **búsqueda** y **filtros**.
- **PHP (versión 7.4.33)**: Lenguaje utilizado para el manejo de sesiones, conexión a la base de datos y ejecución de consultas.
- **PostgreSQL**: Sistema de gestión de base de datos utilizado.
- **PgAdmin 4**: Entorno gráfico utilizado para administrar la base de datos.
- **XAMPP**: Entorno local utilizado para ejecutar Apache y levantar el proyecto.

## Acceso al Módulo

Para ingresar al módulo, debes abrir el archivo `index.html`. Este archivo cuenta con un **inicio de sesión** (Nota: aunque no fue solicitado, se añadieron funcionalidades adicionales que se consideraron necesarias para un módulo más completo).

### Credenciales de acceso:

- **Usuario**: `admin`  
- **Contraseña**: `123`

## Funcionalidades del Módulo de Bodegas

Al iniciar sesión, se desplegará el módulo de **Bodegas**, donde podrás:

- **Agregar** una nueva bodega.
- **Visualizar** la información de todas las bodegas registradas.
- **Editar** los datos de una bodega existente.
- **Eliminar** una bodega de forma permanente.

Este módulo también incluye:

- **Buscador por columnas**: permite buscar por cada una de las columnas de información.
- **Filtro por estado**: puedes filtrar qué bodegas están **activas** y cuáles están **desactivadas**.
- Las bodegas desactivadas se mostrarán en la tabla **con color rojo y subrayadas**, para facilitar su identificación.
- Posibilidad de cambiar el **estado** y los **encargados** de la bodega al hacer clic en el ícono de lápiz (editar).
- Botón para **agregar un nuevo encargado**, con el fin de acelerar las pruebas al momento de asignarlos a las bodegas.

## Navegación y Cierre de Sesión

En la esquina superior izquierda se encuentra un **ícono de menú** que despliega una barra lateral. Desde ahí podrás:

- Cambiar entre posibles **futuros módulos**.
- Hacer clic en **"Cerrar sesión"** para salir del módulo actual.

## Link del proyecto en Github

- https://github.com/LH-Programer/Sistemas_Expertos
