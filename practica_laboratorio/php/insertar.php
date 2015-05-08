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
 *  Recogida de datos del ajax
 */

$doctor = $_POST["doctor"];
$numcolegiado = $_POST["numcolegiado"];
$id_clinicas = $_POST["clinicas"];

/*
 * SQL queries
 * Get data to display
 */

$sQuery = "select MAX(id_doctor) as id_doctor from doctores";
$rResult = mysql_query($sQuery, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());
$resultado = mysql_fetch_assoc($rResult);
$id_doctor = $resultado['id_doctor'];
$id_doctor += 1;

if (empty($numcolegiado)) {
    $sQuery = 'insert into doctores (id_doctor,nombre) values (' . $id_doctor . ', "' . $doctor . '")';
} else {
    $sQuery = 'insert into doctores (id_doctor,nombre, numcolegiado) values (' . $id_doctor . ', "' . $doctor . '",' . $numcolegiado . ' )';
}

mysql_query($sQuery, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());

for ($i = 0; $i < count($id_clinicas); $i++) {
    if (empty($numcolegiado)) {
        $sQuery2 = 'insert into clinica_doctor (id_doctor, id_clinica) values (' . $id_doctor . ',' . $id_clinicas[$i] . ')';
        mysql_query($sQuery2, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());
    } else {
        $sQuery2 = 'insert into clinica_doctor (id_doctor, id_clinica, numdoctor) values (' . $id_doctor . ',' . $id_clinicas[$i] . ',' . $numcolegiado . ')';
        mysql_query($sQuery2, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());
    }
}

//echo json_encode("");

echo "[" . $id_doctor . "] " . $doctor;
?>