<?php 
	include ("abrir_conexion.php");
 
			$nombre = $_POST['nombre'];
			$apellido = $_POST['apellido'];
			$mail = $_POST['mail'];
			$cont = $_POST['cont'];
			$conf = $_POST['conf'];
			$rol = 'LECTOR';

			session_start();
		
			$soloLetras = "/^[a-zA-z\s]*$/";
			$password = "/^((?=.*\d)|(?=.*\W+))(?=.*[A-Z])(?=.*[a-z])\S{6,16}$/";
			//validacion de los campos

			$ok=true;

			if (empty($nombre) or empty($apellido) or empty($mail) or empty($cont) or empty($conf) ) {
				$_SESSION['error_vacios']="Debe completar todos los campos";
				$ok=false;	
			}                                              
			if (!preg_match($soloLetras, $nombre)){
				$_SESSION['error_nombre']="El nombre debe estar compuesto por letras";
				$ok=false;
			}
			if(!preg_match($soloLetras, $apellido)){
				$_SESSION['error_apellido']="El apellido debe estar compuesto por letras";
				$ok=false;
	
			}
			if (!preg_match($password, $cont)) {
				$_SESSION['error_clave']="La contraseña debe tener al menos 6 caracteres, incluyendo una mayúscula, una minúscula y un caracter especial o número";
				$ok=false;
			}
			if(!empty($mail)){	
				if(!filter_var($mail,FILTER_VALIDATE_EMAIL)){//valida el mail
					$_SESSION['error_mail']='El email debe respetar el formato de tipo "nombre@gmail.com"';
					$ok=false;
				}
			}
			if (is_uploaded_file($_FILES['foto']['tmp_name'])){
				$foto = addslashes(file_get_contents($_FILES['foto']['tmp_name']));	
			}
			else {
				$_SESSION['error_foto']="Debe subir una foto de perfil";
				$ok=false;
			}


			$registro = "SELECT email FROM usuarios where email='$mail'";//me fijo si la direccion de mail figura en la base de datos
			$consulta = mysqli_query($conexion,$registro);


			if ($fila = mysqli_fetch_array($consulta)){//Si tiene contenido es porque se trajo una direccion de mail igual a la ingresada en ek campo mail
				$_SESSION['error_mail_existente']="El mail ingresado ya se encuentra registrado en el sistema";
				$ok=false;
			}

		
			if ($ok){ //Se insertan los datos en la base
					$conexion->query("INSERT INTO usuarios (email,nombre,apellido,foto,clave,rol) values ('$mail','$nombre','$apellido','$foto','$cont', '$rol')");
					header("Location:bienvenido.php");
			}
			else { //Guarda los valores para devolverlos al formulario de registro
				$_SESSION['nombre']=$nombre;
				$_SESSION['apellido']=$apellido;
				$_SESSION['mail']=$mail;
				header ("Location:registro.php");
			}
 
    		

	include ("cerrar_conexion.php");
?>