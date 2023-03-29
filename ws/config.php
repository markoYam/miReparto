<?php
$username = 'root';
$password = '';
$dbname = 'mireparto';
$servername = 'localhost';


$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die('Conexión fallida: ' . mysqli_connect_error());
}

?>