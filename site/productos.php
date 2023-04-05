<?php
require_once("templates/header.php");
require_once("templates/sidebar.php");
?>

<div id="layoutSidenav_content">

    <div class="ml-5 mr-5">
        <h2>Productos</h2>

        <div class="input-group mb-3 mt-3">
            <input type="text" class="form-control" placeholder="Buscar" aria-label="Buscar"
            id="txt-search"
                aria-describedby="basic-addon2">
            <span class="input-group-text" id="basic-addon2">Buscar</span>
            <button type="button" id="btn-add" class="btn btn-primary ml-2">
                <a href="nuevoProducto.php" style="text-decoration: none; color: white;">Agregar</a>
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-auto">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio Compra</th>
                        <th>Precio Venta</>
                        <th>Comisión</th>
                        <th style="width: 5%;">Tallas</th>
                        <th style="width: 5%;">Fotos</th>
                        <th style="width: 5%;">Editar</th>
                        <th style="width: 5%;">Eliminar</th>
                    </tr>
                </thead>
                <tbody id="productos-table">
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal delete-->
    <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modalDeleteRutaTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteTitle">Eliminar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idProductoDelete">
                    <p id="message"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnDeleteProducto" class="btn btn-primary">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal fotos-->
    <div class="modal fade" id="modalFotos" tabindex="-1" role="dialog" aria-labelledby="modalDeleteRutaTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteTitle">Fotos del producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators" id="carrucel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner" id="carrucel-items-container">
                            
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                            data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                            data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal tallas-->
    <div class="modal fade" id="modalTallas" tabindex="-1" role="dialog" aria-labelledby="modalDeleteRutaTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteTitle">Tallas del producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" id="tallasProductoContainer">
                        <div class="col-sm">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>



    <?php
        require_once("templates/loadingUtils.php");
require_once("templates/footer.php");
?>

    <script src="js/productos.js"></script>