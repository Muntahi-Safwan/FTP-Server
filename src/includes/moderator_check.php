<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'moderator') {
    header("Location: index.php?page=login");
    exit;
}
