
<?php

require_once("header.php");
require_once("sidebar.php");

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Nueva Parada</h2>
            <form id="miFormulario">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating mb-3 mb-md-0">
                            <select class="form-control" id="idRuta" name="idRuta" >
                              <option value="0" selected>--Seleccionar--</option>
                            </select>
                            <label for="Ruta">Id ruta</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3 mb-md-0">
                            <input class="form-control" id="fecha" name="fecha" required type="date" placeholder="Fecha" />
                            <label for="fecha">Fecha</label>
                        </div>
                    </div>
                </div>

                <!--<div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating mb-3 mb-md-0">
                            <input class="form-control" id="fecha" name="fecha" required type="date" placeholder="Fecha" />
                            <label for="fecha">Fecha</label>
                        </div>
                    </div>
                </div>-->

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating mb-3 mb-md-0">
                            <select class="form-control" id="idEstatus" name="idEstatus" >
                              <option value="0" selected>--Seleccionar--</option>
                              <option value="1">Generado</option>
                              <option value="2">Entregado</option>
                              <option value="3">Cancelado</option>
                            </select>
                            <label for="status">Id estatus</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="map">Buscar ubicación:</label>
                            <input type="text" class="form-control" id="search-input" placeholder="Escriba una dirección o lugar" style="position: absolute; left: 193px;top: 0px; width: 70%; margin: 10px;">
                        </div>
                    </div>
                </div>        
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">            
                            <label for="map">Ubicación:</label>
                            <div id="map" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="latitud" name="latitud" required placeholder="Latitud" readonly="" />
                            <label for="latitud">Latitud</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="longitud" name="longitud" required placeholder="Longitud" readonly="" />
                            <label for="longitud">Longitud</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="cliente" name="cliente" required placeholder="cliente" />
                            <label for="cliente">Cliente</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <textarea type="text" class="form-control" id="comentarios" name="comentarios" placeholder="Direccion - Articulos" rows="10" cols="50"></textarea>
                            <label for="comentario">Comentarios</label>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="total" name="total" required placeholder="Total" />
                            <label for="total">Total</label>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="action" value="create">
                
                <div class="mt-4 mb-0">
                        <button type="submit" class="btn btn-primary btn-block">Guardar</button>
                </div>
            </form>

        </div>
    </main>



<?php
require_once("footer.php");
?>

<script>
        $(document).ready(function () {

            //set fecha to current date to #fecha
            // Obtener la fecha actual y formatearla en formato ISO
            var fechaActual = new Date().toISOString().substr(0, 10);

            // Establecer la fecha actual como valor por defecto del campo de fecha
            $("input[name='fecha']").val(fechaActual);

            $('#miFormulario').submit(function (event) {
                event.preventDefault(); // Evita que el formulario se envíe por defecto

                // Obtiene los valores de los campos del formulario
                var idParada = parseInt($('#idParada').val());
                var idRuta = parseInt($('#idRuta').val());
                var fecha = $('#fecha').val();
                var idEstatus = parseInt($('#idEstatus').val());
                var latitud = parseFloat($('#latitud').val());
                var longitud = parseFloat($('#longitud').val());
                var comentarios = $('#comentarios').val();
                var total = parseFloat($('#total').val());
                var cliente = $('#cliente').val();


                // Envía los datos del formulario a paradas.php mediante Ajax
                $.ajax({
                    url: '<?=URL_BASE?>ws/paradas.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        console.log(response);
                        // Coloca aquí el código que deseas ejecutar en caso de éxito
                        alert(response["mensaje"]);
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr);
                        // Coloca aquí el código que deseas ejecutar en caso de error
                        alert("Error al registrar la parada");
                    }
                });
            });

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
            google.maps.event.addListener(marker, 'dragend', function () {
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
            searchBox.addListener('places_changed', function () {
                var places = searchBox.getPlaces();
                if (places.length == 0) {
                    return;
                }

                // Movemos el mapa a la ubicación seleccionada
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function (place) {
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

            function obtenerRutas() {
                $.ajax({
                    url: '<?=URL_BASE?>ws/rutas.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        var objectData = new Array();
                        objectData = response.data;
                        
                        $("#idRuta").empty();

                        $("#idRuta").append("<option value='0'>"+"--Seleccionar--"+"</option>");
                        for( var i = 0; i<objectData.length; i++){
                            $("#idRuta").append("<option value='"+objectData[i].idRuteo+"'>"+objectData[i].Repartidor+" - "+objectData[i].fecha+"</option>");
                        }

                        
                    }
                });
            }

            obtenerRutas();

        });
    </script>