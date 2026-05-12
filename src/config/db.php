<?php

$host = "127.0.0.1";
$user = "root";
$password = "";
$database = "ftp_server";

$conn = new mysqli(
    $host,
    $user,
    $password,
    $database
);

if($conn->connect_error){
    die("Database Connection Failed");
}

?>