<?php

class SSP {
    static function data_output($columns, $data)
    {
        $out = array();
        for ($i = 0, $ien = count($data); $i < $ien; $i++) {
            $row = array();
            for ($j = 0, $jen = count($columns); $j < $jen; $j++) {
                $column = $columns[$j];
                if (isset($column['formatter'])) {
                    $row[$column['dt']] = $column['formatter']($data[$i][$column['db']], $data[$i], $i);
                } else {
                    $row[$column['dt']] = $data[$i][$column['db']];
                }
            }
            $out[] = $row;
        }
        return $out;
    }

    static function db($conn)
    {
        if (is_array($conn)) {
            return self::sql_connect($conn);
        }
        return $conn;
    }

    static function limit($request, $columns)
    {
        $limit = '';
        if (isset($request['start']) && $request['length'] != -1) {
            // PostgreSQL uses LIMIT {length} OFFSET {start}
            $limit = 'LIMIT ' . intval($request['length']) . ' OFFSET ' . intval($request['start']);
        }
        return $limit;
    }

    static function order($request, $columns)
    {
        $order = '';
        if (isset($request['order']) && count($request['order'])) {
            $orderBy = array();
            $dtColumns = self::pluck($columns, 'dt');
            for ($i = 0, $ien = count($request['order']); $i < $ien; $i++) {
                $columnIdx = intval($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];
                if ($requestColumn['orderable'] == 'true') {
                    $dir = $request['order'][$i]['dir'] === 'asc' ? 'ASC' : 'DESC';
                    $orderBy[] = '"' . $column['db'] . '" ' . $dir;
                }
            }
            if (count($orderBy)) {
                $order = 'ORDER BY ' . implode(', ', $orderBy);
            }
        }
        return $order;
    }

    static function filter($request, $columns, &$bindings)
    {
        $globalSearch = array();
        $columnSearch = array();
        $dtColumns = self::pluck($columns, 'dt');

        if (isset($request['search']) && $request['search']['value'] != '') {
            $str = $request['search']['value'];
            for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];
                if ($requestColumn['searchable'] == 'true') {
                    if (!empty($column['db'])) {
                        $binding = self::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
                        if ($column['db'] === 'dotacion') {
                        $globalSearch[] = 'CAST("dotacion" AS TEXT) ILIKE ' . $binding;
                        } else {
                            $globalSearch[] = '"' . $column['db'] . "\" ILIKE " . $binding;
                        }
                    }
                }
            }
        }

        // Individual column filtering
        if (isset($request['columns'])) {
            for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];
                $str = $requestColumn['search']['value'];
                if ($requestColumn['searchable'] == 'true' && $str != '') {
                    if (!empty($column['db'])) {
                        $binding = self::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
                        $columnSearch[] = '"' . $column['db'] . "\" ILIKE " . $binding;
                    }
                }
            }
        }

        $where = '';
        if (count($globalSearch)) {
            $where = '(' . implode(' OR ', $globalSearch) . ')';
        }
        if (count($columnSearch)) {
            $where = $where === ''
                ? implode(' AND ', $columnSearch)
                : $where . ' AND ' . implode(' AND ', $columnSearch);
        }
        if ($where !== '') {
            $where = 'WHERE ' . $where;
        }
        return $where;
    }

    static function simple($request, $conn, $table, $primaryKey, $columns, $searchFilter = array(), $extraWhere = array()) {
        $bindings = array();
        $db = self::db($conn);

        $limit = self::limit($request, $columns);
        $order = self::order($request, $columns);
        $where = self::filter($request, $columns, $bindings);

        // Filtros adicionales exactos
        if (!empty($searchFilter['filter'])) {
            $sqlWhere = (strpos($where, 'WHERE') !== false) ? " AND " : " WHERE ";
            $whereAd = '';
            // Solo si existe 'search' y no está vacío
            if (!empty($searchFilter['search'])) {
                $i = 0;
                foreach ($searchFilter['search'] as $key => $val) {
                    $pre = ($i > 0) ? " OR " : "";
                    if ($key === 'dotacion' || $key === 'fecha_creacion') {
                        $whereAd .= $pre . 'CAST("' . $key . '" AS TEXT) ILIKE \'%' . $val . "%'";
                    } else {
                        $whereAd .= $pre . '"' . $key . "\" ILIKE '%" . $val . "%'";
                    }
                    $i++;
                }
            }
            // Si además hay filtro exacto (por ejemplo, estado)
            foreach ($searchFilter['filter'] as $key => $val) {
                if (!empty($whereAd)) $whereAd .= " AND ";
                $whereAd .= '"' . $key . '" = \'' . $val . '\'';
            }
            if (!empty($whereAd)) {
                $where .= $sqlWhere . $whereAd;
            }
        }

        // Filtros LIKE adicionales
        $whereLike = '';
        if (!empty($searchFilter['search'])) {
            $sqlWhere = (strpos($where, 'WHERE') !== false) ? " AND " : " WHERE ";
            $i = 0;
            foreach ($searchFilter['search'] as $key => $val) {
                $pre = ($i > 0) ? " OR " : "";
                if ($key === 'dotacion' || $key === 'fecha_creacion') {
                    $whereLike .= $pre . 'CAST("' . $key . '" AS TEXT) ILIKE \'%' . $val . "%'";
                } else {
                    $whereLike .= $pre . '"' . $key . "\" ILIKE '%" . $val . "%'";
                }
                $i++;
            }
            $whereLike = !empty($whereLike) ? $sqlWhere . ' (' . $whereLike . ') ' : '';
        }
        $where .= $whereLike;

        // Filtro extraWhere
        if (!empty($extraWhere)) {
            $sqlWhere = (strpos($where, 'WHERE') !== false) ? " AND " : " WHERE ";
            $extraWhereSql = '';
            $i = 0;
            foreach ($extraWhere as $key => $val) {
                $pre = ($i > 0) ? " AND " : "";
                $extraWhereSql .= $pre . '"' . $key . "\" = '" . $val . "'";
                $i++;
            }
            $where .= $sqlWhere . $extraWhereSql;
        }

        // Construcción de la consulta SQL final
        $sql = 'SELECT "' . implode('", "', self::pluck($columns, 'db')) . "\" FROM $table $where $order $limit";
        error_log("Consulta SQL: $sql");

        $data = self::sql_exec($db, $bindings, $sql);

        // Calcular el número de registros filtrados
        $resFilterLength = self::sql_exec($db, $bindings,
            "SELECT COUNT(\"{$primaryKey}\") FROM $table $where"
        );
        $recordsFiltered = $resFilterLength[0][0];

        // Calcular el número total de registros
        $resTotalLength = self::sql_exec($db, "SELECT COUNT(\"{$primaryKey}\") FROM $table");
        $recordsTotal = $resTotalLength[0][0];

        return array(
            "draw" => isset($request['draw']) ? intval($request['draw']) : 0,
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => self::data_output($columns, $data)
        );
    }

    static function sql_exec($db, $bindings, $sql = null)
    {
        if ($sql === null) {
            $sql = $bindings;
        }
        $stmt = $db->prepare($sql);
        if (is_array($bindings)) {
            for ($i = 0, $ien = count($bindings); $i < $ien; $i++) {
                $binding = $bindings[$i];
                $stmt->bindValue($binding['key'], $binding['val'], $binding['type']);
            }
        }
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            self::fatal("An SQL error occurred: " . $e->getMessage());
        }
        return $stmt->fetchAll(PDO::FETCH_BOTH);
    }

    static function sql_connect($sql_details)
    {
        try {
            $db = @new PDO(
                "pgsql:host={$sql_details['host']};port=" . (isset($sql_details['port']) ? $sql_details['port'] : 5432) . ";dbname={$sql_details['db']};",
                $sql_details['user'],
                $sql_details['pass'],
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        } catch (PDOException $e) {
            self::fatal(
                "An error occurred while connecting to the database. " .
                "The error reported by the server was: " . $e->getMessage()
            );
        }
        return $db;
    }

    static function fatal($msg)
    {
        echo json_encode(array(
            "error" => $msg
        ));
        exit(0);
    }

    static function bind(&$a, $val, $type)
    {
        $key = ':binding_' . count($a);
        $a[] = array(
            'key' => $key,
            'val' => $val,
            'type' => $type
        );
        return $key;
    }

    static function pluck($a, $prop)
    {
        $out = array();
        for ($i = 0, $len = count($a); $i < $len; $i++) {
            if (!empty($a[$i][$prop])) {
                $out[] = $a[$i][$prop];
            }
        }
        return $out;
    }

    static function _flatten($a, $join = ' AND ')
    {
        if (!$a) {
            return '';
        } else if ($a && is_array($a)) {
            return implode($join, $a);
        }
        return $a;
    }
}
?>