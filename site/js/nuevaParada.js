$(document).ready(function () {
    //searchRepartidores();
    getEstatus();
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
        $('#formNuevaRuta').submit(function(e) {
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
        });
        // Cerrar el modal
        $('#modal-seleccionar-productos').modal('hide');
    });
});

function deleteProduct($idProducto) {
    var table = $("#tabla-productos-seleccionados");
    var productos = [];
    table.find('tr').each(function () {
        var idProducto = $(this).find('td').eq(0).text();
        if ($idProducto == idProducto) {
            $(this).remove();
        }
    });
}

function existProducSelected($idProducto) {
    var table = $("#tabla-productos-seleccionados");
    var existe = false;
    table.find('tr').each(function () {
        var idProducto = $(this).find('td').eq(0).text();
        console.log(idProducto);
        if ($idProducto == idProducto) {
            console.log("existe el producto");
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
            console.log("cantidad: " + cantidad);
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
        }
    });
}


$('#modal-seleccionar-productos').on('shown.bs.modal', function () {
    // Realiza una petición AJAX para obtener los productos disponibles
    $.ajax({
        url: '../ws/v2/productos.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {

            if (data["idEstatus"] == 1) {
                var table = $("#tabla-productos");

                table.empty();

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



function getEstatus() {

    $data = {
        "nbModulo": "PARADAS"
    }
    //SEREALIZAR EL JSON
    $data = JSON.stringify($data);

    $.ajax({
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
        console.log(jsonData);

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

        var settings = {
            "url": "../ws/v2/paradas.php?action=create",
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
                console.log(response);
                hideDialogLoading();
                if(response["idEstatus"] == 1){
                    showDialogSuccess(response["mensaje"]);
                }else{
                    showDialogError(response["mensaje"]);
                }
                
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
                hideDialogLoading();
                showDialogError("Ocurrio un error al guardar la información");
              });
    });
}