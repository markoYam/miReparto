//document ready
$(document).ready(function () {
    searchRepartidores();
    getEstatus();
});
//search all repartidores
function searchRepartidores() {
    $.ajax({
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

function getEstatus() {

    $data = {
        "nbModulo": "RUTA"
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

function saveForm() {
    var formArray = $('#nueva-ruta-form').serializeArray();
    var formData = {};
    $.each(formArray, function (i, field) {
        formData[field.name] = field.value;
    });

    var jsonData = JSON.stringify(formData);
    console.log(jsonData);

    $.ajax({
        url: "../ws/v2/rutas.php?action=create",
        type: "POST",
        data: jsonData,
        contentType: "application/json",
        success: function (data) {
            if (data["idEstatus"] == 1) {
                $("#content-error").html("<div class='alert alert-success' role='alert'>" + data["mensaje"] + "</div>");
            } else {
                $("#content-error").html("<div class='alert alert-danger' role='alert'>" + data["mensaje"] + "</div>");
            }
        },
        error: function (data) {
            console.log(data);
            $("#content-error").html("<div class='alert alert-danger' role='alert'>" + data["mensaje"] + "</div>");
        }
    });

}
