<!doctype html>
<html>
<?php
	include "head.php";
?>	
<body>
<div id= "body">
	
	
	<div id="primeraparte">
	
		<a href="index.php"><div id= "logo"></div></a>	
	</div>

	<?php
		session_start();
		if(isset($_GET['error'])){
			?>
			<div style="width: 40%;" class="alert alert-warning alert-dismissible fade show" role="alert"> 
				 <strong>Error!</strong> Los datos son erróneos!
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				 </button>
			</div><?php
		}
		if (isset($_SESSION['errorMC'])){
			?>
			<div class="alert alert-warning alert-dismissible fade show" role="alert"> 
			   <strong>Error!</strong> <?php echo($_SESSION['errorMC']) ?>
			</div>
	<?php		
		}
	?>
	
	<div id="INICIAR">
		
		<h2> Iniciar Sesión </h2>
		<div id = "formulario">
			<form action="procesarIniciar.php" method="POST" onsubmit="return validarIniciar();">
			
			<div class="form-reg">
				<label for="Nombre">Email</label>
				<input value="<?php echo(isset($_SESSION['mailIniciar'])) ? $_SESSION['mailIniciar'] : ''; unset($_SESSION['mailIniciar']) ?>" type="text" class="form-control"  id="mailIniciar" name="mailIniciar" placeholder="Email" maxlength="45">
			</div>
			<?php
			if (isset($_SESSION['errorMC'])){
				?>
				<div class="alert alert-warning alert-dismissible fade show" role="alert">
 					 <strong>Error!</strong> <?php echo($_SESSION['errorMC']) ?>
				</div>
			<?php
			unset($_SESSION['errorMC']);	
			}			
			?>
	
			
			<div class="form-reg">
				<label for="contraseña">Contrase&ntilde;a</label>
				<input type="password" class="form-control" id="contIniciar" name="contIniciar" placeholder="Contraseña" maxlength="45">
			</div>
			
			
			<button type="submit" class="btn btn-light" style = "margin-top: 3%" >Iniciar Sesión</button>
			</form>

			<p> No tenés una cuenta ? <a href="registro.php"><big>Registrate</big></a></p>
		
		
		</div>
		
    </div>
	
	
 </div>
<?php 
    include "script.php";
 ?>

</body>
</html>
