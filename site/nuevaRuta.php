<?php
require_once("templates/header.php");
require_once("templates/sidebar.php");

$idRuteo = 0;
if(isset($_GET["idRuteo"])){
  $idRuteo = $_GET["idRuteo"];
}
echo "<script>var idRuteo = $idRuteo;</script>";
?>

<div id="layoutSidenav_content">

  <div class="container">
    <h2><?php echo ($idRuteo == 0 ? 'Nueva':'Editar');?> Ruta</h2>
    <form id="nueva-ruta-form" method="post">

      <!--id ruteo hidden field-->
      <input type="hidden" id="idRuteo" name="idRuteo" value="<?php echo $idRuteo;?>">
      <div class="form-group">
        <label for="fecha">Fecha:</label>
        <input type="date" class="form-control" id="fecha" name="fecha">
      </div>     
      <div class="form-group mt-2">
        <label for="Folio">Folio:</label>
        <input type="text" class="form-control" id="Folio" name="Folio">
      </div>
      <!--repartidor field select-->
      <div class="form-group mt-2">
        <label for="idRepartidor">Repartidor:</label>
        <select class="form-control" id="idRepartidor" name="idRepartidor" required>
          <option value="-1">Seleccione un repartidor</option>
        </select>

        <!--estatus field select-->
        <div class="form-group mt-2">
          <label for="idEstatus">Estatus:</label>
          <select class="form-control" id="idEstatus" name="idEstatus" required>
            <option value="-1">Seleccione un estatus</option>
          </select>
        </div>
        <!--THIS SHOW ERRORS-->
        <div id="content-error" class="mt-3"></div>
        <button type="button" id="btn-submit" class="btn btn-primary mt-2">Guardar</button>
    </form>
  </div>

</div>

<?php
require_once("templates/loadingUtils.php");
require_once("templates/footer.php");
?>

<script src="js/nuevaRuta.js"></script>