<?php
header('Content-Type: application/json');
require_once('../config.php');

$request = $_SERVER['REQUEST_METHOD'];

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'getByRuta':
            getByRuta($conn);
            break;

        case 'updateEstatus':
            ActualizarEstatus($conn);
            break;

        case 'delete':
            Delete($conn);
            break;

        case 'create':
            Create ($conn);
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
    $sql = "SELECT * FROM tbl_paradas";
    $result = $conn->query($sql);
    $data = array();
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {

            //get data from productos_parada_view
            $sql2 = "SELECT * FROM productos_parada_view WHERE idRuta = ".$row['idRuta'];

            $resultProductos = $conn->query($sql2);

            $productos = array();

            if (mysqli_num_rows($resultProductos) > 0) {
                while($rowProductos = mysqli_fetch_assoc($resultProductos)) {
                    $productos[] = $rowProductos;
                }
                $row['productos'] = $productos;

            }else{
                $row['productos'] = array();
            }

            $data[] = $row;
        }

        echo json_encode(array(
            'idEstatus' => 1,
            'data' => $data,
            'mensaje' => 'OK'
        ));

    }else{
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'No hay paradas registradas.'
        ));
    }
}

function ActualizarEstatus($conn){
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

    $idParada = $obj['idParada'];
    $idEstatus = $obj['idEstatus'];
    $feActualizacion =  date('Y-m-d H:i:s');

    //prevent sql injection
    $idParada = mysqli_real_escape_string($conn, $idParada);
    $idEstatus = mysqli_real_escape_string($conn, $idEstatus);
    $feActualizacion = mysqli_real_escape_string($conn, $feActualizacion);

    $sql = "UPDATE tbl_paradas SET idEstatus = $idEstatus, feActualizacion = '$feActualizacion' WHERE idParada = $idParada";

    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'Parada actualizada.'
        ));
    }else{
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'No se pudo actualizar la parada.'
        ));
    }
}

function getByRuta($conn){
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

    $idRuta = $obj['idRuta'];
    //prevent sql injection
    $idRuta = mysqli_real_escape_string($conn, $idRuta);

    $sql = "SELECT * FROM tbl_paradas WHERE idRuta = $idRuta";
    $result = $conn->query($sql);
    $data = array();
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            //get data from productos_parada_view
            $sql2 = "SELECT * FROM productos_parada_view WHERE idRuta = ".$row['idRuta'];

            $resultProductos = $conn->query($sql2);

            $productos = array();

            if (mysqli_num_rows($resultProductos) > 0) {
                while($rowProductos = mysqli_fetch_assoc($resultProductos)) {
                    $productos[] = $rowProductos;
                }
                $row['productos'] = $productos;

            }else{
                $row['productos'] = array();
            }
            $data[] = $row;
        }
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => $data,
            'mensaje' => 'OK'
        ));
    }else{
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'No hay paradas registradas.'
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

    //delete
    $idParada = $obj['idParada'];

    //prevent sql injection
    $idParada = mysqli_real_escape_string($conn, $idParada);

    $sql = "DELETE FROM tbl_paradas WHERE idParada = $idParada";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'Parada eliminada.'
        ));
    }else{
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'No se pudo eliminar la parada.'
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

    //update
    $idParada = $obj['idParada'];
    $idRuta = $obj['idRuta'];
    $fecha = $obj['fecha'];
    $idEstatus = $obj['idEstatus'];
    $latitud = $obj['latitud'];
    $longitud = $obj['longitud'];
    $comentarios = $obj['comentarios'];
    $cliente = $obj['cliente'];

    //prevent sql injection
    $idParada = mysqli_real_escape_string($conn, $idParada);
    $idRuta = mysqli_real_escape_string($conn, $idRuta);
    $fecha = mysqli_real_escape_string($conn, $fecha);
    $idEstatus = mysqli_real_escape_string($conn, $idEstatus);
    $latitud = mysqli_real_escape_string($conn, $latitud);
    $longitud = mysqli_real_escape_string($conn, $longitud);
    $comentarios = mysqli_real_escape_string($conn, $comentarios);
    $cliente = mysqli_real_escape_string($conn, $cliente);

    $sql = "UPDATE tbl_paradas SET idRuta = '$idRuta', fecha = '$fecha', idEstatus = '$idEstatus', latitud = '$latitud', longitud = '$longitud', comentarios = '$comentarios', cliente = '$cliente' WHERE idParada = $idParada";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'Parada actualizada correctamente.'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'Error al actualizar la parada.'
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
    $idRuta = $obj['idRuta'];
    $fecha = $obj['fecha'];
    $idEstatus = $obj['idEstatus'];
    $latitud = $obj['latitud'];
    $longitud = $obj['longitud'];
    $comentarios = $obj['comentarios'];
    $cliente = $obj['cliente'];

    //prevent sql injection
    $idRuta = mysqli_real_escape_string($conn, $idRuta);
    $fecha = mysqli_real_escape_string($conn, $fecha);
    $idEstatus = mysqli_real_escape_string($conn, $idEstatus);
    $latitud = mysqli_real_escape_string($conn, $latitud);
    $longitud = mysqli_real_escape_string($conn, $longitud);
    $comentarios = mysqli_real_escape_string($conn, $comentarios);
    $cliente = mysqli_real_escape_string($conn, $cliente);

    $sql = "INSERT INTO tbl_paradas (idRuta, fecha, idEstatus, latitud, longitud, comentarios, cliente) VALUES ('$idRuta', '$fecha', '$idEstatus', '$latitud', '$longitud', '$comentarios', '$cliente')";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'Parada creada correctamente.'
        ));
    }else{
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'Error al crear la parada.'
        ));
    }

}
?>