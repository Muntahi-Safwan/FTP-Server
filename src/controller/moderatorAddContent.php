<?php
session_start();

require_once '../includes/moderator_check.php';
require_once '../model/content_model.php';

if (isset($_POST['content'])) {
    $content = json_decode($_POST['content']);

    if ($content->title == '' || $content->description == '' || $content->categoryId == '' || $content->filePath == '') {
        echo 'null title/description/category/file!';
        exit;
    }

    $uploaderId = $_SESSION['user_id'] ?? 0;

    $result = createContent($content->title, $content->description, $content->categoryId, $content->filePath, $uploaderId);

    if ($result) {
        echo 'Content added successfully.';
    } else {
        echo 'Failed to add content.';
    }
    exit;
}

echo 'please submit form...';
