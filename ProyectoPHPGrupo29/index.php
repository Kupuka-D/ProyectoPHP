
<!doctype html>
<html>
<?php
	include "head.php";
  include "claseSesion.php";
?>
<body>
<div id= "body">

	
	<?php


    $sesion = new Sesion;
    if ($sesion->estaLoggeado()){
      include "nombreUsuario.php";
    }
    else include "login.php";

    if (isset($_GET['ok'])) {
      ?>
        <div style="width: 50%;" class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Operación realizada con éxito!</strong> El libro fue reservado correctamente!
        </div>
       <?php
    }
	?>

	<!--  Primera parte, formulario y logo -->

	<div id="primeraparte">
	
		<div id= "logo">
		</div>
	
		<div id="buscador">
		<form method="GET" id= "formulario" class = "form-buscador" onsubmit="return validarBuscador();">
			<div class="form-group">
				<label for="titulo">Título</label>
				<input value="<?php echo (isset($_GET['Titulo'])) ? $_GET['Titulo'] : '' ?>" type="text" name= "Titulo" class="form-control" id="Titulo" placeholder="Título del libro" maxlength="45">
			</div>
			<div class="form-group">
				<label for="autor">Autor</label>
				<input value= "<?php echo (isset($_GET['Autor'])) ? $_GET['Autor'] : '' ?>" type="text" class="form-control" id="Autor" name = "Autor" placeholder="Autor del libro" maxlength="45">
			</div>
			<button type="submit" style= "float: right" class="btn btn-light">Buscar</button>
		</form>
		</div>
	
	</div>
	
	<!-- Catalogo de libros (tabla) -->
	
	<div id="catalogo" class ="cat">
		<h2> Catálogo de libros </h2> 
		<div id="tabla">
			<table class="table-bordered">
			<thead>
				<tr>
					<th> Portada </th>
					<th> Título  </th>
					<th> Autor </th>
					<th> Ejemplares </th>
          <?php
          if ($sesion->estaLoggeado() && $sesion->esLector()) {
            ?>
            <th> Acción </th> <?php
          }
          ?>
				</tr>
			</thead>
			<tbody>
			<?php
          include "abrir_conexion.php";
          //paginacion

            $cantidad_resultados_por_pagina = 5;
            if (isset($_GET['pagina']) && is_numeric($_GET['pagina'])) {
              $pagina = $_GET['pagina'];
            }
           	else {
           		$pagina = 1;
           	};
            //Define el número 0 para empezar a paginar multiplicado por la cantidad de resultados por página
            $empezar_desde = ($pagina-1) * $cantidad_resultados_por_pagina;
           
            $querylibros= "SELECT l.portada, l.titulo, l.id AS libro,a.id, a.nombre, a.apellido, l.cantidad FROM libros l INNER JOIN autores a ON l.autores_id = a.id where 1 = 1";
            
            $querycantidad = "SELECT COUNT(l.id) AS cantidad FROM libros l INNER JOIN autores a ON l.autores_id = a.id where 1 = 1";
            

            $query = "";
            if (!empty($_GET["Titulo"] )){
                $query= $query." and (l.titulo like '%". $_GET["Titulo"]."%')";
            }
      
            if (!empty($_GET["Autor"])){
              $query= $query." and (CONCAT(a.nombre,' ',a.apellido) like '%". $_GET["Autor"]."%' or CONCAT(a.apellido,' ',a.nombre) like '%". $_GET["Autor"]."%')";
            }
              

            $sinlimit = $querycantidad.$query;
             // necesito una variable que no este limitada por la cantidad de libros por pagina

            $querylibros = $querylibros.$query." ORDER BY titulo LIMIT ".$empezar_desde.", ".$cantidad_resultados_por_pagina;

            //Realiza la consulta en el orden de ID ascendente (cambiar "id" por, por ejemplo, "nombre" o "edad", alfabéticamente, etc.)
            
            $consulta = mysqli_query($conexion, $sinlimit); // trae la cantidad (integer) de libros por ID
            $total_registros = mysqli_fetch_array($consulta);
            $total_paginas = ceil($total_registros['cantidad'] / $cantidad_resultados_por_pagina); //obtengo la cantidad de paginas en base al integer recibido en consulta sinlimit
            

            $consulta2 = mysqli_query($conexion, $querylibros); // trae solo 5 libros

            while ($fila = mysqli_fetch_array($consulta2)){
              ?>
             <tr> 
  	      	   <td class = "columnafoto" > 
  	    	  	   <img src="data:image/jpg;base64,<?php echo (base64_encode($fila['portada']));?>" style = "width: 100%; height: 100%;" title="Imagen del Libro " alt="Imagen del Libro">
  	      	   </td>
  	      	   <td class = "columnatitulo"> 
                 <a href="./titulo.php?id=<?php echo $fila["libro"]?>"> <?php echo $fila ["titulo"] ?> </a>
               </td>
  	           <td class= "columnaautor">
                 <a href="autor.php?id=<?php echo $fila["id"]?>"> <?php echo $fila["nombre"]." ".$fila["apellido"] ?></a>
               </td>
  	    	     <td class="columnaCantidad"> <?php echo $fila["cantidad"]?> 
              (
                <?php  //Libros prestados 
                  $libro_id = $fila["libro"];
                  $queryOperaciones = "SELECT o.ultimo_estado FROM operaciones o WHERE $libro_id = o.libros_id";
                  $consultaOperaciones = mysqli_query($conexion,$queryOperaciones);
                  $cantidad_reservados=0;
                  $cantidad_prestados=0;
                  while ($filaOperaciones = mysqli_fetch_array($consultaOperaciones)){
                    if ($filaOperaciones["ultimo_estado"] == 'PRESTADO'){
                      $cantidad_prestados++;
                    }
                    elseif ($filaOperaciones["ultimo_estado"] == 'RESERVADO') {
                      $cantidad_reservados++;
                    }
                  }
                  echo ($cantidad_prestados);
                ?> prestados, 
                <?php //Libros reservados
                  
                  echo ($cantidad_reservados);
                ?> reservados, 
                <?php //Libros disponibles
                  $cantidad_disponibles = $fila['cantidad'] - $cantidad_reservados - $cantidad_prestados;
                  $_SESSION['cantdisp']= $cantidad_disponibles;
                  echo ($cantidad_disponibles);
                ?> diponibles) </td>

        
              <?php
              if($sesion->estaLoggeado() && $sesion->esLector()){
                $pedidos=0;
                $idUsuario=$sesion->getId();
                $queryPedidos= "SELECT COUNT(o.ultimo_estado) AS pedidos  FROM operaciones o WHERE o.lector_id = $idUsuario and (o.ultimo_estado = 'RESERVADO' or o.ultimo_estado='PRESTADO')";
                

                $consultaPedidos=mysqli_query($conexion,$queryPedidos);
                if($filaPedidos = mysqli_fetch_array($consultaPedidos)){
                  $pedidos = $filaPedidos['pedidos'];
                }
    

                $habilitado=true;
                $queryHabilitado= "SELECT o.ultimo_estado FROM operaciones o WHERE o.lector_id = $idUsuario and o.libros_id = $libro_id and (o.ultimo_estado = 'RESERVADO' or o.ultimo_estado='PRESTADO')";

                $consultaHabilitado= mysqli_query($conexion,$queryHabilitado);
                $contadorHabilitado= mysqli_num_rows($consultaHabilitado);
                if($contadorHabilitado){
                  $habilitado=false;
                }  

                if ($cantidad_disponibles && $pedidos<3 && $habilitado){
                  ?>
                  <td><a href="reservar.php?lector=<?php echo $idUsuario?>&libro=<?php echo $libro_id?>"><button>RESERVAR</button></a></td> <?php
                }
              }  
              ?>
  	          </tr>
              <?php } // end while
                include "cerrar_conexion.php";
              ?> 
			</tbody>
			</table>
		
		</div>
	  
		<div id="paginas">
			<div class="btn-group" role="group" aria-label="Basic example">


				<a href="index.php?pagina=1<?php
          if (isset($_GET['Titulo']))
            echo '&Titulo='.$_GET['Titulo'];
          else echo '' ;
          if (isset($_GET['Autor']))
            echo '&Autor='.$_GET['Autor'];
          else echo '' ?>"> 
        <button type="button" class="btn btn-secondary">Primer página</button> </a>


			  <a href="index.php?pagina=<?php 

          if($pagina > 1)
             echo $pagina-1;
          else 
            echo $pagina ; 
          if (isset($_GET['Titulo']))
            echo '&Titulo='.$_GET['Titulo'];
          else echo '';
          if (isset($_GET['Autor']))
            echo '&Autor='.$_GET['Autor'];
          else echo '' ?>">

        <button type="button" class="btn btn-secondary">Página anterior</button> </a>


				<a href="index.php?pagina=<?php
          if($pagina < $total_paginas)
            echo $pagina+1; 
          else 
            echo $pagina;
         if (isset($_GET['Titulo'])) 
            echo '&Titulo='.$_GET['Titulo']; 
         else echo '' ;
         if (isset($_GET['Autor']))
            echo '&Autor='.$_GET['Autor'];
          else echo '' ?>">

        <button type="button" class="btn btn-secondary">Página siguiente</button> </a>


				<a href="index.php?pagina=<?php echo $total_paginas;
          if (isset($_GET['Titulo']))
            echo '&Titulo='.$_GET['Titulo'];
          else echo '' ;
          if (isset($_GET['Autor']))
            echo '&Autor='.$_GET['Autor'];
          else echo '' ?>">
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
