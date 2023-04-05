//document ready
$(document).ready(async function () {

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

    //llenar los select de repartidores y estatus
    //console.log("antes de searchRepartidores");
    await searchRepartidores();
    //console.log("despues de searchRepartidores");
    await getEstatus();
    //console.log("despues de getEstatus");
    await getDetalleRuta();
    //console.log("despues de getDetalleRuta");
});
//search all repartidores
async function searchRepartidores() {
    await $.ajax({
        url: "../ws/v2/repartidores.php",
        type: "GET",
        success: function (data) {
            if (data["idEstatus"] == 1) {
                var repartidores = data["data"];
                var repartidoresSelect = $("#idRepartidor");
                repartidoresSelect.empty();
                repartidoresSelect.append("<option value='-1'>Seleccione un repartidor</option>");
                for (var i = 0; i < repartidores.length; i++) {
                    repartidoresSelect.append("<option value='" + repartidores[i]["idRepartidor"] + "'>" + repartidores[i]["nbRepartidor"] + "</option>");
                }

            }
        },
        error: function (data) {
            console.log(data);
        }
    });
}

async function getEstatus() {

    $data = {
        "nbModulo": "RUTA"
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
                estatusSelect.append("<option value='-1'>Seleccione un estatus</option>");
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

//REGION SAVE FORM nueva-ruta-form SEREALIZADO JSON

//register event click in submit button form
$("#btn-submit").click(function () {
    saveForm();
});

function getDetails() {

}

function saveForm() {
    var formArray = $('#nueva-ruta-form').serializeArray();
    var formData = {};
    $.each(formArray, function (i, field) {
        formData[field.name] = field.value;
    });

    var jsonData = JSON.stringify(formData);
    //console.log(jsonData);

    var action = "create";
    if (idRuteo != 0) {
        action = "update";
    }

    if(formData["idRepartidor"] == -1){
        showDialogError("Seleccione un repartidor");
        return;
    }
    if(formData["idEstatus"] == -1){
        showDialogError("Seleccione un estatus");
        return;
    }

    //console.log(action);

    var url = "../ws/v2/rutas.php?action=" + action;
    //console.log(url);

    showDialogLoading();

    $.ajax({
        url: url,
        type: "POST",
        data: jsonData,
        contentType: "application/json",
        success: function (response) {
            if (response["idEstatus"] == 1) {
                showDialogSuccess(response["mensaje"]);
            } else {
                showDialogError(response["mensaje"]);
            }
        },
        error: function (data) {
            hideDialogLoading();
            showDialogError("Ocurrio un error al guardar la información");
        }
    });

}

async function getDetalleRuta() {

    if (idRuteo == 0) {
        return;
    }

    data = {
        "idRuteo": idRuteo,
    }
    var jsonData = JSON.stringify(data);
    console.log(jsonData);

    var settings = {
        "url": "../ws/v2/rutas.php?action=getById",
        "method": "POST",
        "timeout": 0,
        "headers": {
            "Content-Type": "application/json"
        },
        "data": jsonData,
    };

    showDialogLoading();

    await $.ajax(settings)
        .done(function (response) {
            //console.log(response);
            hideDialogLoading();
            if (response["idEstatus"] == 1) {
                //showDialogSuccess(response["mensaje"]);
                var ruta = response["data"];
                //console.log(ruta);
                $("#fecha").val(ruta["fecha"]);
                $("#Folio").val(ruta["Folio"]);
                $("#idRepartidor").val(ruta["idRepartidor"]);
                $("#idEstatus").val(ruta["idEstatus"]);

                //$("#horaInicio").val(ruta["horaInicio"]);
                // $("#horaFin").val(ruta["horaFin"]);
            } else {
                showDialogError(response["mensaje"]);
            }

        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            hideDialogLoading();
            showDialogError("No fue posible obtener la información de la ruta.");
        });

}
