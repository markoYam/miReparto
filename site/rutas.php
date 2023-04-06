<?php
require_once("templates/header.php");
require_once("templates/sidebar.php");
?>

<div id="layoutSidenav_content">

    <div class="ml-5 mr-5">
        <h2>Rutas</h2>

        <div class="input-group mb-3 mt-3">
            <input type="text" class="form-control" placeholder="Buscar" aria-label="Buscar"
            id="txt-search"
                aria-describedby="basic-addon2">
            <span class="input-group-text" id="basic-addon2">Buscar</span>
            <button type="button" id="btn-add" class="btn btn-primary ml-2">
                <a href="nuevaRuta.php" style="text-decoration: none; color: white;">Agregar</a>
            </button>
        </div>
        <div class="table-responsive">
        <table class="table table-auto">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Folio</th>
                    <th>Repartidor</th>
                    <th>Estatus</th>
                    <th style="width: 5%;">Paradas</th>
                    <th style="width: 5%;">Editar</th>
                    <th style="width: 5%;">Eliminar</th>
                </tr>
            </thead>
            <tbody id="rutas-table">
            </tbody>
        </table>
        </div>
    </div>

    <!-- Modal delete ruta-->
    <div class="modal fade" id="modalDeleteRuta" tabindex="-1" role="dialog" aria-labelledby="modalDeleteRutaTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteRutaTitle">Eliminar Ruta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idRuteoDelete">
                    <p id="message">¿Está seguro de eliminar la ruta?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnDeleteRuta" class="btn btn-primary">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <?php
        require_once("templates/loadingUtils.php");
require_once("templates/footer.php");
?>

    <script src="js/rutas.js"></script>