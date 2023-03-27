<?php
$username = '';
$password = '';
$dbname = '';
$servername = 'localHost';

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die('Conexión fallida: ' . mysqli_connect_error());
}

?>