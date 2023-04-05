<?php
header('Content-Type: application/json');
require_once('../config.php');

$request = $_SERVER['REQUEST_METHOD'];

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'create':
            Create($conn);
            break;
        case 'update':
            Update($conn);
            break;
        case 'delete':
            Delete($conn);
            break;
        case 'getByRepartidor':
            GetByRepartidor($conn);
            break;
        case 'getCustomView':
            getCustomView($conn);
            break;
        case 'getById':
            getById($conn);
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
    //index
    $sql = "SELECT * FROM tbl_tallas";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $rutas = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rutas[] = $row;
        }
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => $rutas,
            'mensaje' => 'OK'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'No se encontraron tallas'
        ));
    }
}

?>