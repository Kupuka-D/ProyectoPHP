<div id="login" class="log">
  <a href="
  <?php echo ($sesion->esLector()) ? 'perfil.php' : 'backend.php'?>"> <button type="button" class="btn btn-light"><?php echo ($_SESSION['nombre'].' '.$_SESSION['apellido']); ?></button></a>
  <a href="cerrarSesion.php"> <button type="button" class="btn btn-light">Cerrar Sesión</button></a>
</div>