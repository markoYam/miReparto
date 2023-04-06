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
        case 'create':
            Create($conn);
            break;
        case 'getFotosProducto':
            getFotosProducto($conn);
            break;
        case 'getTallasProducto':
            getTallasProducto($conn);
            break;
        case 'delete':
            Delete($conn);
            break;
        case 'getProductoDetalleById':
            getProductoDetalleById($conn);
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
} else {
    index($conn);
}

function getProductoDetalleById($conn)
{
    $json = file_get_contents('php://input');
    $obj = json_decode($json, true);

    if ($obj == null) {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Bad request.'
        ));
        return;
    }

    $idProducto = $obj['idProducto'];

    $sql = "SELECT * FROM tbl_productos WHERE idProducto = $idProducto LIMIT 1";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $producto = mysqli_fetch_assoc($result);

        //buscar fotos
        $sql = "SELECT * FROM tbl_fotosproductos WHERE idProducto = $idProducto LIMIT 4";
        $result = mysqli_query($conn, $sql);

        $fotos = array();

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $fotos[] = $row;
            }

            $producto['fotos'] = $fotos;
        }

        //buscar tallas
        $sql = "SELECT * FROM tbl_tallasproductos WHERE idProducto = $idProducto";
        $result = mysqli_query($conn, $sql);

        $tallas = array();

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $tallas[] = $row;
            }

            $producto['tallas'] = $tallas;
        }

        echo json_encode(array(
            'idEstatus' => 1,
            'data' => $producto,
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

function index($conn)
{
    $querySearch = "";

    $json = file_get_contents('php://input');
    $obj = json_decode($json, true);

    if ($obj != null) {
        $search = $obj['query'];
        $querySearch = " WHERE nbProducto LIKE '%$search%' OR desProducto LIKE '%$search%'";
    }

    $sql = "SELECT * FROM tbl_productos";

    if ($querySearch != "") {
        $sql .= $querySearch;
    }
    $sql .= " ORDER BY idProducto DESC";

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

function getFotosProducto($conn)
{
    $json = file_get_contents('php://input');
    $obj = json_decode($json, true);

    if ($obj == null) {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Bad request.'
        ));
        return;
    }

    $idProducto = $obj['idProducto'];

    $sql = "SELECT * FROM tbl_fotosproductos WHERE idProducto = $idProducto";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $fotos = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $fotos[] = $row;
        }
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => $fotos,
            'mensaje' => 'OK'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'No se encontraron fotos para este producto'
        ));
    }
}

function getTallasProducto($conn)
{
    $json = file_get_contents('php://input');
    $obj = json_decode($json, true);

    if ($obj == null) {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Bad request.'
        ));
        return;
    }

    $idProducto = $obj['idProducto'];

    $sql = "SELECT tbl_tallas.* from tbl_tallas INNER JOIN tbl_tallasproductos on tbl_tallas.idTalla = tbl_tallasproductos.idTalla WHERE tbl_tallasproductos.idProducto = $idProducto";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $tallas = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $tallas[] = $row;
        }
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => $tallas,
            'mensaje' => 'OK'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'No se encontraron tallas para este producto'
        ));
    }
}

function Update($conn)
{
    $json = file_get_contents('php://input');
    $obj = json_decode($json, true);

    if ($obj == null) {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Bad request.'
        ));
        return;
    }

    $idProducto = $obj['idProducto'];
    $nbProducto = $obj['nbProducto'];
    $desProducto = $obj['desProducto'];
    $idCategoria = 1;
    $idEstatus = 1;
    $dcPrecioCompra = $obj['dcPrecioCompra'];
    $dcPrecioVenta = $obj['dcPrecioVenta'];
    $dcComision = $obj['dcComision'];

    //prevent sql injection
    $nbProducto = mysqli_real_escape_string($conn, $nbProducto);
    $desProducto = mysqli_real_escape_string($conn, $desProducto);
    $idCategoria = mysqli_real_escape_string($conn, $idCategoria);
    $idEstatus = mysqli_real_escape_string($conn, $idEstatus);
    $dcPrecioCompra = mysqli_real_escape_string($conn, $dcPrecioCompra);
    $dcPrecioVenta = mysqli_real_escape_string($conn, $dcPrecioVenta);
    $dcComision = mysqli_real_escape_string($conn, $dcComision);

    $sql = "UPDATE tbl_productos SET nbProducto = '$nbProducto', desProducto = '$desProducto', idCategoria = $idCategoria, idEstatus = $idEstatus, dcPrecioCompra = $dcPrecioCompra, dcPrecioVenta = $dcPrecioVenta, dcComision = $dcComision WHERE idProducto = $idProducto";

    $fotosForm = $obj['fotos'];
    $tallasForm = $obj['tallas'];

    //eliminar fotos anteriores
    $sql = "DELETE FROM tbl_fotosproductos WHERE idProducto = $idProducto";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'Error al actualizar el producto'
        ));
        return;
    }

    $absolute = "../../";
    $path = "site/images/productos/$idProducto";

    foreach ($fotosForm as $foto) {

        $desPathFoto = $foto['desPathFoto'];
        $numFoto = $foto['numFoto'];

        $pos = strpos($desPathFoto, $path);

        if($pos === false){
            $extension = ".jpg";
            $file_decoded_1 = explode(',', $desPathFoto);
            $file_decoded = $file_decoded_1[1];
    
            //echo $file_decoded;
    
            $decoded_image = base64_decode($file_decoded);
    
            $random = rand(1, 10000000000);
    
            $file_path = $absolute . $path . $random . $extension;
    
            $file_db = $path . $random . $extension;
    
            file_put_contents($file_path, $decoded_image);
            $sql = "INSERT INTO tbl_fotosproductos (idProducto, desPathFoto,numFoto) VALUES ($idProducto, '$file_db', $numFoto)";
        }else{
            $sql = "INSERT INTO tbl_fotosproductos (idProducto, desPathFoto,numFoto) VALUES ($idProducto, '$desPathFoto', $numFoto)";
        }        
        
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            echo json_encode(array(
                'idEstatus' => 0,
                'data' => array(),
                'mensaje' => 'Error al actualizar el producto'
            ));
            return;
        }
    }

    //eliminar tallas anteriores
    $sql = "DELETE FROM tbl_tallasproductos WHERE idProducto = $idProducto";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'Error al actualizar el producto'
        ));
        return;
    }

    foreach ($tallasForm as $talla) {

        $idTalla = $talla['idTalla'];

        $sql = "INSERT INTO tbl_tallasproductos (idProducto, idTalla) VALUES ($idProducto, $idTalla)";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            echo json_encode(array(
                'idEstatus' => 0,
                'data' => array(),
                'mensaje' => 'Error al actualizar el producto'
            ));
            return;
        }
    }


    if (mysqli_query($conn, $sql)) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'Producto actualizado correctamente'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'Error al actualizar el producto'
        ));
    }

    mysqli_close($conn);
}

function Create($conn)
{
    $json = file_get_contents('php://input');
    $obj = json_decode($json, true);

    if ($obj == null) {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Bad request.'
        ));
        return;
    }

    $nbProducto = $obj['nbProducto'];
    $desProducto = $obj['desProducto'];
    $idCategoria = 1;
    $idEstatus = 1;
    $dcPrecioCompra = $obj['dcPrecioCompra'];
    $dcPrecioVenta = $obj['dcPrecioVenta'];
    $dcComision = $obj['dcComision'];

    //prevent sql injection
    $nbProducto = mysqli_real_escape_string($conn, $nbProducto);
    $desProducto = mysqli_real_escape_string($conn, $desProducto);
    $idCategoria = mysqli_real_escape_string($conn, $idCategoria);
    $idEstatus = mysqli_real_escape_string($conn, $idEstatus);
    $dcPrecioCompra = mysqli_real_escape_string($conn, $dcPrecioCompra);
    $dcPrecioVenta = mysqli_real_escape_string($conn, $dcPrecioVenta);
    $dcComision = mysqli_real_escape_string($conn, $dcComision);

    $sql = "INSERT INTO tbl_productos (nbProducto, desProducto, idCategoria, idEstatus, dcPrecioCompra, dcPrecioVenta, dcComision) VALUES ('$nbProducto', '$desProducto', $idCategoria, $idEstatus, $dcPrecioCompra, $dcPrecioVenta, $dcComision)";

    if (mysqli_query($conn, $sql)) {
        $idProducto = mysqli_insert_id($conn);

        //registrar tallas
        $tallas = $obj['tallas'];
        foreach ($tallas as $talla) {
            $idTalla = $talla['idTalla'];
            $sql = "INSERT INTO tbl_tallasproductos (idProducto, idTalla) VALUES ($idProducto, $idTalla)";
            mysqli_query($conn, $sql);
        }

        //validate if exist folder
        $absolute = "../../";
        $path = "site/images/productos/$idProducto";

        //registrar fotos
        $fotos = $obj['fotos'];
        foreach ($fotos as $foto) {
            $desPathFoto = $foto['desPathFoto'];

            $extension = ".jpg";
            $file_decoded_1 = explode(',', $desPathFoto);
            $file_decoded = $file_decoded_1[1];

            //echo $file_decoded;

            $decoded_image = base64_decode($file_decoded);

            $random = rand(1, 10000000000);

            $file_path = $absolute . $path . $random . $extension;

            $file_db = $path . $random . $extension;

            file_put_contents($file_path, $decoded_image);

            $numFoto = $foto['numFoto'];
            $sql = "INSERT INTO tbl_fotosproductos (idProducto, desPathFoto,numFoto) VALUES ($idProducto, '$file_db','$numFoto')";
            mysqli_query($conn, $sql);
        }

        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(
                'idProducto' => $idProducto
            ),
            'mensaje' => 'Producto registrado correctamente'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'Error al crear el producto'
        ));
    }

    mysqli_close($conn);
}

function Delete($conn)
{
    $json = file_get_contents('php://input');
    $obj = json_decode($json, true);

    if ($obj == null) {
        echo json_encode(array(
            'idEstatus' => -1,
            'data' => array(),
            'mensaje' => 'Bad request.'
        ));
        return;
    }

    $idProducto = $obj['idProducto'];

    //prevent sql injection
    $idProducto = mysqli_real_escape_string($conn, $idProducto);

    //verificar si existe el producto
    $sql = "SELECT * FROM tbl_productos WHERE idProducto = $idProducto";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0) {
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'No se encontró el producto'
        ));
        return;
    }

    //verificar si el producto tiene productos paradas
    $sql = "SELECT * FROM tbl_productosparadas WHERE idProducto = $idProducto";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'No se puede eliminar el producto porque tiene Paradas asociadas'
        ));
        return;
    }

    //eliminar productos fotos
    $sql = "DELETE FROM tbl_fotosproductos WHERE idProducto = $idProducto";

    //verificar si se elimino las fotos
    if (mysqli_query($conn, $sql)) {
        //eliminar productos tallas
        $sql = "DELETE FROM tbl_tallasproductos WHERE idProducto = $idProducto";
        mysqli_query($conn, $sql);
    } else {
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'Error al eliminar las fotos del producto'
        ));
        return;
    }

    //eliminar tallas productos
    $sql = "DELETE FROM tbl_tallasproductos WHERE idProducto = $idProducto";

    //verificar si se elimino las tallas
    if (mysqli_query($conn, $sql)) {
        //eliminar productos tallas
        $sql = "DELETE FROM tbl_productos WHERE idProducto = $idProducto";
        mysqli_query($conn, $sql);
    } else {
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'Error al eliminar las tallas del producto'
        ));
        return;
    }

    $sql = "DELETE FROM tbl_productos WHERE idProducto = $idProducto";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(array(
            'idEstatus' => 1,
            'data' => array(),
            'mensaje' => 'Producto eliminado correctamente'
        ));
    } else {
        echo json_encode(array(
            'idEstatus' => 0,
            'data' => array(),
            'mensaje' => 'Error al eliminar el producto'
        ));
    }

    mysqli_close($conn);
}
