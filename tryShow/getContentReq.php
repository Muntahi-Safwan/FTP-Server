<?php

$conn = getConnect();

// Select all data from your content_requests table
$sql = "SELECT * FROM content_requests";
$result = mysqli_query($conn, $sql);

$data = array();

if ($result && mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

mysqli_close($conn);

// Output the array as JSON so JavaScript can read it
echo json_encode($data);

?>