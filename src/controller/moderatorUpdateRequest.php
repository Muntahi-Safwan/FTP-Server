<?php
session_start();

require_once '../includes/moderator_check.php';
require_once '../model/request_model.php';

if (isset($_POST['data'])) {
    $payload = json_decode($_POST['data'], true);
    $id = $payload['id'] ?? '';
    $status = $payload['status'] ?? '';

    if ($id !== '' && $status !== '') {
        $result = updateRequestStatus($id, $status);
        if ($result) {
            echo 'Request status updated successfully.';
        } else {
            echo 'Failed to update request status.';
        }
    } else {
        echo 'Invalid request ID or status.';
    }
    exit();
}
