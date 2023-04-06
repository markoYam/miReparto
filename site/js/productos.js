$(document).ready(async function () {
    searchProductos();
    eventDelete();

    $("#txt-search").keyup(function () {
        searchProductos();
    });
});

function convertToStringCurrency(number) {
    return number.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
}

var lsProductos = [];
async function searchProductos() {
    try {
        let query = $("#txt-search").val();

        let settings = {
            "url": "../ws/v2/productos.php",
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

        let table = $("#productos-table");
        table.empty();

        $.ajax(settings)
            .done(function (response) {
                hideDialogLoading();
                if (response["idEstatus"] == 1) {
                    let productos = response["data"];
                    lsProductos = productos;
                    let table = $("#productos-table");
                    table.empty();
                    for (let i = 0; i < productos.length; i++) {

                        
                        let dcPrecioCompra_f = parseFloat(productos[i]["dcPrecioCompra"]);
                        let dcPrecioVenta_f = parseFloat(productos[i]["dcPrecioVenta"]);
                        let dcComision_f = parseFloat(productos[i]["dcComision"]);

                        let actionEditButton = "<a href='nuevoProducto.php?idProducto=" + productos[i]["idProducto"] + "' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
                        let actionDeleteButton = "<a href='javascript:deleteProductoModal(" + productos[i]["idProducto"] + ")' class='btn btn-danger btn-xs'><i class='fa-solid fa-trash'></i></a>";
                        let idProducto = "<td>" + productos[i]["idProducto"] + "</td>";
                        let nombre = "<td>" + productos[i]["nbProducto"] + "</td>";
                        let descripcion = "<td>" + productos[i]["desProducto"] + "</td>";
                        let dcPrecioCompra = "<td>$" + convertToStringCurrency(dcPrecioCompra_f) + "</td>";
                        let dcPrecioVenta = "<td>$" + convertToStringCurrency(dcPrecioVenta_f) + "</td>";
                        let dcComision = "<td>$" + convertToStringCurrency(dcComision_f) + "</td>";
                        //tallas modal
                        let tallasbtn = "<td> <a href='javascript:showModalTallas(" + productos[i]["idProducto"] + ")' class='btn btn-primary btn-xs'><i class='fa-solid fa-barcode'></i></a></td>";
                        //fotos modal
                        let fotosbtn = "<td> <a href='javascript:showModalFotos(" + productos[i]["idProducto"] + ")' class='btn btn-primary btn-xs'><i class='fa-solid fa-camera'></i></a></td>";
                        let editar = "<td class='center'>" + actionEditButton + "</td>";
                        let eliminar = "<td class='center'>" + actionDeleteButton + "</td>";
        
                        table.append("<tr>" + idProducto + nombre + descripcion + dcPrecioCompra + dcPrecioVenta + dcComision + tallasbtn + fotosbtn + editar + eliminar + "</tr>");
                   
                    }

                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                hideDialogLoading();
                //showDialogError("Error al obtener los productos");
            });
    } catch (error) {
        console.log(error);
    }
}

async function showModalFotos(idProducto) {

    let json = {
        "idProducto": idProducto
    };
    let data = JSON.stringify(json);
    //console.log(data);
    var settings = {
        "url": "../ws/v2/productos.php?action=getFotosProducto",
        "method": "POST",
        "timeout": 0,
        "headers": {
            "Content-Type": "application/json"
        },
        "data": data,
    };

    showDialogLoading();

    let carrucelContainer = $("#carrucel-items-container");
    carrucelContainer.empty();

    let carrucelIndicators = $("#carrucel-indicators");
    carrucelIndicators.empty();

    await $.ajax(settings)
        .done(function (response) {
            //console.log(response);
            hideDialogLoading();
            if (response["idEstatus"] == 1) {
                let fotos = response["data"];
                for (let i = 0; i < fotos.length; i++) {
                    let foto = fotos[i];
                    let item = $("<div class='carousel-item'></div>");
                    let img = $("<img src='" + "../"+foto["desPathFoto"] + "' class='d-block w-100' alt='' style='height: 40rem;' >");
                    if (i == 0) {
                        item.addClass("active");
                    }
                    item.append(img);
                    carrucelContainer.append(item);

                    //generate indicators
                    let indicator = $("<li data-target='#carouselExampleIndicators' data-slide-to='" + i + "'></li>");
                    if (i == 0) {
                        indicator.addClass("active");
                    }
                    carrucelIndicators.append(indicator);                    
                }

                $("#modalFotos").modal("show");

            } else {
                showDialogError(response["mensaje"]);
            }

        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            hideDialogLoading();
            showDialogError("No se encontraron fotos para este producto");
        });



}

function showModalTallas(idProducto) {

    let json = {
        "idProducto": idProducto
    };

    let data = JSON.stringify(json);
    //console.log(data);
    var settings = {
        "url": "../ws/v2/productos.php?action=getTallasProducto",
        "method": "POST",
        "timeout": 0,
        "headers": {
            "Content-Type": "application/json"
        },
        "data": data,
    };

    showDialogLoading();

    let tallasContainer = $("#tallasProductoContainer");
    tallasContainer.empty();

    $.ajax(settings)
        .done(function (response) {
            //console.log(response);
            hideDialogLoading();
            if (response["idEstatus"] == 1) {
                let tallas = response["data"];
                for (let i = 0; i < tallas.length; i++) {
                    let talla = tallas[i];
                    let container = $("<div class='col-sm'></div>");
                    let card = $("<div class='card mt-2' style='width: 100%;'></div>");
                    let cardBody = $("<div class='card-body text-center'></div>");
                    let tallaH2 = $("<h2>" + talla["nbTalla"] + "</h2>");
                    card.append(cardBody);
                    cardBody.append(tallaH2);
                    container.append(card);
                    tallasContainer.append(container);
                }

                $("#modalTallas").modal("show");

            } else {
                showDialogError(response["mensaje"]);
            }

        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            hideDialogLoading();
            showDialogError("No se encontraron tallas para este producto");
        });

}

function deleteProductoModal(idProducto) {
    $("#idProductoDelete").val(idProducto);
    var nombre = "";
    for (var i = 0; i < lsProductos.length; i++) {
        if (lsProductos[i]["idProducto"] == idProducto) {
            nombre = lsProductos[i]["nbProducto"];
        }
    }

    //set message modal-body
    $("#message").html("¿Está seguro de eliminar el producto: " + nombre + "?");
    $("#modalDelete").modal("show");
}

function eventDelete() {
    $("#btnDeleteProducto").click(function () {
        $("#modalDelete").modal("hide");
        let idProducto = $("#idProductoDelete").val();
        let json = {
            "idProducto": idProducto
        };
        let data = JSON.stringify(json);
        //console.log(data);
        var settings = {
            "url": "../ws/v2/productos.php?action=delete",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": data,
        };

        showDialogLoading();

        $.ajax(settings)
            .done(function (response) {
                //console.log(response);
                hideDialogLoading();
                if (response["idEstatus"] == 1) {
                    showDialogSuccess(response["mensaje"]);
                    searchProductos();
                } else {
                    showDialogError(response["mensaje"]);
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
                hideDialogLoading();
                showDialogError("No se pudo eliminar el producto");
            });
    });
}