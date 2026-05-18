<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_check($token) {
    if (empty($_SESSION['csrf']) || !is_string($token) || !hash_equals($_SESSION['csrf'], $token)) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'error' => 'Invalid CSRF token.']);
        exit;
    }
}

function csrf_token_from_request() {
    $header = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if ($header !== '') return $header;
    return $_POST['csrf'] ?? '';
}
