//document ready
$(document).ready(function () {
    searchRutas();
    getEstatus();
});

function searchRutas() {
    $.ajax({
        url: "../ws/v2/rutas.php?action=getCustomView",
        type: "GET",
        success: function (data) {
            if (data["idEstatus"] == 1) {
                var rutas = data["data"];
                //fill table with data
                var table = $("#rutas-table");
                table.empty();
                for (var i = 0; i < rutas.length; i++) {
                    //actions edit/delete
                    var actionEditButton = "<a href='nuevaRuta.php?id=" + rutas[i]["idRuteo"] + "' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i> Editar </a>";
                    var actionDeleteButton = "<a href='javascript:deleteRuta(" + rutas[i]["idRuteo"] + ")' class='btn btn-danger btn-xs'><i class='fa fa-trash-o'></i> Eliminar </a>";
                    var actionParadas = "<a href='paradas.php?id=" + rutas[i]["idRuteo"] + "' class='btn btn-success btn-xs'><i class='fa fa-map-marker'></i> Paradas </a>";
                    var actions = actionEditButton + " " + actionDeleteButton + " " + actionParadas;
                    table.append("<tr><td>" + rutas[i]["fecha"] + "</td><td>" + rutas[i]["Folio"] + "</td><td>" + rutas[i]["nbRepartidor"] + "</td><td>" + rutas[i]["nbEstatus"] + "</td><td>"+actions+"</td></tr>");

                }

            }
        },
        error: function (data) {
            console.log(data);
        }
    });
}