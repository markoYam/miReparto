<?php
header('Content-Type: application/json');
require_once('config.php');

// Endpoint para obtener todas las paradas de una ruta
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['idRuta'])) {
    $idRuta = $_GET['idRuta'];

    $sql = "SELECT * FROM Paradas WHERE idRuta = $idRuta";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $paradas = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $paradas[] = $row;
        }
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => $paradas,
            'mensaje' => 'Paradas encontradas.'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'No se encontraron paradas para la ruta especificada.'
        ));
    }

    mysqli_close($conn);
}

// Endpoint para agregar una nueva parada
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idRuta'])) {
    $idRuta = $_POST['idRuta'];
    $fecha = $_POST['fecha'];
    $latitud = $_POST['latitud'];
    $longitud = $_POST['longitud'];
    $comentarios = $_POST['comentarios'];
    $total = $_POST['total'];
    $cliente = $_POST['cliente'];

    $sql = "INSERT INTO Paradas (idRuta, fecha, idEstatus, latitud, longitud, comentarios, total, cliente) VALUES ($idRuta, '$fecha', 0, $latitud, $longitud, '$comentarios', $total, '$cliente')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'La parada fue agregada exitosamente.'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Hubo un error al agregar la parada.'
        ));
    }

    mysqli_close($conn);
}

// Endpoint para actualizar una parada existente
if ($_SERVER['REQUEST_METHOD'] == 'PUT' && isset($_GET['idParada']) && isset($_GET['accion']) && $_GET['accion'] == 'actualizar') {
    parse_str(file_get_contents("php://input"), $post_vars);
    $idParada = $_GET['idParada'];
    $fecha = $post_vars['fecha'];
    $idEstatus = $post_vars['idEstatus'];
    $latitud = $post_vars['latitud'];
    $longitud = $post_vars['longitud'];
    $comentarios = $post_vars['comentarios'];
    $total = $post_vars['total'];
    $cliente = $post_vars['cliente'];

    $sql = "UPDATE Paradas SET fecha = '$fecha', idEstatus = $idEstatus, latitud = $latitud, longitud = $longitud, comentarios = '$comentarios', total = $total, cliente = '$cliente' WHERE idParada = $idParada";
   
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'La parada fue actualizada exitosamente.'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Hubo un error al actualizar la parada.'
        ));
    }
    
    mysqli_close($conn);
}

// Endpoint para eliminar una parada existente
if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && isset($_GET['idParada'])) {
$idParada = $_GET['idParada'];

$sql = "DELETE FROM Paradas WHERE idParada = $idParada";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo json_encode(array(
        'idEstatus' => 1,
        'data' => array(),
        'mensaje' => 'La parada fue eliminada exitosamente.'
    ));
} else {
    echo json_encode(array(
        'idEstatus' => -1,
        'data' => array(),
        'mensaje' => 'Hubo un error al eliminar la parada.'
    ));
}

mysqli_close($conn);
}
//Endpoint para actualizar el estatus de una parada validar el accion == actualizarRuta

if ($_SERVER['REQUEST_METHOD'] == 'PUT' && isset($_GET['idParada']) && isset($_GET['accion']) && $_GET['accion'] == 'actualizarRutas'  && isset($_GET['idEstatus'])) {
    $idParada = $_GET['idParada'];
    $idEstatus = $_GET['idEstatus'];

    $sql = "UPDATE Paradas SET idEstatus = $idEstatus WHERE idParada = $idParada";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'El estatus de la parada fue actualizado exitosamente.'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Hubo un error al actualizar el estatus de la parada.'
        ));
    }

    mysqli_close($conn);
}

?>