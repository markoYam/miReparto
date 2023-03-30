<?php
header('Content-Type: application/json');
require_once('../config.php');

$request = $_SERVER['REQUEST_METHOD'];

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'create':
            if ($request == 'POST') {
                //create
                Create ($conn);
            }else{
                echo json_encode(array(
                    'idEstatus' => -1,
                    'data' => array(),
                    'mensaje' => 'Bad request.'
                ));
            }
            break;
        case 'update':
            if ($request == 'POST') {
                //update
                Update($conn);
            }else{
                echo json_encode(array(
                    'idEstatus' => -1,
                    'data' => array(),
                    'mensaje' => 'Bad request.'
                ));
            }
            break;
        case 'delete':
            if ($request == 'POST') {
                Delete($conn);
            }else{
                echo json_encode(array(
                    'idEstatus' => -1,
                    'data' => array(),
                    'mensaje' => 'Bad request.'
                ));
            }
            break;

        case 'getByRepartidor':
            GetByRepartidor($conn);
            break;
        case 'registerLocationUser':
            if ($request == 'POST') {
                //create
                RegisterLocationUser ($conn);
            }else{
                echo json_encode(array(
                    'idEstatus' => -1,
                    'data' => array(),
                    'mensaje' => 'Bad request.'
                ));
            }
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
    $sql = "SELECT * FROM tbl_seguimientogps";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $data = array();
        while($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => $data,
            'mensaje' => 'OK'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'No se encontraron registros.'
        ));
    }
}

function GetByRepartidor($conn){
    //get values from json request body
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

    $idRepartidor = $obj['idRepartidor'];

    //prevent sql injection
    $idRepartidor = mysqli_real_escape_string($conn, $idRepartidor);

    $sql = "SELECT * FROM tbl_seguimientogps WHERE idRepartidor = '$idRepartidor' ORDER BY idLocalizacion DESC LIMIT 1";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $data = array();
        while($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => $data,
            'mensaje' => 'OK'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'No se encontraron registros.'
        ));
    }

}

function Delete($conn){
    //get values from json request body
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

    $idLocalizacion = $obj['idLocalizacion'];

    //prevent sql injection
    $idLocalizacion = mysqli_real_escape_string($conn, $idLocalizacion);

    $sql = "DELETE FROM tbl_seguimientogps WHERE idLocalizacion = '$idLocalizacion'";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'Eliminación exitosa.'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Error al eliminar.'
        ));
    }

}

function Update($conn){
    //get values from json request body
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

    $idLocalizacion = $obj['idLocalizacion'];
    $idRepartidor = $obj['idRepartidor'];
    $latitud = $obj['latitud'];
    $longitud = $obj['longitud'];
    $idRuta = $obj['idRuta'];
    $feRegistro = date('Y-m-d H:i:s');

    //prevent sql injection
    $idRepartidor = mysqli_real_escape_string($conn, $idRepartidor);
    $latitud = mysqli_real_escape_string($conn, $latitud);
    $longitud = mysqli_real_escape_string($conn, $longitud);
    $idRuta = mysqli_real_escape_string($conn, $idRuta);
    $feRegistro = mysqli_real_escape_string($conn, $feRegistro);

    $sql = "UPDATE tbl_seguimientogps SET latitud = '$latitud', longitud = '$longitud', idRuta = '$idRuta', feRegistro = '$feRegistro',idRepartidor = '$idRepartidor' WHERE idLocalizacion = '$idLocalizacion'";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'Actualización exitosa.'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Error al actualizar.'
        ));
    }
}

function Create($conn){
    //get values from json request body
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

    $idRepartidor = $obj['idRepartidor'];
    $latitud = $obj['latitud'];
    $longitud = $obj['longitud'];
    $idRuta = $obj['idRuta'];
    $feRegistro = date('Y-m-d H:i:s');

    //prevent sql injection
    $idRepartidor = mysqli_real_escape_string($conn, $idRepartidor);
    $latitud = mysqli_real_escape_string($conn, $latitud);
    $longitud = mysqli_real_escape_string($conn, $longitud);
    $idRuta = mysqli_real_escape_string($conn, $idRuta);
    $feRegistro = mysqli_real_escape_string($conn, $feRegistro);

    $sql = "INSERT INTO tbl_seguimientogps (idRepartidor, latitud, longitud, idRuta, feRegistro) VALUES ('$idRepartidor', '$latitud', '$longitud', '$idRuta', '$feRegistro')";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'Registro exitoso.'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Error al registrar.'
        ));
    }

}

function RegisterLocationUser($conn){

    //get values from json request body
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

    $idRepartidor = $obj['idRepartidor'];
    $latitud = $obj['latitud'];
    $longitud = $obj['longitud'];
    $idRuta = $obj['idRuta'];
    $feRegistro = date('Y-m-d H:i:s');

    //prevent sql injection
    $idRepartidor = mysqli_real_escape_string($conn, $idRepartidor);
    $latitud = mysqli_real_escape_string($conn, $latitud);
    $longitud = mysqli_real_escape_string($conn, $longitud);
    $idRuta = mysqli_real_escape_string($conn, $idRuta);
    $feRegistro = mysqli_real_escape_string($conn, $feRegistro);

    $sql = "INSERT INTO tbl_seguimientogps (idRepartidor, latitud, longitud, idRuta, feRegistro) VALUES ('$idRepartidor', '$latitud', '$longitud', '$idRuta', '$feRegistro')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'Registro exitoso.'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Error al registrar.'
        ));
    }
}

?>