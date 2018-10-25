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
	?>
	
	<div id="primeraparte">
		<a href="index.php"><div id= "logo"></div></a>
	</div>
	
	
	<div class ="autor">
		<h2> Libros de 
      <?php
      include "abrir_conexion.php";

      $id=$_GET['id'];

      $query="SELECT * FROM autores a where a.id = $id"; 

       $consulta=mysqli_query($conexion, $query);
       if($fila = mysqli_fetch_array($consulta)){             
         $nombre=$fila['nombre']; 
         $apellido=$fila['apellido'];
       }
       echo $nombre.' '.$apellido;

      ?> </h2> 

		<div id="tabla">
			<table class="table-bordered">
			<thead>
				<tr>
					<th> Portada </th>
					<th> Título  </th>
					<th> Ejemplares </th>
				</tr>
			</thead>
			<tbody>
			<?php	
			
              $pagina = 1; 
              $cantidad_resultados_por_pagina = 5;

              if (isset($_GET['pagina']) && is_numeric($_GET['pagina'])) {
                   $pagina = $_GET['pagina'];
              }
              else {
                   $pagina = 1;
              }

              
              $empezar_desde = ($pagina-1) * $cantidad_resultados_por_pagina;
              
           
              $querycantidad="SELECT COUNT(l.id) AS cantidad FROM libros l where 1=1 and l.autores_id = $id";
              $querylibros="SELECT * FROM libros l where 1=1 and l.autores_id = $id";


              $query= $querylibros." ORDER BY titulo LIMIT ".$empezar_desde.", " .$cantidad_resultados_por_pagina;

              $consulta = mysqli_query($conexion, $query);
              $consulta2 = mysqli_query($conexion, $querycantidad); // trae un numero con la cantidad de libros
              $total_registros = mysqli_fetch_array($consulta2);

              //Obtiene el total de páginas existentes
              $total_paginas = ceil($total_registros['cantidad'] / $cantidad_resultados_por_pagina); 

              while ($fila = mysqli_fetch_array($consulta)){
            ?>
             <tr> 
  	      	   <td class = "columnafoto" > 
  	    	  	   <img src="data:image/jpg;base64,<?php echo (base64_encode($fila['portada']));?>" title="Imagen del Libro " style = "width: 100%; height: 100%;" alt="Imagen del Libro">
  	      	   </td>
  	      	   <td class = "columnatitulo"> 
                 <a href="./titulo.php<?php echo '?id='.$fila["id"] ?>"> <?php echo $fila ["titulo"] ?> </a>
               </td>
  	    	     <td> <?php echo $fila["cantidad"]?> 
              (
                <?php  //Libros prestados 
                  $libro_id = $fila["id"];
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
                  $cantidad_disponibles = $fila['cantidad'] - $cantidad_reservados -$cantidad_prestados;
                  echo ($cantidad_disponibles);
                ?> diponibles) </td>
                <?php
              if($sesion->estaLoggeado() && $sesion->esLector()){
                            $pedidos=0;
                $idUsuario=$sesion->getId();
                $queryPedidos= "SELECT o.ultimo_estado FROM operaciones o WHERE o.lector_id = $idUsuario and (o.ultimo_estado = 'RESERVADO' or o.ultimo_estado='PRESTADO')";
                

                $consultaPedidos=mysqli_query($conexion,$queryPedidos);
                while ($filaPedidos = mysqli_fetch_array($consultaPedidos)){
                  $pedidos++;
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
                }              }  
              ?>
              </tr>
              <?php }
                include "cerrar_conexion.php";
              ?>
			</tbody>
			</table>
		
		</div>
	  
		<div id="paginas">
			<div class="btn-group" role="group" aria-label="Basic example">
				<a href="autor.php?pagina=1<?php
              echo '&id='.$id;
           ?>">
        <button type="button" class="btn btn-secondary">Primer página</button> </a>


				<a href="autor.php?pagina=<?php
            if($pagina > 1)
               echo $pagina-1; 
            else 
              echo $pagina;
            echo '&id='.$id;
        ?>">

        <button type="button" class="btn btn-secondary">Página anterior</button>

				<a href="autor.php?pagina=<?php
            if($pagina < $total_paginas)
               echo $pagina+1; 
             else 
               echo $pagina;
             echo '&id='.$id;
        ?>">
        <button type="button" class="btn btn-secondary">Página siguiente</button>

				<a href="autor.php?pagina=<?php
             echo $total_paginas;
             echo '&id='.$id;?>">
        <button type="button" class="btn btn-secondary">Última página</button>
			</div>
		</div>
		
    </div>
	
  </div>


</body>
</html>
