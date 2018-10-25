<?php
include "claseSesion.php";
	if (!empty($_POST['mailIniciar']) and !empty($_POST['contIniciar'])) {
		try{
			$sesion = new Sesion;
		    $sesion->login($_POST['mailIniciar'],$_POST['contIniciar']);
		    if ($sesion->esLector()) {
		    	header("Location:index.php");
		    }
		    else header ("Location:backend.php");
		    
		}
		catch(Exception $e){
			$_SESSION['errorMC'] = "El mail o la contraseña son incorrectos";
			header("Location:iniciar.php?error=1");
		}
	}	
	else {
		header ("Location:iniciar.php");
		} 
?>