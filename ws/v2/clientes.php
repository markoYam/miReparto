<?php
header('Content-Type: application/json');
require_once('../config.php');

$request = $_SERVER['REQUEST_METHOD'];

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'getById':
            getById($conn);
            break;

        case 'updateEstatus':
            ActualizarEstatus($conn);
            break;

        case 'delete':
            Delete($conn);
            break;

        case 'create':
            Create($conn);
            break;

        case 'update':
            Update($conn);
            break;
        default:
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Bad request.'
        ));
    }
}else{
    index($conn);
}

function index($conn){
    $querySearch = "";

    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);

    if($obj != null){
        $search = $obj['query'];
        $querySearch = " WHERE nbCliente LIKE '%$search%' OR desCalleUno LIKE '%$search%' OR desCalleDos LIKE '%$search%' OR desCalleTres LIKE '%$search%' OR desReferencias LIKE '%$search%' OR numTelefono LIKE '%$search%' OR urlFacebook LIKE '%$search%'";
    }
    
    $sql = "SELECT * FROM tbl_clientes";
    if($querySearch != ""){
        $sql .= $querySearch;
    }
    $sql .= " ORDER BY idCliente DESC";
    
    $result = mysqli_query($conn, $sql);
    $data = array();
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    echo json_encode(array(
        'idEstatus' => 1,
        'data' => $data,
        'mensaje' => 'Clientes obtenidos correctamente.'
    ));
}

function Update($conn){
    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);

    if($obj == null){
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Bad request.'
        ));
        return;
    }
    $idCliente = $obj['idCliente'];
    $nbCliente = $obj['nbCliente'];
    $desCalleUno = $obj['desCalleUno'];
    $desCalleDos = $obj['desCalleDos'];
    $desCalleTres = $obj['desCalleTres'];
    $desReferencias = $obj['desReferencias'];
    $numTelefono = $obj['numTelefono'];
    $urlFacebook = $obj['urlFacebook'];

    //prevent sql injection
    $idCliente = mysqli_real_escape_string($conn, $idCliente);
    $nbCliente = mysqli_real_escape_string($conn, $nbCliente);
    $desCalleUno = mysqli_real_escape_string($conn, $desCalleUno);
    $desCalleDos = mysqli_real_escape_string($conn, $desCalleDos);
    $desCalleTres = mysqli_real_escape_string($conn, $desCalleTres);
    $desReferencias = mysqli_real_escape_string($conn, $desReferencias);
    $numTelefono = mysqli_real_escape_string($conn, $numTelefono);
    $urlFacebook = mysqli_real_escape_string($conn, $urlFacebook);

    $sql = "UPDATE tbl_clientes SET nbCliente = '$nbCliente', desCalleUno = '$desCalleUno', desCalleDos = '$desCalleDos', desCalleTres = '$desCalleTres', desReferencias = '$desReferencias', numTelefono = '$numTelefono', urlFacebook = '$urlFacebook' WHERE idCliente = $idCliente";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'Cliente actualizado correctamente.'
        ));
    }else{
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'No se pudo actualizar el cliente.'
        ));

}
}
function getById($conn){
    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);

    if($obj == null){
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Bad request.'
        ));
        return;
    }
    $idCliente = $obj['idCliente'];

    //prevent sql injection
    $idCliente = mysqli_real_escape_string($conn, $idCliente);

    $sql = "SELECT * FROM tbl_clientes WHERE idCliente = $idCliente";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => $row,
            'mensaje' => 'OK'
        ));
    }else{
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'No se encontró el cliente.'
        ));
    }
}
function Create($conn){
    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);

    if($obj == null){
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Bad request.'
        ));
        return;
    }
    
    $nbCliente = $obj['nbCliente'];
    $desCalleUno = $obj['desCalleUno'];
    $desCalleDos = $obj['desCalleDos'];
    $desCalleTres = $obj['desCalleTres'];
    $desReferencias = $obj['desReferencias'];
    $numTelefono = $obj['numTelefono'];
    $urlFacebook = $obj['urlFacebook'];

    //prevent sql injection
    $nbCliente = mysqli_real_escape_string($conn, $nbCliente);
    $desCalleUno = mysqli_real_escape_string($conn, $desCalleUno);
    $desCalleDos = mysqli_real_escape_string($conn, $desCalleDos);
    $desCalleTres = mysqli_real_escape_string($conn, $desCalleTres);
    $desReferencias = mysqli_real_escape_string($conn, $desReferencias);
    $numTelefono = mysqli_real_escape_string($conn, $numTelefono);
    $urlFacebook = mysqli_real_escape_string($conn, $urlFacebook);


    //validate if the client already exists by phone number or name or facebook url
    $sql = "SELECT * FROM tbl_clientes WHERE nbCliente = '$nbCliente'";

    if($numTelefono !== ''){
        $sql .= " OR numTelefono = '$numTelefono'";
    }

    if($urlFacebook !== ''){
        $sql .= " OR urlFacebook = '$urlFacebook'";
    }

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'El cliente ya existe.'
        ));
        return;
    }

    //insert the client
    $sql = "INSERT INTO tbl_clientes (nbCliente, desCalleUno, desCalleDos, desCalleTres, desReferencias, numTelefono, urlFacebook) VALUES ('$nbCliente', '$desCalleUno', '$desCalleDos', '$desCalleTres', '$desReferencias', '$numTelefono', '$urlFacebook')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'Cliente creado correctamente.'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Error al crear el cliente.'
        ));
    }

}

?>