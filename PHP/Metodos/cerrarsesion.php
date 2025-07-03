<?php 
    session_start();  // Iniciar la sesión
    session_unset();  // Eliminar todas las variables de sesión
    session_destroy();  // Destruir la sesión

    echo '<script type="text/javascript">
    alert("Se ha cerrado la sesión correctamente");
    window.location.href="/Prueba_Tecnica_SistemasExpertos_LH/index.html";
    </script>';
?>