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
    }
}else{
    Index($conn);
}

function Index($conn){
    $sql = "SELECT * FROM tbl_repartidores";
    $result = $conn->query($sql);
    $data = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    echo json_encode(array(
        'idEstatus' => 1,
        'data' => $data,
        'mensaje' => 'OK'
    ));

}
function Create($conn){
    //get values from json request body
    $json = file_get_contents('php://input');
    $obj = json_decode($json);

    if($obj == null){
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Bad request.'
        ));
        return;
    }


    $nbRepartidor = $obj['nbRepartidor'];

    //prevent sql injection
    $nbRepartidor = $conn->real_escape_string($nbRepartidor);

    $sql = "INSERT INTO tbl_repartidores (nbRepartidor) VALUES ('$nbRepartidor')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'OK'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Error al insertar registro.'
        ));
    }
    
}
function Update($conn){
    //get values from json request body
    $json = file_get_contents('php://input');
    $obj = json_decode($json);

    if($obj == null){
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Bad request.'
        ));
        return;
    }

    $idRepartidor = $obj['idRepartidor'];
    $nbRepartidor = $obj['nbRepartidor'];

    //prevent sql injection
    $idRepartidor = $conn->real_escape_string($idRepartidor);
    $nbRepartidor = $conn->real_escape_string($nbRepartidor);

    $sql = "UPDATE tbl_repartidores SET nbRepartidor = '$nbRepartidor' WHERE idRepartidor = $idRepartidor";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'Repartidor actualizado.'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Error al actualizar registro.'
        ));
    }

    
}
function Delete($conn){
    //get values from json request body
    $json = file_get_contents('php://input');
    $obj = json_decode($json);

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
    $idRepartidor = $conn->real_escape_string($idRepartidor);

    $sql = "DELETE FROM tbl_repartidores WHERE idRepartidor = $idRepartidor";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'Repartidor eliminado.'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Error al eliminar registro.'
        ));
    }
    
    
}

?>