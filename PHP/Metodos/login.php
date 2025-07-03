<?php
include_once 'conexion.php';
?>

<?php
//Validar datos del formulario
if (isset($_POST['usuario']) && isset($_POST['contraseña'])) {
    $Usuario = pg_escape_string($conn, $_POST['usuario']);
    $Contraseña = pg_escape_string($conn, $_POST['contraseña']);

    // Consulta SQL
    $query = "SELECT * FROM usuarios WHERE Usuario = '$Usuario' AND Contraseña = '$Contraseña'";
    $result = pg_query($conn, $query);

    // Verificar si hay resultados
    if ($result && pg_num_rows($result) == 1) {
        $row = pg_fetch_assoc($result);
        $_SESSION['usuario'] = $row['usuario'];
        echo '<script type="text/javascript">
              alert("Sesión iniciada");
              window.location.href="../administracion.php";
              </script>';
    } else {
        echo '<script type="text/javascript">
              alert("Usuario o contraseña incorrectos");
              window.location.href="/Prueba_Tecnica_SistemasExpertos_LH/index.html";
              </script>';
    }

    // Cerrar la consulta y la conexión
    if ($result) pg_free_result($result);
    pg_close($conn);

} else {
    echo '<script type="text/javascript">
          alert("Acceso incorrecto o faltan datos del formulario.");
          window.location.href="/Prueba_Tecnica_SistemasExpertos_LH/index.html";
          </script>';
    exit;
}

?>