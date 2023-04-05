$(document).ready(async function () {
    await getTallas();
    addFotoToListWhenSelected();
    onFormSave();
    eventsFoto();

    if (idProducto != 0) {
        await getProductoDetalle();
    }
});

async function getProductoDetalle() {
    let data = {
        "idProducto": idProducto
    };
    var jsonData = JSON.stringify(data);
    var settings = {
        "url": "../ws/v2/productos.php?action=getProductoDetalleById",
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
                var producto = response["data"];
                //$("#idProducto").val(producto["idProducto"]);
                $("#nbProducto").val(producto["nbProducto"]);
                $("#desProducto").val(producto["desProducto"]);

                $("#dcPrecioCompra").val(producto["dcPrecioCompra"]);
                $("#dcPrecioVenta").val(producto["dcPrecioVenta"]);
                $("#dcComision").val(producto["dcComision"]);

                let tallas = producto["tallas"];

                //seach talla in tallas-table and check it
                var table = $("#tallas-table");
                table.find('tr').each(function () {
                    var idTalla = $(this).find('td').eq(1).text();
                    for (let i = 0; i < tallas.length; i++) {
                        if (tallas[i]["idTalla"] == idTalla) {
                            $(this).find('input').prop('checked', true);
                        }
                    }
                });

                let fotos = producto["fotos"];

                $.each(fotos, function (index, foto) {
                    var numFoto = foto["numFoto"];
                    var img = $("#foto-" + numFoto);
                    img.attr("src", "../" + foto["desPathFoto"]);
                    img.attr("data-idFotoProducto", foto["idFotoProducto"]);
                    fotosBase64.push(foto);
                });


            } else {
                showDialogError(response["mensaje"]);
            }
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            hideDialogLoading();
            showDialogError("Error al obtener detalle del producto");
        });

}

function deleteTallaSeleccionada() {
    var table = $("#tallas-table");
    table.find('tr').each(function () {
        var idTalla = $(this).find('td').eq(0).text();
        if ($idTalla == idTalla) {
            $(this).remove();
        }
    });
}

async function getTallas() {
    var jsonData = JSON.stringify({});
    var settings = {
        "url": "../ws/v2/tallas.php",
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
            console.log(response);
            hideDialogLoading();
            if (response["idEstatus"] == 1) {
                //load in tallas-table-modal
                var tallasList = $("#tallas-table");
                tallasList.empty();
                var lsTallas = response["data"];
                for (var i = 0; i < lsTallas.length; i++) {
                    var seleccionarCheckbox = "<input type='checkbox' name='idTalla' value='" + lsTallas[i]["idTalla"] + "'>";

                    tallasList.append("<tr><td class='text-center'>" + seleccionarCheckbox + "</td><td>" + lsTallas[i]["idTalla"] + "</td><td>" + lsTallas[i]["nbTalla"]);
                }
            } else {
                showDialogError(response["mensaje"]);
            }

        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            hideDialogLoading();
            showDialogError("Error al obtener las tallas");
        });
}

async function addFotoToListWhenSelected() {
    var container = $("#fotos-container");

    $("#fotos").change(async function () {
        var files = this.files;

        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var reader = new FileReader();
            reader.onloadend = async function (event) {
                var res = event.target.result;
                var columnRow = $("<div class='col-12 col-md-6 col-lg-4 mt-2 mb-2'></div>");
                var card = $("<div class='card'></div>");
                var hidderidFotoProducto = $("<input type='hidden' name='idFotoProducto' value='0'>");
                card.append(hidderidFotoProducto);
                var cardBody = $("<div class='card-body'></div>");
                var img = $("<img class='card-img-top p-2' src='" + res + "' alt='Card image cap'>");
                var btnDelete = $("<button type='button' class='btn btn-danger btn-sm btn-block'>Eliminar</button>");
                btnDelete.click(function () {
                    $(this).parent().parent().parent().remove();
                });
                cardBody.append(img);
                cardBody.append(btnDelete);
                card.append(cardBody);
                columnRow.append(card);
                container.append(columnRow);
            }
            if (file) {
                reader.readAsDataURL(file);
            } else {
                //container.empty();
            }
        }
    });
}

async function eventsFoto() {
    
    //on change foto-input-1
    //var fotoInput = $("#foto-input-1");

    //loop 1..4
    let fotoInput1 = $("#foto-input-1");
    fotoInput1.change(async function () {
        adSelectFoto(1,this);
    });

    let fotoInput2 = $("#foto-input-2");
    fotoInput2.change(async function () {
        adSelectFoto(2,this);
    });

    let fotoInput3 = $("#foto-input-3");
    fotoInput3.change(async function () {
        adSelectFoto(3,this);
    });

    let fotoInput4 = $("#foto-input-4");
    fotoInput4.change(async function () {
        adSelectFoto(4,this);
    });

    deleteFotoEvent(1);
    deleteFotoEvent(2);
    deleteFotoEvent(3);
    deleteFotoEvent(4);

}
function adSelectFoto(i,input) {
    var imgTmp = "https://via.placeholder.com/150"
    var files = input.files;
    var file = files[0];
    var reader = new FileReader();
    reader.onloadend = async function (event) {
        var res = event.target.result;
        var img = $("#foto-" + i);
        img.attr("src", res);
        img.attr("data-idFotoProducto", 0)
        var obj = { "desPathFoto": res, "numFoto": i, "idFotoProducto": 0 };
        fotosBase64.push(obj);
    }
    if (file) {
        reader.readAsDataURL(file);
    } else {
        var img = $("#foto-" + i);
        img.attr("src", imgTmp);
    }
}

function deleteFotoEvent(i){
    var imgTmp = "https://via.placeholder.com/150"
    var btnDelete = $("#eliminar-btn-" + i);
    btnDelete.click(function(){        
        var img = $("#foto-" + i);
        img.attr("src", imgTmp);
        img.attr("data-idFotoProducto", 0)
        //delete from array by numFoto
        var index = fotosBase64.findIndex(x => x.numFoto == i);
        if (index > -1) {
            fotosBase64.splice(index, 1);
        }
        //clear input file
        var input = $("#foto-input-" + i);
        input.val("");
       
    });
}

var fotosBase64 = [];

async function onFormSave() {

    $("#btnGuardar").on("click", async function () {

        var form = $("#crearProductoForm");

        //disable auto send form
        form.submit(function (e) {
            e.preventDefault();
        });

        var nbProducto = $("#nbProducto").val();
        var desProducto = $("#desProducto").val();
        var dcPrecioCompra = $("#dcPrecioCompra").val();
        var dcPrecioVenta = $("#dcPrecioVenta").val();
        var dcComision = $("#dcComision").val();

        var tallas = [];

        var tableTallas = $("#tallas-table");
        tableTallas.find('tr').each(function () {
            var idTalla = $(this).find('td').eq(1).text();
            //verificar si esta seleccionado
            var seleccionado = $(this).find('td').eq(0).find('input').is(':checked');
            if (seleccionado) {
                tallas.push({
                    "idTalla": idTalla
                });
            }
        });

        if (tallas.length == 0) {
            showDialogError("Debe seleccionar al menos una talla");
            return;
        }


        if (fotosBase64.length == 0) {
            showDialogError("Debe seleccionar al menos una foto");
            return;
        }

        var jsonData = {
            "idProducto": idProducto,
            "nbProducto": nbProducto,
            "desProducto": desProducto,
            "dcPrecioCompra": dcPrecioCompra,
            "dcPrecioVenta": dcPrecioVenta,
            "dcComision": dcComision,
            "tallas": tallas,
            "fotos": fotosBase64
        };

        await sendRequest(jsonData);

    });
}

async function sendRequest(jsonData) {
    var data = JSON.stringify(jsonData);
    console.log(data);
    var action = "create";
    if (idProducto > 0) {
        action = "update";
    }
    var settings = {
        "url": "../ws/v2/productos.php?action="+action,
        "method": "POST",
        "timeout": 0,
        "headers": {
            "Content-Type": "application/json"
        },
        "data": data,
    };

    showDialogLoading();

    await $.ajax(settings)
        .done(function (response) {
            console.log(response);
            hideDialogLoading();
            console.log(response["idEstatus"]);

            if (response["idEstatus"] == 1) {
                //load in tallas-table-modal
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
            showDialogError("Error al guardar el producto");
        });
}