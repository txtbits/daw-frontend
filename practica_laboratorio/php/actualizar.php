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

$id_doctor = $_POST["id_doctor"];
$doctor = $_POST["doctor"];
$numcolegiado = $_POST["numcolegiado"];
$clinicas = $_POST["clinicas"];

/* // Ya que el numcolegiado no es obligatorio, si viene vacio, le ponemos el mismo que id_doctor
  if ($numcolegiado == ""){
  $numcolegiado = $id_doctor;
  } */

/*
 * SQL queries
 * Get data to display
 */

// Actualizamos en la tabla doctores el nombre del doctor y el num. de colegiado.
$sQuery = "UPDATE doctores SET 
            nombre = '" . $doctor . "', 
            numcolegiado = '" . $numcolegiado . "'
            WHERE id_doctor = " . $id_doctor;

mysql_query($sQuery, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());

// Eliminamos el registro del doctor de la tabla que relaciona las clinicas con los doctores
$sQuery2 = "DELETE FROM clinica_doctor WHERE id_doctor =" . $id_doctor;

mysql_query($sQuery2, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());

// Añadimos el registro del doctor actualizado a la tabla que relaciona las clinicas con los doctores
for ($i = 0; $i < count($clinicas); $i++) {
    if (empty($numcolegiado)) {
        $sQuery3 = 'insert into clinica_doctor (id_doctor, id_clinica) values (' . $id_doctor . ',' . $clinicas[$i] . ')';
        mysql_query($sQuery3, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());
    } else {
        $sQuery3 = 'insert into clinica_doctor (id_doctor, id_clinica, numdoctor) values (' . $id_doctor . ',' . $clinicas[$i] . ',' . $numcolegiado . ')';
        mysql_query($sQuery3, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());
    }
}

//echo json_encode("");

echo "[" . $id_doctor . "] " . $doctor;
?>