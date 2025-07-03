<?php

require_once '../Metodos/conexion.php';

// Variable global para ocuparla mas adelante
global $conn;

// Detalles de la conexión a la base de datos
$dbDetails = array(
    'host' => 'localhost',
    'user' => 'postgres',
    'pass' => 'admin',
    'db'   => 'prueba_tecnica',
);

// Consulta para mostrar las bodegas
// Se utiliza una subconsulta para obtener los datos de las bodegas y sus encargados
$table = <<<EOT
 (
SELECT 
    b.id_bodega,
    b.codigo_idt,
    b.nombre,
    b.direccion,
    b.dotacion,
    STRING_AGG(e.nombre || ' ' || e.primer_apellido || ' ' || e.segundo_apellido, ', ') AS encargados,
    es.nombre as estado,
    b.id_estado as id_estado,
    b.fecha_creacion
FROM bodegas b
LEFT JOIN bodega_encargados be ON b.id_bodega = be.id_bodega
LEFT JOIN encargados e ON be.id_encargado = e.id_encargado
LEFT JOIN estados es ON b.id_estado = es.id_estado
GROUP BY b.id_bodega, es.nombre, b.codigo_idt, b.nombre, b.direccion, b.dotacion, b.fecha_creacion
 ) AS temp
EOT;

// Clave primaria de la tabla bodegas
$primaryKey = 'id_bodega';

$columns = array(
    array('db' => 'codigo_idt', 'dt' => 0),
    array('db' => 'nombre', 'dt' => 1),
    array('db' => 'direccion', 'dt' => 2),
    array('db' => 'dotacion', 'dt' => 3),
    array('db' => 'encargados', 'dt' => 4),
    array('db' => 'estado', 'dt' => 5),
    array(  'db' =>'fecha_creacion',
            'dt' => 6,
            // Formateamos los datos para que se muestren de forma agradable al usuario
            'formatter' => function($d, $row) {
                $meses_espanol = array(
                    'Jan' => 'Ene', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Abr', 
                    'May' => 'May', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Ago', 
                    'Sep' => 'Sep', 'Oct' => 'Oct', 'Nov' => 'Nov', 'Dec' => 'Dic'
                );
                $fecha = date('j M Y H:i:s', strtotime($d));
                $mes = date('M', strtotime($d));
                return str_replace($mes, $meses_espanol[$mes], $fecha);
            }
                ),
    array(  'db'        => 'id_bodega', 
            'dt'        => 7, 
            'formatter' => function( $d, $row ) use($conn){

            //Consulta para saber el valor del estado
            $query_estado = "SELECT id_estado FROM bodegas WHERE id_bodega = $d";
            $result_encargados = pg_query($conn, $query_estado);
            $row_estado = pg_fetch_assoc($result_encargados);
            
            // Si el estado es 'desactivada' se le agrega la clase desactivada que subrraya los datos de la columna
            if ($row_estado['id_estado'] == 2) {
                $class = 'desactivada';
            } else {
                $class = '';
            }

            return '
                <span class="'.$class.'">
                    <div class="acciones">
                        <a title="Editar Bodega" onclick="btnAbrirPopup1(' . $d . ')" href="#" class="btn-editar"><i class="fa-solid fa-pen"></i></a>&nbsp;
                        <a title="Eliminar Bodega" onclick="return eliminar()" href="Metodos/deletebodega.php?Id='.$d.'" class="btn-eliminar"><i class="fa-solid fa-trash"></i></a>  
                    </div>
                </span>
            '; 
        } 
    ),
);

// Buscador escrito
$searchFilter = array();

if (!empty($_GET['search_keywords'])) {
    $searchFilter['search'] = array(
        'codigo_idt' => $_GET['search_keywords'],
        'nombre' => $_GET['search_keywords'],
        'direccion' => $_GET['search_keywords'],
        'dotacion' => $_GET['search_keywords'],
        'encargados' => $_GET['search_keywords'],
        "estado" => $_GET['search_keywords'],
        'fecha_creacion' => $_GET['search_keywords']
    );
}

// Filtro de estados
if (!empty($_GET['filter_option'])) {
    $estado = $_GET['filter_option'];
    $searchFilter['filter']['estado'] = $estado;
}


// llamamos al siguiente archivo para que la tabla y los filtros funcionen correctamente
require 'ssp.class.pgsql.php';

$data = SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns, $searchFilter);


// Si existen condiciones, agrégalas a la consulta
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

//enviamos la data
header('Content-Type: application/json');
echo json_encode([
    "draw" => intval($_GET['draw'] ?? 0),
    "recordsTotal" => $data['recordsTotal'] ?? 0,
    "recordsFiltered" => $data['recordsFiltered'] ?? 0,
    "data" => $data['data'] ?? [],
]);


?>
