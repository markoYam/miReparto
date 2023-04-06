<?php
require_once("templates/header.php");
require_once("templates/sidebar.php");

$idProducto = 0;
if(isset($_GET["idProducto"])){
  $idProducto = $_GET["idProducto"];
}
echo "<script>var idProducto = $idProducto;</script>";
?>

<div id="layoutSidenav_content">
    <div class="container">
        <h2><?php echo ($idProducto == 0 ? 'Nuevo':'Editar');?> Producto</h2>
        <form id="crearProductoForm">
            <div class="form-group">
                <label for="nbProducto">Nombre del producto</label>
                <input type="text" class="form-control" id="nbProducto" name="nbProducto" required>
            </div>
            <div class="form-group">
                <label for="desProducto">Descripción del producto</label>
                <textarea class="form-control" id="desProducto" name="desProducto"></textarea>
            </div>
            <label for="fotos">Fotos</label>
            <div class="row">
                <div class="col-sm">
                    <div class="card" style="width:100%; ">
                        <img style="height: 15rem;" src="https://via.placeholder.com/150" id="foto-1" class="card-img-top p-2" alt="">
                        <div class="card-body">
                            <form>
                                <div class="form-group">
                                    <input type="file" class="form-control-file" id="foto-input-1" name="foto-input"
                                        accept="image/*">
                                </div>
                                <button type="button" class="btn btn-danger btn-sm" id="eliminar-btn-1">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="card" style="width:100%;">
                        <img style="height: 15rem;" src="https://via.placeholder.com/150" id="foto-2" class="card-img-top p-2" alt="">
                        <div class="card-body">
                            <form>
                                <div class="form-group">
                                    <input type="file" class="form-control-file" id="foto-input-2" name="foto-input"
                                        accept="image/*">
                                </div>
                                <button type="button" class="btn btn-danger btn-sm" id="eliminar-btn-2">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="card" style="width:100%;">
                        <img style="height: 15rem;" src="https://via.placeholder.com/150" id="foto-3" class="card-img-top p-2" alt="">
                        <div class="card-body">
                            <form>
                                <div class="form-group">
                                    <input type="file" class="form-control-file" id="foto-input-3" name="foto-input"
                                        accept="image/*">
                                </div>
                                <button type="button" class="btn btn-danger btn-sm" id="eliminar-btn-3">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="card" style="width:100%;">
                        <img style="height: 15rem;" src="https://via.placeholder.com/150" id="foto-4" class="card-img-top p-2" alt="">
                        <div class="card-body">
                            <form>
                                <div class="form-group">
                                    <input type="file" class="form-control-file" id="foto-input-4" name="foto-input"
                                        accept="image/*">
                                </div>
                                <button type="button" class="btn btn-danger btn-sm" id="eliminar-btn-4">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-sm">
                    <div class="form-group">
                        <label for="dcPrecioCompra">Precio de compra</label>
                        <input type="number" class="form-control" id="dcPrecioCompra" name="dcPrecioCompra" required>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="dcPrecioVenta">Precio de venta</label>
                        <input type="number" class="form-control" id="dcPrecioVenta" name="dcPrecioVenta" required>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="dcComision">Comisión</label>
                        <input type="number" class="form-control" id="dcComision" name="dcComision" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="tallas">Tallas</label>

                <table class="table table-auto mt-3">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Talla</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody id="tallas-table">
                    </tbody>
                </table>
            </div>

            <button type="button" id="btnGuardar" class="btn btn-primary mb-3">Guardar</button>
        </form>
    </div>

    <?php
        require_once("templates/loadingUtils.php");
require_once("templates/footer.php");
?>

    <script src="js/nuevoProducto.js"></script>