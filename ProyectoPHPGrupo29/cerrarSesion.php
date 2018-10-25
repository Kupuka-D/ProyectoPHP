<?php
include "claseSesion.php";
$sesion = new Sesion;
$sesion->destroy();
header ("Location:index.php");
?>