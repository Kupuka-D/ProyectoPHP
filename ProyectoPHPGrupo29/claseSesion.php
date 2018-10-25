<?php
class Sesion extends Exception
{
	
	public function __construct()
	{
		session_start();
	}

	public function start($usuario){
		$_SESSION['id'] = $usuario['id'];
		$_SESSION['rol'] = $usuario['rol'];
		$_SESSION['nombre'] = $usuario['nombre'];
		$_SESSION['apellido'] = $usuario['apellido'];
	}

	public function destroy(){
		session_destroy();
	}

	public function estaLoggeado(){
		if (isset($_SESSION['id'])){
			return true;
		}
		else return false;
	}


	public function esLector(){
		if ($_SESSION['rol'] == 'LECTOR') {
			return true;
		}
		else return false;
	}


	public function login($email,$clave){
		include "abrir_conexion.php";
		$query = "SELECT * FROM usuarios u WHERE u.email = '$email' and u.clave = '$clave'";
		$consulta = mysqli_query($conexion,$query);
		if (mysqli_num_rows($consulta)){
			$usuario = mysqli_fetch_array($consulta);
			$this->start($usuario);
			return true;
		}
		throw new Exception("Error", 1);
		return false;
	}

	public function getId(){
		return $_SESSION['id'];
	}

}

?>