//document ready
$(document).ready(function () {
    searchRutas();
    //getEstatus();
    $("#txt-search").keyup(function () {
        searchRutas();
    });
});

var lsRutas = [];
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
            lsRutas = rutas;
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
                var paradas = "<td class='center'>" + actionParadas + "</td>";
                var editar = "<td class='center'>" + actionEditButton + "</td>";
                var eliminar = "<td class='center'>" + actionDeleteButton + "</td>";

                table.append("<tr>" + idRuteo + fecha + folio + repartidor + estatus + paradas + editar + eliminar + "</tr>");
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        hideDialogLoading();
        // showDialogError("Error al obtener las rutas");
    });
}

function deleteRutaModal(idRuteo) {
    $("#idRuteoDelete").val(idRuteo);
    var folio = "";
    for (var i = 0; i < lsRutas.length; i++) {
        if (lsRutas[i]["idRuteo"] == idRuteo) {
            folio = lsRutas[i]["Folio"];
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