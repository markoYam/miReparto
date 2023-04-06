function showDialogLoading(){
    hideDialogError();
    hideDialogSuccess();
    $('#dialog-carga').modal('hide');
}

function hideDialogLoading(){
    $('#dialog-carga').modal('hide');
}

function showDialogError($message){
    hideDialogError();
    $('#modal-error').modal('show');    
    $('#modal-error .modal-body').html($message);
}

function hideDialogError(){
    $('#modal-error').modal('hide');
}

function showDialogSuccess($message){
    hideDialogSuccess();
    $('#modal-exito').modal('show');
    $('#modal-exito .modal-body').html($message);
}

function hideDialogSuccess(){
    $('#modal-exito').modal('hide');
}