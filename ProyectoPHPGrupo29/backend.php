<!doctype html>
<html>
<?php
	include "head.php";
	include "claseSesion.php";
	$sesion=new Sesion;
?>
<body>
<div id= "body">

	<?php
	include "nombreUsuario.php";

	if (!$sesion->estaLoggeado() || $sesion->esLector()) {
		header("Location:index.php");
	}

	if (isset($_GET['ok'])) {
      ?>
       <div style="width: 50%;" class="alert alert-warning alert-dismissible fade show" role="alert">
         <strong>Operación realizada con éxito!</strong>
       </div>
       <?php
    }

	?>
	
	<div id="primeraparte">
	
		<a href="index.php"><div id= "logo"></div></a>	

		<div id="buscador">
		<form style="height:500px;" method="GET" id= "formulario" class = "form-buscador" >
			<div class="form-group">
				<label for="titulo">Título</label>
				<input value="<?php echo (isset($_GET['Titulo'])) ? $_GET['Titulo'] : '' ?>" type="text" name= "Titulo" class="form-control" id="Titulo" placeholder="Título del libro" maxlength="45">
			</div>
			<div class="form-group">
				<label for="autor">Autor</label>
				<input value= "<?php echo (isset($_GET['Autor'])) ? $_GET['Autor'] : '' ?>" type="text" class="form-control" id="Autor" name = "Autor" placeholder="Autor del libro" maxlength="45">
			</div>
			<div class="form-group">
				<label for="lector">Lector</label>
				<input value= "<?php echo (isset($_GET['Lector'])) ? $_GET['Lector'] : '' ?>" type="text" class="form-control" id="Lector" name = "Lector" placeholder="Lector" maxlength="45">
			</div>
			<div class="form-group">
				<label for="fecha">Fecha Desde</label>
				<input value= "<?php echo (isset($_GET['FechaDesde'])) ? $_GET['FechaDesde'] : '' ?>" type="date" class="form-control" id="FechaDesde" name = "FechaDesde" placeholder="DD/MM/AAAA">
			</div>
			<div class="form-group">
				<label for="fecha">Fecha Hasta</label>
				<input value= "<?php echo (isset($_GET['FechaHasta'])) ? $_GET['FechaHasta'] : '' ?>" type="date" class="form-control" id="FechaHasta" name = "FechaHasta" placeholder="DD/MM/AAAA">
			</div>
			<button type="submit" style= "float: right" class="btn btn-light">Buscar</button>
		</form>
		</div>

	</div>



	<div id="catalogo" class="cat">
	<h2 style="margin-top: 20%"> Operaciones </h2>	
	<div id="tabla">
		<?php
		//CONSULTAS

		include "abrir_conexion.php";

		$queryTabla="SELECT l.titulo,l.id AS id_libro, a.nombre AS nombre_autor, a.id AS id_autor ,a.apellido AS apellido_autor, u.nombre AS nombre_lector, u.apellido AS apellido_lector, o.ultimo_estado, o.fecha_ultima_modificacion, o.id AS id_operacion FROM operaciones o INNER JOIN libros l ON (o.libros_id = l.id) INNER JOIN autores a ON (l.autores_id = a.id) INNER JOIN usuarios u ON (o.lector_id = u.id) WHERE 1=1";




        $query = "";
        if (!empty($_GET["Titulo"] )){
            $query= $query." and (l.titulo like '%". $_GET["Titulo"]."%')";
        }
      
        if (!empty($_GET["Autor"])){
          $query= $query." and (CONCAT(a.nombre,' ',a.apellido) like '%". $_GET["Autor"]."%' or CONCAT(a.apellido,' ',a.nombre) like '%". $_GET["Autor"]."%')";
        }

		if (!empty($_GET["Lector"])){
          $query= $query." and (CONCAT(u.nombre,' ',u.apellido) like '%". $_GET["Lector"]."%' or CONCAT(u.apellido,' ',u.nombre) like '%". $_GET["Lector"]."%')";
        }    

        if (!empty($_GET["FechaDesde"])) { //invierto la fecha ingresada en el formulario para poder procesarla
        	$fecha_desde= $_GET['FechaDesde'];
			$fecha_desde= date("Y-m-d",strtotime($fecha_desde));

        	$query= $query." and (o.fecha_ultima_modificacion >= '".$fecha_desde."')";
        }

        if (!empty($_GET["FechaHasta"])) {
			$fecha_hasta= $_GET['FechaHasta'];
			$fecha_hasta= date("Y-m-d",strtotime($fecha_hasta));

        	$query= $query." and (o.fecha_ultima_modificacion <= '".$fecha_hasta."')";
        }


        $queryTabla= $queryTabla.$query." ORDER BY ultimo_estado";    


		?>


		<table class="table-bordered">
		<thead>
			<tr>
				<th> Titulo </th>
				<th> Autor </th>
				<th> Lector </th>
				<th> Estado </th>
				<th> Fecha </th>
				<th> Acción </th>
			</tr>
		</thead>
		<tbody>
			<?php

			$consultaTabla = mysqli_query($conexion,$queryTabla);

			while ($fila = mysqli_fetch_array($consultaTabla)){
			?>

		</tbody>
			<tr>
 	        <td class = "columnatitulo"> 
               <a href="./titulo.php?id=<?php echo $fila["id_libro"]?>"> <?php echo $fila ["titulo"] ?> </a>
            </td>
 	        <td class= "columnaautor">
               <a href="autor.php?id=<?php echo $fila["id_autor"]?>"> <?php echo $fila["nombre_autor"]." ".$fila["apellido_autor"] ?></a>
            </td>
            <td>
				<p><?php echo $fila['nombre_lector']." ".$fila['apellido_lector'] ?></p>            	
            </td>	
			<td>
				<p><?php echo $fila['ultimo_estado'] ?></p>
			</td>
			<td>
				<p><?php
				$fecha= $fila['fecha_ultima_modificacion'];
				$fecha= date("d-m-Y",strtotime($fecha)); 
				echo $fecha ?></p>
			</td>
			<td>
				<?php
					if ($fila['ultimo_estado'] == 'RESERVADO') {
						?><a href="prestar.php?id=<?php echo $fila['id_operacion']?>"><button>PRESTAR</button></a>
					<?php	
					}
					elseif ($fila['ultimo_estado'] == 'PRESTADO') {
						?><a href="devolver.php?id=<?php echo $fila['id_operacion']?>"><button>DEVOLVER</button></a>
					<?php	
					}
				?>
			</td>

			</tr>
			<?php
			}?>
	
		</table>

		</div>

	</div>


		
	
	
	
</div>
<?php 
    include "script.php";
 ?>

</body>
</html>
