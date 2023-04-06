<?php
require_once("templates/header.php");
require_once("templates/sidebar.php");
$idCliente = 0;
if(isset($_GET["idCliente"])){
  $idCliente = $_GET["idCliente"];
}
echo "<script>var idCliente = $idCliente;</script>";
?>


<div id="layoutSidenav_content">
    <div class="ml-5 mr-5">
        <h2><?php echo ($idCliente == 0 ? 'Nuevo':'Editar');?> Cliente</h2>
        <form id="clienteForm">
            <div class="form-group">
                <label for="nbCliente">Nombre del cliente:</label>
                <input type="text" class="form-control" id="nbCliente" name="nbCliente" required>
            </div>
            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label for="desCalleUno">Calle 1:</label>
                        <input type="text" class="form-control" id="desCalleUno" name="desCalleUno" required>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="desCalleDos">Calle 2:</label>
                        <input type="text" class="form-control" id="desCalleDos" name="desCalleDos">
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="desCalleTres">Calle 3:</label>
                        <input type="text" class="form-control" id="desCalleTres" name="desCalleTres">
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="numTelefono">Teléfono:</label>
                        <input type="tel" class="form-control" id="numTelefono" name="numTelefono" pattern="[0-9]{10}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="desReferencias">Referencias:</label>
                <input type="text" class="form-control" id="desReferencias" name="desReferencias">
            </div>

            <div class="form-group">
                <label for="urlFacebook">URL de Facebook:</label>
                <input type="url" class="form-control" id="urlFacebook" name="urlFacebook">
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>

    </div>

    <?php
require_once("templates/loadingUtils.php");
require_once("templates/footer.php");
?>
    <script src="js/nuevoCliente.js"></script>