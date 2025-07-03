<?php
//Conexion
include 'conexion.php';

// Iniciar sesión si no ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si la sesión está activa y si el usuario está autenticado
$varsession = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

if ($varsession == null || $varsession == '') {
    echo '<script type="text/javascript">
    alert("Necesitas iniciar sesión");
    window.location.href="/Prueba_Tecnica_SistemasExpertos_LH/index.html";
    </script>';
    die();
}

// Escapar el valor para evitar inyección SQL
$usuario_escapado = pg_escape_string($conn, $varsession);

// Consulta para obtener los datos del usuario
$query = "SELECT * FROM usuarios WHERE Usuario = '$usuario_escapado'";
$result = pg_query($conn, $query);

if ($result && pg_num_rows($result) > 0) {
    while($row = pg_fetch_assoc($result)){
        $Usuario = $row['usuario'];
        // Puedes usar $Usuario como necesites aquí
    }
} else {
    echo '<script type="text/javascript">
    alert("Usuario no encontrado");
    window.location.href="/Prueba_Tecnica_SistemasExpertos_LH/index.html";
    </script>';
    die();
}

// Liberar resultado
pg_free_result($result);

// Consulta para obtener los encargados
$query1 = pg_query($conn, "SELECT Id_Encargado, Nombre, Primer_Apellido, Segundo_Apellido FROM encargados");


?>