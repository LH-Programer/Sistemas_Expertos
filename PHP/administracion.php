<?php
include_once 'Metodos/sesion.php';
?>
<!DOCTYPE html>

<head>
<html lang="es">
<head>
    <link rel="icon" href="../Img/Bodega_Logo.png">
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable= no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Administracion</title>
    <!--Estilos-->
    <link rel="stylesheet" href="../CSS/administracion.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!--iconos-->
    <script src="https://kit.fontawesome.com/0b6d2c7b29.js" crossorigin="anonymous"></script>
    <!--Datatables-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">
    <script src="https://unpkg.com/scrollreveal"></script>
</head>
</head>
<body>
    <nav class="nav">
        <div class="nav__container">
        <img src="../Img/Logo_bodega.png" alt="logo" class="nav__title" height="60px">
        <h1 class="nav__seccion">Bodegas</h1>
            <a href="#menu" class="nav__menu">
                <img src="../Img/menu.svg" alt="abrir" class="nav__icon">
            </a>

            <a href="#" class="nav__menu nav__menu--second">
                <img src="../Img/close.svg" alt="cerrar" class="nav__icon">
            </a>

            <ul class="dropdown" id="menu">

            
                <div class="dropdown__account">
                    <p>Bienvenido</p>
                    <h3><?php echo $Usuario;?></h3>
                    <br><br>

                <li class="dropdown__list">
                    <a href="administracion.php" class="dropdown__link">
                        <i class="fa-solid fa-warehouse" id="dropdown__icon"></i>
                        <span class="dropdown__span">Bodegas</span>
                    </a>
                </li>

                </div>

                <div class="dropdown__exit">
                <li class="dropdown__list">
                    <a href="Metodos/cerrarsesion.php" class="dropdown__link">
                        <i class="fa-solid fa-door-open" id="dropdown__icon"></i>
                        <span class="dropdown__span">Cerrar Sesion</span>
                    </a>
                </li>
                </div>
            </ul>
            
        </div>
    </nav>

    <!--Formulario para agregar una nueva Bodega-->
    <div class="overlay" id="overlay">
        <div class="popup" id="popup">
            <a href="#" id="btn-cerrar-popup" class="btn-cerrar-popup"><i class="fa-solid fa-xmark"></i></a>
            <form action="Metodos/addbodega.php" method="POST">
                <h2>Nueva Bodega</h2>
                <div class="inputBox">
                    <input type="text" maxlength="5" name="codigo_idt" id="codigo_idt" required>
                    <span class="text">Codigo Identificador</span>
                    <i></i>
                </div>
                <div class="inputBox">
                    <input type="text" name="nombre" id="nombre" required>
                    <span class="text">Nombre</span>
                    <i></i>
                </div>
                <div class="inputBox">
                    <input type="text" name="direccion" id="direccion" required>
                    <span class="text">Direccion</span>
                    <i></i>
                </div>
                <div class="inputBox">
                    <input type="number" min="0" name="dotacion" id="dotacion" required>
                    <span class="text">Dotacion</span>
                    <i></i>
                </div>
                <div class="inputBox">
                    <label for="encargados">Encargados: (presiona ctrl para seleccionar mas de 1)</label>
                    <select name="encargados[]" id="encargados" multiple required>
                    <?php
                        // Cargar encargados
                        while ($row = pg_fetch_assoc($query1)) {
                            echo "<option value='{$row['id_encargado']}'>{$row['nombre']} {$row['primer_apellido']} {$row['segundo_apellido']}</option>";
                        }
                        pg_free_result($query1);
                    ?>
                    </select>
                    <i></i>
                </div>
                <br><br>
                <input type="submit" value="Agregar">
            </form>
        </div>
    </div>

    <!--Formulario para editar la Bodega seleccionada-->
    <div class="overlay" id="overlay1">
        <div class="popup" id="popup1">
            <a href="#" id="btn-cerrar-popup1" class="btn-cerrar-popup"><i class="fa-solid fa-xmark"></i></a>
            <form action="Metodos/editbodega.php" method="POST">
                <h2>Editar Bodega</h2>
                <div class="inputBox">
                    <input type="text" name="id_bodega" id="id_bodega_edit" hidden>
                    <input type="text" maxlength="5" name="codigo_idt" id="codigo_idt_edit" readonly required>
                    <span class="text">Codigo Identificador: (no editable)</span>
                    <i></i>
                </div>
                <div class="inputBox">
                    <input type="text" name="nombre" id="nombre_edit" required>
                    <span class="text">Nombre:</span>
                    <i></i>
                </div>
                <div class="inputBox">
                    <input type="text" name="direccion" id="direccion_edit" required>
                    <span class="text">Direccion:</span>
                    <i></i>
                </div>
                <div class="inputBox">
                    <input type="number" min="0" name="dotacion" id="dotacion_edit" required>
                    <span class="text">Dotacion:</span>
                    <i></i>
                </div>
                <div class="inputBox">
                    <?php
                    // Recargar encargados para el select de editar
                    $query1_edit = pg_query($conn, "SELECT id_encargado, nombre, primer_apellido, segundo_apellido FROM encargados");
                    ?>
                    <label for="encargados">Encargados: (presiona ctrl para seleccionar mas de 1)</label>
                    <select name="encargados[]" id="encargados_edit" multiple required>
                    <?php
                        // Cargar encargados
                        while ($row = pg_fetch_assoc($query1_edit)) {
                            echo "<option value='{$row['id_encargado']}'>{$row['nombre']} {$row['primer_apellido']} {$row['segundo_apellido']}</option>";
                        }
                        pg_free_result($query1_edit);
                    ?>
                    </select>
                    <i></i>
                </div>
                <div class="inputBox">
                    <label for="estado">Estado:</label>
                    <select name="id_estado" id="estado">
                        <option value="1">Activada</option>
                        <option value="2">Desactivada</option>
                    </select>
                </div>
                <br><br>
                <input type="submit" onclick="return modificar()" value="Editar">
            </form>
        </div>
    </div>

    <!--Formulario para agregar un nuveo Encargado-->
    <div class="overlay" id="overlay2">
        <div class="popup" id="popup2">
            <a href="#" id="btn-cerrar-popup2" class="btn-cerrar-popup"><i class="fa-solid fa-xmark"></i></a>
            <form action="Metodos/addencargado.php" method="POST">
                <h2>Nuevo Encargado</h2>
                <div class="inputBox">
                    <input type="text" name="run" maxlength="10" id="run" required>
                    <span class="text">RUN</span>
                    <i></i>
                </div>
                <div class="inputBox">
                    <input type="text" name="nombre" id="nombre" required>
                    <span class="text">Nombre</span>
                    <i></i>
                </div>
                <div class="inputBox">
                    <input type="text" name="primer_apellido" id="primer_apellido" required>
                    <span class="text">Primer Apellido</span>
                    <i></i>
                </div>
                <div class="inputBox">
                    <input type="text" name="segundo_apellido" id="segundo_apellido" required>
                    <span class="text">Segundo Apellido</span>
                    <i></i>
                </div>
                <div class="inputBox">
                    <input type="text" name="direccion" id="direccion" required>
                    <span class="text">Direccion</span>
                    <i></i>
                </div>
                <div class="inputBox">
                    <input type="text" name="telefono" id="telefono" required>
                    <span class="text">Telefono</span>
                    <i></i>
                </div>
                <br><br>
                <input type="submit" value="Agregar">
            </form>
        </div>
    </div>
        

    <div class="container">
        <!--Zona para el buscador y el filtro-->
        <div class="post-search-table">
        <label for="buscar">Buscador:</label>
        <input type="text" id="searchInput" placeholder=" Busca aqui...">

        <label for="Estado">Estado:</label>
        <select name="" id="sortBy">
            <option value="">Ambos</option>
            <option value="Activada">Activada</option>
            <option value="Desactivada">Desactivada</option>
        </select>

        <div class="reset">
                    <label for="">Quitar filtros:</label>
                    <button id="resetFilters"><i class="fa-solid fa-arrow-right-arrow-left"></i></button>
                </div>

        </div>

        <!--Zona de botonos para agregar-->
        <div class="botones">
            <div class="container_botones">
            <div class="agregar">
                    <a title="Agregar Bodega" href="#" onclick="btnAbrirPopup()" class="btn">Agregar Bodega +</a>
                    <a title="Agregar Encargado" href="#" onclick="btnAbrirPopup2()" class="btn">Agregar Encargado +</a>
                </div>
            </div>
        </div>

        <div class="container-table">
            <!--Tabla con la lista de informacion-->
            <table id="memListTable" class="display">
                <thead>
                    <tr>
                        <th>Codigo Idenficador</th>
                        <th>Nombre</th>
                        <th>Direccion</th>
                        <th>Dotacion</th>
                        <th>Encargados</th>
                        <th>Estado</th>
                        <th>Fecha_creacion</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tfoot>
                </tfoot>
            </table>
        </div>
    </div>


    <!-- Datatables -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!-- Botones de Datatable -->
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <!--Archivo JS para funcionalidades-->
    <script src="../JavaScript/administracion.js"></script>

    <script>

        //Inicializacion de Datatables + configuracion
        $(document).ready(function() {
            var table = $('#memListTable').DataTable({
                "searching": false,
                "processing": true,
                "serverSide": true,
                "stateSave": true, 
                responsive: true,
                "order": [[7, "desc"]],
                "createdRow": function(row, data, dataIndex) {
                    if (data[7].includes('desactivada')) {
                        $(row).addClass('desactivada');
                    }
                },
                dom: 'frtip',
                buttons: [],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-MX.json',
                },
                // Inicializacion Ajax para obtener los datos en la tabla
                "ajax": {
                    "url": "./ServerSide/fetchData.php",
                    "data": function(d) {
                        return $.extend({}, d, {
                            "search_keywords": $('#searchInput').val().toLowerCase(),
                            "filter_option": $('#sortBy').val(),
                        });
                    },
                    //Funcion para comprobar de que se estan mandando correctamente los datos
                            "dataSrc": function(json) {
                        if (!json || !json.data || !Array.isArray(json.data)) {
                            console.error("El formato de datos devuelto por el servidor es incorrecto", json);
                            alert("Se ha producido un error en la carga de datos. Revisa la consola para más detalles.");
                            return [];
                        }

                        return json.data;
                    }
                },
                    stateSaveParams: function(settings, data) {
                        // Guarda los valores de los filtros personalizados
                        data.search_keywords = $('#searchInput').val();
                        data.filter_option = $('#sortBy').val();
                    },
                    stateLoadParams: function(settings, data) {
                        // Restaura los valores de los filtros personalizados
                        $('#searchInput').val(data.search_keywords || '');
                        $('#sortBy').val(data.filter_option || '');
                    }
            });

            // Buscador y Filtro
            $('#searchInput,#sortBy').bind("keyup change", function() {
                table.draw();
            });

                // Botón para limpiar todos los filtros
                $('#resetFilters').click(function() {
                // Limpia los filtros personalizados
                $('#searchInput').val('');
                $('#sortBy').val('');

                // Limpia la búsqueda global y las columnas
                table.search('').columns().search('');

                // Reinicia la paginación al inicio
                table.page(0).draw(false); // false para evitar recargar desde el servidor

                table.order([7, "desc"]).draw(true);
            });

            window.btnAbrirPopup1 = function(idBodega) {
            // Llamada AJAX para obtener la información de la bodega
            $.ajax({
                url: 'Metodos/traerbodega.php',
                method: 'GET',
                data: { Id_Bodega : idBodega },
                success: function(data) {
                    var bodega = JSON.parse(data);
                    if (bodega.error) {
                        alert(bodega.error);
                    } else {
                        $('#id_bodega_edit').val(bodega.id_bodega);
                        $('#codigo_idt_edit').val(bodega.codigo_idt);
                        $('#nombre_edit').val(bodega.nombre);
                        $('#direccion_edit').val(bodega.direccion);
                        $('#dotacion_edit').val(bodega.dotacion);

                        var encargadosIds = [];
                        if (Array.isArray(bodega.encargados)) {
                            encargadosIds = bodega.encargados.map(function(e) { return e.id; });
                        }
                        $('#encargados_edit').val(encargadosIds).trigger('change');
                        $('#estado').val(bodega.id_estado);
                        $('#fecha_Creacion').val(bodega.fecha_Creacion);
                        $('#popup1').addClass('active');
                        $('#overlay1').addClass('active');
                    }
                },
                error: function() {
                    alert('Error al obtener la información de la bodega.');
                }
            });
        };

        });
    </script>

</body>

</html>