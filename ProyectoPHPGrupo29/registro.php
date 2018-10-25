<!doctype html>
<html>
<?php
	include "head.php";
?>
<body>
<div id= "body">
	
	<!--  Primera parte (logo) -->

	<div id="primeraparte">
	
		<a href="index.php"><div id= "logo"></div></a>
	
	</div>
	
	<!--  Formulario de registro -->
	<?php
		session_start();

		if (isset($_SESSION['error_vacios'])){
			?>
			<div class="alert alert-warning alert-dismissible fade show" role="alert"> 
			   <strong>Error!</strong> <?php echo($_SESSION['error_vacios']) ?>
			</div>
		<?php
		unset($_SESSION['error_vacios']);	
		}
		if (isset($_SESSION['error_mail_existente'])){
			?>
			<div class="alert alert-warning alert-dismissible fade show" role="alert"> 
			   <strong>Error!</strong> <?php echo($_SESSION['error_mail_existente']) ?>
			</div>
		<?php
		unset($_SESSION['error_mail_existente']);	
		}			



	?>


	<div id="REGISTRO">
		
		<h2> Registro de lector </h2>
		<div id = "formulario">
			<form  action ="procesarRegistro.php" method ="POST" enctype="multipart/form-data" onsubmit="return validarRegistro();">
			
			<div class="form-reg">
				<label for="Nombre">Nombre</label>
				<input value="<?php echo (isset($_SESSION['nombre'])) ? $_SESSION['nombre'] : ''; unset($_SESSION['nombre']) ?>" type="text" class="form-control" name = "nombre" id="nombre" placeholder="Nombre completo" maxlength="45">
			</div>
			<?php
			if (isset($_SESSION['error_nombre'])){
				?>
				<div class="alert alert-warning alert-dismissible fade show" role="alert">
 					 <strong>Error!</strong> <?php echo($_SESSION['error_nombre']) ?>
				</div>
			<?php
			unset($_SESSION['error_nombre']);	
			}			
			?>

			
			<div class="form-reg">
				<label for="Apellido">Apellido</label>
				<input value="<?php echo (isset($_SESSION['apellido'])) ? $_SESSION['apellido'] : ''; unset($_SESSION['apellido']) ?>" type="text" class="form-control" name = "apellido" id="apellido" placeholder="Apellido/s" maxlength="45">
			</div>
			<?php
			if (isset($_SESSION['error_apellido'])){
				?>
				<div class="alert alert-warning alert-dismissible fade show" role="alert">
 					 <strong>Error!</strong> <?php echo($_SESSION['error_apellido']) ?>
				</div>
			<?php	
			unset($_SESSION['error_apellido']);
			}			
			?>
			
			<div class="form-reg">
				<label for="Foto">Foto</label>
				<input type="file" class="form-control-file" name="foto" id="foto">
			</div>
			<?php
			if (isset($_SESSION['error_foto'])){
				?>
				<div class="alert alert-warning alert-dismissible fade show" role="alert">
 					 <strong>Error!</strong> <?php echo($_SESSION['error_foto']) ?>
				</div>
			<?php
			unset($_SESSION['error_foto']);	
			}			
			?>		
			
			<div class="form-reg">
				<label for="email">Email</label>
				<input value="<?php echo (isset($_SESSION['mail'])) ? $_SESSION['mail'] : ''; unset($_SESSION['mail']) ?>" type="email" class="form-control" name = "mail" id="mail" aria-describedby="emailHelp" placeholder="Email" maxlength="45">
			</div>
			<?php
			if (isset($_SESSION['error_mail'])){
				?>
				<div class="alert alert-warning alert-dismissible fade show" role="alert">
 					 <strong>Error!</strong> <?php echo($_SESSION['error_mail']) ?>
				</div>
			<?php	
			unset($_SESSION['error_mail']);
			}			
			?>

			
			<div class="form-reg">
				<label for="contraseña">Contrase&ntilde;a</label>
				<input type="password" class="form-control" name ="cont" id="cont" placeholder="Contraseña" maxlength="45">
			</div>
			<?php
			if (isset($_SESSION['error_clave'])){
				?>
				<div class="alert alert-warning alert-dismissible fade show" role="alert">
 					 <strong>Error!</strong> <?php echo($_SESSION['error_clave']) ?>
				</div>
			<?php	
			unset($_SESSION['error_clave']);
			}			
			?>
	
			<div class="form-reg">
				<label for="contraseña">Repetir contrase&ntilde;a</label>
				<input type="password" class="form-control" name="conf" id="conf" placeholder="Repetir contraseña" maxlength="45">
			</div>
	
			<button type="submit" class="btn btn-light" style = "margin-top: 3%" name = "registrar" >Registrar</button>
			</form>		
		
		</div>
		
    </div>
		
 </div>
<?php 
    include "script.php";
 ?>

</body>
</html>
