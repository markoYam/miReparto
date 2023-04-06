//document ready
$(document).ready(function () {
    searchRutas();
    //getEstatus();
    $("#txt-search").keyup(function () {
        searchRutas();
    });
});

var lsParadas = [];
function searchRutas() {

    let query = $("#txt-search").val();
    let settings = {
        "url": "../ws/v2/rutas.php?action=getCustomView",
        "method": "POST",
        "timeout": 0,
        "headers": {
            "Content-Type": "application/json"
        },
        "data": JSON.stringify({
            "query": query
        }),
    };

    showDialogLoading();

    var table = $("#rutas-table");
    table.empty();

    $.ajax(settings).done(function (response) {
        hideDialogLoading();
        if (response["idEstatus"] == 1) {
            var rutas = response["data"];
            lsParadas = rutas;
            for (var i = 0; i < rutas.length; i++) {
                //actions edit/delete
                var actionEditButton = "<a href='nuevaRuta.php?idRuteo=" + rutas[i]["idRuteo"] + "' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
                var actionDeleteButton = "<a href='javascript:deleteRutaModal(" + rutas[i]["idRuteo"] + ")' class='btn btn-danger btn-xs'><i class='fa-solid fa-trash'></i></a>";
                var actionParadas = "<a href='paradas.php?idRuteo=" + rutas[i]["idRuteo"] + "' class='btn btn-success btn-xs'><i class='fa fa-map-marker'></i></a>";
                var actions = actionEditButton + " " + actionDeleteButton + " " + actionParadas;

                var idRuteo = "<td>" + rutas[i]["idRuteo"] + "</td>";
                var fecha = "<td>" + rutas[i]["fecha"] + "</td>";
                var folio = "<td>" + rutas[i]["Folio"] + "</td>";
                var repartidor = "<td>" + rutas[i]["nbRepartidor"] + "</td>";
                var estatus = "<td>" + rutas[i]["nbEstatus"] + "</td>";
                var inventarioBtn = "<td class='text-center'><a href='javascript:showModalProductos(" + rutas[i]["idRuteo"] + ")' class='btn btn-info btn-xs'><i class='fa fa-list'></i></a></td>";
                var paradas = "<td class='text-center'>" + actionParadas + "</td>";
                var editar = "<td class='text-center'>" + actionEditButton + "</td>";
                var eliminar = "<td class='text-center'>" + actionDeleteButton + "</td>";

                table.append("<tr>" + idRuteo + fecha + folio + repartidor + estatus + inventarioBtn + paradas + editar + eliminar + "</tr>");
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        hideDialogLoading();
        // showDialogError("Error al obtener las rutas");
    });
}

function showModalProductos(idRuteo) {
    var data = {
        "idRuteo": idRuteo,
    }

    let settings = {
        "url": "../ws/v2/rutas.php?action=getInventarioRuta",
        "method": "POST",
        "timeout": 0,
        "headers": {
            "Content-Type": "application/json"
        },
        "data": JSON.stringify(data),
    };

    let table = $("#tabla-productos");
    table.empty();

    $.ajax(settings).done(function (response) {

        if(response["idEstatus"] == 1){
            var lsProductos = response["data"];

            for (var i = 0; i < lsProductos.length; i++) {
               
                var nbProducto = "<td>" + lsProductos[i]["nbProducto"] + "</td>";
                var dcCantidad = "<td>" + lsProductos[i]["dcCantidad"] + "</td>";
                var dcPrecioCompra = "<td>" + lsProductos[i]["dcPrecioCompra"] + "</td>";
                var dcPrecioVenta = "<td>" + lsProductos[i]["dcPrecioVenta"] + "</td>";
                var dcComision = "<td>" + lsProductos[i]["dcComision"] + "</td>";

                table.append("<tr>" + nbProducto+dcCantidad+ dcPrecioCompra + dcPrecioVenta + dcComision + "</tr>");
            }

            $("#modal-productos").modal("show");
        }else{
            showDialogError("La ruta no tiene productos");
        }
       


    }).fail(function (jqXHR, textStatus, errorThrown) {
        hideDialogLoading();
     showDialogError("La ruta no tiene productos");
    });
}

function deleteRutaModal(idRuteo) {
    $("#idRuteoDelete").val(idRuteo);
    var folio = "";
    for (var i = 0; i < lsParadas.length; i++) {
        if (lsParadas[i]["idRuteo"] == idRuteo) {
            folio = lsParadas[i]["Folio"];
        }
    }

    //set message modal-body
    $("#message").html("¿Está seguro de eliminar la ruta con folio: " + folio + "?");
    $("#modalDeleteRuta").modal("show");
}

//delete ruta when click on button Eliminar modal modalDeleteRuta
$("#btnDeleteRuta").click(function () {
    deleteRuta();
    //dismiss modal
    $("#modalDeleteRuta").modal("hide");
});

function deleteRuta() {
    var idRuteo = $("#idRuteoDelete").val();

    var data = {
        "idRuteo": idRuteo,
    };

    var jsonData = JSON.stringify(data);

    var settings = {
        "url": "../ws/v2/rutas.php?action=delete",
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
            if (response["idEstatus"] == 1) {
                showDialogSuccess(response["mensaje"]);
                searchRutas();
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

}