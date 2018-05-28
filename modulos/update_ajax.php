<?php
include_once 'establecer_conexion.php';

$conn = establecer_conexion();

$n_horas = $_REQUEST['n_horas'];
$id_tarea = $_REQUEST['id_tarea'];

$sql = "UPDATE administrador_base SET n_horas = $n_horas, fecha_actualizacion = NOW() WHERE id_tarea = $id_tarea;";

if ($conn->query($sql) === TRUE) {
    echo 'updated';
}

?>