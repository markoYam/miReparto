<?php
header('Content-Type: application/json');
require_once('../config.php');

$request = $_SERVER['REQUEST_METHOD'];

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'getByModule':
            getEstatusByModule($conn);
            break;

        default:
            echo json_encode(array(
                'idEstatus' => -1,
                'data' => array(),
                'mensaje' => 'Bad request.'
            ));
            break;
    }
}else{
    echo json_encode(array(
        'idEstatus' => -1,
        'data' => array(),
        'mensaje' => 'Bad request.'
    ));
}

function getEstatusByModule($conn){
    //get values from json request body
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if($data == null){
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Bad request.'
        ));
        return;
    }

    $nbModulo = $data['nbModulo'];

    $sql = "SELECT * FROM tbl_estatus WHERE nbModulo = '$nbModulo'";

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
?>
