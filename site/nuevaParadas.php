<?php
require_once("templates/header.php");
require_once("templates/sidebar.php");
?>


<div id="layoutSidenav_content">
    <div class="ml-5 mr-5">
        <h2>Nueva parada</h2>
        <form id="formNuevaRuta">

            <input type="hidden" class="form-control" id="idRuta" name="idRuta" value="<?php echo $_GET['idRuteo']?>">
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
            <input type="text" class="form-control mt-2" id="search-input"
                style="width: 70%; height: 45px !important; font-size: 14px; top:10px;"
                placeholder="Escriba una dirección o lugar">
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
                <label for="cliente">Cliente:</label>
                <input type="text" class="form-control" id="cliente" name="cliente" required>
            </div>
            <div class="form-group mt-2">
                <label for="comentarios">Comentarios:</label>
                <textarea class="form-control" id="comentarios" name="comentarios"
                    placeholder="Direccion - Articulos"> </textarea>
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
                </table>
            </div>

            <button type="button" id="btn-submit" class="btn btn-primary mt-2">Enviar</button>
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
                    <table class="table">
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
    <script src="js/nuevaParada.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDLEdYk_QRPcaClwl1i6SXf54bTMZNK5mQ&libraries=places">
    </script>
    <script>
    $(document).ready(function() {

        //set fecha to current date to #fecha
        // Obtener la fecha actual y formatearla en formato ISO
        var fechaActual = new Date().toISOString().substr(0, 10);

        // Establecer la fecha actual como valor por defecto del campo de fecha
        $("input[name='fecha']").val(fechaActual);

        // Obtenemos la referencia al contenedor del mapa
        var mapContainer = document.getElementById('map');

        // Creamos un objeto LatLng con la ubicación del usuario
        var userLocation = new google.maps.LatLng(20.2055611, -89.2852394);

        // Configuramos las opciones del mapa
        var mapOptions = {
            zoom: 16,
            center: userLocation
        };

        // Creamos el objeto de mapa
        var map = new google.maps.Map(mapContainer, mapOptions);

        // Creamos un marcador en la ubicación del usuario
        var marker = new google.maps.Marker({
            position: userLocation,
            map: map,
            draggable: true
        });

        // Actualizamos la latitud y longitud en los campos de formulario cuando se mueve el marcador
        google.maps.event.addListener(marker, 'dragend', function() {
            var position = marker.getPosition();
            $('input[name="latitud"]').val(position.lat());
            $('input[name="longitud"]').val(position.lng());
        });


        //nuevo
        // Obtenemos la referencia al input de búsqueda
        var searchInput = document.getElementById('search-input');

        // Creamos un objeto de búsqueda de Places
        var searchBox = new google.maps.places.SearchBox(searchInput);

        // Vinculamos el objeto de búsqueda con el mapa
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(searchInput);

        // Agregamos un evento cuando se selecciona una ubicación en el buscador
        searchBox.addListener('places_changed', function() {
            var places = searchBox.getPlaces();
            if (places.length == 0) {
                return;
            }

            // Movemos el mapa a la ubicación seleccionada
            var bounds = new google.maps.LatLngBounds();
            places.forEach(function(place) {
                if (!place.geometry) {
                    console.log("Ubicación sin coordenadas: " + place.name);
                    return;
                }

                if (place.geometry.viewport) {
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);

            // Movemos el marcador a la ubicación seleccionada
            var position = places[0].geometry.location;
            marker.setPosition(position);
            $('input[name="latitud"]').val(position.lat());
            $('input[name="longitud"]').val(position.lng());
        });

    });
    </script>
    </body>