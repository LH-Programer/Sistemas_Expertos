<?php 
include_once 'conexion.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Tomar datos del formulario
$Run = pg_escape_string($conn, $_POST['run']);
$Nombre = pg_escape_string($conn, $_POST['nombre']);
$Primer_apellido = pg_escape_string($conn, $_POST['primer_apellido']);
$Segundo_apellido = pg_escape_string($conn, $_POST['segundo_apellido']);
$Direccion = pg_escape_string($conn, $_POST['direccion']);
$Telefono = pg_escape_string($conn, $_POST['telefono']);

// Insertar el nuveo Encargado
$query = "INSERT INTO encargados (Run, Nombre, Primer_apellido, Segundo_apellido, Direccion, Telefono) 
          VALUES ('$Run', '$Nombre', '$Primer_apellido', '$Segundo_apellido', '$Direccion', '$Telefono')";
$result = pg_query($conn, $query);

if ($result) {
    echo '<script type="text/javascript">
          alert("Encargado agregado correctamente");
          window.location.href="../administracion.php";
          </script>';
} else {
    echo '<script type="text/javascript">
          alert("Error al agregar el encargado: ' . pg_last_error($conn) . '");
          window.location.href="../administracion.php";
          </script>';
}

// Cerrar la consulta y la conexión
if ($result) pg_free_result($result);
pg_close($conn);

?>