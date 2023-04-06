$(document).ready(async function () {
    //searchRepartidores();
    $('#formNuevaRuta').submit(function (e) {
        e.preventDefault();
    });

    await loadMaps();
    await getEstatus();
    await getClientes();
    guardarInfo();


    //set fecha actual formato yyyy/mm/dd
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd
    }
    if (mm < 10) {
        mm = '0' + mm
    }
    today = yyyy + '-' + mm + '-' + dd;
    $("#fecha").val(today);

    $('#btn-seleccionar-productos').on('click', function () {
        // Abre el diálogo modal
        $('#formNuevaRuta').submit(function (e) {
            e.preventDefault();
        });

        $('#modal-seleccionar-productos').modal('show');
    });

    // Agregar un evento "click" al botón "Agregar"
    $('#guardar-cambios').click(function () {

        //Obtener los datos de los productos con cantidad mayor a 0
        var table = $("#tabla-productos");
        var productos = [];
        table.find('tr').each(function () {

            //console.log($(this).html());        
            var idProducto = $(this).find('td').eq(0).text();
            // console.log(idProducto);
            var nbProducto = $(this).find('td').eq(1).text();
            //console.log(nbProducto);
            var dcPrecioVenta = $(this).find('td').eq(2).text();
            // console.log(dcPrecioVenta);
            var dcCantidad = $(this).find('td').eq(3).find('input').val();
            // console.log(dcCantidad);

            var existe = existProducSelected(idProducto);
            if (existe) {
                updateCurrentProduct(idProducto, dcCantidad);
            } else {
                if (dcCantidad > 0) {

                    productos.push({
                        idProducto: idProducto,
                        dcCantidad: dcCantidad
                    });

                    // Crear una nueva fila para la tabla
                    const fila = `
                                  <tr>
                                    <td>${idProducto}</td>
                                    <td>${nbProducto}</td>
                                    <td>${dcCantidad}</td> 
                                    <td>${dcPrecioVenta}</td>                                
                                    <td>${dcPrecioVenta * dcCantidad}</td>
                                    <td><button class="btn btn-danger" onclick="deleteProduct(${idProducto})">Eliminar</button></td>
                                  </tr>
                                 `;

                    // Agregar la nueva fila a la tabla
                    $('#tabla-productos-seleccionados').append(fila);

                }

            }

            ActualizarToTalTabla();
        });

       
        // Cerrar el modal
        $('#modal-seleccionar-productos').modal('hide');
        actualizarDireccion();
    });

    if (idParada != 0) {
        await getParadaById(idParada);
    }

    eventsModalProductos();
});

function ActualizarToTalTabla() {
    $("#globalInfo").hide();
    //actualizar el total
    var total = 0;
    $('#tabla-productos-seleccionados tr').each(function () {
        var dcCantidad = $(this).find('td').eq(2).text();
        var dcPrecioVenta = $(this).find('td').eq(3).text();
        total += dcPrecioVenta * dcCantidad;
    });
    if (total > 0) {
        $("#globalInfo").show();
        //format total to currency 
        total = total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        $("#dcTotalProductos").html("<b>$" + total + " MXN</b>");
    }
}
marker = null;
async function loadMaps() {
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
    marker = new google.maps.Marker({
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
}

async function getParadaById() {

    let data = {
        "idParada": idParada
    }
    var settings = {
        "url": "../ws/v2/paradas.php?action=getParadaById",
        "method": "POST",
        "timeout": 0,
        "headers": {
            "Content-Type": "application/json"
        },
        "data": JSON.stringify(data),
    };

    showDialogLoading();

    $.ajax(settings)
        .done(function (response) {

            hideDialogLoading();
            if (response["idEstatus"] == 1) {
                //showDialogSuccess(response["mensaje"]);
                var data = response["data"];
                //add data to form
                $("#idParada").val(data["idParada"]);
                $("#idCliente").val(data["idCliente"]);
                $("#nbCliente").val(data["nbCliente"]);
                $("#comentarios").val(data["comentarios"]);
                $("#fecha").val(data["fecha"]);
                $("#idEstatus").val(data["idEstatus"]);
                $("#latitud").val(data["latitud"]);
                $("#longitud").val(data["longitud"]);

                //load productos into tabla-productos-seleccionados
                var productos = data["productos"];
                //console.log(productos);

                for (var i = 0; i < productos.length; i++) {
                    var producto = productos[i];
                    const fila = `
                                  <tr>
                                    <td>${producto["idProducto"]}</td>
                                    <td>${producto["nbProducto"]}</td>
                                    <td>${producto["dcCantidad"]}</td> 
                                    <td>${producto["dcPrecioVenta"]}</td>                                
                                    <td>${producto["dcPrecioVenta"] * producto["dcCantidad"]}</td>
                                    <td><button class="btn btn-danger" onclick="deleteProduct(${producto["idProducto"]})">Eliminar</button></td>
                                  </tr>
                                 `;

                    // Agregar la nueva fila a la tabla
                    $('#tabla-productos-seleccionados').append(fila);
                }

                //update map to latitud and longitud
                var latitud = data["latitud"];
                var longitud = data["longitud"];
                var myLatLng = { lat: parseFloat(latitud), lng: parseFloat(longitud) };


                marker.setPosition(myLatLng);

                ActualizarToTalTabla();


            } else {
                showDialogError(response["mensaje"]);
            }

        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            hideDialogLoading();
            showDialogError("Ocurrio al obtener la parada");
        });

}
function deleteProduct($idProducto) {
    var table = $("#tabla-productos-seleccionados");
    var productos = [];
    table.find('tr').each(function () {
        var idProducto = $(this).find('td').eq(0).text();
        if ($idProducto == idProducto) {
            $(this).remove();
        }
    });

    ActualizarToTalTabla();
}

function existProducSelected($idProducto) {
    var table = $("#tabla-productos-seleccionados");
    var existe = false;
    table.find('tr').each(function () {
        var idProducto = $(this).find('td').eq(0).text();
        //console.log(idProducto);
        if ($idProducto == idProducto) {
            //console.log("existe el producto");
            existe = true;
            return true;
        }
    });
    return existe;
}

function isProductosSelected() {
    var table = $("#tabla-productos-seleccionados");
    var productos = [];
    table.find('tr').each(function () {
        var idProducto = $(this).find('td').eq(0).text();
        var dcCantidad = $(this).find('td').eq(2).text();
        if (dcCantidad > 0) {
            productos.push({
                idProducto: idProducto,
                dcCantidad: dcCantidad
            });
        }
    });
    return productos;
}

function getCantidadProducto($idProducto) {
    var table = $("#tabla-productos-seleccionados");
    var cantidad = 0;
    table.find('tr').each(function () {
        var idProducto = $(this).find('td').eq(0).text();
        if ($idProducto == idProducto) {
            cantidad = $(this).find('td').eq(2).text();
            //console.log("cantidad: " + cantidad);
            return cantidad;
        }
    });
    return cantidad;
}

function updateCurrentProduct($idProducto, $dcCantidad) {
    var table = $("#tabla-productos-seleccionados");
    var productos = [];
    table.find('tr').each(function () {
        var idProducto = $(this).find('td').eq(0).text();
        if ($idProducto == idProducto) {
            $(this).find('td').eq(2).text($dcCantidad);

            var dcPrecioVenta = $(this).find('td').eq(3).text();
            var dcTotal = $dcCantidad * dcPrecioVenta;
            $(this).find('td').eq(4).text(dcTotal);
        }
    });
}

var lsProductos = [];
function eventsModalProductos() {
    $('#modal-seleccionar-productos').on('shown.bs.modal', function () {
        $('#txt-search-productos').val('');
        // Realiza una petición AJAX para obtener los productos disponibles
        $.ajax({
            url: '../ws/v2/productos.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {

                if (data["idEstatus"] == 1) {
                    var table = $("#tabla-productos");

                    table.empty();

                    lsProductos = data["data"];

                    data["data"].forEach(function (producto) {
                        var existe = existProducSelected(producto.idProducto);
                        var dcCantidad = 0;
                        if (existe) {
                            dcCantidad = getCantidadProducto(producto.idProducto);
                            //console.log(dcCantidad);
                        } else {
                            dcCantidad = 0;
                        }

                        $('#tabla-productos').append('<tr>' +
                            '<td>' + producto.idProducto + '</td>' +
                            '<td>' + producto.nbProducto + '</td>' +
                            '<td class="with:10px;">' + producto.dcPrecioVenta + '</td>' +
                            '<td><input type="number" value="' + dcCantidad + '"></td>' +
                            '</tr>');
                    });
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Error al obtener los productos: ' + textStatus);
            }
        });
    });

    $('#txt-search-productos').keyup(function () {
        //filter table
        var filter = $(this).val();
        var table = $("#tabla-productos");
        table.find('tr').each(function () {
            var nbProducto = $(this).find('td').eq(1).text();
            if (nbProducto.search(new RegExp(filter, "i")) < 0) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });
}

var lsClientes = [];
async function getClientes() {

    $("#idCliente").on("change", function () {
        actualizarDireccion();
    });

    await $.ajax({
        url: '../ws/v2/clientes.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            if (data["idEstatus"] == 1) {
                var clientes = data["data"];
                lsClientes = clientes;
                var clientesSelect = $("#idCliente");
                clientesSelect.empty();
                for (var i = 0; i < clientes.length; i++) {
                    clientesSelect.append("<option value='" + clientes[i]["idCliente"] + "'>" + clientes[i]["nbCliente"] + "</option>");
                }

                actualizarDireccion();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('Error al obtener los clientes: ' + textStatus);
        }
    });


}

function actualizarDireccion() {
    var idCliente = $("#idCliente").val();
    var cliente = lsClientes.find(function (cliente) {
        return cliente.idCliente == idCliente;
    });

    $("#nbCliente").val(cliente.nbCliente);

    var format = "";
    format += cliente.desCalleUno + " ";
    if (cliente.desCalleDos != null && cliente.desCalleDos != "") {
        format += " x " + cliente.desCalleDos + " ";
    }
    if (cliente.desCalleTres != null && cliente.desCalleTres != "") {
        format += " y " + cliente.desCalleTres + " ";
    }
    format += "\n";
    if (cliente.desReferencias != null && cliente.desReferencias != "") {
        format += cliente.desReferencias + "\n";
    }

    format += cliente.numTelefono + "\n";

    $("#comentarios").val(format);

}


async function getEstatus() {

    $data = {
        "nbModulo": "PARADAS"
    }
    //SEREALIZAR EL JSON
    $data = JSON.stringify($data);

    await $.ajax({
        url: "../ws/v2/estatus.php?action=getByModule",
        type: "POST",
        data: $data,
        contentType: "application/json",
        success: function (data) {
            if (data["idEstatus"] == 1) {
                var estatus = data["data"];
                var estatusSelect = $("#idEstatus");
                estatusSelect.empty();
                for (var i = 0; i < estatus.length; i++) {
                    estatusSelect.append("<option value='" + estatus[i]["idEstatus"] + "'>" + estatus[i]["nbEstatus"] + "</option>");
                }

            }
        },
        error: function (data) {
            console.log(data);
        }
    });
}

function guardarInfo() {
    $('#btn-submit').click(function () {

        var prod = isProductosSelected();
        if (prod.length == 0) {
            showDialogError("No se ha seleccionado ningún producto");
            return;
        }

        var formArray = $('#formNuevaRuta').serializeArray();
        var formData = {};
        $.each(formArray, function (i, field) {
            formData[field.name] = field.value;
        });

        var jsonData = JSON.stringify(formData);
        //console.log(jsonData);

        //get list productos
        var productos = [];
        var table = $("#tabla-productos-seleccionados");
        table.find('tr').each(function () {
            var idProducto = $(this).find('td').eq(0).text();
            var dcCantidad = $(this).find('td').eq(2).text();
            if (dcCantidad > 0) {
                productos.push({
                    idProducto: idProducto,
                    dcCantidad: dcCantidad
                });
            }
        });

        //add productos to json
        jsonData = JSON.parse(jsonData);
        jsonData["productos"] = productos;
        jsonData = JSON.stringify(jsonData);

        var action = "create";
        if (idParada != null && idParada != 0) {
            action = "update";
        }

        var settings = {
            "url": "../ws/v2/paradas.php?action=" + action,
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": jsonData,
        };

        showDialogLoading();

        $.ajax(settings)
            .done(function (response) {
                //console.log(response);
                hideDialogLoading();
                if (response["idEstatus"] == 1) {
                    showDialogSuccess(response["mensaje"]);
                } else {
                    showDialogError(response["mensaje"]);
                }

            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
                hideDialogLoading();
                showDialogError("Ocurrio un error al guardar la información");
            });
    });
}