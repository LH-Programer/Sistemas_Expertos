<?php
require_once '../Metodos/conexion.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//Tomamos el ID de la bodega
$Id_Bodega = $_GET['Id_Bodega'] ?? null;

if ($Id_Bodega === null || !is_numeric($Id_Bodega)) {
    die(json_encode(['error' => 'No se proporcionó un Id_Bodega válido.']));
}

// Consulta principal de la bodega
$query = "SELECT 
                b.id_bodega,
                b.codigo_idt,
                b.nombre,
                b.direccion,
                b.dotacion,
                b.id_estado,
                es.nombre as estado,
                b.Fecha_creacion
            FROM bodegas b
            LEFT JOIN estados es ON b.id_estado = es.id_estado
            WHERE b.id_bodega = $Id_Bodega
            LIMIT 1";
$result = pg_query($conn, $query);

if ($result && pg_num_rows($result) > 0) {
    $bodega = pg_fetch_assoc($result);

    // Consulta de encargados
    $query_encargados = "SELECT e.id_encargado as id, e.nombre, e.primer_apellido, e.segundo_apellido
                         FROM bodega_encargados be
                         JOIN encargados e ON be.id_encargado = e.id_encargado
                         WHERE be.id_bodega = $Id_Bodega";
    $result_encargados = pg_query($conn, $query_encargados);

    $encargados = [];
    if ($result_encargados) {
        while ($row = pg_fetch_assoc($result_encargados)) {
            $encargados[] = $row;
        }
        pg_free_result($result_encargados);
    }
    $bodega['encargados'] = $encargados;

    echo json_encode($bodega);
} else {
    echo json_encode(['error' => 'No se encontró el detalle de la bodega.']);
}

if ($result) pg_free_result($result);
?>