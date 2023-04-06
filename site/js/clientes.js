$(document).ready(function () {


    getClientes();

    $("#txt-search").keyup(function () {
        getClientes();
    });

    function getClientes() {
        let query = $("#txt-search").val();
        let settings = {
            "url": "../ws/v2/clientes.php",
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
        var table = $("#clientes-table")
        table.empty();

        $.ajax(settings)
            .done(function (response) {
                //console.log(response);
                hideDialogLoading();
                if (response["idEstatus"] == 1) {
                    //showDialogSuccess(response["mensaje"]);
                    //loop and append to table
                    $.each(response["data"], function (index, value) {
                        let idCliente = "<td>" + value["idCliente"] + "</td>";
                        let nbCliente = "<td>" + value["nbCliente"] + "</td>";
                        let desCalleUno = "<td>" + value["desCalleUno"] + "</td>";
                        let desCalleDos = "<td>" + value["desCalleDos"] + "</td>";
                        let desCalleTres = "<td>" + value["desCalleTres"] + "</td>";
                        let desReferencias = "<td>" + value["desReferencias"] + "</td>";
                        let numTelefono = "<td>" + value["numTelefono"] + "</td>";
                        //let urlFacebook = "<td>" + value["urlFacebook"] + "</td>";

                        //let editar = "<td>"+"<a href='nuevoCliente.php?idCliente="+idCliente+"'>Editar</a>"+"</td>";
                        //let eliminar = "<td>"+"<a href='nuevoCliente.php?idCliente="+idCliente+"'>Eliminar</a>"+"</td>";
                        let editar = "<td> <a href='nuevoCliente.php?idCliente=" + value["idCliente"] + "' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a></td>";
                        let eliminar = "<td><a href='javascript:deleteParada(" + value["idCliente"] + ")' class='btn btn-danger btn-xs'><i class='fa-solid fa-trash'></i></a></td>";

                        //if urlFacebook is null, then don't show the button
                        let abrirURl = "";
                        if (value["urlFacebook"] != null && value["urlFacebook"] != "")
                            abrirURl = "<td><a href='" + value["urlFacebook"] + "' target='_blank' class='btn btn-danger btn-xs'><i class='fa-solid fa-share'></i></a></td>";
                        else
                            abrirURl = "<td></td>";
                        let tr = "<tr>" + idCliente + nbCliente + desCalleUno + desCalleDos + desCalleTres + desReferencias + numTelefono + abrirURl + editar + eliminar + "</tr>";

                        table.append(tr);
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
                showDialogError("Error al obtener los clientes");
            });
    }
});