<?php
$host = 'localhost';
$db = 'ftp_server';
$user = 'root';
$pass = '';

function getConnect() {
    global $host;
    global $db;
    global $user;
    global $pass;

    $conn = mysqli_connect($host, $user, $pass, $db);
    if (!$conn) {
        throw new Exception("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}
?>