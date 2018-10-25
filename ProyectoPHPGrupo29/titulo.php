<!doctype html>
<html>
<?php
	include "head.php";
	include "claseSesion.php";
?>
<body>
<div id= "body">
	
	<?php
	include ('abrir_conexion.php');
    $sesion = new Sesion;
    if ($sesion->estaLoggeado()){
      include "nombreUsuario.php";

    }
    else include "login.php";
	?>
	
	<div id="primeraparte">
	
		<a href="index.php"><div id= "logo"></div></a>
	
	
	</div>

	<div style="margin-right: 30%" class = "container">
	

	<?php
    $id_libro=$_GET['id'];
		$query = "SELECT l.titulo, l.portada, a.id, a.nombre, a.apellido, l.cantidad, l.descripcion FROM libros l INNER JOIN autores a ON l.autores_id = a.id WHERE 1=1 and $id_libro = l.id";

		$consulta = mysqli_query($conexion,$query);
		if ($fila = mysqli_fetch_array($consulta)){

	?>

  <h2 style="font-weight: bold";> <?php echo $fila['titulo']?> </h2>

	<?php 
		$id_autor = $fila["id"];
    $titulo= $fila["titulo"];

		?>
		<a href="./autor.php<?php echo '?id='.$id_autor; ?>">

		<p style="font-weight: bolder;">
		<?php	
			echo $fila["nombre"].' '.$fila["apellido"];
		?>
		</p>
	 </a>
	 <?php

    		$queryOperaciones = "SELECT o.ultimo_estado FROM operaciones o WHERE $id_libro = o.libros_id";
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
            $cantidad_disponibles = $fila['cantidad'] - $cantidad_reservados -$cantidad_prestados;


	        if($sesion->estaLoggeado() && $sesion->esLector()){

            $pedidos=0;
                $idUsuario=$sesion->getId();
                $queryPedidos= "SELECT o.ultimo_estado FROM operaciones o WHERE o.lector_id = $idUsuario and (o.ultimo_estado = 'RESERVADO' or o.ultimo_estado='PRESTADO')";
                

                $consultaPedidos=mysqli_query($conexion,$queryPedidos);
                while ($filaPedidos = mysqli_fetch_array($consultaPedidos)){
                  $pedidos++;
                }

                $habilitado=true;
                $queryHabilitado= "SELECT o.ultimo_estado FROM operaciones o WHERE o.lector_id = $idUsuario and o.libros_id = $id_libro and (o.ultimo_estado = 'RESERVADO' or o.ultimo_estado='PRESTADO')";

                $consultaHabilitado= mysqli_query($conexion,$queryHabilitado);
                $contadorHabilitado= mysqli_num_rows($consultaHabilitado);
                if($contadorHabilitado){
                  $habilitado=false;
                }  

                if ($cantidad_disponibles && $pedidos<3 && $habilitado){
                  ?>
                  <td><a href="reservar.php?lector=<?php echo $idUsuario?>&libro=<?php echo $id_libro?>"><button>RESERVAR</button></a></td> <?php
                }

           }  
    
    ?>

	 <img src="data:image/jpg;base64,<?php echo (base64_encode($fila['portada']));?>" align="right" style="width: 10%; height: 15%;">


    <br/>
    <p style="font-weight: bolder;">   
                <?php
                  echo ($cantidad_prestados);
                ?> prestados, 
                <?php //Libros reservados
                  echo ($cantidad_reservados);
                ?> reservados, 
                <?php //Libros disponibles
                  
                  echo ($cantidad_disponibles);
                ?> diponibles
    </p>
    <br/>
    <p> <?php echo $fila["descripcion"];} ?></p>
	
	<?php include ("cerrar_conexion.php"); ?> 
	</div>	

</div>		
	


</body>
</html>
