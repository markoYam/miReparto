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
            Create($conn);
            break;

        case 'update':
            Update($conn);
            break;
        case 'getParadaById':
            getParadaById($conn);
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

    $queryText = '';
    if(isset($obj['queryFiltro'])){
        $queryText = $obj['queryFiltro'];
        $queryText = mysqli_real_escape_string($conn, $queryText);
    }
    
    //filters
    $idEstatus = -1;
    if(isset($obj['idEstatus'])){
        $idEstatus = $obj['idEstatus'];
        $idEstatus = mysqli_real_escape_string($conn, $idEstatus);
    }

    $fechaInicio = '';
    if(isset($obj['fechaInicio'])){
        $fechaInicio = $obj['fechaInicio'];
        $fechaInicio = mysqli_real_escape_string($conn, $fechaInicio);
    }

    $fechaFin = '';
    if(isset($obj['fechaFin'])){
        $fechaFin = $obj['fechaFin'];
        $fechaFin = mysqli_real_escape_string($conn, $fechaFin);
    }

    //prevent sql injection
    $idRuta = mysqli_real_escape_string($conn, $idRuta);

    $sql = "SELECT * FROM paradas_view WHERE idRuta like case when ($idRuta is null or $idRuta = '' or $idRuta = 0) then '%' else '$idRuta' end";

    if($queryText != ''){
        $sql .= " AND (cliente LIKE '%$queryText%' OR comentarios LIKE '%$queryText%')";
    }
    if($idEstatus != -1){
        $sql .= " AND idEstatus = $idEstatus";
    }

    if($fechaInicio != '' && $fechaFin != ''){
        $sql .= " AND ( fecha BETWEEN '$fechaInicio' AND '$fechaFin' AND date(IFNULL(feActualizacion,'')) BETWEEN '$fechaInicio' AND '$fechaFin' )";
    }

    if($fechaInicio != '' && $fechaFin == ''){
        $sql .= " AND (fecha = '$fechaInicio' OR date(IFNULL(feActualizacion,'')) = '$fechaInicio')";
    }

    if($fechaFin != '' && $fechaInicio == ''){
        $sql .= " AND (fecha = '$fechaFin' OR date(IFNULL(feActualizacion,'')) = '$fechaFin')";
    }

    $sql .= " ORDER BY 1 DESC";
    
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
            'mensaje' =>  'OK'
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
    
     //eliminar productos paradas si esta tiene
    $sql = "DELETE FROM tbl_productosparadas WHERE idParada = $idParada";
    
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'No se pudo eliminar los productos de la parada.'
        ));

        return;
    }


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
    $idCliente = $obj['idCliente'];
    $nbCliente = $obj['nbCliente'];

    //prevent sql injection
    $idParada = mysqli_real_escape_string($conn, $idParada);
    $idRuta = mysqli_real_escape_string($conn, $idRuta);
    $fecha = mysqli_real_escape_string($conn, $fecha);
    $idEstatus = mysqli_real_escape_string($conn, $idEstatus);
    $latitud = mysqli_real_escape_string($conn, $latitud);
    $longitud = mysqli_real_escape_string($conn, $longitud);
    $comentarios = mysqli_real_escape_string($conn, $comentarios);
    $idCliente = mysqli_real_escape_string($conn, $idCliente);
    $nbCliente = mysqli_real_escape_string($conn, $nbCliente);

    //delete productosParadas
    $sql = "DELETE FROM tbl_productosparadas WHERE idParada = $idParada";

    if (!mysqli_query($conn, $sql)) {
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'Error al actualizar la parada.'
        ));
        return;
    }

    //insert productosParadas
    $productos = $obj['productos'];
    foreach($productos as $producto){
        $idProducto = $producto['idProducto'];
        $dcCantidad = $producto['dcCantidad'];
        $sql = "INSERT INTO tbl_productosparadas (idParada, idProducto, dcCantidad) VALUES ($idParada, $idProducto, $dcCantidad)";
        if (!mysqli_query($conn, $sql)) {
            echo json_encode(array(
                'idEstatus' => 0,
                'data' => array(),
                'mensaje' => 'Error al actualizar la parada.'
            ));
            return;
        }
    }

    $sql = "UPDATE tbl_paradas SET idRuta = $idRuta, fecha = '$fecha', idEstatus = $idEstatus, latitud = '$latitud', longitud = '$longitud', comentarios = '$comentarios', idCliente = $idCliente, cliente = '$nbCliente' WHERE idParada = $idParada";
    
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

function getParadaById($conn){
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

    //prevent sql injection
    $idParada = mysqli_real_escape_string($conn, $idParada);

    $sql = "SELECT * FROM tbl_paradas WHERE idParada = $idParada";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $data = array();
        while($row = mysqli_fetch_assoc($result)) {
            $idParada = $row['idParada'];
            $sqlProductos = "SELECT * FROM tbl_productosparadas pp INNER JOIN tbl_productos p on pp.idProducto = p.idProducto  WHERE idParada = $idParada";
            $resultProductos = mysqli_query($conn, $sqlProductos);
            if (mysqli_num_rows($resultProductos) > 0) {
                $productos = array();
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
            'data' => $data[0],
            'mensaje' =>  'OK'
        ));
    }else{
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'No hay paradas registradas.'
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
    $idCliente = $obj['idCliente'];
    $nbCliente = $obj['nbCliente'];

    $productos = $obj['productos'];

    //validate fiels
    $errors = array();
    if($idRuta == null){
        $errors[] = 'idRuta';
    }
    if($fecha == null){
        $errors[] = 'fecha';
    }
    if($idEstatus == null || $idEstatus == -1){
        $errors[] = 'idEstatus';
    }
    if($latitud == null || $latitud == 0){
        $errors[] = 'latitud';
    }
    if($longitud == null || $longitud == 0){
        $errors[] = 'longitud';
    }   
    if($comentarios == null || $comentarios == '' || $comentarios == ' '){
        $errors[] = 'comentarios';
    }

    if(count($errors) > 0){

        $mgs = 'Los siguientes campos son requeridos: ';
        foreach($errors as $error){
            //if is last not ad comma
            if($error == end($errors)){
                $mgs .= $error;
                break;
            }else{
                $mgs .= $error . ', ';
            }
        }
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => $mgs
        ));
        return;
    }

    //prevent sql injection
    $idRuta = mysqli_real_escape_string($conn, $idRuta);
    $fecha = mysqli_real_escape_string($conn, $fecha);
    $idEstatus = mysqli_real_escape_string($conn, $idEstatus);
    $latitud = mysqli_real_escape_string($conn, $latitud);
    $longitud = mysqli_real_escape_string($conn, $longitud);
    $comentarios = mysqli_real_escape_string($conn, $comentarios);
    $idCliente = mysqli_real_escape_string($conn, $idCliente);
    $nbCliente = mysqli_real_escape_string($conn, $nbCliente);
    $sql = "INSERT INTO tbl_paradas (idRuta, fecha, idEstatus, latitud, longitud, comentarios, idCliente, cliente) VALUES ('$idRuta', '$fecha', '$idEstatus', '$latitud', '$longitud', '$comentarios', '$idCliente', '$nbCliente')";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        $last_id = mysqli_insert_id($conn);

        //foreach products
        foreach($productos as $producto){
            $idProducto = $producto['idProducto'];
            $cantidad = $producto['dcCantidad'];
            $sql_productos_paradas = "INSERT INTO tbl_productosparadas (idParada, idProducto, dcCantidad) VALUES ('$last_id', '$idProducto', '$cantidad')";
            $result_productos_paradas = mysqli_query($conn, $sql_productos_paradas);

            if(!$result_productos_paradas){
                echo json_encode(array(
                    'idEstatus' => 0,
                    'data' => 1,
                    'mensaje' => 'Error al crear la parada.'
                ));
                return;
            }
        }

        echo json_encode(array(
            'idEstatus' => 1,
            'data' => $last_id,
            'mensaje' => 'Parada creada correctamente.'
        ));
    }else{
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => 1,
            'mensaje' => 'Error al crear la parada.'
        ));
    }

}
