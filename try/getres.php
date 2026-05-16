<?php
//header('Content-Type: application/json');

$conn = mysqli_connect('localhost', 'root', '', 'ftp_server');

if (!$conn) {
    die();
}

$sql = "SELECT id, name FROM categories";
$result = mysqli_query($conn, $sql);

$data = array();

if ($result && mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

mysqli_close($conn);
echo json_encode($data);
?>