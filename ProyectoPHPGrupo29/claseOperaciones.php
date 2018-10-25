<?php

class claseOperaciones
{
	
	public function __construct()
	{
		session_start();
	}

	public function setReserva(){
		$_SESSION['reservado']="El libro fue reservado correctamente.";
	}

	public function setPrestado(){
		$_SESSION['prestado']="El libro fue prestado correctamente.";
	}

	public function setDevuelto(){
		$_SESSION['devuelto']="El libro fue devuelto correctamente.";
	}

	public function destroy(){
		session_destroy();
	}

}
?>