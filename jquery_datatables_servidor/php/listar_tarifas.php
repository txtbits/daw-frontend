<?php

/* Database connection information */
/*$gaSql = array();*/
/*include( $_SERVER['DOCUMENT_ROOT']."/datatables_servidor/php/mysql.php" );*/
$gaSql['user'] = "root";
$gaSql['password'] = "root";
$gaSql['db'] = "laboratorio";
$gaSql['server'] = "localhost";


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
$sQuery = "select id_tarifa, nombre from tarifas order by nombre";

$rResult = mysql_query($sQuery, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());

$resultado = array();
while ($fila = mysql_fetch_array($rResult)) {
    $resultado[] = array(
      'id_tarifa' => $fila['id_tarifa'],
      'nombre' => $fila['nombre']
   );
}
echo json_encode($resultado);
?>