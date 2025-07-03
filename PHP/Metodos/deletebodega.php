<?php 
include_once "conexion.php";

if (session_status () == PHP_SESSION_NONE){
    session_start();
}

// Toma del ID de la bodega a eliminar
$Id_bodega = pg_escape_string($conn, $_GET['Id']);

// Consulta para eliminar la bodega selecionada
$query = "DELETE FROM bodegas WHERE id_bodega='$Id_bodega'";
$result = pg_query($conn, $query);

if ($result){
    // Consulta para eliminar la relacion de los encargados
    $query2 = "DELETE FROM bodega_encargados WHERE id_bodega = '$Id_bodega'";
    pg_query($conn, $query2);
    
    echo '<script type="text/javascript">
        alert("Bodega Eliminada correctamente");
        window.location.href="../administracion.php";
        </script>';
} else {
    echo '<script type="text/javascript">
          alert("No se pudo borrar la bodega, intente mas tarde");
          window.location.href="../administracion.php";
          </script>';
}

// Cerrar la consulta y la conexion
if ($result) pg_free_result($result);
pg_close($conn);

?>