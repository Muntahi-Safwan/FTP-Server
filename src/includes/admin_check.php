<?php
// src/includes/admin_check.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../../public/index.php?page=home');
    exit;
}