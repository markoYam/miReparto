<?php
header('Content-Type: application/json');
require_once('config.php');

// Endpoint para obtener todas las ruteo
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT * FROM Ruteo";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $ruteo = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $ruteo[] = $row;
        }
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => $ruteo,
            'mensaje' => 'ruteo encontradas.'
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

// Endpoint para agregar una nueva ruta
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $idRuteo = $_POST['idRuteo'];
    $fecha = $_POST['fecha'];
    $Repartidor = $_POST['Repartidor'];
    //$idEstatus = $_POST['idEstatus'];
    //$idEstatus = 1;

    $sql = "INSERT INTO Ruteo (fecha, Repartidor) VALUES ('$fecha', '$Repartidor')";
    echo $sql;
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

// Endpoint para actualizar una ruta
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $idRuteo = $_POST['idRuteo'];
    $fecha = $_POST['fecha'];
    $Repartidor = $_POST['Repartidor'];

    $sql = "UPDATE Ruteo SET idRuteo = '$idRuteo', fecha = '$fecha', Repartidor = '$Repartidor' WHERE idRuteo = $idRuteo";
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

// Endpoint para eliminar una ruta
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $idRuta = $_GET['idRuta'];

    $sql = "DELETE FROM Ruteo WHERE idRuteo = $idRuta";
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