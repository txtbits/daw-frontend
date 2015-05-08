<?php

/* Database connection information */
include( $_SERVER['DOCUMENT_ROOT']."/tareadatatables/php/mysql.php" );
/*
$gaSql['user'] = "root";
$gaSql['password'] = "root";
$gaSql['db'] = "rauldatabase";
$gaSql['server'] = "localhost";
*/

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

$nombredoctor = $_REQUEST["nombredoctor"];
$numcolegiado = $_REQUEST["numcolegiado"];
$clinicas = $_REQUEST["clinicas"];

/*
 * SQL queries
 * Get data to display
 */

$sQuery = "select MAX(id_doctor) as id_doctor from doctores";
$rResult = mysql_query($sQuery, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());
$resultado = mysql_fetch_assoc($rResult);
$id_doctor = $resultado['id_doctor'];
$id_doctor += 1;


if (empty($numcolegiado))
    $sQuery = 'insert into doctores (id_doctor,nombre) values (' .$id_doctor . ', "'.$nombredoctor . '")';
else
    $sQuery = 'insert into doctores (id_doctor,nombre, numcolegiado) values (' .$id_doctor . ', "'.$nombredoctor . '",' .$numcolegiado .' )';

$rResult = mysql_query($sQuery, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());


$array_clinicas = explode(",", $clinicas);
for($i=0; $i < count($array_clinicas); ++$i){
    $numdoctor = strval($array_clinicas[$i]) . strval($id_doctor);
    $numdoctor = (int)$numdoctor;
    echo "numero " . $numdoctor;
        $sQuery = 'insert into clinica_doctor (id_clinica, id_doctor, numdoctor) values (' .$array_clinicas[$i] . ',' .$id_doctor. ',' .$numdoctor .')';
        $rResult = mysql_query($sQuery, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());
}

/*
//echo json_encode($rResult);
*/
?>