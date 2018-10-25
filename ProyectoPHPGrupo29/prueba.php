<?php 
include "abrir_conexion.php"; 
$querycantidad = "SELECT COUNT(o.id) AS cantidad FROM operaciones o  where ultimo_estado = 'RESERVADO'";
$consulta = mysqli_query($conexion, $querycantidad); // trae la cantidad (integer) de libros por ID
$total_registros = mysqli_fetch_array($consulta);
echo ($total_registros['cantidad']); 
 ?>           
