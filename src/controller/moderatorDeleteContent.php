<?php
session_start();

require_once '../includes/moderator_check.php';
require_once '../model/content_model.php';

if (isset($_POST['data'])) {
    $payload = json_decode($_POST['data'], true);
    $id = $payload['id'] ?? '';

    if ($id !== '') {
        $result = deleteContent($id);
        if ($result) {
            echo 'Content deleted successfully.';
        } else {
            echo 'Failed to delete content.';
        }
    } else {
        echo 'Invalid content ID.';
    }
    exit();
}
