<?php

require_once("templates/header.php");
require_once("templates/sidebar.php");

//get variable idRuta and save in js variable
$idRuta = 0;
if(isset($_GET['idRuteo'])){
    $idRuta = $_GET['idRuteo'];
    echo "<script>var idRuteo = $idRuta;</script>";
}else{
    echo "<script>var idRuteo = 0;</script>";
}

?>

<div id="layoutSidenav_content">

    <div class="ml-5 mr-5">
        <h2>Paradas</h2>

        <div class="input-group mb-3 mt-3">
            <input type="text" class="form-control" placeholder="Buscar" aria-label="Buscar" id="queryFiltro"
                aria-describedby="basic-addon2">
            <span class="input-group-text" id="basic-addon2">Buscar</span>
            <button type="button" id="btn-add" class="btn btn-primary ml-2">
                <a href="nuevaParadas.php?idRuteo=<?php echo $idRuta?>"
                    style="text-decoration: none; color: white;">Agregar</a>
            </button>
        </div>
        <div class="row">
            <div class="col-sm">
                <div class="form-group">
                    <label for="idEstatus">Estatus:</label>
                    <select class="form-control" id="idEstatusFiltro" name="idEstatusFiltro" required>
                        <option value="-1">Seleccione un estatus</option>
                    </select>
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label for="fecha">Fecha Inicio:</label>
                    <div class="input-group date">
                        <input type="date" id="fechaInicioFiltro" class="form-control">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary" id="eliminar-fecha">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label for="fecha">Fecha Fin:</label>
                    <div class="input-group date">
                        <input type="date" id="fechaFinFiltro" class="form-control">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary" id="eliminar-fecha-fin">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-auto">
                <thead>
                    <tr>
                        <th>#</th>
                        <th style="width: 5%;">Estatus</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>N° productos</th>
                        <th>Subtotal</th>
                        <th>Comisión</th>
                        <th>Total</th>
                        <th>Fecha Actualización</th>
                        <th style="width: 5%;">Mapa</th>
                        <th style="width: 5%;">Editar</th>
                        <th style="width: 5%;">Eliminar</th>
                    </tr>
                </thead>
                <tbody id="paradas-table">
                </tbody>
                <tfoot id="globalInfo" style="display:none;">
                    <tr>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td id="numProductosGlobal">N° productos <b>0</b></td>
                        <td id="dcSubtotalGlobal">SubTotal: <b>$0.0</b></td>
                        <td id="dcComisionGlobal">Comisión: <b>$0.0</b></td>
                        <td id="dcTotalGlobal">Total: <b>$0.0</b></td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Modal maps large -->
    <div class="modal fade" id="modalMap" tabindex="-1" role="dialog" aria-labelledby="modalMapTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMapTitle">Mapa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="map" style="height: 600px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal delete -->
    <div class="modal fade" id="modalDeleteParada" tabindex="-1" role="dialog" aria-labelledby="modalDeleteRutaTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteRutaTitle">Eliminar Parada</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idParadaDelete">
                    <p id="message">¿Está seguro de eliminar la parada?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnDeleteParada" class="btn btn-primary">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <?php
 require_once("templates/loadingUtils.php");
require_once("templates/footer.php");
?>
    <script src="js/paradas.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDLEdYk_QRPcaClwl1i6SXf54bTMZNK5mQ&libraries=places">
    </script>