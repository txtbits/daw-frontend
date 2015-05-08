<?php

/* Database connection information */
require_once("mysql.php");

/*
 * Local functions
 */

function fatal_error($sErrorMessage = '') {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
    die($sErrorMessage);
}

/*
 * MySQL connection
 */
if (!$gaSql['link'] = mysql_pconnect($gaSql['server'], $gaSql['user'], $gaSql['password'])) {
    fatal_error('Could not open connection to server');
}

if (!mysql_select_db($gaSql['db'], $gaSql['link'])) {
    fatal_error('Could not select database ');
}

mysql_query('SET names utf8');

/*
 * SQL queries
 * Get data to display
 */

$sQuery = "SELECT id_clinica, nombre FROM clinicas order by id_clinica";
$rResult = mysql_query($sQuery, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());

$resultado = array();
while ($fila = mysql_fetch_array($rResult)) {
    $resultado[] = array(
        'id_clinica' => $fila['id_clinica'],
        'nombre' => $fila['nombre']
    );
}

echo json_encode($resultado);
?>
