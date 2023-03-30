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
    $sql = "SELECT * FROM tbl_ruteo";
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
            'mensaje' => 'No se encontraron rutas'
        ));
    }

    mysqli_close($conn);
}

function getCustomView($conn){
    $sql = "SELECT * FROM rutas_view";
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
            'mensaje' => 'No se encontraron rutas'
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
    //create
    //$idRuteo = $obj['idRuteo'];
    $fecha = $obj['fecha'];
    $Folio = $obj['Folio'];
    $idRepartidor = $obj['idRepartidor'];
    $idEstatus = $obj['idEstatus'];
    //$feFin = $obj['feFin'];

    //prevent sql injection
    //$idRuteo = mysqli_real_escape_string($conn, $idRuteo);
    $fecha = mysqli_real_escape_string($conn, $fecha);
    $Folio = mysqli_real_escape_string($conn, $Folio);
    $idRepartidor = mysqli_real_escape_string($conn, $idRepartidor);
    $idEstatus = mysqli_real_escape_string($conn, $idEstatus);
    //$feFin = mysqli_real_escape_string($conn, $feFin);
    
    $mgs = "";
    if($fecha == ""){
        $mgs = $mgs.($mgs == "" ? "":", ")."Fecha\n";
    }
    if($Folio == ""){
        $mgs = $mgs.($mgs == "" ? "":", ")."Folio\n";
    }
    if($idRepartidor == "" || $idRepartidor == -1){
        $mgs = $mgs.($mgs == "" ? "":", ")."Repartidor\n";
    }
    if($idEstatus == "" || $idEstatus == -1){
        $mgs = $mgs.($mgs == "" ? "":", ")."Estatus\n";
    }

    if($mgs != ""){
        $mgs = "Los siguientes campos son requeridos: \n".$mgs;
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => $mgs
        ));
        return;
    }

    $sql = "INSERT INTO tbl_ruteo (fecha, Folio, idRepartidor, idEstatus) VALUES ('$fecha', '$Folio', '$idRepartidor', '$idEstatus')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'La ruta fue agregada exitosamente.'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Hubo un error al agregar la ruta.'
        ));
    }

    mysqli_close($conn);
}

function GetByRepartidor($conn){
    //index
    $idRepartidor = $_GET['idRepartidor'];

    //prevent sql injection
    $idRepartidor = mysqli_real_escape_string($conn, $idRepartidor);

    $sql = "SELECT * FROM tbl_ruteo WHERE idRepartidor = '$idRepartidor'";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $rutas = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rutas[] = $row;
        }
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => $rutas,
            'mensaje' => 'rutas encontradas.'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'No se encontraron rutas'
        ));
    }

    mysqli_close($conn);
}

function Update($conn){
    //update
    $idRuteo = $_POST['idRuteo'];
    $fecha = $_POST['fecha'];
    $Folio = $_POST['Folio'];
    $idRepartidor = $_POST['idRepartidor'];
    $idEstatus = $_POST['idEstatus'];
    $feFin = $_POST['feFin'];

    //prevent sql injection
    $idRuteo = mysqli_real_escape_string($conn, $idRuteo);
    $fecha = mysqli_real_escape_string($conn, $fecha);
    $Folio = mysqli_real_escape_string($conn, $Folio);
    $idRepartidor = mysqli_real_escape_string($conn, $idRepartidor);
    $idEstatus = mysqli_real_escape_string($conn, $idEstatus);
    $feFin = mysqli_real_escape_string($conn, $feFin);

    $sql = "UPDATE tbl_ruteo SET fecha = '$fecha', Folio = '$Folio', idRepartidor = '$idRepartidor', idEstatus = '$idEstatus', feFin = '$feFin' WHERE idRuteo = '$idRuteo'";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'La ruta fue actualizada exitosamente.'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Hubo un error al actualizar la ruta.'
        ));
    }

    mysqli_close($conn);
}

function Delete($conn){
    //delete
    $idRuteo = $_POST['idRuteo'];

    //prevent sql injection
    $idRuteo = mysqli_real_escape_string($conn, $idRuteo);

    $sql = "DELETE FROM tbl_ruteo WHERE idRuteo = '$idRuteo'";
  
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'La ruta fue eliminada exitosamente.'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Hubo un error al eliminar la ruta.'
        ));
    }

    mysqli_close($conn);
}

?>