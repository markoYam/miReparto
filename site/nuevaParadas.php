<?php
require_once("templates/header.php");
require_once("templates/sidebar.php");
$idRuteo = 0;
$idParada = 0;
if (isset($_GET['idRuteo'])) {
    $idRuta = $_GET['idRuteo'];
    echo "<script>var idRuteo = $idRuta;</script>";
} else {
    echo "<script>var idRuteo = 0;</script>";
}
if (isset($_GET['idParada'])) {
    $idParada = $_GET['idParada'];
    echo "<script>var idParada = $idParada;</script>";
} else {
    echo "<script>var idParada = 0;</script>";
}
?>


<div id="layoutSidenav_content">
    <div class="ml-5 mr-5">
        <h2><?php echo ($idParada == 0 ? 'Nueva' : 'Editar'); ?> Parada</h2>
        <form id="formNuevaRuta">

            <input type="hidden" class="form-control" id="idRuta" name="idRuta" value="<?php echo $idRuta ?>">
            <input type="hidden" class="form-control" id="idParada" name="idParada" value="<?php echo $idParada ?>">

            <div class="row">

                <div class="col-sm">
                    <div class="form-group">
                        <label for="fecha">Fecha:</label>
                        <input type="date" class="form-control" id="fecha" name="fecha">
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="idEstatus">Estatus:</label>
                        <select class="form-control" id="idEstatus" name="idEstatus" required>
                            <option value="-1">Seleccione un estatus</option>
                        </select>
                    </div>
                </div>
            </div>
            <input type="text" class="form-control mt-2" id="search-input" style="width: 70%; height: 45px !important; font-size: 14px; top:10px;" placeholder="Escriba una dirección o lugar">
            <div class="form-group mt-2">
                <label for="map">Ubicación:</label>
                <div id="map" style="height: 600px;"></div>
            </div>

            <div class="row">
                <div class="col-sm">
                    <div class="form-group mt-2">
                        <label for="latitud">Latitud:</label>
                        <input type="text" class="form-control" id="latitud" name="latitud" value="0">
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group mt-2">
                        <label for="longitud">Longitud:</label>
                        <input type="text" class="form-control" id="longitud" name="longitud" , value="0">
                    </div>
                </div>
            </div>
            <div class="form-group mt-2">
                <div class="form-group">
                    <label for="idEstatus">Cliente:</label>
                    <select class="form-control" id="idCliente" name="idCliente" required>
                        <option value="-1">Seleccione un cliente</option>
                    </select>
                    <!--hide nbCliente-->
                    <input type="hidden" class="form-control" id="nbCliente" name="nbCliente" value="">
                </div>
            </div>
            <div class="form-group mt-2">
                <label for="comentarios">Comentarios:</label>
                <textarea class="form-control" id="comentarios" name="comentarios" placeholder="Direccion - Articulos"> </textarea>
            </div>
            <div class="form-group mt-2">
                <label for="comentarios">Productos:</label>
                <button id="btn-seleccionar-productos">Seleccionar productos</button>
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Precio Venta</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tabla-productos-seleccionados">
                        <!-- Filas de productos seleccionados y cantidad -->
                    </tbody>
                    <tfoot id="globalInfo" style="display:none;">
                        <tr>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td id="dcTotalProductos">Total: $<b>0</b></td>
                            <td> </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <button type="button" id="btn-submit" class="btn btn-primary mt-2 mb-3">Guardar</button>
        </form>
    </div>

    <div id="modal-seleccionar-productos" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!-- Header del modal -->
                <div class="modal-header">
                    <h5 class="modal-title">Seleccionar productos</h5>
                    <!-- Botón para cerrar el modal usando bootstrap y automatico -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- input search -->
                    <input type="text" class="form-control" placeholder="Buscar" aria-label="Buscar" id="txt-search-productos" aria-describedby="basic-addon2">
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Precio Venta</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-productos">
                            <!-- Filas de productos seleccionados y cantidad -->
                        </tbody>

                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="guardar-cambios" class="btn btn-primary">Guardar</button>
                </div>

            </div>
        </div>
    </div>

    <?php
    require_once("templates/loadingUtils.php");
    require_once("templates/footer.php");
    ?>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDLEdYk_QRPcaClwl1i6SXf54bTMZNK5mQ&libraries=places"></script>
    <script src="js/nuevaParada.js"></script>
    </body>