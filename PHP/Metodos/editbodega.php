<?php 
include_once 'conexion.php';

if (session_status () == PHP_SESSION_NONE){
    session_start();
}

// Tomar datos del formulario
$Id_Bodega = pg_escape_string($conn, $_POST['id_bodega']);
$Nombre = pg_escape_string($conn, $_POST['nombre']);
$Direccion = pg_escape_string($conn, $_POST['direccion']);
$Dotacion = pg_escape_string($conn, $_POST['dotacion']);
$Id_Estado = pg_escape_string($conn, $_POST['id_estado']);
$Fecha_Modificacion = date('Y-m-d H:i:s');

// Actualizar datos de la bodega
$query = "UPDATE bodegas SET nombre='$Nombre', direccion='$Direccion', dotacion='$Dotacion', id_estado='$Id_Estado', fecha_modificacion='$Fecha_Modificacion' WHERE id_bodega = '$Id_Bodega'";
$result = pg_query($conn, $query);

if ($result) {
    // Eliminar encargados actuales de la bodega
    $delete_query = "DELETE FROM bodega_encargados WHERE id_bodega = '$Id_Bodega'";
    pg_query($conn, $delete_query);

    // Insertar los nuevos encargados seleccionados
    if (!empty($_POST['encargados'])) {
        foreach ($_POST['encargados'] as $id_encargado) {
            $id_encargado = pg_escape_string($conn, $id_encargado);
            $insert_query = "INSERT INTO bodega_encargados (id_bodega, id_encargado) VALUES ('$Id_Bodega', '$id_encargado')";
            pg_query($conn, $insert_query);
        }
    }

    echo '<script type="text/javascript">
          alert("Bodega editada correctamente");
          window.location.href="../administracion.php";
          </script>';
} else {
    echo '<script type="text/javascript">
          alert("Hubo un error al editar los datos");
          window.location.href="../administracion.php";
          </script>';
}

// Cerrar la consulta y la conexion
if ($result) pg_free_result($result);
pg_close($conn);

?>