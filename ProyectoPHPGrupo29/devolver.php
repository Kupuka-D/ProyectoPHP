<?php
include "abrir_conexion.php";
$id_operacion=$_GET['id'];
$tabla_fecha = getdate();
$a=$tabla_fecha['year'];
$m=$tabla_fecha['mon'];
$d=$tabla_fecha['mday'];

$fecha= "$a-$m-$d";

$conexion->query("UPDATE operaciones o SET o.ultimo_estado = 'DEVUELTO', o.fecha_ultima_modificacion = '$fecha' WHERE o.id = '$id_operacion'");

include "cerrar_conexion.php";
header("Location:backend.php?ok=1");

?>