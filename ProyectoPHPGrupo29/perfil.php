<!doctype html>
<html>
<?php
	include "head.php";
	include "claseSesion.php";
	$sesion=new Sesion;
?>
<body>
<div id= "body">
	
	<!-- agregar cerrar sesion -->
	<?php
	include "nombreUsuario.php";
	
	if (!$sesion->estaLoggeado() || !$sesion->esLector()) {
		header("Location:index.php");
	}
	?>
	
	<div id="primeraparte">
	
		<a href="index.php"><div id= "logo"></div></a>
	
	</div>

	<?php
	include "abrir_conexion.php";

	$id_usuario=$sesion->getId();

	$queryUsuario="SELECT * FROM usuarios u WHERE u.id='$id_usuario'";
	$consultaUsuario=mysqli_query($conexion,$queryUsuario);
	$filaUsuario=mysqli_fetch_array($consultaUsuario);

	?>


	<div style="width: 1007px; margin-right: 30%;" name="datosPerfil" class="container">
		<h1>Mi Perfil</h1>

		<img src="data:image/jpg;base64,<?php echo (base64_encode($filaUsuario['foto']));?>" style = "width: 15%; height: 15%; float: right;" title="Imagen del Usuario" alt="Imagen del Usuario">

		<br>	
		<p style='font-weight: bold;'>Nombre:</p><p><?php echo $filaUsuario['nombre'] ?></p>
	
		<p style='font-weight: bold;'>Apellido:</p><p><?php echo $filaUsuario['apellido'] ?></p>

		<p style='font-weight: bold;'>Email:</p><p><?php echo $filaUsuario['email'] ?></p>

	</div>


	<div id="catalogo" class="cat">
	<h2 style="margin-top: 5%"> Historial de Operaciones </h2>	
	<div id="tabla">
		<?php
		$id_usuario= $sesion->getId();

		$cantidad_resultados_por_pagina = 5;
        if (isset($_GET['pagina']) && is_numeric($_GET['pagina'])) {
            $pagina = $_GET['pagina'];
        }
       	else {
       		$pagina = 1;
       	}

        $empezar_desde = ($pagina-1) * $cantidad_resultados_por_pagina;


		$queryCantidad="SELECT COUNT(o.id) AS cantidad FROM operaciones o INNER JOIN libros l ON (o.libros_id = l.id) INNER JOIN autores a ON (l.autores_id = a.id) WHERE o.lector_id = $id_usuario";

		$consultaCantidad=mysqli_query($conexion,$queryCantidad);
		$total_registros = mysqli_fetch_array($consultaCantidad);
		$total_paginas = ceil($total_registros['cantidad'] / $cantidad_resultados_por_pagina);


		$queryTabla="SELECT l.portada,l.id AS id_libro, l.titulo, a.nombre, a.apellido, a.id, o.ultimo_estado, o.fecha_ultima_modificacion FROM operaciones o INNER JOIN libros l ON (o.libros_id = l.id) INNER JOIN autores a ON (l.autores_id = a.id) WHERE o.lector_id = $id_usuario ORDER BY ultimo_estado LIMIT ".$empezar_desde.", ".$cantidad_resultados_por_pagina;


		?>

	

		<table class="table-bordered">
		<thead>
			<tr>
				<th class="columnafoto"> Portada </th>
				<th class = "columnatitulo"> Título  </th>
				<th> Autor </th>
				<th> Estado </th>
				<th> Fecha </th>
			</tr>
		</thead>
		<tbody>

			<?php

			$consultaTabla=mysqli_query($conexion,$queryTabla);

			while ($fila = mysqli_fetch_array($consultaTabla)){

			?>
			<tr>
			<td class = "columnafoto" >
				<img src="data:image/jpg;base64,<?php echo (base64_encode($fila['portada']));?>" style = "width: 100%; height: 100%;" title="Imagen del Libro " alt="Imagen del Libro">
			</td>
  	        <td class = "columnatitulo"> 
               <a href="./titulo.php?id=<?php echo $fila["id_libro"]?>"> <?php echo $fila ["titulo"] ?> </a>
            </td>
  	        <td class= "columnaautor">
               <a href="autor.php?id=<?php echo $fila["id"]?>"> <?php echo $fila["nombre"]." ".$fila["apellido"] ?></a>
            </td>	
			<td>
				<p><?php echo $fila['ultimo_estado'] ?></p>
			</td>
			<td>
				<p><?php echo $fila['fecha_ultima_modificacion'] ?></p>
			</td>	

			</tr>
			<?php
			} //end while
			?>
		</tbody>
		</table>

		</div>

		<div id="paginas">
		<div class="btn-group" role="group" aria-label="Basic example">

		<a href="perfil.php?pagina=1"><button type="button" class="btn btn-secondary">Primer página</button> </a>

        <a href="perfil.php?pagina=<?php 

          if($pagina > 1)
             echo $pagina-1;
          else 
            echo $pagina ; 
          ?>">
        <button type="button" class="btn btn-secondary">Página anterior</button> </a>

        <a href="perfil.php?pagina=<?php
          if($pagina < $total_paginas)
            echo $pagina+1; 
          else 
            echo $pagina;
         ?>">

        <button type="button" class="btn btn-secondary">Página siguiente</button> </a>

        <a href="perfil.php?pagina=<?php echo $total_paginas;?>">
         <button type="button" class="btn btn-secondary">Última página</button> </a>

		</div>
		</div>	

	</div>
	
	
	
 </div>
<?php 
    include "script.php";
 ?>

</body>
</html>
