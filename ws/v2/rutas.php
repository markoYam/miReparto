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

function Create($conn){
    //create
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


    $sql = "INSERT INTO tbl_ruteo (fecha, Folio, idRepartidor, idEstatus, feFin) VALUES ('$fecha', '$Folio', '$idRepartidor', '$idEstatus', '$feFin')";
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