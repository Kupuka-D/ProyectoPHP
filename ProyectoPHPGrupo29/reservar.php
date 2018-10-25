<?php
include "abrir_conexion.php";
$lector_id=$_GET['lector'];
$libro_id=$_GET['libro'];
$tabla_fecha = getdate();
$a=$tabla_fecha['year'];
$m=$tabla_fecha['mon'];
$d=$tabla_fecha['mday'];

$fecha= "$a-$m-$d";

$conexion->query("INSERT INTO operaciones (ultimo_estado,fecha_ultima_modificacion,lector_id,libros_id) values ('RESERVADO','$fecha','$lector_id','$libro_id')");

include "cerrar_conexion.php";
header("Location:index.php?ok=1");

?>