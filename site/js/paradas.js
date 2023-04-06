$(document).ready(async function () {
    deleteFechaInicio();
    deleteFechaFin();
    await getEstatus();
    await searchParadas();
    onEventsPage();
});


async function searchParadas() {
    //get filters
    //var idRuteo = $("#idRuteo").val();
    var idEstatusFiltro = $("#idEstatusFiltro").val();
    var fechaInicioFiltro = $("#fechaInicioFiltro").val();
    var fechaFinFiltro = $("#fechaFinFiltro").val();
    var queryFiltro = $("#queryFiltro").val();

    $data = {
        "idRuta": idRuteo,
        "idEstatus": idEstatusFiltro,
        "fechaInicio": fechaInicioFiltro,
        "fechaFin": fechaFinFiltro,
        "queryFiltro": queryFiltro
    }
    await $.ajax({
        url: "../ws/v2/paradas.php?action=getByRuta",
        type: "POST",
        data: JSON.stringify($data),
        contentType: "application/json",
        success: function (data) {
            console.log(data);
            //fill table with data
            var table = $("#paradas-table");
            table.empty();

            $("#globalInfo").hide();

            if (data["idEstatus"] == 1) {
                var paradas = data["data"];
                //show id="globalInfo"
                $("#globalInfo").show();


                var numProductosGlobal = 0;
                var dcSubtotalGlobal = 0;
                var dcComisionGlobal = 0;
                var dcTotalGlobal = 0;
                for (var i = 0; i < paradas.length; i++) {

                    numProductosGlobal += parseInt(paradas[i]["numProductos"]);
                    dcSubtotalGlobal += parseFloat(paradas[i]["dcSubtotal"]);
                    dcComisionGlobal += parseFloat(paradas[i]["dcComision"]);
                    dcTotalGlobal += parseFloat(paradas[i]["dcTotal"]);

                    let dcComision_f = parseFloat(paradas[i]["dcComision"]);
                    let dcTotal_f = parseFloat(paradas[i]["dcTotal"]);
                    let dcSubtotal_f = parseFloat(paradas[i]["dcSubtotal"]);

                    var actionEditButton = "<td> <a href='nuevaParadas.php?idParada=" + paradas[i]["idParada"] +"&idRuteo="+idRuteo+ "' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a></td>";
                    var actionDeleteButton = "<td><a href='javascript:deleteParada(" + paradas[i]["idParada"] + ")' class='btn btn-danger btn-xs'><i class='fa-solid fa-trash'></i></a></td>";
                    var actionMapa = "<td><a href='javascript:openModalMaps(" + paradas[i]["idParada"] + "," + paradas[i]["latitud"] + "," + paradas[i]["longitud"] + ")' class='btn btn-success btn-xs'><i class='fa fa-map-marker'></i></a></td>";
                    var id = "<td>" + paradas[i]["idParada"] + "</td>";
                    var estatus = "<td>" + paradas[i]["nbEstatus"] + "</td>";
                    var folio = "<td>" + paradas[i]["fecha"] + "</td>";
                    var dcSubTotal = "<td>$" + convertToStringCurrency(dcSubtotal_f) + "</td>";
                    var numProductos = "<td>" + paradas[i]["numProductos"] + "</td>";
                    var dcComision = "<td>$" + convertToStringCurrency(dcComision_f) + "</td>";
                    var dcTotal = "<td>$" + convertToStringCurrency(dcTotal_f) + "</td>";
                    var cliente = "<td>" + paradas[i]["cliente"] + "</td>";

                    //when status is ENTREGADO show circle with green color
                    if (paradas[i]["idEstatus"] == 8) {
                        estatus = "<td class='text-center'><i class='fa fa-circle' style='color: gray; font-size: 25px;'></i></td>";                       
                    }

                    if (paradas[i]["idEstatus"] == 9) {
                        estatus = "<td class='text-center'><i class='fa fa-circle' style='color: green; font-size: 25px;'></i></td>";                       
                    }
                    //when status is 10 show circle with reed color
                    if (paradas[i]["idEstatus"] == 10) {
                        estatus = "<td class='text-center'><i class='fa fa-circle' style='color: red; font-size: 25px;'></i></td>";
                    }

                    //var comentarios = "<td>" + paradas[i]["comentarios"] + "</td>";
                    var fechaActualizacion = "<td>" + (paradas[i]["feActualizacion"] == null ? '' : paradas[i]["feActualizacion"]) + "</td>";
                    table.append("<tr>" + id + estatus + folio + cliente + numProductos + dcSubTotal + dcComision + dcTotal  + fechaActualizacion + actionMapa + actionEditButton + actionDeleteButton + "</tr>");
                    //table.append("<tr><td>" + paradas[i]["nbParada"] + "</td><td>" + paradas[i]["nbEstatus"] + "</td><td style='width: 100px;'>"+actionEditButton+"</td><td style='width: 100px;'>"+actionDeleteButton+"</td></tr>");
                }

                //fill totals and mark as bold
                $("#numProductosGlobal").html("Productos: <b>" + convertToStringCurrency(numProductosGlobal) + "</b>");
                $("#dcSubtotalGlobal").html("SubTotal: <b>$" + convertToStringCurrency(dcSubtotalGlobal) + "</b>");
                $("#dcComisionGlobal").html("Comisión: <b>$" + convertToStringCurrency(dcComisionGlobal) + "</b>");
                $("#dcTotalGlobal").html("Total: <b>$" + convertToStringCurrency(dcTotalGlobal) + "</b>");

            }
        },
        error: function (data) {
            console.log(data);
           // $("#globalInfo").hide();
        }
    });
}

function convertToStringCurrency(number) {
    return number.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
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
                var estatusSelect = $("#idEstatusFiltro");
                estatusSelect.empty();
                //option all
                estatusSelect.append("<option value='-1'>Todos</option>");
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

function openModalMaps($idParada, $latitud, $longitud) {
    $('#modalMap').modal('show');
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 8,
        center: { lat: $latitud, lng: $longitud }
    });

    var marker = new google.maps.Marker({
        map: map,
        position: { lat: $latitud, lng: $longitud }
    });

    //zoom to marker
    map.setCenter(marker.getPosition());
    map.setZoom(15);

}

function geocodeAddress(geocoder, resultsMap, $idParada) {
    var address = "Mexico";
    geocoder.geocode({ 'address': address }, function (results, status) {
        if (status === 'OK') {
            resultsMap.setCenter(results[0].geometry.location);

        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}

function deleteFechaInicio() {
    $('#eliminar-fecha').on('click', async function () {
        $('#fechaInicioFiltro').val('');
        await searchParadas();
    });
}

function deleteFechaFin() {
    $('#eliminar-fecha-fin').on('click', async function () {
        $('#fechaFinFiltro').val('');
        await searchParadas();
    });
}

function onEventsPage() {
     //add event status change
     //queryFiltro TEXT CHANGE

    $("#queryFiltro").keyup(async function () {
        console.log("change queryFiltro");
        await searchParadas();
    });

    //add event status change
    $("#idEstatusFiltro").change(async function () {
        console.log("change status");
        await searchParadas();
    });

    //add event date change
    $("#fechaInicioFiltro").change(async function () {
        console.log("change date inicio");
        await searchParadas();
    });

    //add event date change
    $("#fechaFinFiltro").change(async function () {
        console.log("change date fin");
        await searchParadas();
    });


}

