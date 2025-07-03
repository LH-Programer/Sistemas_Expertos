<?php 
include_once 'conexion.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Tomar datos del formulario
$Codigo_Idt = pg_escape_string($conn, $_POST['codigo_idt']);
$Nombre = pg_escape_string($conn, $_POST['nombre']);
$Direccion = pg_escape_string($conn, $_POST['direccion']);
$Dotacion = pg_escape_string($conn, $_POST['dotacion']);
$Id_Estado = 1; // Estado activo por defecto
$Fecha_Creacion = date('Y-m-d H:i:s');

// Insertar la bodega
$query = "INSERT INTO bodegas (Codigo_Idt, Nombre, Direccion, Dotacion, Id_Estado, Fecha_Creacion) 
          VALUES ('$Codigo_Idt', '$Nombre', '$Direccion', '$Dotacion', $Id_Estado, '$Fecha_Creacion') RETURNING Id_Bodega";
$result = pg_query($conn, $query);

if ($result) {
    $row = pg_fetch_assoc($result);
    $Id_Bodega = $row['id_bodega'];

    // Insertar los encargados seleccionados en la tabla intermedia
    if (!empty($_POST['encargados'])) {
        foreach ($_POST['encargados'] as $id_encargado) {
            $id_encargado = pg_escape_string($conn, $id_encargado);
            $query_encargado = "INSERT INTO bodega_encargados (Id_Bodega, Id_Encargado) VALUES ($Id_Bodega, $id_encargado)";
            pg_query($conn, $query_encargado);
        }
    }

    echo '<script type="text/javascript">
          alert("Bodega agregada correctamente");
          window.location.href="../administracion.php";
          </script>';
} else {
    echo '<script type="text/javascript">
          alert("Error al agregar la bodega: ' . pg_last_error($conn) . '");
          window.location.href="../administracion.php";
          </script>';
}

// Cerrar la consulta y la conexión
if ($result) pg_free_result($result);
pg_close($conn);

?>