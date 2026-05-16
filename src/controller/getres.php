<?php
$conn = mysqli_connect('localhost', 'root', '', 'ftp_server');

$sql = "SELECT id, name FROM categories";
$result = mysqli_query($conn, $sql);
$data = array();

while($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
?>