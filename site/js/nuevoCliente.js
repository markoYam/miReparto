$(document).ready(function () {

    //submit form
    $("#clienteForm").submit(function (e) {
        e.preventDefault();

        
        var nbCliente = $('#nbCliente').val();
        var desCalleUno = $('#desCalleUno').val();
        var desCalleDos = $('#desCalleDos').val();
        var desCalleTres = $('#desCalleTres').val();
        var desReferencias = $('#desReferencias').val();
        var numTelefono = $('#numTelefono').val();
        var urlFacebook = $('#urlFacebook').val();

        var data = {
            idCliente: idCliente,
            nbCliente: nbCliente,
            desCalleUno: desCalleUno,
            desCalleDos: desCalleDos,
            desCalleTres: desCalleTres,
            desReferencias: desReferencias,
            numTelefono: numTelefono,
            urlFacebook: urlFacebook
        };

        var jsonData = JSON.stringify(data);

        var action = "create";
        if (idCliente != 0) {
            action = "update";
        }

        let settings = {
            "url": "../ws/v2/clientes.php?action="+action,
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


    if(idCliente != 0){
        getDetalleCliente();
    }

    function getDetalleCliente(){

        var data = {
            "idCliente": idCliente,
        };

        var jsonData = JSON.stringify(data);

        var settings = {
            "url": "../ws/v2/clientes.php?action=getById",
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
                    var cliente = response["data"];
                    $("#nbCliente").val(cliente["nbCliente"]);
                    $("#desCalleUno").val(cliente["desCalleUno"]);
                    $("#desCalleDos").val(cliente["desCalleDos"]);
                    $("#desCalleTres").val(cliente["desCalleTres"]);
                    $("#desReferencias").val(cliente["desReferencias"]);
                    $("#numTelefono").val(cliente["numTelefono"]);
                    $("#urlFacebook").val(cliente["urlFacebook"]);
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

    }
});
